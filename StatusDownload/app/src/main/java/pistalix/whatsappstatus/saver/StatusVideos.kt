package pistalix.whatsappstatus.saver

import android.content.Context
import android.content.Intent
import android.net.Uri
import android.os.Environment
import android.support.v7.widget.RecyclerView
import android.view.*
import android.widget.*

import com.google.android.gms.ads.InterstitialAd
import org.jetbrains.anko.find
import java.io.File
import java.util.ArrayList
import android.view.ViewGroup
import com.bumptech.glide.Glide
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdListener
import com.google.android.gms.ads.AdRequest
import org.apache.commons.io.FileUtils
import java.io.IOException


class StatusVideos (var name: ArrayList<File>,var select : ArrayList<File>): RecyclerView.Adapter<StatusVideos.ViewHolder>()
{
    lateinit var context1:Context
    val externalDirectory = Environment.getExternalStorageDirectory().toString()

    var mInterstitialAd: InterstitialAd? = null
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {
        Glide.with(context1).load(name[position].toString()).into(holder.video)

        if(select.contains(name[position])){
            holder.download_status.visibility = View.VISIBLE
        }else{

            holder.download_status.visibility = View.GONE
        }
//        holder.video.setOnClickListener{
//            val intent = Intent(context1, WhatsappView::class.java)
//            intent.putExtra("videoid", name[position].toString())
//            intent.putExtra("Name",name[position].name)
//            see_ad()
//            context1.startActivity(intent)
//
//        }
        val folder = File(externalDirectory+"/WhatsappStatusSaver/"+name[position].name)
        holder.download_status.setOnClickListener{
            var file  = File(name[position].toString())
            try {

                FileUtils.copyFile(file,folder)
                SingleMediaScanner(context1,folder)
                ToastInstallSucc("Status Downloded to Storage/WhatsappStatusSaver")

            } catch (e: IOException) {
                Toast.makeText(context1,e.toString(),Toast.LENGTH_SHORT).show()
            }

        }
    }

    override fun getItemCount(): Int {
        return name.size
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): StatusVideos.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.status_setter, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        var video: ImageView = itemView.find(R.id.video_view)
        var download_status  :ImageView = itemView.find(R.id.download_whatsapp)


        init {

        }
    }

    fun ToastInstallSucc(str :String){

        SuperActivityToast.create(context1).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_GREEN)).setAnimations(Style.ANIMATIONS_POP).show();
    }

    private fun showInterstitial() {
        if (mInterstitialAd!!.isLoaded()) {
            mInterstitialAd!!.show()
        }
    }
}