package com.example.lime.myapplication

import android.os.Bundle
import android.support.v7.app.AppCompatActivity
import android.view.KeyEvent
import android.view.MotionEvent
import android.webkit.WebChromeClient
import android.webkit.WebViewClient
import kotlinx.android.synthetic.main.web_view.*
import org.jetbrains.anko.alert
import org.jetbrains.anko.toast

class Webview : AppCompatActivity(){

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.web_view)
        Web.webViewClient = WebViewClient()
//        Web.webChromeClient = WebChromeClient()
        Web.loadUrl("https://www.kjaldci.com/")


        Web.setOnKeyListener { _, _, keyEvent ->
            if (keyEvent.keyCode == KeyEvent.KEYCODE_BACK && !Web.canGoBack()) {
                false
            } else if (keyEvent.keyCode == KeyEvent.KEYCODE_BACK && keyEvent.action == MotionEvent.ACTION_UP) {
                Web.goBack()
                true
            } else true
        }

    }
    override fun onBackPressed(){

        alert("You want to close", "close") {

            positiveButton("Yes") {

                super.onBackPressed()
                finish()
            }

            negativeButton("No") {
                toast("thnx")
            }

        }.show()

    }
}