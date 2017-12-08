package pistalix.sadvideostatus.sadvideosong

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
//        val connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
//        val nwInfo = connectivityManager.activeNetworkInfo
//        if (nwInfo != null && nwInfo.isConnectedOrConnecting) {
//            try{
//                val url =getString(R.string.virsion_url)
//                val CurruntVirsion :Int = BuildConfig.VERSION_CODE
//                val Virsionquery = Volley.newRequestQueue(this@MainActivity)
//                val jsonobj2 = JsonObjectRequest(Request.Method.GET, url,null,
//                        Response.Listener<JSONObject> {
//                            response ->
//                            try{
//                                val Newvirsion :JSONObject = response.getJSONObject("SadStatus")
//                                var virsion  =Newvirsion.getString("virsion")
//                                if(Integer.parseInt(virsion) > CurruntVirsion){
//
//                                }
//                            }catch (e:JSONException){
//
//                            }catch (e:NullPointerException){
//
//                            }catch (e:IllegalArgumentException){
//
//                            }catch (e:Exception){
//                                }
//                        }, Response.ErrorListener {
//                    toast("error")
//
//                })
//                Virsionquery.add(jsonobj2)
//            }catch (e:NullPointerException){
//                toast("Exception check yout network")
//            }catch (e :IllegalArgumentException){
//                toast("Exception check yout network")
//            }catch (e:Exception){
//                toast("Exception check yout network")
//            }
//        }else{
//            SuperActivityToast.create(this).setText("Check Your Network Connection").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
//        }
        val bottomNavigation = findViewById<View>(R.id.bottom_navigation) as BottomNavigation
        bottomNavigation.defaultItem =1
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

//            dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.activity_back_button)).setInAnimation(R.anim.abc_fade_in).create()
//            try{
//
//                var yes = dailog.findViewById(R.id.yes_button)
//                var no = dailog.findViewById(R.id.no_button)
//                var rate = dailog.findViewById(R.id.rate_app_back)
//                yes.setOnClickListener{
//                    try {
//                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
//                    } catch (anfe: android.content.ActivityNotFoundException) {
//                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
//                    }
//                }
//                no.setOnClickListener{
//
//                    moveTaskToBack(true);
//                    android.os.Process.killProcess(android.os.Process.myPid());
//                    System.exit(1);
//
//                }
//                rate.setOnClickListener{
//                    try {
//                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.sadvideostatus.sadvideosong")))
//                    } catch (anfe: android.content.ActivityNotFoundException) {
//                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.sadvideostatus.sadvideosong")))
//                    }
//                }
//                dailog.show()
//            }catch (e :NullPointerException){
//
//
//            }catch (e:IllegalArgumentException){
//
//            }
        startActivity(Intent(this@MainActivity, ListApp::class.java))
    }

    fun update_app(str:String){

        dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.update_app)).setInAnimation(R.anim.abc_fade_in).create()
        try{

            var yes = dailog.findViewById(R.id.yes_button)
            var no = dailog.findViewById(R.id.no_button)
            var msg :TextView  = dailog.findViewById(R.id.update_msg) as TextView
            msg.text =str
            yes.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.sadvideostatus.sadvideosong")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.sadvideostatus.sadvideosong")))
                }
            }
            no.setOnClickListener{
                try{
                    dailog.dismiss()
                }catch (e :NullPointerException){

                    toast("error")
                }catch (e:IllegalArgumentException){
                    toast("error")
                }


            }
            dailog.show()
        }catch (e :NullPointerException){

        toast("error")
        }catch (e:IllegalArgumentException){
            toast("error")
        }

    }
}
