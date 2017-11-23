package pistalix.romanticvideostatus.romanticvideosong
import android.Manifest
import android.annotation.SuppressLint
import android.app.Activity
import android.app.DownloadManager
import android.app.ProgressDialog
import android.content.Context
import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.content.pm.PackageManager
import android.database.Cursor
import android.net.ConnectivityManager
import android.os.Build
import android.os.Environment
import android.preference.PreferenceManager
import android.support.annotation.RequiresApi
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.support.v7.widget.LinearLayoutManager
import android.util.SparseArray
import android.view.View
import android.widget.*
import at.huber.youtubeExtractor.YouTubeUriExtractor
import at.huber.youtubeExtractor.YtFile
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdListener
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.google.android.gms.ads.InterstitialAd
import com.google.android.youtube.player.YouTubeBaseActivity
import com.google.android.youtube.player.YouTubeInitializationResult
import com.google.android.youtube.player.YouTubePlayer
import kotlinx.android.synthetic.main.activity_video_view.*
import org.jetbrains.anko.onUiThread
import org.json.JSONArray
import org.json.JSONObject
import java.io.File
import java.util.*


class VideoView : YouTubeBaseActivity(), YouTubePlayer.OnInitializedListener {

    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    var formatsToShowList: YtFragmentedVideo? = null
    lateinit var formatsToShowList1: MutableList<YtFragmentedVideo>
    private val ITAG_FOR_AUDIO = 140
    internal var m_id: Long? = null
    internal var playlistid: String? =null
    var last_int=0
    var hasMore = true
    lateinit var mainJson: JSONArray
    internal var DEVELOPER_KEY = "AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs"
    var mInterstitialAd: InterstitialAd? = null
    private var mAdView: AdView? = null
    var videourl: String? = null
    var Title: String? = null
    var FalgDownload =0
    private var progress:ProgressDialog? = null
    var a: String? = null
    var filename:String? = null
    @RequiresApi(Build.VERSION_CODES.LOLLIPOP)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_video_view)
        overridePendingTransition(R.xml.enter, R.xml.exit);
        var ads = applicationContext.getResources().getString(R.string.interstial_ads)
        val check = (ContextCompat.checkSelfPermission(this@VideoView, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {

            ActivityCompat.requestPermissions(this@VideoView as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }

        a = intent.getStringExtra("videoid")
        playlistid=intent.getStringExtra("playlistId")
        val videoClass = SingelFunction()
        val video_id = videoClass.DounloadVideos()
        val FILES =  videoClass.DounloadVideosName()
        var i=0;
        while(i<video_id.length()){
            val  check = video_id.get(i) as JSONObject
            if(check.get("Id")==a){
                FalgDownload=1
                filename = FILES[i].toString()
            }
            i+=1;
        }

        if(FalgDownload==1){
            val imageResource = resources.getIdentifier("@drawable/downloaded", null, packageName)
            val res = getResources().getDrawable(imageResource);
            whatsapp_share.setImageDrawable(res)
        }
        Title =intent.getStringExtra("Title")+" $ "+a
        val strin_title = Title

        if (strin_title != null && strin_title.length >=30) {
            videotitle.text=strin_title.substring(0,30)+"..."
        }else{
            videotitle.text=strin_title
        }

//        a="ZEhN2vb2Ngw"
        videourl = "https://www.youtube.com/watch?v=$a"
//        videourl = "https://www.youtube.com/watch?v=uja4bxbyHqY"
        download(this@VideoView)

        val connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val nwInfo = connectivityManager.activeNetworkInfo
        if (nwInfo == null && !nwInfo!!.isConnectedOrConnecting) {
            ToastInstallApp("Check your Network Connection")
            progress!!.cancel()
        }
        youtubevideo.initialize(DEVELOPER_KEY, this)
        try{
            getYoutubeDownloadUrl(videourl!!)
        }catch (e :IllegalArgumentException){
            ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")
        }catch (e :NullPointerException){
            ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")
        }




        recyclerViewVideosuggestion.layoutManager = LinearLayoutManager(this@VideoView, LinearLayoutManager.HORIZONTAL, false)
        suggetion_set()
        mAdView = findViewById<View>(R.id.adView) as AdView
        val adRequest = AdRequest.Builder()
                .build()
        mAdView!!.loadAd(adRequest)

        whatsapp_share.setOnClickListener{
            if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    val intent = Intent(this, DownloadVideoView::class.java)
                    intent.putExtra("videoid", filename.toString())
                    intent.putExtra("Name",Title)
                    startActivity(intent)
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")
                }

            }else{
                ToastInstallSucc("Downloading start...")
                if(formatsToShowList!=null){

                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrl(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext);


                        })
                    }
                    thread.start();

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }


        }

        whatsapp.setOnClickListener{
            val isAppInstalled = appInstalledOrNot("com.whatsapp")
            if(!isAppInstalled){
                ToastInstallAppBottom()
            } else if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    var string_path =Uri.parse(filename)
                    val sharingIntent = Intent(android.content.Intent.ACTION_SEND)
                    sharingIntent.type = "video/*"
                    sharingIntent.`package` = "com.whatsapp"
                    sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)
                    try {
                        startActivity(sharingIntent)
                    } catch (e:android.content.ActivityNotFoundException) {
                        ToastInstallAppBottom()
                    }
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }

            }else{
                ToastInstallSucc("Downloading start for share video")

                if(formatsToShowList!=null){

                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrlMain(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext,"com.whatsapp");


                        })
                    }
                    thread.start();

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }


        }

        fb.setOnClickListener{
            val isAppInstalled = appInstalledOrNot("com.facebook.katana")
            if(!isAppInstalled){
                ToastInstallAppBottom()
            } else if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    var string_path =Uri.parse(filename)
                    val sharingIntent = Intent(android.content.Intent.ACTION_SEND)
                    sharingIntent.type = "video/*"
                    sharingIntent.`package` = "com.facebook.katana"
                    sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)
                    try {
                        startActivity(sharingIntent)
                    } catch (e:android.content.ActivityNotFoundException) {
                        ToastInstallAppBottom()
                    }
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }

            }else{
                ToastInstallSucc("Downloading start for share video")

                if(formatsToShowList!=null){


                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrlMain(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext,"com.facebook.katana");


                        })
                    }
                    thread.start()

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }

        }
        insta.setOnClickListener{
            val isAppInstalled = appInstalledOrNot("com.instagram.android")
            if(!isAppInstalled){
                ToastInstallAppBottom()
            } else if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    var string_path =Uri.parse(filename)
                    val sharingIntent = Intent(android.content.Intent.ACTION_SEND)
                    sharingIntent.type = "video/*"
                    sharingIntent.`package` = "com.instagram.android"
                    sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)
                    try {
                        startActivity(sharingIntent)
                    } catch (e:android.content.ActivityNotFoundException) {
                        ToastInstallAppBottom()
                    }
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }

            }else{
                ToastInstallSucc("Downloading start for share video")

                if(formatsToShowList!=null){

                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrlMain(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext,"com.instagram.android");


                        })
                    }
                    thread.start();

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }

        }

        hike.setOnClickListener{
            val isAppInstalled = appInstalledOrNot("com.bsb.hike")
            if(!isAppInstalled){
                ToastInstallAppBottom()
            } else if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    var string_path =Uri.parse(filename)
                    val sharingIntent = Intent(android.content.Intent.ACTION_SEND)
                    sharingIntent.type = "video/*"
                    sharingIntent.`package` = "com.bsb.hike"
                    sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)
                    try {
                        startActivity(sharingIntent)
                    } catch (e:android.content.ActivityNotFoundException) {
                        ToastInstallAppBottom()
                    }
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }

            }else{
                ToastInstallSucc("Downloading start for share video")

                if(formatsToShowList!=null){

                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrlMain(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext,"com.bsb.hike");


                        })
                    }
                    thread.start();

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }


        }

        fbmsg.setOnClickListener{
            val isAppInstalled = appInstalledOrNot("com.facebook.orca")
            if(!isAppInstalled){
                ToastInstallAppBottom()
            } else if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    var string_path =Uri.parse(filename)
                    val sharingIntent = Intent(android.content.Intent.ACTION_SEND)
                    sharingIntent.type = "video/*"
                    sharingIntent.`package` = "com.facebook.orca"
                    sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)
                    try {
                        startActivity(sharingIntent)
                    } catch (e:android.content.ActivityNotFoundException) {
                        ToastInstallAppBottom()
                    }
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }

            }else{
                ToastInstallSucc("Downloading start for share video")
                if(formatsToShowList!=null){

                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrlMain(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext,"com.facebook.orca");


                        })
                    }
                    thread.start();

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }

        }
        main_share.setOnClickListener{

            if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    var string_path =Uri.parse(filename)
                    val intent = Intent(android.content.Intent.ACTION_SEND)
                    intent.type = "video/*"
                    intent.putExtra(Intent.EXTRA_STREAM, string_path)
                    startActivity(Intent.createChooser(intent, "Share via"))
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }

            }else{
                ToastInstallSucc("Downloading start for share video")

                if(formatsToShowList!=null){


                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrlShare(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext);


                        })
                    }
                    thread.start();

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }


        }
        share_video.setOnClickListener{

            if(FalgDownload == 1){
                if(formatsToShowList!=null){

                    var string_path =Uri.parse(filename)
                    val intent = Intent(android.content.Intent.ACTION_SEND)
                    intent.type = "video/*"
                    intent.putExtra(Intent.EXTRA_STREAM, string_path)
                    startActivity(Intent.createChooser(intent, "Share via"))
                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }

            }else{
                ToastInstallSucc("Downloading start for share video")

                if(formatsToShowList!=null){

                    var thread =  Thread(){
                        kotlin.run({

                            Thread.sleep(3500); // As I am using LENGTH_LONG in Toast
                            downloadFromUrlShare(formatsToShowList!!.videoFile!!.getUrl(), "Download RomanticStatus",
                                    "$Title." + formatsToShowList!!.videoFile!!.getMeta().ext);


                        })
                    }
                    thread.start();

                }else{
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
            }


        }
    }

    override fun onInitializationFailure(provider: YouTubePlayer.Provider,
                                         errorReason: YouTubeInitializationResult) {

    }

    override fun onInitializationSuccess(provider: YouTubePlayer.Provider,
                                         player: YouTubePlayer, wasRestored: Boolean) {
        if (!wasRestored) {
//            player.fullscreenControlFlags = YouTubePlayer.FULLSCREEN_FLAG_CONTROL_ORIENTATION
//            player.addFullscreenControlFlag(YouTubePlayer.FULLSCREEN_FLAG_ALWAYS_FULLSCREEN_IN_LANDSCAPE)
            player.setShowFullscreenButton(false)

            player.loadVideo(a)
            player.setPlayerStyle(YouTubePlayer.PlayerStyle.MINIMAL)
        }
    }


    inner class YtFragmentedVideo {
        internal var height: Int = 0
        internal var audioFile: YtFile? = null
        internal var videoFile: YtFile? = null
    }
    private fun getYoutubeDownloadUrl(youtubeLink: String) {
        val ytEx = @SuppressLint("StaticFieldLeak")
        object : YouTubeUriExtractor(this) {

            override fun onUrisAvailable(videoId: String, videoTitle: String, ytFiles: SparseArray<YtFile>?) {

//                context1..getWindow().clearFlags(WindowManager.LayoutParams.FLAG_NOT_TOUCHABLE)
                if (ytFiles == null) {
                    ToastInstallApp("Sorry Problem On Server, Try Again After One Minute")

                }
                formatsToShowList1 = ArrayList()

                var i = 0
                var itag = -1
                if (ytFiles != null) {
                    var flag =0
                    while (i < ytFiles.size()) {

                        itag = ytFiles.keyAt(i)

                        if(itag == -20){
                            val intent = Intent(this@VideoView, VideoView::class.java)
                            intent.putExtra("videoid", videoId)
                            intent.putExtra("playlistId", playlistid)
                            intent.putExtra("Title",title)
                            startActivity(intent)
                            finish()
                        }
                        val ytFile = ytFiles.get(itag)
                        var frVideo = YtFragmentedVideo()

                        if (ytFile.meta.height === -1 || ytFile.getMeta().getHeight() >= 240) {
                            if (!ytFile.meta.isDashContainer) {
                                if(ytFile.format.height > 0){
                                    frVideo.videoFile = ytFile
                                    frVideo.audioFile = ytFiles.get(ITAG_FOR_AUDIO)
                                    frVideo.height=ytFile.meta.height

                                    formatsToShowList1.add(frVideo)
                                    if(ytFile.getMeta().getHeight()==360 ){

                                        formatsToShowList=frVideo
                                        flag =1
                                    }
                                }
                            }

                        }
                        if(flag==0){
                            if (ytFile.meta.height === -1 || ytFile.getMeta().getHeight() >= 240) {
                                if (!ytFile.meta.isDashContainer) {
                                    if(ytFile.format.height > 0){
                                        frVideo.videoFile = ytFile
                                        frVideo.audioFile = ytFiles.get(ITAG_FOR_AUDIO)
                                        frVideo.height=ytFile.meta.height

                                        formatsToShowList1.add(frVideo)

                                        if(ytFile.getMeta().getHeight()== 240 || ytFile.getMeta().getHeight()== 144 ){

                                            formatsToShowList=frVideo
                                            if(ytFile.getMeta().getHeight()== 240){
                                                break
                                            }

                                        }
                                    }
                                }

                            }


                        }
                        i++
                    }
                }
                Collections.sort(formatsToShowList1) { lhs, rhs -> lhs.height - rhs.height }
                progress!!.cancel()
            }
        }
        ytEx.setIncludeWebM(false)
        ytEx.setParseDashManifest(true)
        ytEx.execute(youtubeLink)

    }



    fun suggetion_set(){

        val queyj2 = Volley.newRequestQueue(this)
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
                        last_int=jsona.length()
                        mainJson = jsona
                        if (setert.length() > 0) {

//                                            json1.text=mainJson.toString()
                            recyclerViewVideosuggestion.adapter = RecycleJsonSug(mainJson,playlistid.toString())
                        }
                    }else{
                        ToastInstallApp("No video Found")

                    }

//                    json1.text=jsona.toString()

                }, Response.ErrorListener {
//                toast("somthing went wrong")
//
        })



        queyj2.add(jsonobj2)


    }

    fun download(view: VideoView) {
        progress = ProgressDialog(this)
        progress!!.setMessage("Keep Calm,\n" +
                "we are requesting video")
        progress!!.setProgressStyle(ProgressDialog.STYLE_SPINNER)
        val res = resources
        progress!!.isIndeterminate = true
        progress!!.progress = 0
        progress!!.setCancelable(false)
        progress!!.show()
        val totalProgressTime = 100
        val t = object : Thread() {
            override fun run() {
                var jumpTime = 0

                while (jumpTime < totalProgressTime) {
                    try {
                        Thread.sleep(200)
                        jumpTime += 10
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

    private fun downloadFromUrl(youtubeDlUrl: String, downloadTitle: String, fileName: String) {

        val uri = Uri.parse(youtubeDlUrl)
        val request = DownloadManager.Request(uri)
        val REQUEST_WRITE_EXTERNAL_STORAGE = 1
        request.setTitle(downloadTitle)
        var downloading =true;
        var check = (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {

            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }

        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val folder = File(externalDirectory + "/RomanticStatus")
        if (!folder.exists()) {
            folder.mkdir()
        }
        check = (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if(check){
            request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED)

            request.setDestinationInExternalPublicDir(externalDirectory + "/RomanticStatus", fileName)


            val manager = getSystemService(Context.DOWNLOAD_SERVICE) as DownloadManager
            m_id = manager.enqueue(request)
            var query: DownloadManager.Query  = DownloadManager.Query()
            query.setFilterByStatus(DownloadManager.STATUS_FAILED or DownloadManager.STATUS_PAUSED or DownloadManager.STATUS_SUCCESSFUL or DownloadManager.STATUS_RUNNING or DownloadManager.STATUS_PENDING)
            var c: Cursor
            while (downloading) {
                c = manager.query(query)
                if(c.moveToNext()) {
                    var  status =c.getInt(c.getColumnIndex(DownloadManager.COLUMN_STATUS))
                    if (status==DownloadManager.STATUS_SUCCESSFUL ) {
                        progress!!.cancel()
                        this.onUiThread {
                            ToastInstallSucc("Download Complete...")
                            val imageResource = resources.getIdentifier("@drawable/downloaded", null, packageName)
                            val res = getResources().getDrawable(imageResource);
                            whatsapp_share.setImageDrawable(res)
                        }


                        FalgDownload=1
                        filename =c.getString(c.getColumnIndex("local_uri"))
//                    var string_path = Uri.parse(c.getString(c.getColumnIndex("local_uri")))
//
//                    val intent = Intent(android.content.Intent.ACTION_SEND)
//                    intent.type = "video/*"
//                    intent.putExtra(Intent.EXTRA_STREAM, string_path)
//                    startActivity(Intent.createChooser(intent, "Share via"))
                        break
                    }
                    if(status == DownloadManager.STATUS_FAILED ){

                        this.onUiThread {
                            ToastInstallApp("Failed ...")
                        }
                        break
                    }
                }
                c.close()
            }


            val sharedPreferences = PreferenceManager
                    .getDefaultSharedPreferences(this)
            val editor = sharedPreferences.edit()
            editor.putLong("Download_ID", m_id!!)
            editor.apply()


        }else{
            ToastInstallApp("To Download Give or Accept Permission Of SD-Card ")
        }

    }
    private fun downloadFromUrlMain(youtubeDlUrl: String, downloadTitle: String, fileName: String,packeg :String) {

        val uri = Uri.parse(youtubeDlUrl)
        val request = DownloadManager.Request(uri)
        val REQUEST_WRITE_EXTERNAL_STORAGE = 1
        request.setTitle(downloadTitle)
        var downloading =true;
        var check = (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {

            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }

        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val folder = File(externalDirectory + "/RomanticStatus")
        if (!folder.exists()) {
            folder.mkdir()
        }
        check = (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if(check){

            request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED)

            request.setDestinationInExternalPublicDir(externalDirectory + "/RomanticStatus", fileName)


            val manager = getSystemService(Context.DOWNLOAD_SERVICE) as DownloadManager
            m_id = manager.enqueue(request)
            var query: DownloadManager.Query  = DownloadManager.Query()
            query.setFilterByStatus(DownloadManager.STATUS_FAILED or DownloadManager.STATUS_PAUSED or DownloadManager.STATUS_SUCCESSFUL or DownloadManager.STATUS_RUNNING or DownloadManager.STATUS_PENDING)
            var c: Cursor
            while (downloading) {
                c = manager.query(query)
                if(c.moveToNext()) {
                    var  status =c.getInt(c.getColumnIndex(DownloadManager.COLUMN_STATUS))
                    if (status==DownloadManager.STATUS_SUCCESSFUL ) {
                        progress!!.cancel()
                        this.onUiThread {
                            ToastInstallSucc("Download Complete...")

                            val imageResource = resources.getIdentifier("@drawable/downloaded", null, packageName)
                            val res = getResources().getDrawable(imageResource);
                            whatsapp_share.setImageDrawable(res)
                        }

                        FalgDownload=1
                        filename =c.getString(c.getColumnIndex("local_uri"))
                        var string_path = Uri.fromFile(File(c.getString(c.getColumnIndex("local_uri"))))
                        val sharingIntent = Intent(Intent.ACTION_SEND)
                        sharingIntent.type = "video/*"
                        sharingIntent.`package` = packeg
                        sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)


                        try {
                            startActivity(sharingIntent)
                        } catch (e:android.content.ActivityNotFoundException) {
                            this.onUiThread {
                                ToastInstallApp("First Install App...")
                            }

                        }

                        break
                    }
                    if(status == DownloadManager.STATUS_FAILED ){

                        this.onUiThread {
                            ToastInstallApp("Failed...")
                        }
                        break
                    }
                }
                c.close()
            }


            val sharedPreferences = PreferenceManager
                    .getDefaultSharedPreferences(this)
            val editor = sharedPreferences.edit()
            editor.putLong("Download_ID", m_id!!)
            editor.apply()


        }else{
            ToastInstallApp("To Download Give or Accept Permission Of SD-Card ")
        }

    }
    private fun downloadFromUrlShare(youtubeDlUrl: String, downloadTitle: String, fileName: String) {

        val uri = Uri.parse(youtubeDlUrl)
        val request = DownloadManager.Request(uri)
        val REQUEST_WRITE_EXTERNAL_STORAGE = 1
        request.setTitle(downloadTitle)
        var downloading =true;
        var check = (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {

            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }

        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val folder = File(externalDirectory + "/RomanticStatus")
        if (!folder.exists()) {
            folder.mkdir()
        }
        check = (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if(check){
            request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED)

            request.setDestinationInExternalPublicDir(externalDirectory + "/RomanticStatus", fileName)


            val manager = getSystemService(Context.DOWNLOAD_SERVICE) as DownloadManager
            m_id = manager.enqueue(request)
            var query: DownloadManager.Query  = DownloadManager.Query()
            query.setFilterByStatus(DownloadManager.STATUS_FAILED or DownloadManager.STATUS_PAUSED or DownloadManager.STATUS_SUCCESSFUL or DownloadManager.STATUS_RUNNING or DownloadManager.STATUS_PENDING)
            var c: Cursor
            while (downloading) {
                c = manager.query(query)
                if(c.moveToNext()) {
                    var  status =c.getInt(c.getColumnIndex(DownloadManager.COLUMN_STATUS))
                    if (status==DownloadManager.STATUS_SUCCESSFUL ) {
                        progress!!.cancel()
                        this.onUiThread {
                            ToastInstallSucc("Download Complete...")
                            val imageResource = resources.getIdentifier("@drawable/downloaded", null, packageName)
                            val res = getResources().getDrawable(imageResource);
                            whatsapp_share.setImageDrawable(res)
                        }

                        FalgDownload=1
                        filename =c.getString(c.getColumnIndex("local_uri"))
                        var string_path = Uri.fromFile(File(c.getString(c.getColumnIndex("local_uri"))))

                        val intent = Intent(android.content.Intent.ACTION_SEND)
                        intent.type = "video/*"
                        intent.putExtra(Intent.EXTRA_STREAM, string_path)
                        startActivity(Intent.createChooser(intent, "Share via"))
                        break
                    }
                    if(status == DownloadManager.STATUS_FAILED ){

                        this.onUiThread {
                            ToastInstallApp("Failed...")
                        }
                        break
                    }
                }
                c.close()
            }


            val sharedPreferences = PreferenceManager
                    .getDefaultSharedPreferences(this)
            val editor = sharedPreferences.edit()
            editor.putLong("Download_ID", m_id!!)
            editor.apply()
        }else{
            ToastInstallApp("To Download Give or Accept Permission Of SD-Card ")
        }

    }


    fun toast_String(str:String){

        var start =Toast.makeText(this,str,Toast.LENGTH_LONG)
        start.show()
    }

    private fun appInstalledOrNot(uri: String): Boolean {
        val pm = packageManager
        try {
            pm.getPackageInfo(uri, PackageManager.GET_ACTIVITIES)
            return true
        } catch (e: PackageManager.NameNotFoundException) {
        }

        return false
    }


    override fun onBackPressed(){
        finish()
        overridePendingTransition(R.xml.nathing,R.xml.exit)
    }

    fun ToastInstallApp(str :String){

        SuperActivityToast.create(this).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }

    fun ToastInstallAppBottom(){

        SuperActivityToast.create(this).setText("First Install App...").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_LOLLIPOP).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }
    fun ToastInstallSucc(str :String){

        SuperActivityToast.create(this).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_GREEN)).setAnimations(Style.ANIMATIONS_POP).show();
    }
}
