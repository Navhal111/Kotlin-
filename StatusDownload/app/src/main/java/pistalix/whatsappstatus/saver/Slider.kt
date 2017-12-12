package pistalix.whatsappstatus.saver

import android.content.Intent
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.os.Environment
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import kotlinx.android.synthetic.main.activity_slider.*
import java.io.File
import java.util.ArrayList


class Slider : AppCompatActivity() {
    lateinit var MainFiles: ArrayList<File>
    lateinit var videourl : String
    var position : Int = 0
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_slider)
        overridePendingTransition(R.xml.slide_in_down, R.xml.nathing);
        var list = DounloadVideosName()
        videourl = intent.getStringExtra("videoid")
        var set_position = intent.getIntExtra("position",0)
        var i=list.size-1
        var j=0
        var set_file = ArrayList<File>()
        while(i >= 0){
                set_file.add(list[i])
                j+=1
            i-=1
        }
        i=0
        j=0
        var set_file1 = ArrayList<File>()
        while(i < set_file.size){
            
            if(set_file[i].extension == "jpg"){
                if(set_position == i){
                    position =j
                }
                set_file1.add(set_file[i])
                j++
            }
           i+=1
        }

        MainFiles = set_file1
        var myCustomPagerAdapter = MyCustomPagerAdapter(this@Slider, MainFiles)
        viewPager.adapter = myCustomPagerAdapter
        viewPager.currentItem = position

        share.setOnClickListener{
            try {
                var uri = Uri.parse(MainFiles[viewPager.currentItem].toString())
                val intent = Intent(Intent.ACTION_SEND)
                intent.type = "image/*"
                intent.putExtra(Intent.EXTRA_TEXT, "Share Whatsapp Status")
                intent.putExtra(Intent.EXTRA_STREAM, uri)
                startActivity(Intent.createChooser(intent, "Share via"))
            }catch (e:NullPointerException){
                    ToastMainError("Something went wrong")
            }catch (e:IllegalArgumentException){
                    ToastMainError("Something went wrong")
            }catch (e:Exception){
                    ToastMainError("Something went wrong")
            }
        }
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

    override fun onBackPressed(){
        finish()
        overridePendingTransition(R.xml.nathing,R.xml.slide_out_down)

    }
    fun ToastMainError(Str :String){
        SuperActivityToast.create(this).setText(Str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
    }
}
