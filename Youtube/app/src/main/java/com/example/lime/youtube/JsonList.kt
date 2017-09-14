package com.example.lime.youtube

import android.os.Build
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.annotation.RequiresApi
import android.support.v7.widget.LinearLayoutManager
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_json_list.*
import kotlinx.android.synthetic.main.tab1.*
import kotlinx.android.synthetic.main.tab2.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject
import java.net.URL

class JsonList : AppCompatActivity() {
    var stringid:String? = null
    lateinit var mainJson:JSONArray
    @RequiresApi(Build.VERSION_CODES.KITKAT)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_json_list)
       var s=0

//        val queyj2 = Volley.newRequestQueue(this@JsonList)
//        val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/search?key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY&channelId=UCx8g6OKTHAyIsmEJr6FPl5w&part=snippet,id&order=date&maxResults=20",null,
//
//                Response.Listener<JSONObject>
//                {
//                    response ->
//                    val setert: JSONArray = response.get("items") as JSONArray
//                    val jsona = JSONArray()
//                    var j1 = JSONObject()
//                    var j3 = JSONObject()
//
//
////                        toast(setert.get(0).toString())
//                    var i=0
//                    while(i<setert.length()-1){
//                        j1= setert.get(i) as JSONObject
//                        j3=j1.get("snippet") as JSONObject
//                        var j4 = JSONObject()
//                        j4=j1.get("id") as JSONObject
//                        val j5= JSONObject()
//                        var thj1 = j3.getJSONObject("thumbnails")
//                        var thj2 = thj1.getJSONObject("default")
//                        var mainthum = thj2.getString("url")
//                        j5.put("id",j4.get("videoId"))
//                        j5.put("title",j3.get("title"))
//                        j5.put("thum",mainthum)
//                        stringid =j4.get("videoId").toString()
//                        val queyj1 = Volley.newRequestQueue(this@JsonList)
//                        val jsonobj1 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/videos?part=statistics&id="+stringid+"&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY",null,
//
//                                Response.Listener<JSONObject>
//                                {
//                                    response ->
//                                    val setert1: JSONArray = response.get("items") as JSONArray
//                                    var j=0
//                                    while(j<setert1.length()){
//                                        j1= setert1.get(j) as JSONObject
//                                        j3=j1.get("statistics") as JSONObject
//                                        j5.put("view",j3.get("viewCount"))
//                                        j5.put("like",j3.get("likeCount"))
//                                        j5.put("dislike",j3.get("dislikeCount"))
//                                        j5.put("comment",j3.get("commentCount"))
//
//                                        j++
//                                        s++
//                                    }
////                                    json1.text=jsona.toString()
//                                    mainJson= jsona
//                                    if(s==setert.length()-1){
//                                        json1.text=mainJson.toString()
//                                    }
//
//                                }, Response.ErrorListener {
//
//                            toast("somthing went wrong")
////
//                        })
//
//                        queyj1.add(jsonobj1)
//
//                        jsona.put(i,j5)
//                        i++
//                    }
//
////                    json1.text=jsona.toString()
//
//                }, Response.ErrorListener {
//
//            toast("somthing went wrong")
////
//        })
//
//        queyj2.add(jsonobj2)

        val queyj1 = Volley.newRequestQueue(this@JsonList)
        val jsonobj1 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/search?part=snippet&q=Solve%20the%20Cube&type=channel&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY",null,

                Response.Listener<JSONObject>
                {
                    response ->
                    json1.text=response.toString()

                }, Response.ErrorListener {
            toast("Network issue")
        })

        queyj1.add(jsonobj1)

    }

}
