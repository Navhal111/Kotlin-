package com.example.lime.videopost

import android.Manifest
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import kotlinx.android.synthetic.main.activity_main.*
import android.R.attr.start
import android.app.Activity
import android.content.pm.PackageManager
import android.os.Environment
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.os.Build
import android.support.annotation.RequiresApi
import java.io.*
import java.net.URL


class MainActivity : AppCompatActivity() {
    val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private val SAMPLE_VIDEO = "https://instagram.fstv1-1.fna.fbcdn.net/t50.2886-16/21228474_145282626061004_7636809833517154304_n.mp4"
    @RequiresApi(Build.VERSION_CODES.N)
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        var check = (ContextCompat.checkSelfPermission(this, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {

            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }
//        val externalDirectory = Environment.getExternalStorageDirectory().toString()
//        val folder = File(externalDirectory + "/PostVideo")
//        if (!folder.exists()) {
//            folder.mkdir()
//        }
//        video_view.start(SAMPLE_VIDEO)
//
//        video_view.setOnClickListener {
//            if (video_view.isPlaying)
//                video_view.pause()
//            else
//                video_view.play()
//            video_view.setShowSpinner(true)
//            video_view.setStopSystemAudio(true)
//        }
//        share.setOnClickListener {
//
//            downloadFile("http://www.androhub.com/demo/demo.mp4",folder)
//
//
//        }
//
//

    }
//
//    override fun onStop() {
//        super.onStop()
//        video_view.release()
//    }

//    @RequiresApi(Build.VERSION_CODES.N)
//    private fun downloadFile(url: String, outputFile: File) {
//        try {
//            val u = URL(url)
//            val conn = u.openConnection()
//            val contentLength = conn.contentLength
//
//            val stream = DataInputStream(u.openStream())
//
//            val buffer = ByteArray(contentLength)
//            stream.readFully(buffer)
//            stream.close()
//
//            val fos = DataOutputStream(FileOutputStream(outputFile))
//            fos.write(buffer)
//            fos.flush()
//            fos.close()
//        } catch (e: FileNotFoundException) {
//            return  // swallow a 404
//        } catch (e: IOException) {
//            return  // swallow a 404
//        }
//
//    }



}
