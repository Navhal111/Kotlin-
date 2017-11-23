package pistalix.sadvideostatus.sadvideosong


import android.app.Fragment
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.support.v7.widget.LinearLayoutManager
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import kotlinx.android.synthetic.main.activity_download_videos.view.*


class DownloadVideos : Fragment(){
    lateinit var rootView : View
    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        rootView = inflater.inflate(R.layout.activity_download_videos, container, false)
        val Mainfunction = SingelFunction()
        val files =Mainfunction.DounloadVideosName()

        if(files.isNotEmpty()){

            rootView.recyclerView.layoutManager = LinearLayoutManager(rootView.context)
            rootView.recyclerView.adapter = RecycleDownloade(files)
        }else{
            ToastInstallApp("No File Downloaded")

        }
        return rootView

    }

    fun ToastInstallApp(str :String){

        SuperActivityToast.create(rootView.context).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }


}