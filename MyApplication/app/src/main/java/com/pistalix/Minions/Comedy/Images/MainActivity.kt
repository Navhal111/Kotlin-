package com.pistalix.Minions.Comedy.Images

import android.Manifest
import android.app.Activity
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

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minions.india");
            startActivity(intent)
            finish()

        }
        gujju_chu.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "_minions_india");
            startActivity(intent)
            finish()
        }

        thegujjugyan.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minions.us");
            startActivity(intent)
            finish()
        }
        gujju_amdavadi.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minionsco");
            startActivity(intent)
            finish()
        }

        gujaraticomedy.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minions.v");
            startActivity(intent)
            finish()
        }
        gujaratibablo.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minionsoninstagram");
            startActivity(intent)
            finish()
        }

        gujju_comedy.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "_despicable_minions");
            startActivity(intent)
            finish()
        }
        thegujjurocks.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minions.dose");
            startActivity(intent)
            finish()
        }

        gujarati_tweets.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "instaminionsofficial");
            startActivity(intent)
            finish()
        }
        gujju_bhasha.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minionsontour");
            startActivity(intent)
            finish()
        }

        gujarati_shayar.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minionsofminions");
            startActivity(intent)
            finish()
        }
        gujju_thegreat.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minions_kicks");
            startActivity(intent)
            finish()
        }

        gujju_facts.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minionstory");
            startActivity(intent)
            finish()
        }
        gujju_minion.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minions_luv_27");
            startActivity(intent)
            finish()
        }

        gujju_prem.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "_minionquotes");
            startActivity(intent)
            finish()
        }
        gujarati_fatakdo.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "minions.mart");
            startActivity(intent)
            finish()
        }

        share_app.setOnClickListener{

            val intent = Intent(android.content.Intent.ACTION_SEND)
            intent.type = "text/plain*"
            intent.putExtra(Intent.EXTRA_SUBJECT, "Yoga");
            intent.putExtra(Intent.EXTRA_TEXT, "https://goo.gl/6vWecZ");
            startActivity(Intent.createChooser(intent, "Share via"))
        }
        rate_app.setOnClickListener{

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/6vWecZ" )))
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

        startActivity(Intent(this@MainActivity,BackButton::class.java))

    }
}
