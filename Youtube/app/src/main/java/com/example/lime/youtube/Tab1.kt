package com.example.lime.youtube

import android.os.Bundle
import android.support.v7.app.AppCompatActivity
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.tab1.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject


//MANq5cMLgPU
class Tab1 : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.tab1)

        ref1.setOnClickListener{
            val queyj = Volley.newRequestQueue(this@Tab1)
            val jsonobj = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=UCx8g6OKTHAyIsmEJr6FPl5w&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY",null,

                    Response.Listener<JSONObject>
                    {
                        response ->
                        //                    toast(response.toString())
                        val setert: JSONArray = response.get("items") as JSONArray
                        val views: JSONObject = setert.getJSONObject(0).getJSONObject("statistics")

                        viewcount.text=views.getString("viewCount")
                        subcount.text=views.getString("subscriberCount")
                        videocount.text=views.getString("videoCount")
                        toast("Refresh Values")
                    }, Response.ErrorListener {
                toast("Network issue")
            })
            queyj.add(jsonobj)

        }
    }
}