package pistalix.romanticvideostatus.romanticvideosong


import android.os.Environment
import org.json.JSONArray
import org.json.JSONObject
import java.io.File
import java.util.ArrayList


class SingelFunction {

    fun DounloadVideos(): JSONArray {
        var VideoId = JSONArray()
        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val files = File(externalDirectory+"/"+externalDirectory+ "/RomanticStatus/")
        val inFiles = ArrayList<File>()
        val fileslist = files.listFiles()

        if(fileslist != null){
            for (file in fileslist) {
                inFiles.add(file)
                var TestId = JSONObject()
                var Teatarray = file.name.split(" $ ")
                if(Teatarray.size > 1){
                    TestId.put("Id",Teatarray[1].replace(".mp4",""))
                    VideoId.put(TestId)
                }

            }
            return VideoId
        }
        return VideoId
    }

    fun DounloadVideosName(): ArrayList<File> {
        val externalDirectory = Environment.getExternalStorageDirectory().toString()
        val files = File(externalDirectory+"/"+externalDirectory+ "/RomanticStatus/")
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


}