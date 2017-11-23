package pistalix.hindi.shayari

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
import org.jetbrains.anko.toast


class MainActivity : AppCompatActivity() {
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
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


        gujju_quotes.setOnClickListener{
            checkConnection("luv.line")

        }
        gujju_chu.setOnClickListener{
            checkConnection("the_untold_shayari")
        }

        thegujjugyan.setOnClickListener{

            checkConnection("hearts_never_lies")
        }
        gujju_amdavadi.setOnClickListener{

            checkConnection("i_love_shayari")

        }

        gujaraticomedy.setOnClickListener{

            checkConnection("awesome_shayari_")

        }
        gujaratibablo.setOnClickListener{

            checkConnection("phir_mohabaat")

        }

        gujju_comedy.setOnClickListener{

            checkConnection("first_love_never_dies")

        }
        thegujjurocks.setOnClickListener{
            checkConnection("world_of_shayari")
        }

        gujarati_tweets.setOnClickListener{
            checkConnection("eyes_never_lies")

        }
        gujju_bhasha.setOnClickListener{
            checkConnection("silent_loverss")
        }

        gujarati_shayar.setOnClickListener{
            checkConnection("aye.jaan")
        }
        gujju_thegreat.setOnClickListener{
            checkConnection("meri_diary_")
        }

        gujju_facts.setOnClickListener{
            checkConnection("shayari.for.jaan")
        }
        gujju_minion.setOnClickListener{
            checkConnection("_ultimate.shayari_")

        }

        gujju_prem.setOnClickListener{
            checkConnection("shayris__")
        }
        gujarati_fatakdo.setOnClickListener{
            checkConnection("2_lines__shayari")
        }

        gujjubox.setOnClickListener{
            checkConnection("hidden_world_")

        }
        gujjutroller.setOnClickListener{
            checkConnection("awesome_shayari143")

        }

        gujju_mathabhare.setOnClickListener{
            checkConnection("u_broke_me_bae")
        }
        gujju_no_love.setOnClickListener{
            checkConnection("hum_tum_forever")

        }

        gujju__quotes__2017.setOnClickListener{
            checkConnection("innocent__lover_")

        }
        gujju_ni_aashiqui.setOnClickListener{
            checkConnection("sad_shayri_by_broken_heart_")
        }


        share_app.setOnClickListener{

            val intent = Intent(android.content.Intent.ACTION_SEND)
            intent.type = "text/plain*"
            intent.putExtra(Intent.EXTRA_SUBJECT, "Yoga")
            intent.putExtra(Intent.EXTRA_TEXT, "https://goo.gl/x65DPQ")
            startActivity(Intent.createChooser(intent, "Share via"))
        }
        rate_app.setOnClickListener{

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/x65DPQ" )))
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
//
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

        startActivity(Intent(this@MainActivity,BackButton::class.java))

    }
}

