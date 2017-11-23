package pistalix.gujaratibalgeetvideo.gujaratirhymes


import android.content.Context
import android.net.Uri
import android.support.v7.widget.RecyclerView
import android.view.*
import android.widget.*
import com.google.android.gms.ads.InterstitialAd
import org.jetbrains.anko.find
import java.io.File
import java.util.ArrayList
import android.view.ViewGroup
import android.content.Intent
import com.bumptech.glide.Glide


class RecycleDownloade (var name: ArrayList<File>): RecyclerView.Adapter<RecycleDownloade.ViewHolder>()
{
    lateinit var context1:Context

    var mInterstitialAd: InterstitialAd? = null
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {
    holder.title.text  = name[position].name.toString()

        Glide.with(context1).load(name[position].toString()).centerCrop().into(holder.video)

        holder.share.setOnClickListener{


                    var string_path = Uri.parse(name[position].toString())

                    val intent = Intent(Intent.ACTION_SEND)
                    intent.type = "video/*"
                    intent.putExtra(Intent.EXTRA_STREAM, string_path)
                    context1.startActivity(Intent.createChooser(intent, "Share via"))
        }
        holder.video.setOnClickListener{

            val intent = Intent(context1, DownloadVideoView::class.java)
            intent.putExtra("videoid", name[position].toString())
            intent.putExtra("Name",name[position].name)
            context1.startActivity(intent)

        }

//    holder.vieo.seekTo(4)
//        holder.video.

    }

    override fun getItemCount(): Int {
        return name.size
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecycleDownloade.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.downloadvideo_list, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        var video: ImageView = itemView.find(R.id.video_view)
        var title: TextView = itemView.find(R.id.videotitle)
        var share :ImageView =itemView.find(R.id.whatsapp_share)
//        var video_layout:LinearLayout = itemView.find(R.id.play)

        init {

        }
    }
}
