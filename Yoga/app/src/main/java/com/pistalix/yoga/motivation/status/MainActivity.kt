package com.pistalix.yoga.motivation.status

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
import org.jetbrains.anko.alert
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

        startActivity(Intent(this@MainActivity,BackButton::class.java))

    }
}
