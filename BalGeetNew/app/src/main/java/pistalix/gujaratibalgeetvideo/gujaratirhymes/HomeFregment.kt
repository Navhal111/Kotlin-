package pistalix.gujaratibalgeetvideo.gujaratirhymes

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.app.Fragment
import android.content.Intent
import android.net.Uri
import android.support.v7.widget.LinearLayoutManager
import com.google.android.gms.ads.AdView
import kotlinx.android.synthetic.main.activity_back_button.*
import kotlinx.android.synthetic.main.activity_main.view.*
import kotlinx.android.synthetic.main.home.*
import kotlinx.android.synthetic.main.home.view.*
import org.json.JSONArray
import org.json.JSONObject

class HomeFregment : Fragment() {
    private var mAdView: AdView? = null
    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        val rootView = inflater.inflate(R.layout.home, container, false)


        rootView.rate_us.setOnClickListener{
            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.gujaratibalgeetvideo.gujaratirhymes")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.gujaratibalgeetvideo.gujaratirhymes")))
            }

//            startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://goo.gl/R7d5ft" )))
        }

        rootView.share.setOnClickListener{
            val intent = Intent(Intent.ACTION_SEND)
            intent.type = "text/plain*"
            intent.putExtra(Intent.EXTRA_SUBJECT, "Bal Geet");
            intent.putExtra(Intent.EXTRA_TEXT, "https://goo.gl/rZsZTS");
            startActivity(Intent.createChooser(intent, "via"))
        }
        rootView.other_app.setOnClickListener{
            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
            }
}
        return rootView
    }

}