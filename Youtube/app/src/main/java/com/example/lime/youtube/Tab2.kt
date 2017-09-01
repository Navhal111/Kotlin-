package com.example.lime.youtube

import android.os.Bundle
import android.support.v7.app.AppCompatActivity
import android.support.v7.widget.LinearLayoutManager
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.tab1.*
import kotlinx.android.synthetic.main.tab2.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject

class Tab2 : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.tab2)


        val queyj2 = Volley.newRequestQueue(this@Tab2)
        val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/search?key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY&channelId=UCx8g6OKTHAyIsmEJr6FPl5w&part=snippet,id&order=date&maxResults=20",null,

                Response.Listener<JSONObject>
                {
                    response ->
                    //                    toast(response.toString())
                    val setert:JSONArray = response.get("items") as JSONArray
                    val j2 = JSONArray()
                    var j1 =JSONObject()
                    var j3 =JSONObject()
//                        toast(setert.get(0).toString())
                    var i=0
                    while(i<setert.length()-1){
                        j1= setert.get(i) as JSONObject
                        j3=j1.get("snippet") as JSONObject
                        j2.put(i,j3.get("title"))
                        i++
                    }
////                        toast(j2.toString())
//                    recyclerView.layoutManager = LinearLayoutManager(this@Tab2)
//
//                    recyclerView.adapter = RecyleJson(j2)


                }, Response.ErrorListener {
            toast("Network issue")
        })

        queyj2.add(jsonobj2)

        }

}