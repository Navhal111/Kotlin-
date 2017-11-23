package pistalix.sadvideostatus.sadvideosong

import android.app.Fragment
import android.app.ProgressDialog
import android.content.Context
import android.net.ConnectivityManager
import android.os.Build
import android.os.Bundle
import android.support.annotation.RequiresApi
import android.support.v7.widget.LinearLayoutManager
import android.support.v7.widget.RecyclerView
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.google.android.gms.ads.InterstitialAd
import kotlinx.android.synthetic.main.activity_videos_list.view.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject


class VideosList: Fragment() {
    private var mAdView: AdView? = null
    var nextpage: String? = null
    var last_int=0
    var hasMore = true
    lateinit var rootView : View
    lateinit var mainJson: JSONArray
    internal lateinit var mInterstitialAd: InterstitialAd
    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        rootView = inflater.inflate(R.layout.activity_videos_list, container, false)
        val playlistid = "PL86DbUdlKu1OZoyqGKp6fwt8O1rewR0BC"
//        val playlistid = "PLsyeobzWxl7rooJFZhc3qPLwVROovGCfh"
        val connectivityManager = rootView.context.getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val nwInfo = connectivityManager.activeNetworkInfo
        if (nwInfo != null && nwInfo.isConnectedOrConnecting) {

        }else{
            ToastInstallApp("Check your Network Connection")
        }
        var adRequest = AdRequest.Builder()
                .build()
        mInterstitialAd = InterstitialAd(rootView.context)
        adRequest = AdRequest.Builder().build()
        val unitId = getString(R.string.interstial_ads)
        mInterstitialAd.setAdUnitId(unitId)
        mInterstitialAd.loadAd(adRequest)
        var s = 0
        var stringid: String? = null

        var videotitle: String? = null;
        var imageurl: String? = null;
        val queyj2 = Volley.newRequestQueue(rootView.context)
        //        https@ //www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=12&playlistId="+"+playlistid+"+"&key=AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs
        val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=12&playlistId="+playlistid+"&key=AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs", null,

                Response.Listener<JSONObject>
                {
                    response ->
                    val setert: JSONArray = response.get("items") as JSONArray
                    if(setert.length()>0){
                        val jsona = JSONArray()
                        var j1 = JSONObject()
                        var j3 = JSONObject()
                        if(response.has("nextPageToken")){

                            nextpage= response.get("nextPageToken").toString()
                        }else{
                            nextpage =null
                        }

//                        toast(setert.get(0).toString())
                        var i = 0
                        var j=0
                        while (i < setert.length()) {
                            j1 = setert.get(i) as JSONObject
                            j3 = j1.get("snippet") as JSONObject
                            var j4 = JSONObject()
                            if(j3.has("thumbnails")){
                                j4=j3.get("thumbnails") as JSONObject
                                imageurl = j4.getJSONObject("high").getString("url")
                                j4 = j3.get("resourceId") as JSONObject
                                val j5 = JSONObject()
                                j5.put("id", j4.get("videoId"))
                                j5.put("title", j3.get("title"))
                                j5.put("imageurl",imageurl)
                                stringid = j4.get("videoId").toString()

                                if(j3.getString("title") != "Private video"){
                                    jsona.put(j, j5)
                                    j++;
                                }
                            }

                            i++
                        }
                        last_int=setert.length()
                        mainJson = jsona
                        if (setert.length() > 0) {

//                                            json1.text=mainJson.toString()
                            rootView.recyclerView.layoutManager = LinearLayoutManager(rootView.context)
//
                            rootView.recyclerView.adapter = RecyleJson(mainJson,playlistid,mInterstitialAd)
                        }
                    }else{
                        ToastInstallApp("No video Found")
                    }

//                    json1.text=jsona.toString()

                }, Response.ErrorListener {
//                    ToastInstallApp("Check your Network Connection")
//                toast("somthing went wrong")
//
        })



        queyj2.add(jsonobj2)

        rootView.recyclerView.addOnScrollListener(object : RecyclerView.OnScrollListener() {
            override fun onScrolled(recyclerView: RecyclerView?, dx: Int, dy: Int) {
                super.onScrolled(recyclerView, dx, dy)
                if (hasMore) {
                    val layoutManager = recyclerView!!.layoutManager as LinearLayoutManager
                    //position starts at 0
                    if (layoutManager.findLastCompletelyVisibleItemPosition() == layoutManager.itemCount - 1) {
                        SuperActivityToast.create(rootView.context,Style(),Style.TYPE_PROGRESS_CIRCLE).setText("Loading..").setDuration(Style.DURATION_SHORT).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_GREEN)).setAnimations(Style.ANIMATIONS_POP).show()
                        if(last_int>=12 && nextpage != null){
                            var imageurl: String? = null;
                            val queyj2 = Volley.newRequestQueue(rootView.context)

                            val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=12&pageToken=$nextpage&playlistId="+playlistid+"&key=AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs", null,

                                    Response.Listener<JSONObject>
                                    {
                                        response ->

                                        val setert: JSONArray = response.get("items") as JSONArray

                                        if(setert.length()>0){
                                            val jsona = JSONArray()
                                            var j1 = JSONObject()
                                            var j3 = JSONObject()
                                            if(response.has("nextPageToken")){

                                                nextpage= response.get("nextPageToken").toString()
                                            }else{
                                                nextpage =null
                                            }

//                        toast(setert.get(0).toString())
                                            var i = 0
                                            var m=0
                                            while (i < setert.length()) {
                                                j1 = setert.get(i) as JSONObject
                                                j3 = j1.get("snippet") as JSONObject
                                                var j4 = JSONObject()
                                                if(j3.has("thumbnails")){
                                                    j4=j3.get("thumbnails") as JSONObject
                                                    imageurl = j4.getJSONObject("high").getString("url")
                                                    j4 = j3.get("resourceId") as JSONObject
                                                    val j5 = JSONObject()
                                                    j5.put("id", j4.get("videoId"))
                                                    j5.put("title", j3.get("title"))
                                                    j5.put("imageurl",imageurl)
                                                    stringid = j4.get("videoId").toString()

                                                    if(j3.getString("title") != "Private video"){
                                                        jsona.put(m, j5)
                                                        m++;
                                                    }
                                                }
                                                i++
                                            }
                                            last_int=setert.length()
                                            var j=0
                                            while(j<jsona.length()){

                                                mainJson.put(jsona.get(j))


                                                j+=1

                                            }
                                            rootView.recyclerView.adapter.notifyDataSetChanged()
                                        }

//                                        mainJson = jsona

                                    }, Response.ErrorListener {
                                ToastInstallApp("Check your Network Connection")
//
                            })



                            queyj2.add(jsonobj2)
//                        toast(layoutManager.findLastCompletelyVisibleItemPosition().toString())
                        }


                    }
                }
            }
        })


        return rootView
    }



    fun ToastInstallApp(str :String){

        SuperActivityToast.create(rootView.context).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }
}