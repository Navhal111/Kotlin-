package com.example.lime.myapplication

import android.content.Context
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import kotlinx.android.synthetic.main.activity_main.*
import org.jetbrains.anko.toast
import android.content.Intent
import android.net.Uri
import android.view.View
import android.widget.AdapterView
import android.widget.ArrayAdapter
import android.widget.MediaController
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.Response.Listener
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import org.jetbrains.anko.alert
import org.jetbrains.anko.email
import org.jetbrains.anko.selector
import org.json.JSONArray
import org.json.JSONObject
import android.net.ConnectivityManager
import android.net.wifi.WifiManager


@Suppress("NAME_SHADOWING")
class MainActivity : AppCompatActivity(){


    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        val j = JSONObject()
        j.put("email", "ritesh@gmail.com")
        j.put("password", "ritesh123")

        val j1 = JSONObject()
        j1.put("new", j)

        val j2 = JSONArray()
        j2.put(0, j)
        j2.put(1, j1)

       @Suppress("VARIABLE_WITH_REDUNDANT_INITIALIZER")
       var st = " hello"

        button.setOnClickListener {

            val str= "set : set"
            toast(str.split(":").toString())

            val queue = Volley.newRequestQueue(this@MainActivity)
            val postRequest = StringRequest(Request.Method.GET, "https://freemusicarchive.org/api/trackSearch?q=deerhoof&limit=10", Listener<String>
            {
                response ->

                toast(response)
                st = "success request string"
                textid.text=st

            }, Response.ErrorListener {

                toast("Errror")
                st ="Error string"
                textid.text=st
            })

            queue.add(postRequest)
        }
//        https@ //github.com/telerik/Android-samples/tree/master/Blogs/Json-Reader
// https://www.youtube.com/watch?v=d8ALcQiuPWs
        button1.setOnClickListener {
            val queyj = Volley.newRequestQueue(this@MainActivity)
            val jsonobj = JsonObjectRequest(Request.Method.POST, "http://192.168.0.3:8080/api/v1/index.php/adminlogin", j, Listener<JSONObject>
            {
                response ->
                toast(response.toString())
                st = "success request json"
                textid.text = st

            }, Response.ErrorListener {
                toast("Errror")
                st ="Error json "
                textid.text =st
            })

            queyj.add(jsonobj)
        }


        button3.setOnClickListener {

            alert("chnage", j2.toString()) {

                positiveButton("Yes") {

                    val intent = Intent(this@MainActivity, Newscr::class.java)
//                    intent.putExtra("name", "Ritesh")
                    startActivity(intent)
                    finish()
                }

                negativeButton("No") {

                    toast(j2.toString())
                }

            }.show()


        }


        button4.setOnClickListener {

            val lang = listOf("Java", "Reactjs", "Kotlin")
            selector("What is your favorite Langvage?", lang) { i ->
                toast("So your favorite Langvage is ${lang[i]}, right?")
            }

        }
        button5.setOnClickListener {

            alert("You want to send email", "Email") {

                positiveButton("Yes") {

                    email("ylight528@gmail.com", "Here I am!", "Heloo")
                }

                negativeButton("Play video") {



                    val uri ="http://192.168.0.3:8080/api/v1/demo_upload/31323a33323a3530_2017-08-10%2012:32:50_31323a30373a3533_2017-08-10%2012-07-53_ready%20shree%20krishna%20ringtone.3gp"
                    videoView.setVideoURI(Uri.parse(uri))
                    val mediaController =MediaController(this@MainActivity)
                    mediaController.setAnchorView(videoView)
                    videoView.setMediaController(mediaController)
                    videoView.start()
                    toast("Not send")


                }

            }.show()

        }
//        val numbers: IntArray = intArrayOf(10, 20, 30, 40, 50)
        val lang = arrayOf("a", "b", "c")
        val num2 = arrayOf(1,2,3)
        spinner.adapter = ArrayAdapter<String>(this,android.R.layout.simple_spinner_item,lang)
        spinner1.adapter = ArrayAdapter<Int>(this,android.R.layout.simple_spinner_item,num2)

        val li: List<String> = listOf("Java","Kotlin","Reactjs","puthon","PHP","Js","MySql","XHP")
        profile.adapter = ArrayAdapter<String>(this,android.R.layout.simple_list_item_1,li)

        spinner.onItemSelectedListener = object : AdapterView.OnItemSelectedListener {

            override fun onItemSelected(parent: AdapterView<*>, view: View, position: Int, id: Long) {
                toast(lang[position])

                if(lang[position] =="c"){
                    set()
                }else{
                    spinner1.setSelection(position)
                }

            }
            override fun onNothingSelected(parent: AdapterView<*>) {

            }
        }

        spinner1.onItemSelectedListener = object : AdapterView.OnItemSelectedListener {

            override fun onItemSelected(parent: AdapterView<*>, view: View, position: Int, id: Long) {

               toast(parent.getItemAtPosition(position).toString())
            }
            override fun onNothingSelected(parent: AdapterView<*>) {

            }
        }

        button6.setOnClickListener{

            val cm = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
            val netInfo = cm.activeNetworkInfo



            if (netInfo != null && netInfo.isConnectedOrConnecting) {

                toast("Networks on")

            } else {

                toast("Networks off")
                val wifiManager = application.getSystemService(Context.WIFI_SERVICE) as WifiManager
                wifiManager.isWifiEnabled = true
//                finish()
            }
        }
        button7.setOnClickListener {
            val cm = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
            val netInfo = cm.activeNetworkInfo


            if (netInfo != null && netInfo.isConnectedOrConnecting) {

                val intent = Intent(this@MainActivity, Webview::class.java)
                startActivity(intent)
                toast("Networks on")

            } else {

                toast("Please On Connection ")
                val wifiManager = application.getSystemService(Context.WIFI_SERVICE) as WifiManager
                wifiManager.isWifiEnabled = true
//                finish()
            }


        }


    }

    private fun set(){
        val num1= arrayOf(4,5,6)
        val sead = ArrayAdapter<Int>(this,android.R.layout.simple_spinner_item,num1)

        spinner1.adapter = sead
    }

    override fun onBackPressed(){

        alert("You want close", "close") {

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





