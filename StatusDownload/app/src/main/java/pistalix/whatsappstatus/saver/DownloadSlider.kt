package pistalix.whatsappstatus.saver

import android.content.Intent
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.os.Environment
import kotlinx.android.synthetic.main.activity_download_slider.*
import java.io.File
import java.util.*


class DownloadSlider : AppCompatActivity() {
    lateinit var MainFiles: ArrayList<File>
    lateinit var videourl : String
    var position : Int = 0
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_slider)
        overridePendingTransition(R.xml.slide_in_down, R.xml.nathing);
        var list = DounloadVideosName()
        videourl = intent.getStringExtra("videoid")
        position = intent.getIntExtra("position",0)
        var i=list.size-1
        var j=0
        var set_file = ArrayList<File>()
        while(i >= 0){
            if(list[i].extension == "jpg"){
                set_file.add(list[i])
                j+=1
            }

            i-=1
        }
        Collections.sort(set_file, object : Comparator<File> {
            override fun compare(f1: File, f2: File): Int {
                return java.lang.Long.valueOf(f2.lastModified())!!.compareTo(f1.lastModified())
            }
        })
        MainFiles = set_file
        var myCustomPagerAdapter = MyCustomPagerAdapter(this@DownloadSlider, MainFiles)
        viewPager.adapter = myCustomPagerAdapter
        viewPager.currentItem = position

        share.setOnClickListener{
            var uri = Uri.parse(MainFiles[viewPager.currentItem].toString())
            val intent = Intent(Intent.ACTION_SEND)
            intent.type = "image/*"
            intent.putExtra(Intent.EXTRA_TEXT, "Share Whatsapp Status")
            intent.putExtra(Intent.EXTRA_STREAM, uri)
            startActivity(Intent.createChooser(intent, "Share via"))
        }
    }
    fun DounloadVideosName(): ArrayList<File> {
        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val files = File(externalDirectory+"/WhatsappStatusSaver")
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

    override fun onBackPressed(){
        finish()
        overridePendingTransition(R.xml.nathing,R.xml.slide_out_down)

    }
}