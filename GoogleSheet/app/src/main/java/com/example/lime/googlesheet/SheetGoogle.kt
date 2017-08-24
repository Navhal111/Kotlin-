package com.example.lime.googlesheet

import android.accounts.AccountManager
import android.annotation.SuppressLint
import android.app.Activity
import android.app.ProgressDialog
import android.content.Context
import android.content.Intent
import android.net.ConnectivityManager
import android.os.AsyncTask
import android.os.Bundle
import android.support.v7.widget.LinearLayoutManager
import android.support.v7.app.AppCompatActivity
import android.widget.Toast

import com.google.android.gms.common.ConnectionResult
import com.google.android.gms.common.GoogleApiAvailability
import com.google.api.client.extensions.android.http.AndroidHttp
import com.google.api.client.googleapis.extensions.android.gms.auth.GoogleAccountCredential
import com.google.api.client.googleapis.extensions.android.gms.auth.GooglePlayServicesAvailabilityIOException
import com.google.api.client.googleapis.extensions.android.gms.auth.UserRecoverableAuthIOException

import com.google.api.client.json.jackson2.JacksonFactory
import com.google.api.client.util.ExponentialBackOff

import com.google.api.services.sheets.v4.SheetsScopes
import kotlinx.android.synthetic.main.activity_sheet_google.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject
import java.io.IOException
import java.util.ArrayList
import java.util.Arrays

import pub.devrel.easypermissions.EasyPermissions

class SheetGoogle : AppCompatActivity(), EasyPermissions.PermissionCallbacks {
    internal lateinit var mCredential: GoogleAccountCredential
    internal lateinit var mProgress: ProgressDialog
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_sheet_google)

        mCredential = GoogleAccountCredential.usingOAuth2(
                applicationContext, Arrays.asList(*SCOPES))
                .setBackOff(ExponentialBackOff())


        mProgress = ProgressDialog(this)
        mProgress.setMessage("Calling Google Sheets API ...")
        getResultsFromApi()


    }

    private fun getResultsFromApi() = if (!isGooglePlayServicesAvailable) {
        acquireGooglePlayServices()
    } else if (mCredential.selectedAccountName == null) {
        chooseAccount()
    } else if (!isDeviceOnline) {
        toast("No network connection available")
    } else {
        val execute: Any = MakeRequestTask(mCredential).execute()
        execute
    }

    private val isGooglePlayServicesAvailable: Boolean
        get() {
            val apiAvailability = GoogleApiAvailability.getInstance()
            val connectionStatusCode = apiAvailability.isGooglePlayServicesAvailable(this)
            return connectionStatusCode == ConnectionResult.SUCCESS
        }

    /**
     * Attempt to resolve a missing, out-of-date, invalid or disabled Google
     * Play Services installation via a user dialog, if possible.
     */
    private fun acquireGooglePlayServices() {
        val apiAvailability = GoogleApiAvailability.getInstance()
        val connectionStatusCode = apiAvailability.isGooglePlayServicesAvailable(this)
        if (apiAvailability.isUserResolvableError(connectionStatusCode)) {
            showGooglePlayServicesAvailabilityErrorDialog(connectionStatusCode)
        }
    }

    private fun chooseAccount() {
        if (EasyPermissions.hasPermissions(
                this, android.Manifest.permission.GET_ACCOUNTS)) {
            val accountName = getPreferences(Context.MODE_PRIVATE)
                    .getString(PREF_ACCOUNT_NAME, null)
            if (accountName != null) {
                mCredential.selectedAccountName = accountName
                getResultsFromApi()
            } else {
                // Start a dialog from which the user can choose an account
                startActivityForResult(
                        mCredential.newChooseAccountIntent(),
                        REQUEST_ACCOUNT_PICKER)
            }
        } else {
            // Request the GET_ACCOUNTS permission via a user dialog
            EasyPermissions.requestPermissions(
                    this,
                    "This app needs to access your Google account (via Contacts).",
                    REQUEST_PERMISSION_GET_ACCOUNTS,
                    android.Manifest.permission.GET_ACCOUNTS)
        }
    }

    private val isDeviceOnline: Boolean
        get() {
            val connMgr = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
            val networkInfo = connMgr.activeNetworkInfo
            return networkInfo != null && networkInfo.isConnected
        }

    override fun onRequestPermissionsResult(requestCode: Int,
                                            permissions: Array<String>,
                                            grantResults: IntArray) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults)
        EasyPermissions.onRequestPermissionsResult(
                requestCode, permissions, grantResults, this)
    }


    override fun onPermissionsGranted(requestCode: Int, perms: List<String>) {

    }

    override fun onPermissionsDenied(requestCode: Int, perms: List<String>) {

    }

    override fun onActivityResult(
            requestCode: Int, resultCode: Int, data: Intent?) {
        super.onActivityResult(requestCode, resultCode, data)
        when (requestCode) {
            REQUEST_GOOGLE_PLAY_SERVICES -> if (resultCode != Activity.RESULT_OK) {
                toast("This app requires Google Play Services. Please install " + "Google Play Services on your device and relaunch this app.")
            } else {
                getResultsFromApi()
            }
            REQUEST_ACCOUNT_PICKER -> if (resultCode == Activity.RESULT_OK && data != null &&
                    data.extras != null) {
                val accountName = data.getStringExtra(AccountManager.KEY_ACCOUNT_NAME)
                if (accountName != null) {
                    val settings = getPreferences(Context.MODE_PRIVATE)
                    val editor = settings.edit()
                    editor.putString(PREF_ACCOUNT_NAME, accountName)
                    editor.apply()
                    mCredential.selectedAccountName = accountName
                    getResultsFromApi()
                }
            }
            REQUEST_AUTHORIZATION -> if (resultCode == Activity.RESULT_OK) {
                getResultsFromApi()
            }
        }
    }


    internal fun showGooglePlayServicesAvailabilityErrorDialog(
            connectionStatusCode: Int) {
        val apiAvailability = GoogleApiAvailability.getInstance()
        val dialog = apiAvailability.getErrorDialog(
                this@SheetGoogle,
                connectionStatusCode,
                REQUEST_GOOGLE_PLAY_SERVICES)
        dialog.show()
    }

    @SuppressLint("StaticFieldLeak")
    private inner class MakeRequestTask internal constructor(credential: GoogleAccountCredential) : AsyncTask<Void, Void, List<String>>() {
        private var mService: com.google.api.services.sheets.v4.Sheets? = null
        private var mLastError: Exception? = null

        init {
            val transport = AndroidHttp.newCompatibleTransport()
            val jsonFactory = JacksonFactory.getDefaultInstance()
            mService = com.google.api.services.sheets.v4.Sheets.Builder(
                    transport, jsonFactory, credential)
                    .setApplicationName("Google Sheets API Android Quickstart")
                    .build()
        }

        /**
         * Background task to call Google Sheets API.
         * @param params no parameters needed for this task.
         */
        override fun doInBackground(vararg params: Void): List<String>? {
            try {
                return dataFromApi
            } catch (e: Exception) {
                mLastError = e
                cancel(true)
                return null
            }

        }

        /**
         * Fetch a list of names and majors of students in a sample spreadsheet:
         * https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
         * @return List of names and majors
         * @throws IOException
         */
        private val dataFromApi: List<String>
            @Throws(IOException::class)
            get() {
                val spreadsheetId = "1mV_x0JvE-nP3xRMMtBX8NlQoKjp4qs7oq83t1TfkHx4"
                val range = "A8:D"
                val results = ArrayList<String>()
                val response = this.mService!!.spreadsheets().values()
                        .get(spreadsheetId, range)
                        .execute()
                val values = response.getValues()
                if (values != null) {
                    results.add("Name, Major")
                    for (row in values) {
                        results.add(row[0].toString() + ", " + row[3])
                    }
                }
                return results
            }


        override fun onPreExecute() {
            Toast.makeText(applicationContext, " ", Toast.LENGTH_SHORT).show()
            mProgress.show()
        }

        override fun onPostExecute(output: List<String>?) {
            mProgress.hide()
            val j2 = JSONArray()
            var j1 =JSONObject()
            if (output == null || output.isEmpty() ) {
                toast("No results returned")
            } else {
//                output.add(0, "Data retrieved using the Google Sheets API:")
                var i=0;

                for (data in output){
                    j1.put("email", data)
                    j2.put(i,data)
                    i = i+1
                }

                recyclerView.layoutManager = LinearLayoutManager(this@SheetGoogle)

//                recyclerView.adapter = RecyleJson(j2)
                recyclerView.adapter = RecyleJson(j2)

                toast("Show data")
            }
        }

        override fun onCancelled() {
            mProgress.hide()
            if (mLastError != null) {
                if (mLastError is GooglePlayServicesAvailabilityIOException) {
                    showGooglePlayServicesAvailabilityErrorDialog(
                            (mLastError as GooglePlayServicesAvailabilityIOException)
                                    .connectionStatusCode)
                } else if (mLastError is UserRecoverableAuthIOException) {
                    startActivityForResult(
                            (mLastError as UserRecoverableAuthIOException).intent,
                            SheetGoogle.REQUEST_AUTHORIZATION)
                } else {
                    Toast.makeText(applicationContext, "The following error occurred:" + mLastError!!.message, Toast.LENGTH_SHORT).show()

                }
            } else {
                Toast.makeText(applicationContext, "Request cancelled", Toast.LENGTH_SHORT).show()
            }
        }
    }

    companion object {
        internal val REQUEST_ACCOUNT_PICKER = 1000
        internal val REQUEST_AUTHORIZATION = 1001
        internal val REQUEST_GOOGLE_PLAY_SERVICES = 1002
        internal val REQUEST_PERMISSION_GET_ACCOUNTS = 1003
        private val PREF_ACCOUNT_NAME = "ylight528@gmail.com"
        private val SCOPES = arrayOf(SheetsScopes.SPREADSHEETS_READONLY)
    }
}
