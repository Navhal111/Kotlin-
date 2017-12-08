//package pistalix.romanticvideostatus.romanticvideosong
//
//import android.app.Activity
//import android.content.Intent
//import android.net.Uri
//import android.support.v7.app.AppCompatActivity
//import android.os.Bundle
//import android.util.DisplayMetrics
//import kotlinx.android.synthetic.main.activity_back_button.*
//import kotlinx.android.synthetic.main.activity_main.*
//
//class BackButton : Activity() {
//
//    override fun onCreate(savedInstanceState: Bundle?) {
//        super.onCreate(savedInstanceState)
//        setContentView(R.layout.activity_back_button)
//
//        val metrics = DisplayMetrics()
//        windowManager.defaultDisplay.getMetrics(metrics)
//
//        var hight = metrics!!.heightPixels
//        var wight = metrics!!.widthPixels
//
//        window.setLayout(((hight*.6).toInt()),((wight*.3).toInt()))
//
//        no_button.setOnClickListener{
//            moveTaskToBack(true);
//            android.os.Process.killProcess(android.os.Process.myPid());
//            System.exit(1);
//
//        }
//        yes_button.setOnClickListener{
//            try {
//                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
//            } catch (anfe: android.content.ActivityNotFoundException) {
//                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
//            }
//        }
//
//        rate_app_back.setOnClickListener{
//            finish()
//            try {
//                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.romanticvideostatus.romanticvideosong")))
//            } catch (anfe: android.content.ActivityNotFoundException) {
//                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.romanticvideostatus.romanticvideosong")))
//            }
//        }
//    }
//}