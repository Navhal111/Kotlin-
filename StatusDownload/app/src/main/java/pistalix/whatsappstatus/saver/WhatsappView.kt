package pistalix.whatsappstatus.saver
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.os.Build
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.os.FileUriExposedException
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import kotlinx.android.synthetic.main.activity_whatsapp_view.*
import java.io.File
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.InterstitialAd
import android.support.v4.media.session.MediaControllerCompat.setMediaController
import android.widget.MediaController


class WhatsappView : AppCompatActivity() {
    var videourl: String? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_whatsapp_view)
        overridePendingTransition(R.xml.enter, R.xml.exit);
        videourl = intent.getStringExtra("videoid")
        try{
            val mediaController = MediaController(this)
            video_view.setVideoURI(Uri.parse(videourl))
            mediaController.setAnchorView(video_view)
            video_view.setMediaController(mediaController)
            video_view.start()
        }catch (e:NullPointerException){
            ToastMainError("Something went wrong")
        }catch (e:IllegalArgumentException){
            ToastMainError("Something went wrong")
        }catch (e:Exception){
            ToastMainError("Something went wrong")
        }
        whatsapp.setOnClickListener {
            if (appInstalledOrNot("com.whatsapp")) {

                var set = videourl
                share_video(set.toString(), "com.whatsapp")
            } else {

                ToastInstallApp()
            }

        }
        fb.setOnClickListener {

            if (appInstalledOrNot("com.facebook.katana")) {
                var set = videourl
                share_video(set.toString(), "com.facebook.katana")
            } else {

                ToastInstallApp()
            }
        }

        insta.setOnClickListener {

            if (appInstalledOrNot("com.instagram.android")) {
                var set = videourl
                share_video(set.toString(), "com.instagram.android")
            } else {

                ToastInstallApp()
            }
        }

        hike.setOnClickListener {

            if (appInstalledOrNot("com.bsb.hike")) {
                var set = videourl
                share_video(set.toString(), "com.bsb.hike")
            } else {

                ToastInstallApp()
            }
        }

        fbmsg.setOnClickListener {

            if (appInstalledOrNot("com.facebook.orca")) {
                var set = videourl
                share_video(set.toString(), "com.facebook.orca")
            } else {

                ToastInstallApp()
            }
        }

        main_share.setOnClickListener {

            var set = videourl
            mainshare(set.toString())
        }

        other_app.setOnClickListener {
            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
            }
        }
    }

    fun share_video(filepath: String, packeg: String) {
            try {
                var string_path = Uri.parse(filepath)
            val sharingIntent = Intent(Intent.ACTION_SEND)
            sharingIntent.type = "video/*"
            sharingIntent.`package` = packeg
            sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path)

           startActivity(sharingIntent)
            } catch (e: android.content.ActivityNotFoundException) {
                ToastInstallApp()
            }catch (e:Exception){
                ToastMainError(" Your Device Not give permission to Get Files")
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

    fun mainshare(filename: String) {

            try {
                var string_path = Uri.parse(filename)
                val intent = Intent(android.content.Intent.ACTION_SEND)
                intent.type = "video/*"
                intent.putExtra(Intent.EXTRA_STREAM, string_path)
                startActivity(Intent.createChooser(intent, "Share via"))
            } catch (e: android.content.ActivityNotFoundException) {
                ToastInstallApp()
            }catch (e: Exception){
                ToastMainError(" Your Device Not give permission to Get Files")
            }
    }

    override fun onBackPressed() {
        finish()
        overridePendingTransition(R.xml.nathing, R.xml.exit)

    }

    fun ToastInstallApp() {

        SuperActivityToast.create(this@WhatsappView).setText("First Install App...").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_LOLLIPOP).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
    }
    fun ToastMainError(Str :String){
        SuperActivityToast.create(this@WhatsappView).setText(Str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
        }
}
