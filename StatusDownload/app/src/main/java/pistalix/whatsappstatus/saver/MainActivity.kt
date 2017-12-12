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
import android.net.Uri
import android.os.Environment
import android.os.Handler
import android.support.v7.widget.LinearLayoutManager
import android.view.Gravity
import android.view.View
import android.widget.TextView
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.orhanobut.dialogplus.DialogPlus
import com.orhanobut.dialogplus.ViewHolder
import org.jetbrains.anko.toast
import java.io.File
import java.util.ArrayList


class MainActivity : AppCompatActivity() {
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    var MainFiles : ArrayList<File>? = null
    private var mAdView: AdView? = null
    lateinit var dailog: DialogPlus
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

        try{
            var check_update = intent.getStringExtra("update");
            var msg = intent.getStringExtra("msg")
            if(check_update == "1"){
                update_app(msg)
            }
        }catch (e:NullPointerException){

        }catch (e:IllegalArgumentException){

        }catch (e:Exception){
        }
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

    fun update_app(str:String){

        dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.update_app)).setInAnimation(R.anim.abc_fade_in).create()
        try{

            var yes = dailog.findViewById(R.id.yes_button)
            var no = dailog.findViewById(R.id.no_button)
            var msg : TextView = dailog.findViewById(R.id.update_msg) as TextView
            msg.text =str
            yes.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.whatsappstatus.saver")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.whatsappstatus.saver")))
                }
            }
            no.setOnClickListener{
                try{
                    dailog.dismiss()
                }catch (e :NullPointerException){

                    toast("error")
                }catch (e:IllegalArgumentException){
                    toast("error")
                }


            }
            dailog.show()
        }catch (e :NullPointerException){

            toast("error")
        }catch (e:IllegalArgumentException){
            toast("error")
        }

    }

    override fun onBackPressed() {
        dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.activity_back_button)).setInAnimation(R.anim.abc_fade_in).create()
        try{

            var yes = dailog.findViewById(R.id.yes_button)
            var no = dailog.findViewById(R.id.no_button)
            var rate = dailog.findViewById(R.id.rate_app_back)
            yes.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
                }
            }
            no.setOnClickListener{

                moveTaskToBack(true);
                android.os.Process.killProcess(android.os.Process.myPid());
                System.exit(1);

            }
            rate.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.whatsappstatus.saver")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.whatsappstatus.saver")))
                }
            }
            dailog.show()
        }catch (e :NullPointerException){
            toast("Something went wrong")
        }catch (e:IllegalArgumentException){
            toast("Something went wrong")

        }
    }

}
