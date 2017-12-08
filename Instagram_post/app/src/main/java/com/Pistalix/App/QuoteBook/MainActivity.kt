package com.Pistalix.App.QuoteBook

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
import kotlinx.android.synthetic.main.activity_main.*
import com.google.android.gms.ads.AdView
import com.orhanobut.dialogplus.DialogPlus
import com.orhanobut.dialogplus.ViewHolder
import org.jetbrains.anko.toast
import org.json.JSONException
import org.json.JSONObject


class MainActivity : AppCompatActivity() {
    val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
    lateinit var dailog: DialogPlus
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        mAdView = findViewById<View>(R.id.adView) as AdView
        val adRequest = AdRequest.Builder()
                .build()
        mAdView!!.loadAd(adRequest)

        var check = (ContextCompat.checkSelfPermission(this, Manifest.permission.ACCESS_NETWORK_STATE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {

            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.ACCESS_NETWORK_STATE),
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
                                val Newvirsion : JSONObject = response.getJSONObject("QueotsBook")
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
        millionairebull.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "millionairebull");
            startActivity(intent)
            finish()

        }

        fashion_worth_billions.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "fashion_worth_billions");
            startActivity(intent)
            finish()
        }

        addicted2success.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "addicted2success");
            startActivity(intent)
            finish()
        }
        successallday.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "successallday");
            startActivity(intent)
            finish()
        }
        mindsetpilot.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "mindsetpilot");
            startActivity(intent)
            finish()
        }
        billionaire_mindset.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "billionaire.mindset");
            startActivity(intent)
            finish()
        }
        motivationmafia.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "motivationmafia");
            startActivity(intent)
            finish()
        }
        quotesnsuccess.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "quotesnsuccess");
            startActivity(intent)
            finish()
        }
        motivationblog.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "motivationblog");
            startActivity(intent)
            finish()
        }

        words_worth_billions.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "words_worth_billions");
            startActivity(intent)
            finish()
        }

        motivation_mondays.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "motivation_mondays");
            startActivity(intent)
            finish()
        }

        positiveenergy_plus.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "positiveenergy_plus");
            startActivity(intent)
            finish()
        }

        share_app.setOnClickListener{

            val intent = Intent(android.content.Intent.ACTION_SEND)
            intent.type = "text/plain*"
            intent.putExtra(Intent.EXTRA_SUBJECT, "Yoga");
            intent.putExtra(Intent.EXTRA_TEXT, "https://goo.gl/ds4qND");
            startActivity(Intent.createChooser(intent, "Share via"))
        }
        rate_app.setOnClickListener{

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/pU51Aw" )))
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


    public override fun onPause() {
        if (mAdView != null) {
            mAdView!!.pause()
        }
        super.onPause()
    }

    public override fun onResume() {
        super.onResume()
        if (mAdView != null) {
            mAdView!!.resume()
        }
    }

    public override fun onDestroy() {
        if (mAdView != null) {
            mAdView!!.destroy()
        }
        super.onDestroy()
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
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=com.Pistalix.App.QuoteBook")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=com.Pistalix.App.QuoteBook")))
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
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=com.Pistalix.App.QuoteBook")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=com.Pistalix.App.QuoteBook")))
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
