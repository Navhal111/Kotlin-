package pistalix.romanticvideostatus.romanticvideosong

import android.Manifest
import android.app.Activity
import android.app.Fragment
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.net.ConnectivityManager
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.view.Gravity
import android.view.View
import android.widget.TextView
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdView
import com.orhanobut.dialogplus.DialogPlus
import com.orhanobut.dialogplus.ViewHolder
import com.ss.bottomnavigation.BottomNavigation
import org.jetbrains.anko.toast
import org.json.JSONException
import org.json.JSONObject


class MainActivity : AppCompatActivity() {
    var fragmentTab4: Fragment = FregmentStatus()
    var fragmentTab1: Fragment = VideosList()
    var fragmentTab3: Fragment = DownloadVideos()
    var fragmentTab2: Fragment = HomeFragment()
    lateinit var dailog: DialogPlus
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        val check = (ContextCompat.checkSelfPermission(applicationContext, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {
            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }
        try{
            var check_update = intent.getStringExtra("update");
            var msg = intent.getStringExtra("msg")
            if(check_update == "1"){
                update_app(msg)
            }
        }catch (e:NullPointerException){

        }catch (e:IllegalArgumentException){

        }catch (e:Exception){
        }
        val bottomNavigation = findViewById<View>(R.id.bottom_navigation) as BottomNavigation
//        bottomNavigation.
        bottomNavigation.defaultItem = 1
        bottomNavigation.setOnSelectedItemChangeListener { itemId ->
            when (itemId) {
                R.id.tab_home -> {
                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab2)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }
                R.id.tab_images -> {
                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab1)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }
                R.id.tab_camera -> {

                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab4)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }
                R.id.tab_products -> {

                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab3)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }

            }
        }
    }

    override fun onBackPressed(){

        startActivity(Intent(this@MainActivity, ListApp::class.java))


    }

    fun update_app(str:String){

        dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.update_app)).setInAnimation(R.anim.abc_fade_in).create()
        try{

            var yes = dailog.findViewById(R.id.yes_button)
            var no = dailog.findViewById(R.id.no_button)
            var msg : TextView = dailog.findViewById(R.id.update_msg) as TextView
            msg.text =str
            yes.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.romanticvideostatus.romanticvideosong")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.romanticvideostatus.romanticvideosong")))
                }
            }
            no.setOnClickListener{

                dailog.dismiss()

            }
            dailog.show()
        }catch (e :NullPointerException){

            toast("error")
        }catch (e:IllegalArgumentException){
            toast("error")
        }

    }
}
