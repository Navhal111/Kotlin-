package pistalix.romanticvideostatus.romanticvideosong

import android.content.Context
import android.content.Intent
import android.support.v7.widget.CardView
import android.support.v7.widget.RecyclerView
import android.view.*
import android.widget.*
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdListener
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.google.android.gms.ads.InterstitialAd
import com.squareup.picasso.Picasso
import org.jetbrains.anko.find
import org.json.JSONArray
import org.json.JSONException
import org.json.JSONObject


class RecyleJson (var name: JSONArray,var playlistId:String,var ads :InterstitialAd): RecyclerView.Adapter<RecyleJson.ViewHolder>()
{
    lateinit var context1:Context
    val adRequest = AdRequest.Builder().build()

    override fun onBindViewHolder(holder:ViewHolder, position: Int) {
        val json1:JSONObject = name.getJSONObject(position)
        try {
        val imageurl=json1.getString("imageurl")
        Picasso.with(context1).load(imageurl).fit().into(holder.thummail)
        ads.adListener = object : AdListener() {
            override fun onAdClosed() {
                val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
                ads.loadAd(adRequest)
            }
        }
        }catch (e:NullPointerException){
            ToastInstallApp("Something Went wrong")
        }catch (e: JSONException){
            ToastInstallApp("Something Went wrong")
        }catch (e:IllegalArgumentException){
            ToastInstallApp("Something Went wrong")
        }catch (e:Exception){
            ToastInstallApp("Something Went wrong")
        }
        if(position%3==0 && position !=0){
            try{
            holder.mAdView.visibility = View.VISIBLE
            holder.mAdView.loadAd(adRequest)
            holder.title.text= json1.getString("title")
            val videoid =json1.getString("id")
            holder.video.setOnClickListener {
                val intent = Intent(context1, MainVideoView::class.java)
                intent.putExtra("videoid", videoid)
                intent.putExtra("playlistId", playlistId)
                intent.putExtra("Title",json1.getString("title"))
                context1.startActivity(intent)
                ads.show()
                val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
                ads.loadAd(adRequest)
            }
            }catch (e:NullPointerException){
                ToastInstallApp("Something Went wrong")
            }catch (e: JSONException){
                ToastInstallApp("Something Went wrong")
            }catch (e:IllegalArgumentException){
                ToastInstallApp("Something Went wrong")
            }catch (e:Exception){
                ToastInstallApp("Something Went wrong")
            }
        }else{
        try{
            holder.mAdView.visibility = View.GONE
            holder.title.text= json1.getString("title")
            val videoid =json1.getString("id")
            val imageurl=json1.getString("imageurl")
            holder.video.setOnClickListener {

                val intent = Intent(context1, MainVideoView::class.java)
                intent.putExtra("videoid", videoid)
                intent.putExtra("playlistId", playlistId)
                intent.putExtra("Title",json1.getString("title"))
                context1.startActivity(intent)
                ads.show()
                val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
                ads.loadAd(adRequest)
            }
        }catch (e:NullPointerException){
            ToastInstallApp("Something Went wrong")
        }catch (e: JSONException){
            ToastInstallApp("Something Went wrong")
        }catch (e:IllegalArgumentException){
            ToastInstallApp("Something Went wrong")
        }catch (e:Exception){
            ToastInstallApp("Something Went wrong")
        }
        }


    }

    override fun getItemCount(): Int {
        return name.length()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyleJson.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.listview, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val title:TextView = itemView.find(R.id.title)
        val thummail:ImageView = itemView.find(R.id.imagethum)
        val video:CardView = itemView.find(R.id.card_view2)
        var mAdView: AdView = itemView.find(R.id.adView)

        init {

        }
    }

    fun ToastInstallApp(str :String){

        SuperActivityToast.create(context1).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }
}

