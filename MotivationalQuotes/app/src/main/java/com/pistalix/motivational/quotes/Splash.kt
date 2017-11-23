package com.pistalix.motivational.quotes


import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.content.Intent
import android.os.Handler


class Splash : AppCompatActivity() {
    private val SPLASH_TIME_OUT = 3000
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_splash)

        Handler().postDelayed(Runnable /*
                    * Showing splash screen with a timer. This will be useful when you
                    * want to show case your app logo / company
                    */

        {
            // This method will be executed once the timer is over
            // Start your app main activity
            val i = Intent(this@Splash, MainActivity::class.java)
            startActivity(i)

            // close this activity
            finish()
        }, SPLASH_TIME_OUT.toLong())
    }
}
