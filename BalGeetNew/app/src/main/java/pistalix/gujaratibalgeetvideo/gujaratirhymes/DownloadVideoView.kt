package pistalix.gujaratibalgeetvideo.gujaratirhymes
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_download_video_view.*
import org.jetbrains.anko.onUiThread
import org.jetbrains.anko.toast
import java.io.File

class DownloadVideoView : AppCompatActivity() {
    var videoname:String? =null
    var videourl:String?=null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_download_video_view)
        videourl = intent.getStringExtra("videoid")
        videoname =intent.getStringExtra("Name")
        var title = videoname
        video_title.text= title!!.replace(".mp4","")
        video_view.setMediaController(media_controller)
        video_view.setVideoURI(Uri.parse(videourl))
        video_view.start()
        whatsapp.setOnClickListener{
            if(appInstalledOrNot("com.whatsapp")){
                var set =videourl
                share_video(set.toString(),"com.whatsapp")
            }else{

                toast("First Install App")
            }

        }
        fb.setOnClickListener{

            if(appInstalledOrNot("com.facebook.katana")){
                var set =videourl
                share_video(set.toString(),"com.facebook.katana" +
                        "")
            }else{

                toast("First Install App")
            }
        }

        insta.setOnClickListener{
            if(appInstalledOrNot("com.instagram.android")){
                var set =videourl
                share_video(set.toString(),"com.instagram.android")
            }else{

                toast("First Install App")
            }
        }

        hike.setOnClickListener{
            if(appInstalledOrNot("com.bsb.hike")){
                var set =videourl
                share_video(set.toString(),"com.bsb.hike")
            }else{

                toast("First Install App")
            }
        }

        fbmsg.setOnClickListener{

            if(appInstalledOrNot("com.facebook.orca")){
                var set =videourl
                share_video(set.toString(),"com.facebook.orca")
            }else{

                toast("First Install App")
            }
        }

        main_share.setOnClickListener{
            var set =videourl
            mainshare(set.toString())
        }

        other_app.setOnClickListener{
            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
            }
        }
    }

    fun share_video(filepath:String,packeg:String) {
        var string_path = Uri.fromFile(File(filepath))
        val sharingIntent = Intent(Intent.ACTION_SEND)
        sharingIntent.type = "video/*"
        sharingIntent.`package` = packeg
        sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)

        try {
            startActivity(sharingIntent)
        } catch (e: android.content.ActivityNotFoundException) {
                Toast.makeText(this, "First Install App...", Toast.LENGTH_SHORT).show();

        }
    }

   fun appInstalledOrNot(uri: String): Boolean {
       val pm = packageManager
       try {
           pm.getPackageInfo(uri, PackageManager.GET_ACTIVITIES)
           return true
       } catch (e: PackageManager.NameNotFoundException) {
       }

       return false
   }

   fun mainshare(filename:String){

       var string_path =Uri.parse(filename)
       val intent = Intent(android.content.Intent.ACTION_SEND)
       intent.type = "video/*"
       intent.putExtra(Intent.EXTRA_STREAM, string_path)
       startActivity(Intent.createChooser(intent, "Share via"))
   }
}
