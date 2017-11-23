package pistalix.whatsappstatus.saver

import android.Manifest
import android.app.Activity
import android.content.pm.PackageManager
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import kotlinx.android.synthetic.main.activity_main.*
import android.content.Intent
import android.os.Environment
import android.os.Handler
import android.support.v7.widget.LinearLayoutManager
import android.view.View
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import org.json.JSONObject
import java.io.File
import java.util.ArrayList


class MainActivity : AppCompatActivity() {
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    var MainFiles : ArrayList<File>? = null
    private var mAdView: AdView? = null
    val externalDirectory = Environment.getExternalStorageDirectory().toString()
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        mAdView = findViewById<View>(R.id.adView) as AdView
        val adRequest = AdRequest.Builder()
                .build()
        mAdView!!.loadAd(adRequest)
        val check = (ContextCompat.checkSelfPermission(applicationContext, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {
            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }

        var virsion = BuildConfig.VERSION_NAME

//        ToastInstallSucc(virsion)
//        check_virsion()
//        https://androidquery.appspot.com/api/market?app=pistalix.whatsapp.video.status
        val folder = File(externalDirectory + "/WhatsappStatusSaver")
        if (!folder.exists()) {
            folder.mkdir()
        }
        recyclerView.layoutManager = LinearLayoutManager(this@MainActivity, LinearLayoutManager.HORIZONTAL, false)
        val imageResource = resources.getIdentifier("@drawable/buttonstyle", null, packageName)
        story.setOnClickListener{

            val i = Intent(this@MainActivity, MainTab::class.java)
            startActivity(i)

        }
        save.setOnClickListener{

            val i = Intent(this@MainActivity, DownloadMainTab::class.java)
            startActivity(i)
        }
        val handler = Handler()
        handler.postDelayed(Runnable {
                var list = DounloadVideosName()
                var i=list.size-1
                var j=0
                var set_file = ArrayList<File>()
                while(i >= 0){

                    set_file.add(list[i])
                    j+=1
                    i-=1
                }
                MainFiles = set_file
                recyclerView.adapter = StatusImage(MainFiles!!)
        }, 10000)
    }

    fun DounloadVideosName(): ArrayList<File> {
        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val files = File(externalDirectory+ "/WhatsApp/Media/.Statuses")
        val inFiles = ArrayList<File>()
        if(files.exists()){
            val fileslist = files.listFiles()
            if(fileslist != null){
                for (file in fileslist) {
                    inFiles.add(file)
                }
                return inFiles
            }
            return inFiles
        }
        return inFiles
    }
    fun check_virsion(){
        val queyj2 = Volley.newRequestQueue(this)
        //        https@ //www.googleapis.com/youtube/v3/playlistItems?part=snippet&maxResults=12&playlistId="+"+playlistid+"+"&key=AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs
        val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://androidquery.appspot.com/api/market?app=pistalix.whatsappstatus.saver", null,

                Response.Listener<JSONObject>
                {
                    response ->
                    var Json:JSONObject= response
                    ToastInstallSucc(Json.getString("version"))

                }, Response.ErrorListener {
                     ToastInstallApp("wrong")
//
        })
        queyj2.add(jsonobj2)
    }
    fun ToastInstallSucc(str :String){

        SuperActivityToast.create(this).setText(str).setDuration(Style.DURATION_VERY_LONG).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_GREEN)).setAnimations(Style.ANIMATIONS_POP).show();
    }
    fun ToastInstallApp(str :String){

        SuperActivityToast.create(this).setText(str).setDuration(Style.DURATION_VERY_LONG).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }
}
