package pistalix.romanticvideostatus.romanticvideosong

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.app.Fragment
import android.os.Environment
import android.support.v7.widget.LinearLayoutManager
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdView
import kotlinx.android.synthetic.main.activity_main.view.*
import kotlinx.android.synthetic.main.status_whatsapp.view.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject
import java.io.File
import java.util.ArrayList

class FregmentStatus : Fragment() {
    lateinit var rootView :View
    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        rootView = inflater.inflate(R.layout.status_whatsapp, container, false)

        var list = DounloadVideosName()
        var MainFiles = ArrayList<File>()
        var i=list.size-1
        var j=0
        while(i >= 0){

            if(list[i].extension == "mp4"){

                MainFiles.add(list[i])
                j+=1
            }
            i-=1
        }

        if(MainFiles !=null){
            rootView.recyclerView.layoutManager = LinearLayoutManager(rootView.context)
            rootView.recyclerView.adapter = StatusVideos(MainFiles)
        }else{

            ToastInstallApp("Not yet Seen any story ")
        }

        return rootView
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
    fun ToastInstallApp(str :String){

        SuperActivityToast.create(rootView.context).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }
}