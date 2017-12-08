package pistalix.sadvideostatus.sadvideosong

import android.app.Activity
import android.content.Context
import android.os.Environment
import android.support.v7.widget.LinearLayoutManager
import android.support.v7.widget.RecyclerView
import android.view.View
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.InterstitialAd
import org.json.JSONArray
import org.json.JSONObject
import java.io.File
import java.util.ArrayList


class SingelFunction {
    internal var m_id: Long? = null
    var mainJson : JSONArray? = null
    var set1 =0;
    fun DounloadVideos(): JSONArray {
        var VideoId = JSONArray()
        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val files = File(externalDirectory+ "/SadStatus/")
        val inFiles = ArrayList<File>()
        val fileslist = files.listFiles()

        if(fileslist != null){
            for (file in fileslist) {
                if(file.length()>0){
                    inFiles.add(file)
                    var TestId = JSONObject()
                    var Teatarray = file.name.split(" $ ")
                    if(Teatarray.size > 1){
                        TestId.put("Id",Teatarray[1].replace(".mp4",""))
                        VideoId.put(TestId)
                    }
                }


            }
            return VideoId
        }
        return VideoId
    }

    fun DounloadVideosName(): ArrayList<File> {
        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val files = File(externalDirectory+ "/SadStatus/")
        val inFiles = ArrayList<File>()
        if(files.exists()){
            val fileslist = files.listFiles()
            if(fileslist != null){
                for (file in fileslist){
                    if(file.length()>0){
                        inFiles.add(file)
                    }

                }
                return inFiles
            }
            return inFiles
        }
        return inFiles
    }

    fun suggetion_set(playlistid:String,contex : Context,a:String,ads : InterstitialAd){

        val queyj2 = Volley.newRequestQueue(contex)
        //        https@ //www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=12&playlistId="+"+playlistid+"+"&key=AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs
        val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=12&playlistId=$playlistid&key=AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs", null,

                Response.Listener<JSONObject>
                {
                    response ->
                    val setert: JSONArray = response.get("items") as JSONArray
                    if(setert.length()>0){
                        val jsona = JSONArray()
                        var j1 = JSONObject()
                        var j3 = JSONObject()

//                        toast(setert.get(0).toString())
                        var i = 0
                        var j=0
                        while (i < setert.length()) {
                            j1 = setert.get(i) as JSONObject
                            j3 = j1.get("snippet") as JSONObject
                            var j4 = JSONObject()
                            if(j3.has("thumbnails")){
                                j4=j3.get("thumbnails") as JSONObject
                                var imageurl = j4.getJSONObject("default").getString("url")
                                j4 = j3.get("resourceId") as JSONObject
                                val j5 = JSONObject()
                                j5.put("id", j4.get("videoId"))
                                j5.put("title", j3.get("title"))
                                j5.put("imageurl",imageurl)
                                var stringid = j4.get("videoId").toString()

                                if(stringid!=a){

                                    jsona.put(j, j5)
                                    j++;
                                }
                            }

                            i++
                        }

                        try{
                            mainJson = jsona
                            val txtView = (contex as Activity).findViewById<View>(R.id.recyclerViewVideosuggestion) as RecyclerView
                            txtView.layoutManager = LinearLayoutManager(contex, LinearLayoutManager.HORIZONTAL, false)
                            txtView.adapter =RecycleJsonSug(mainJson!!,playlistid,ads)
                        }catch (e:NullPointerException){
                            SuperActivityToast.create(contex).setText("Sorry Problem On Server, Suggestion Not Load ").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
                        }catch (e:IllegalArgumentException){
                            SuperActivityToast.create(contex).setText("Sorry Problem On Server, Suggestion Not Load ").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
                        }

                    }

                }, Response.ErrorListener {
            //            Toast.makeText(this, "wrong", Toast.LENGTH_SHORT).show();
//                toast("somthing went wrong")
//
        })


        queyj2.add(jsonobj2)

    }

}