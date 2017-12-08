package pistalix.romanticvideostatus.romanticvideosong

import android.Manifest
import android.app.Activity
import android.app.Fragment
import android.app.ProgressDialog
import android.content.pm.PackageManager
import android.net.Uri
import android.os.Build
import android.os.Bundle
import android.support.annotation.RequiresApi
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.support.v7.widget.LinearLayoutManager
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import kotlinx.android.synthetic.main.activity_videos_list.view.*
import kotlinx.android.synthetic.main.downloadvideo_list.*
import kotlinx.android.synthetic.main.downloadvideo_list.view.*
import org.jetbrains.anko.toast
import java.io.File


class DownloadVideos : Fragment(){
    lateinit var rootView : View
    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        rootView = inflater.inflate(R.layout.activity_download_videos, container, false)
//        val check = (ContextCompat.checkSelfPermission(rootView.context, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
//        if (!check) {
//
//            ActivityCompat.requestPermissions(this as Activity,
//                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
//                    REQUEST_WRITE_EXTERNAL_STORAGE)
//        }

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