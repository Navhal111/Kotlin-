package pistalix.gujaratibalgeetvideo.gujaratirhymes

import android.app.Fragment
import android.app.ProgressDialog
import android.os.Build
import android.os.Bundle
import android.support.annotation.RequiresApi
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.support.v7.widget.LinearLayoutManager
import kotlinx.android.synthetic.main.activity_videos_list.view.*
import org.jetbrains.anko.toast



class DownloadVideos : Fragment(){
    private var progress: ProgressDialog? = null
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    lateinit var rootView : View
    @RequiresApi(Build.VERSION_CODES.JELLY_BEAN)
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
            toast("No File Downloaded")

        }
        return rootView

    }

    fun download() {
        progress = ProgressDialog(rootView.context)
        progress!!.setMessage("Keep Calm,\n" +
                "we are requesting video")
        progress!!.setProgressStyle(ProgressDialog.STYLE_SPINNER)
        val res = resources
        progress!!.isIndeterminate = true
        progress!!.progress = 0
        progress!!.show()
        val totalProgressTime = 100
        val t = object : Thread() {
            override fun run() {
                var jumpTime = 0

                while (jumpTime < totalProgressTime) {
                    try {
                        Thread.sleep(200)
                        jumpTime += 5
                        progress!!.setProgress(jumpTime)
                    } catch (e: InterruptedException) {
                        // TODO Auto-generated catch block
                        e.printStackTrace()
                    }

                }
            }
        }
        t.start()

    }

}