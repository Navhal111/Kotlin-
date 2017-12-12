package pistalix.whatsappstatus.saver

import android.content.Context
import android.content.Intent
import android.net.Uri
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



class StatusImage (var name: ArrayList<File>): RecyclerView.Adapter<StatusImage.ViewHolder>()
{
    lateinit var context1:Context
    var mInterstitialAd: InterstitialAd? = null
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {
            if(name[position].extension == "jpg"){
                holder.image.visibility = View.VISIBLE
                Glide.with(context1).load(Uri.fromFile(File(name[position].toString()))).into(holder.image)
                holder.video.visibility = View.GONE

            }else{
                holder.video.visibility = View.VISIBLE
                holder.image.visibility = View.GONE
                Glide.with(context1).load(name[position].toString()).into(holder.video)
            }

        holder.video.setOnClickListener{
            val intent = Intent(context1, WhatsappView::class.java)
            intent.putExtra("videoid", name[position].toString())
            intent.putExtra("Name",name[position].name)
            see_ad()
            context1.startActivity(intent)
        }

            holder.image.setOnClickListener{
                val intent = Intent(context1, Slider::class.java)
                intent.putExtra("videoid", name[position].toString())
                intent.putExtra("position",position)
                context1.startActivity(intent)
            }

    }

    override fun getItemCount(): Int {
        return name.size
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): StatusImage.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.status_image, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        var video: ImageView = itemView.find(R.id.statusvideo)
        var image :ImageView = itemView.find(R.id.status)

        init {

        }
    }

    fun ToastInstallSucc(str :String){

        SuperActivityToast.create(context1).setText(str).setDuration(Style.DURATION_SHORT).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_GREEN)).setAnimations(Style.ANIMATIONS_POP).show();
    }

    private fun showInterstitial() {
        if (mInterstitialAd!!.isLoaded()) {
            mInterstitialAd!!.show()
        }
    }
    fun see_ad(){

        val ads = context1.getResources().getString(R.string.interstial_ads)
        mInterstitialAd = InterstitialAd(context1)

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


}