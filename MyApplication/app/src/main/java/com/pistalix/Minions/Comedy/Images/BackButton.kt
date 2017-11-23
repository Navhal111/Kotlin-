package com.pistalix.Minions.Comedy.Images

import android.app.Activity
import android.content.Intent
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.util.DisplayMetrics
import kotlinx.android.synthetic.main.activity_back_button.*




class BackButton : Activity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_back_button)

        val metrics = DisplayMetrics()
        windowManager.defaultDisplay.getMetrics(metrics)

        var hight = metrics!!.heightPixels
        var wight = metrics!!.widthPixels

        window.setLayout(((hight*.6).toInt()),((wight*.3).toInt()))

        no_button.setOnClickListener{
            moveTaskToBack(true);
            android.os.Process.killProcess(android.os.Process.myPid());
            System.exit(1);

        }
        yes_button.setOnClickListener{
            finish()

            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/RJ9rp6" )))
        }

        rate_app_back.setOnClickListener{
            finish()
            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/vK5fRC" )))
        }


    }
}