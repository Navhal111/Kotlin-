package pistalix.whatsappstatus.saver

import android.annotation.SuppressLint
import android.content.Context
import android.net.Uri
import android.support.v7.widget.RecyclerView
import android.view.*
import android.widget.*
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.InterstitialAd
import org.jetbrains.anko.find
import java.io.File
import com.bumptech.glide.Glide


//https://i.ytimg.com/vi/I4wkxIUQi1g/default.jpg
class ImageAdapter (var name: ArrayList<File>,var select : ArrayList<File>): RecyclerView.Adapter<ImageAdapter.ViewHolder>()
{
    lateinit var context1:Context
    val adRequest = AdRequest.Builder().build()
    var mInterstitialAd: InterstitialAd? = null
    @SuppressLint("WrongConstant")
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {

        Glide.with(context1).load(Uri.fromFile(File(name[position].toString()))).into(holder.thummail)

        if(select.contains(name[position])){
            holder.set_click.visibility = View.VISIBLE
        }else{

            holder.set_click.visibility = View.GONE
        }

    }

    override fun getItemCount(): Int {
        return name.size
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ImageAdapter.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.image_adpter, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

        val thummail:ImageView = itemView.find(R.id.status_image)
        val set_click :ImageView = itemView.find(R.id.status_tick)

        init {

        }
    }
}
