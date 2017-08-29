package com.example.lime.googlesheet

import android.content.Intent
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import kotlinx.android.synthetic.main.activity_main.*



class MainActivity : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)


         button2.setOnClickListener{

             val intent = Intent(this@MainActivity, Main2Activity::class.java)
             startActivity(intent)

         }

        button3.setOnClickListener{

            val intent = Intent(this@MainActivity, SheetGoogle::class.java)
            startActivity(intent)

        }
    }
}
