package com.pistalix.fitness.motivation.status

import android.app.ProgressDialog
import android.content.Intent
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v7.widget.LinearLayoutManager
import android.support.v7.widget.RecyclerView
import android.view.View
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import kotlinx.android.synthetic.main.activity_post_display.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject

class PostDisplayTag : AppCompatActivity() {
    private var progress: ProgressDialog? = null
    var linearLayoutManager : LinearLayoutManager? = null
    lateinit var jasonArray_post: JSONArray
    var last_int=0
    var max_id: String? = null
    var hasMore = true
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_post_display_tag)
        val text = intent.getStringExtra("keyName")

        mAdView = findViewById<View>(R.id.adView) as AdView
        val adRequest = AdRequest.Builder()
                .build()
        mAdView!!.loadAd(adRequest)
        download(this@PostDisplayTag)

        recyclerView.layoutManager = LinearLayoutManager(this@PostDisplayTag)
        val Get_Post_Queue = Volley.newRequestQueue(this@PostDisplayTag)

        val Json_object_post = JsonObjectRequest(Request.Method.GET, "https://www.instagram.com/explore/tags/$text/?__a=1",null,

                Response.Listener<JSONObject>
                {
                    response ->
                    //                    toast(response.toString())

                    val user_main: JSONObject = response.get("tag") as JSONObject
                    val node_all: JSONObject = user_main.get("media") as JSONObject
                    val array_Json:JSONArray = node_all.get("nodes") as JSONArray
                    val main_json_array= JSONArray()
//                    json_post.text=array_Json.toString()
                    var i=0;
//                    while(i<array_Json.length()){
//                        var main_check=array_Json.get(i) as JSONObject
//                        if(main_check.get("__typename")=="GraphImage"){
//
//                            main_json_array.put(main_check)
//
//                        }
//                        i+=1
//                    }
                    jasonArray_post=array_Json
                    recyclerView.adapter = Recycle_Post_tag(jasonArray_post)
                    if(array_Json.length()!=0){
                        last_int=array_Json.length()
                        linearLayoutManager = recyclerView.layoutManager as LinearLayoutManager

                        var demo = array_Json.get(array_Json.length()-1) as JSONObject
                        max_id= demo["id"].toString()
                    }else{
                        toast("Somthing Went wrong")
                    }
                    progress!!.cancel()



                }, Response.ErrorListener {
            toast("Network issue")
        })

        Get_Post_Queue.add(Json_object_post)



        recyclerView.addOnScrollListener(object : RecyclerView.OnScrollListener() {
            override fun onScrolled(recyclerView: RecyclerView?, dx: Int, dy: Int) {
                super.onScrolled(recyclerView, dx, dy)
                if (hasMore) {
                    val layoutManager = recyclerView!!.layoutManager as LinearLayoutManager
                    //position starts at 0
                    if (layoutManager.findLastCompletelyVisibleItemPosition() == layoutManager.itemCount - 1) {

                        if(last_int>=8){
                            val Get_Post_Queue1 = Volley.newRequestQueue(this@PostDisplayTag)
//
                            val Json_object_post1 = JsonObjectRequest(Request.Method.GET, "https://www.instagram.com/explore/tags/$text/?__a=1&max_id=$max_id",null,

                                    Response.Listener<JSONObject>
                                    {
                                        response ->
                                        //                    toast(response.toString())

                                        val user_main: JSONObject = response.get("tag") as JSONObject
                                        val node_all: JSONObject = user_main.get("media") as JSONObject
                                        val array_Json:JSONArray = node_all.get("nodes") as JSONArray
                                        var main_json_array= JSONObject()
                                        last_int=array_Json.length()
                                        var i=0
                                        if(last_int!=0){
                                            while(i<array_Json.length()){

                                                jasonArray_post.put(array_Json.get(i))

                                                i+=1

                                            }
                                            recyclerView.adapter.notifyDataSetChanged()


                                            var demo = array_Json.get(array_Json.length()-1) as JSONObject
                                            max_id= demo["id"].toString()
                                        }


                                    }, Response.ErrorListener {
                                toast("Network issue")
                            })

                            Get_Post_Queue1.add(Json_object_post1)
//                        toast(layoutManager.findLastCompletelyVisibleItemPosition().toString())
                        }


                    }
                }
            }
        })
    }

    fun download(view: PostDisplayTag) {
        progress = ProgressDialog(this)
        progress!!.setMessage("Fetch Images")
        progress!!.setProgressStyle(ProgressDialog.STYLE_SPINNER)
        val res = resources
        progress!!.isIndeterminate = true
        progress!!.progress = 0
        progress!!.show()
        val totalProgressTime = 100
        val t = object : Thread() {
            override fun run() {
                var jumpTime = 0

                while (jumpTime < totalProgressTime) {
                    try {
                        Thread.sleep(200)
                        jumpTime += 5
                        progress!!.setProgress(jumpTime)
                    } catch (e: InterruptedException) {
                        // TODO Auto-generated catch block
                        e.printStackTrace()
                    }

                }
            }
        }
        t.start()

    }

    override fun onBackPressed(){

        startActivity(Intent(this@PostDisplayTag,MainActivity::class.java))
        finish()

    }
}
