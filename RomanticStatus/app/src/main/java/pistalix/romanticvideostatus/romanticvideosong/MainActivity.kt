package pistalix.romanticvideostatus.romanticvideosong

import android.Manifest
import android.app.Activity
import android.app.Fragment
import android.content.Intent
import android.content.pm.PackageManager
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.view.View
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import kotlinx.android.synthetic.main.activity_main.*
import com.ss.bottomnavigation.events.OnSelectedItemChangeListener
import com.ss.bottomnavigation.BottomNavigation
import org.jetbrains.anko.toast


class MainActivity : AppCompatActivity() {
    var fragmentTab4: Fragment = FregmentStatus()
    var fragmentTab1: Fragment = VideosList()
    var fragmentTab3: Fragment = DownloadVideos()
    var fragmentTab2: Fragment = HomeFragment()
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        overridePendingTransition(R.xml.exit,R.xml.enter)
        val check = (ContextCompat.checkSelfPermission(applicationContext, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {
            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }
//
        val bottomNavigation = findViewById<View>(R.id.bottom_navigation) as BottomNavigation
//        bottomNavigation.
        bottomNavigation.defaultItem = 1
        bottomNavigation.setOnSelectedItemChangeListener { itemId ->
            when (itemId) {
                R.id.tab_home -> {
                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab2)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }
                R.id.tab_images -> {
                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab1)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }
                R.id.tab_camera -> {

                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab4)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }
                R.id.tab_products -> {

                    var transaction = fragmentManager.beginTransaction()
                    transaction.replace(R.id.fragment_container,fragmentTab3)
                    transaction.addToBackStack(null)
                    transaction.commit()

                }

            }
        }
    }

    override fun onBackPressed(){

        startActivity(Intent(this@MainActivity,BackButton::class.java))

    }
}
