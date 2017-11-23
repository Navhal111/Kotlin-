package pistalix.gujaratibalgeetvideo.gujaratirhymes
import android.Manifest
import android.app.Activity
import android.app.Fragment
import android.content.Intent
import android.content.pm.PackageManager
import android.os.Build
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.annotation.RequiresApi
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.view.View
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import kotlinx.android.synthetic.main.activity_main_fregment.*
import org.jetbrains.anko.toast

class MainFregment : AppCompatActivity() {
    var fragmentTab1: Fragment = VideosList()
    var fragmentTab2: Fragment = FragmentText()
    var fragmentTab3: Fragment = DownloadVideos()
    var fragmentTab4: Fragment = HomeFregment()
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
    @RequiresApi(Build.VERSION_CODES.LOLLIPOP)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main_fregment)
        val check = (ContextCompat.checkSelfPermission(applicationContext, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {
            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }
        bottomBar.setOnTabSelectListener { tabId ->
            if (tabId == R.id.tab_calls) {

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab4)
                transaction.addToBackStack(null)
                transaction.commit()

            } else if (tabId == R.id.tab_groups) {

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab2)
                transaction.addToBackStack(null)
                transaction.commit()

            } else if (tabId == R.id.tab_chats) {

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab1)
                transaction.addToBackStack(null)
                transaction.commit()

            }else if(tabId == R.id.tab_down){

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab3)
                transaction.addToBackStack(null)
                transaction.commit()
            }


        }
    }

    override fun onBackPressed(){

        startActivity(Intent(this@MainFregment,BackButton::class.java))

    }
}

