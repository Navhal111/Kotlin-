package pistalix.romanticvideostatus.romanticvideosong

import android.app.Fragment
import android.content.Intent
import android.net.Uri
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import kotlinx.android.synthetic.main.home.view.*

class HomeFragment : Fragment() {

    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        val rootView = inflater.inflate(R.layout.home, container, false)

        rootView.icon4.setOnClickListener{

            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
            }
        }

        rootView.icon5.setOnClickListener{
            val intent = Intent(Intent.ACTION_SEND)
            intent.type = "text/plain*"
            intent.putExtra(Intent.EXTRA_SUBJECT, "Bal Geet");
            intent.putExtra(Intent.EXTRA_TEXT, "https://goo.gl/C4Qu1K");
            startActivity(Intent.createChooser(intent, "via"))
        }

        rootView.love.setOnClickListener{

            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.lovevideosongstatus.lovevideosong")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.lovevideosongstatus.lovevideosong")))
            }
        }
        rootView.icon6.setOnClickListener{

            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.romanticvideostatus.romanticvideosong")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.romanticvideostatus.romanticvideosong")))
            }
        }
        rootView.tren.setOnClickListener{

            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.Videostatus.videosongstatus")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.Videostatus.videosongstatus")))
            }
        }
        rootView.sad.setOnClickListener{

            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.sadvideostatus.sadvideosong")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.sadvideostatus.sadvideosong")))
            }
        }
        return rootView
    }


}