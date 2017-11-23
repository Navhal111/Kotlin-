package pistalix.whatsappstatus.saver

import android.content.Intent
import android.os.Bundle
import android.os.Environment
import android.support.v4.app.Fragment
import android.support.v7.widget.GridLayoutManager
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdListener
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.InterstitialAd
import kotlinx.android.synthetic.main.images.view.*
import org.apache.commons.io.FileUtils
import java.io.File
import java.util.*
import kotlin.collections.ArrayList


class DownloadBlankFragment : Fragment() {
    internal var isMultiSelect = false
    lateinit var MainFiles: ArrayList<File>
    lateinit var multiselectAdapter :ImageAdapter
    lateinit var multiselect_list: ArrayList<File>
    var download = false
    lateinit var rootView :View
    var mInterstitialAd: InterstitialAd? = null
    override fun onCreateView(inflater: LayoutInflater?, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        // Inflate the layout for this fragment
        rootView = inflater!!.inflate(R.layout.images, container, false)
        val imageResource = resources.getIdentifier("@drawable/ic_delete", null, rootView.context.packageName)
        rootView.filter.setImageDrawable(resources.getDrawable(imageResource))
        var list = DounloadVideosName()
        var i=list.size-1
        var j=0
        var set_file = ArrayList<File>()
        var set_select  = ArrayList<File>()
        while(i >= 0){

            if(list[i].extension == "jpg"){

                set_file.add(list[i])
                j+=1
            }
            i-=1
        }
//        ArrayList<File>().sort()
        Collections.sort(set_file, object : Comparator<File> {
            override fun compare(f1: File, f2: File): Int {
                return java.lang.Long.valueOf(f2.lastModified())!!.compareTo(f1.lastModified())
            }
        })
        MainFiles = set_file
        multiselect_list = set_select
        rootView.recyclerView.layoutManager = GridLayoutManager(rootView.context,3)
        multiselectAdapter = ImageAdapter(MainFiles,multiselect_list)
        rootView.recyclerView.adapter = multiselectAdapter

        rootView.recyclerView.addOnItemTouchListener(RecyclerItemClickListener(rootView.context, rootView.recyclerView, object : RecyclerItemClickListener.OnItemClickListener {
            override fun onItemClick(view: View, position: Int) {
                if(isMultiSelect){
                    Multiselect(position)
                    refressAdapter()
                }else{
                    val intent = Intent(rootView.context, DownloadSlider::class.java)
                    intent.putExtra("videoid", MainFiles[position].toString())
                    intent.putExtra("position",position)
                    startActivity(intent)
//                    ToastInstallApp("Select One")
//                    Multiselect(position)
//                    refressAdapter()
                }


            }

            override fun onItemLongClick(view: View, position: Int) {
                if (!isMultiSelect) {
                    multiselect_list = ArrayList<File>()
                    isMultiSelect = true
                }
                Multiselect(position)
                refressAdapter()
            }
        }))

        rootView.filter.setOnClickListener{

            if(!download){
                ToastInstallApp("Please Select Image")
            }else{
                var i=0
                while(i<multiselect_list.size){
//                    val folder = File(externalDirectory+"/WhatsappStatusSaver/"+multiselect_list[i].name)
                    FileUtils.deleteQuietly(multiselect_list[i])
                    MainFiles.remove(multiselect_list[i])
                    i++
                }
                ToastInstallSucc("Status Deleted From Storage/WhatsappStatusSaver")
                see_ad()
                val id = resources.getIdentifier("pistalix.whatsappstatus.saver:drawable/ic_delete", null, null)
                rootView.filter.setImageResource(id)
                download = false
                isMultiSelect = false
                multiselect_list = ArrayList<File>()
                refressAdapter()
            }
        }
        return rootView
    }
    fun Multiselect(position :Int){
        if(multiselect_list.contains(MainFiles[position])){

            multiselect_list.remove(MainFiles[position])
        }else{
            multiselect_list.add(MainFiles[position])

        }

        if(multiselect_list.size ==1 ){
            val id = resources.getIdentifier("pistalix.whatsappstatus.saver:drawable/delete1", null, null)
            rootView.filter.setImageResource(id)
            download = true

        }

        if (multiselect_list.size == 0){
            val id = resources.getIdentifier("pistalix.whatsappstatus.saver:drawable/ic_delete", null, null)
            rootView.filter.setImageResource(id)
            download = false
            isMultiSelect = false
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
    fun refressAdapter(){
        multiselectAdapter.select= multiselect_list
        multiselectAdapter.name = MainFiles
        multiselectAdapter.notifyDataSetChanged()
    }
    fun ToastInstallApp(str :String){

        SuperActivityToast.create(rootView.context).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }

    fun ToastInstallSucc(str :String){

        SuperActivityToast.create(rootView.context).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_GREEN)).setAnimations(Style.ANIMATIONS_POP).show();
    }
    fun see_ad(){

        val ads = rootView.context.getResources().getString(R.string.interstial_ads)
        mInterstitialAd = InterstitialAd(rootView.context)

        // set the ad unit ID
        mInterstitialAd!!.adUnitId = ads

        val adRequest1 = AdRequest.Builder()
                .build()

        mInterstitialAd!!.loadAd(adRequest1)

        mInterstitialAd!!.adListener = object : AdListener() {
            override fun onAdLoaded() {
                showInterstitial()
            }
        }
    }
    private fun showInterstitial() {
        if (mInterstitialAd!!.isLoaded()) {
            mInterstitialAd!!.show()
        }
    }
}
