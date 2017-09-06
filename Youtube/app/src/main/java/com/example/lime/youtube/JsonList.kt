package com.example.lime.youtube

import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v7.widget.LinearLayoutManager
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_json_list.*
import kotlinx.android.synthetic.main.tab2.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject
import java.net.URL

class JsonList : AppCompatActivity() {
    var stringid:String? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_json_list)

        val queyj2 = Volley.newRequestQueue(this@JsonList)
        val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/search?key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY&channelId=UCx8g6OKTHAyIsmEJr6FPl5w&part=snippet,id&order=date&maxResults=20",null,

                Response.Listener<JSONObject>
                {
                    response ->
                    val setert: JSONArray = response.get("items") as JSONArray
                    val jsona = JSONArray()
                    var j1 = JSONObject()
                    var j3 = JSONObject()


//                        toast(setert.get(0).toString())
                    var i=0
                    while(i<setert.length()-1){
                        j1= setert.get(i) as JSONObject
                        j3=j1.get("snippet") as JSONObject
                        var j4 = JSONObject()
                        j4=j1.get("id") as JSONObject
                        val j5= JSONObject()
                        j5.put("id",j4.get("videoId"))
                        j5.put("title",j3.get("title"))
                        stringid +=','+j4.get("videoId").toString()
                        jsona.put(i,j5)
                        i++
                    }
                    val queyj1 = Volley.newRequestQueue(this@JsonList)
                    val jsonobj1 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/videos?part=statistics&id="+stringid+"&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY",null,

                            Response.Listener<JSONObject>
                            {
                                response ->
                                val setert1: JSONArray = response.get("items") as JSONArray
                                json1.text=setert1.toString()


                            }, Response.ErrorListener {

                        toast("somthing went wrong")
//
                    })

                    queyj1.add(jsonobj1)
//                    json1.text=jsona.toString()

                }, Response.ErrorListener {

            toast("somthing went wrong")
//
        })

        queyj2.add(jsonobj2)


    }

}
