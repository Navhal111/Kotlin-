@file:Suppress("DEPRECATION")

package com.example.lime.googlesheet

import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import kotlinx.android.synthetic.main.activity_editdata.*
import org.jetbrains.anko.toast
import android.accounts.AccountManager
import android.annotation.SuppressLint
import android.app.Activity
import android.app.ProgressDialog
import android.content.Context
import android.content.Intent
import android.net.ConnectivityManager
import android.os.AsyncTask
import android.os.Build
import android.support.annotation.RequiresApi
import android.support.v7.widget.LinearLayoutManager

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
import org.json.JSONArray
import org.json.JSONObject
import java.io.IOException
import com.google.api.services.sheets.v4.model.ValueRange

import pub.devrel.easypermissions.EasyPermissions
import java.util.*
import kotlin.collections.ArrayList

@Suppress("DEPRECATION")
class Editdata : AppCompatActivity() , EasyPermissions.PermissionCallbacks {
    private lateinit var mCredential: GoogleAccountCredential
    internal lateinit var mProgress: ProgressDialog
    private lateinit var mainarray: List<String>
    var position1: Int = 0
    lateinit var value2: Any
    lateinit var value3: Any
    lateinit var value4: Any
    lateinit var value5: Any

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_editdata)

        val myIntent = intent
        val firstKeyName = myIntent.getStringExtra("Keyvalue").toString()
        position1 = myIntent.getIntExtra("Position",0)
        val lenght = firstKeyName.length
        val str = firstKeyName.substring(1,lenght-1)
        val data = str.split(',')
        name1.setText(data[0])
        name2.setText(data[1])
        name3.setText(data[2])
        name4.setText(data[3])
        mainarray= str.split(',')
        button.setOnClickListener{
            value2 = name1.text.toString()
            value3 = name2.text.toString()
            value4 = name3.text.toString()
            value5 = name4.text.toString()

            mCredential = GoogleAccountCredential.usingOAuth2(
                    applicationContext, Arrays.asList(*SCOPES))
                    .setBackOff(ExponentialBackOff())


            mProgress = ProgressDialog(this)
            mProgress.setMessage("Calling Google Sheets API ...")
            getResultsFromApi()
            toast("updated value at ")
        }
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
                this@Editdata,
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

            return try {
                dataFromApi
            } catch (e: Exception) {
                mLastError = e
                cancel(true)
                null
            }

        }

        /**
         * Fetch a list of names and majors of students in a sample spreadsheet:
         * https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
         * @return List of names and majors
         * @throws IOException
         */
        private val dataFromApi: List<String>
            @RequiresApi(Build.VERSION_CODES.O) @Throws(IOException::class)
            get() {
                val spreadsheetId = "1mV_x0JvE-nP3xRMMtBX8NlQoKjp4qs7oq83t1TfkHx4"
//                val range = "A8:D"
                val results = ArrayList<String>()
//                val response: ValueRange? = this.mService!!.spreadsheets().values()
//                        .get(spreadsheetId, range)
//                        .execute()

                //Where each value represents the list of objects that is to be written to a range
                //I simply want to edit a single row, so I use a single list of objects
                val values1 = ArrayList<List<Any>>()

                val data1 = ArrayList<Any>()
                 data1.add(value2)
                 data1.add(value3)
                 data1.add(value4)
                 data1.add(value5)

                val n = position1+8
                 values1.add(data1)
                val requestBody = ValueRange()
                requestBody.majorDimension = "ROWS"
                requestBody.range = "A$n:D"
                requestBody.setValues(values1)

                this.mService!!.spreadsheets().values()
                        .update(spreadsheetId, "A$n:D", requestBody)
                        .setValueInputOption("RAW")
                        .execute()


                return results
            }


        override fun onPreExecute() {
            mProgress.show()
        }

        override fun onPostExecute(output: List<String>?) {
            mProgress.hide()
            val j2 = JSONArray()
            val j1 =JSONObject()
            if (output == null || output.isEmpty() ) {

                val intent = Intent(this@Editdata, SheetGoogle::class.java)
                startActivity(intent)
                finish()
            } else {
//                output.add(0, "Data retrieved using the Google Sheets API:")
                var i=0

                for (data in output){
                    j1.put("email", data)
                    j2.put(i,data)
                    i += 1
                }

                recyclerView.layoutManager = LinearLayoutManager(this@Editdata)

                recyclerView.adapter = RecyleJson(j2)

                toast("Show data")
            }
        }

        override fun onCancelled() {
            mProgress.hide()
            if (mLastError != null) {
                when (mLastError) {
                    is GooglePlayServicesAvailabilityIOException -> showGooglePlayServicesAvailabilityErrorDialog(
                            (mLastError as GooglePlayServicesAvailabilityIOException)
                                    .connectionStatusCode)
                    is UserRecoverableAuthIOException -> startActivityForResult(
                            (mLastError as UserRecoverableAuthIOException).intent,
                            SheetGoogle.REQUEST_AUTHORIZATION)
                    else -> Toast.makeText(applicationContext, "The following error occurred:" + mLastError!!.message, Toast.LENGTH_SHORT).show()
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
        private val SCOPES = arrayOf(SheetsScopes.SPREADSHEETS)
    }

}
