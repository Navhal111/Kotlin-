package pistalix.ChristmasPhotoStickerFrames.PhotoEditor

import android.Manifest
import android.annotation.TargetApi
import android.app.Activity
import android.content.DialogInterface
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.os.Build
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.provider.MediaStore
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.support.v7.app.AlertDialog
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_main.*
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity.CropActivity
import java.util.ArrayList
import java.util.HashMap

class MainActivity : AppCompatActivity() {
    private val REQUEST_ID_MULTIPLE_PERMISSIONS = 300
    private val RE_GALLERY = 2
    private val MY_REQUEST_CODE = 1
    private val MY_REQUEST_CODE1 = 3
    private val MY_REQUEST_CODE2 = 4
    var uri: Uri? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        imageselect.setOnClickListener{
            if(checkAndRequestPermissions()){
                openGallery()
            }else{
                openGallery()
            }

        }
    }

    private fun openGallery() {
        try{
        val i = Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI)
        startActivityForResult(i, RE_GALLERY)

        }catch(e:IllegalArgumentException){
            Toast.makeText(applicationContext, "Not Select Any Image", Toast.LENGTH_LONG).show()
        }catch(e:Exception){
            Toast.makeText(applicationContext, "Not Select Any Image", Toast.LENGTH_LONG).show()
        }
//        showFBInterstitial()
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int,data: Intent) {

            super.onActivityResult(requestCode, resultCode, data)
            if (resultCode == Activity.RESULT_OK && resultCode !=0) {
                if (requestCode == RE_GALLERY) {
                    uri = data.data
                    val inten = Intent(this, CropActivity::class.java)
                    inten.putExtra("image_Uri", this.uri.toString())
                    startActivity(inten)
                }else if (resultCode == Activity.RESULT_CANCELED) {
                    Toast.makeText(applicationContext, "Not Select Any Image", Toast.LENGTH_LONG).show()
                }
            }


    }
    private fun checkAndRequestPermissions(): Boolean {
        val camera = ContextCompat.checkSelfPermission(this, android.Manifest.permission.CAMERA)
        val writeStorage = ContextCompat.checkSelfPermission(this, android.Manifest.permission.WRITE_EXTERNAL_STORAGE)
        val readStorage = ContextCompat.checkSelfPermission(this, Manifest.permission.READ_EXTERNAL_STORAGE)

        val listPermissionsNeeded = ArrayList<String>()
        if (writeStorage != PackageManager.PERMISSION_GRANTED) {
            listPermissionsNeeded.add(android.Manifest.permission.WRITE_EXTERNAL_STORAGE)
        }
        if (readStorage != PackageManager.PERMISSION_GRANTED) {
            listPermissionsNeeded.add(Manifest.permission.READ_EXTERNAL_STORAGE)
        }
        if (camera != PackageManager.PERMISSION_GRANTED) {
            listPermissionsNeeded.add(android.Manifest.permission.CAMERA)
        }
        if (!listPermissionsNeeded.isEmpty()) {
            ActivityCompat.requestPermissions(this, listPermissionsNeeded.toTypedArray(), REQUEST_ID_MULTIPLE_PERMISSIONS)
            return false
        }
        return true
    }

    @TargetApi(Build.VERSION_CODES.M)
    override fun onRequestPermissionsResult(requestCode: Int, permissions: Array<String>, grantResults: IntArray) {
        when (requestCode) {

            REQUEST_ID_MULTIPLE_PERMISSIONS -> {

                val perms = HashMap<String, Int>()
                // Initialize the map with both permissions
                perms.put(android.Manifest.permission.CAMERA, PackageManager.PERMISSION_GRANTED)
                perms.put(android.Manifest.permission.WRITE_EXTERNAL_STORAGE, PackageManager.PERMISSION_GRANTED)
                perms.put(Manifest.permission.READ_EXTERNAL_STORAGE, PackageManager.PERMISSION_GRANTED)
                // Fill with actual results from user
                if (grantResults.size > 0) {
                    for (i in permissions.indices)
                        perms.put(permissions[i], grantResults[i])
                    // Check for both permissions
                    if (perms[android.Manifest.permission.CAMERA] == PackageManager.PERMISSION_GRANTED
                            && perms[android.Manifest.permission.WRITE_EXTERNAL_STORAGE] == PackageManager.PERMISSION_GRANTED
                            && perms[Manifest.permission.READ_EXTERNAL_STORAGE] == PackageManager.PERMISSION_GRANTED) {
                        //                        openCamera();
                        // process the normal flow
                        //else any one or both the permissions are not granted
                    } else {
                        //permission is denied (this is the first time, when "never ask again" is not checked) so ask again explaining the usage of permission
                        //                        // shouldShowRequestPermissionRationale will return true
                        //show the dialog or snackbar saying its necessary and try again otherwise proceed with setup.
                        if (ActivityCompat.shouldShowRequestPermissionRationale(this, android.Manifest.permission.CAMERA)
                                || ActivityCompat.shouldShowRequestPermissionRationale(this, android.Manifest.permission.WRITE_EXTERNAL_STORAGE)
                                || ActivityCompat.shouldShowRequestPermissionRationale(this, Manifest.permission.READ_EXTERNAL_STORAGE)) {
                            showDialogOK("Permission required for this app",
                                    DialogInterface.OnClickListener { dialog, which ->
                                        when (which) {
                                            DialogInterface.BUTTON_POSITIVE -> checkAndRequestPermissions()
                                            DialogInterface.BUTTON_NEGATIVE -> {
                                            }
                                        }// proceed with logic by disabling the related features or quit the app.
                                    })
                        } else {
                            Toast.makeText(this, "Go to settings and enable permissions", Toast.LENGTH_LONG)
                                    .show()
                            //proceed with logic by disabling the related features or quit the app.
                        }//permission is denied (and never ask again is  checked)
                        //shouldShowRequestPermissionRationale will return false
                    }
                }
            }

            MY_REQUEST_CODE -> if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                openGallery()

            } else {
                if (checkSelfPermission(android.Manifest.permission.READ_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {

                    requestPermissions(arrayOf(android.Manifest.permission.READ_EXTERNAL_STORAGE),
                            MY_REQUEST_CODE)
                }
            }

            MY_REQUEST_CODE2 -> if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {
            } else {
                if (checkSelfPermission(android.Manifest.permission.READ_EXTERNAL_STORAGE) != PackageManager.PERMISSION_GRANTED) {

                    requestPermissions(arrayOf(android.Manifest.permission.READ_EXTERNAL_STORAGE),
                            MY_REQUEST_CODE)
                }
            }
        }
    }

    private fun showDialogOK(message: String, okListener: DialogInterface.OnClickListener) {
        AlertDialog.Builder(this@MainActivity)
                .setMessage(message)
                .setPositiveButton("OK", okListener)
                .setNegativeButton("Cancel", okListener)
                .create()
                .show()
    }

}
