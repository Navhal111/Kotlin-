package com.pistalix.gujrati.jalso

import android.Manifest
import android.app.Activity
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.view.View
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import kotlinx.android.synthetic.main.activity_main.*
import android.net.ConnectivityManager
import android.view.Gravity
import android.widget.TextView
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.orhanobut.dialogplus.DialogPlus
import com.orhanobut.dialogplus.ViewHolder
import org.jetbrains.anko.toast
import org.json.JSONException
import org.json.JSONObject


class MainActivity : AppCompatActivity() {
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
    lateinit var dailog: DialogPlus
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        mAdView = findViewById<View>(R.id.adView) as AdView
        val adRequest = AdRequest.Builder()
                .build()
        mAdView!!.loadAd(adRequest)

        val check = (ContextCompat.checkSelfPermission(this@MainActivity, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {

            ActivityCompat.requestPermissions(this@MainActivity as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }else{
            try{
                val url =getString(R.string.virsion_url)
                val CurruntVirsion :Int = BuildConfig.VERSION_CODE
                val Virsionquery = Volley.newRequestQueue(this@MainActivity)
                val jsonobj2 = JsonObjectRequest(Request.Method.GET, url,null,
                        Response.Listener<JSONObject> {
                            response ->
                            try{
                                val Newvirsion : JSONObject = response.getJSONObject("GujratiJalso")
                                var virsion  =Newvirsion.getString("virsion")
                                if(Integer.parseInt(virsion) > CurruntVirsion){
                                    update_app(Newvirsion.getString("msg"))
                                }
                            }catch (e: JSONException){

                            }catch (e:NullPointerException){

                            }catch (e:IllegalArgumentException){

                            }catch (e:Exception){
                            }
                        }, Response.ErrorListener {
                    toast("error")

                })
                Virsionquery.add(jsonobj2)
            }catch (e:NullPointerException){
                toast("Exception check yout network")
            }catch (e :IllegalArgumentException){
                toast("Exception check yout network")
            }catch (e:Exception){
                toast("Exception check yout network")
            }
        }


        gujju_quotes.setOnClickListener{
            checkConnection("gujju_quotes")

        }
        gujju_chu.setOnClickListener{
            checkConnection("gujju.chu")
        }

        thegujjugyan.setOnClickListener{

            checkConnection("thegujjugyan")
        }
        gujju_amdavadi.setOnClickListener{

            checkConnection("gujju_amdavadi")

        }

        gujaratibablo.setOnClickListener{

            checkConnection("gujaratibablo")

        }

        gujju_comedy.setOnClickListener{

            checkConnection("gujju_comedy")

        }
        thegujjurocks.setOnClickListener{
            checkConnection("thegujjurocks")
        }

        gujarati_tweets.setOnClickListener{
            checkConnection("gujarati_tweets")

        }
        gujju_bhasha.setOnClickListener{
            checkConnection("gujju_bhasha")
        }

        gujarati_shayar.setOnClickListener{
            checkConnection("gujarati_shayar")
        }
        gujju_thegreat.setOnClickListener{
            checkConnection("gujju_thegreat")
        }

        gujju_facts.setOnClickListener{
            checkConnection("gujju.facts")
        }


        gujju_prem.setOnClickListener{
            checkConnection("gujju_prem")
        }
        gujarati_fatakdo.setOnClickListener{
            checkConnection("gujarati_fatakdo")
        }

        gujjubox.setOnClickListener{
            checkConnection("gujjubox")

        }
        gujjutroller.setOnClickListener{
            checkConnection("dil_thi_shayri")

        }

        gujju_mathabhare.setOnClickListener{
            checkConnection("gujju_mathabhare")
        }
        gujju_no_love.setOnClickListener{
            checkConnection("gujju_no_love")

        }

        gujju__quotes__2017.setOnClickListener{
            checkConnection("gujju__quotes__2017")

        }
        gujju_ni_aashiqui.setOnClickListener{
            checkConnection("gujju_ni_aashiqui")
        }

        thegujjuviral.setOnClickListener{
            checkConnection("thegujjuviral")
        }
        gujju_bablo.setOnClickListener{
            checkConnection("gujju_bablo")
        }

        gujju_love_.setOnClickListener{
            checkConnection("gujju_love_")
        }
        gujarati_rasdhar.setOnClickListener{
            checkConnection("gujju_comedy")
        }

        gujju_philosopher.setOnClickListener{
            checkConnection("gujju_philosopher")

        }
        gujarati_jamavat.setOnClickListener{
            checkConnection("gujrati_chats")

        }

        dilgujarati.setOnClickListener{
            checkConnection("dilgujarati")
        }
        gujarati_shayaris.setOnClickListener{
            checkConnection("gujarati_shayaris")

        }

        share_app.setOnClickListener{

            val intent = Intent(android.content.Intent.ACTION_SEND)
            intent.type = "text/plain*"
            intent.putExtra(Intent.EXTRA_SUBJECT, "Yoga")
            intent.putExtra(Intent.EXTRA_TEXT, "https://goo.gl/vK5fRC")
            startActivity(Intent.createChooser(intent, "Share via"))
        }
        rate_app.setOnClickListener{

            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=com.pistalix.gujrati.jalso")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=com.pistalix.gujrati.jalso")))
            }
        }
        other_app.setOnClickListener{

            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
            }
        }

    }

    private fun checkConnection(NamePage:String) {

        val connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val nwInfo = connectivityManager.activeNetworkInfo
        if (nwInfo != null && nwInfo.isConnectedOrConnecting) {

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", NamePage)
            startActivity(intent)
            finish()

        }else{

            toast("Check your Network Connection")
        }
    }

    override fun onBackPressed(){

        dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.activity_back_button)).setInAnimation(R.anim.abc_fade_in).create()
        try{

            var yes = dailog.findViewById(R.id.yes_button)
            var no = dailog.findViewById(R.id.no_button)
            var rate = dailog.findViewById(R.id.rate_app_back)
            yes.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
                }
            }
            no.setOnClickListener{

                moveTaskToBack(true);
                android.os.Process.killProcess(android.os.Process.myPid());
                System.exit(1);

            }
            rate.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=com.pistalix.gujrati.jalso")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=com.pistalix.gujrati.jalso")))
                }
            }
            dailog.show()
        }catch (e :NullPointerException){


        }catch (e:IllegalArgumentException){

        }

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
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=com.pistalix.gujrati.jalso")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=com.pistalix.gujrati.jalso")))
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
