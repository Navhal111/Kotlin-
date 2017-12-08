package com.pistalix.yoga.motivation.status

import android.Manifest
import android.app.Activity
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
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.orhanobut.dialogplus.DialogPlus
import com.orhanobut.dialogplus.ViewHolder
import kotlinx.android.synthetic.main.activity_main.*
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
        }
        val connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val nwInfo = connectivityManager.activeNetworkInfo
        if (nwInfo != null && nwInfo.isConnectedOrConnecting) {
            try{
                val url =getString(R.string.virsion_url)
                val CurruntVirsion :Int = BuildConfig.VERSION_CODE
                val Virsionquery = Volley.newRequestQueue(this@MainActivity)
                val jsonobj2 = JsonObjectRequest(Request.Method.GET, url,null,
                        Response.Listener<JSONObject> {
                            response ->
                            try{
                                val Newvirsion : JSONObject = response.getJSONObject("Yoga")
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

        }else{
            toast("Check your connection")
        }
        yogachallenge.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplayTag::class.java)
            intent.putExtra("keyName", "yogachallenge");
            startActivity(intent)

        }

        yogadaily.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplayTag::class.java)
            intent.putExtra("keyName", "yogadaily");
            startActivity(intent)

        }

        yoga.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yoga");
            startActivity(intent)

        }

        yoga_boost.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yoga.boost");
            startActivity(intent)

        }

        yoga_digest.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yoga_digest");
            startActivity(intent)

        }

        yoga_girl.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yoga_girl");
            startActivity(intent)

        }

        bestyoga.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "bestyoga");
            startActivity(intent)

        }

        yoga_tutorials.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yoga.tutorials");
            startActivity(intent)

        }

        yoga_ky.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yoga_ky");
            startActivity(intent)

        }


        yogaalignment.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yogaalignment");
            startActivity(intent)

        }

        yogachannel.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "michaeldunne13");
            startActivity(intent)

        }

        yogapractice.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "yogapractice");
            startActivity(intent)

        }
        share_app.setOnClickListener{

            val intent = Intent(android.content.Intent.ACTION_SEND)
            intent.type = "text/plain*"
            intent.putExtra(Intent.EXTRA_SUBJECT, "Yoga");
            intent.putExtra(Intent.EXTRA_TEXT, "https://goo.gl/sw8o29");
            startActivity(Intent.createChooser(intent, "Share via"))
        }
        rate_app.setOnClickListener{

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/sw8o29" )))
        }

        fitness.setOnClickListener{

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/ds4qND" )))
        }
        quotesbook.setOnClickListener{

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/pU51Aw" )))
        }
        yoga_app.setOnClickListener{

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/sw8o29" )))
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
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=com.pistalix.yoga.motivation.status")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=com.pistalix.yoga.motivation.status")))
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
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=com.pistalix.yoga.motivation.status")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=com.pistalix.yoga.motivation.status")))
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
