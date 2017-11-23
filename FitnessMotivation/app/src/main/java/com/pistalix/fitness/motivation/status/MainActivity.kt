package com.pistalix.fitness.motivation.status

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
        thegymquotesntips.setOnClickListener{

                val intent = Intent(this@MainActivity, PostDisplay::class.java)
                intent.putExtra("keyName", "thegymquotesntips");
                startActivity(intent)
                 finish()

            }

        gymquotesolver.setOnClickListener{
                val intent = Intent(this@MainActivity, PostDisplay::class.java)
                intent.putExtra("keyName", "gymquotesolver");
                startActivity(intent)
            finish()
            }

        fitnessquotes_.setOnClickListener{
                val intent = Intent(this@MainActivity, PostDisplay::class.java)
                intent.putExtra("keyName", "fitnessquotes_");
                startActivity(intent)
            finish()
            }

        fitnessmotivation_quotes.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "fitnessmotivation_quotes");
            startActivity(intent)
            finish()
        }

        quotefit.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "quotefit");
            startActivity(intent)
            finish()
        }

        _fitness_quotes.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "_fitness_quotes");
            startActivity(intent)
            finish()
        }

        gym_beast_mode.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "gym_beast_mode");
            startActivity(intent)
            finish()
        }

        daily_does_of_fit.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplayTag::class.java)
            intent.putExtra("keyName", "dailygym");
            startActivity(intent)
            finish()
        }

        _gymquotes.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "_gymquotes");
            startActivity(intent)
            finish()
        }


        fitness_motivation_quotes.setOnClickListener{

            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "fitness.motivation.quotes");
            startActivity(intent)
            finish()
        }

        michaeldunne13.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "michaeldunne13");
            startActivity(intent)
            finish()
        }

        gymquotesz.setOnClickListener{
            val intent = Intent(this@MainActivity, PostDisplay::class.java)
            intent.putExtra("keyName", "gymquotesz");
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

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/ds4qND" )))
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
