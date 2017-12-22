package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Adepter

import android.content.Context
import android.content.Intent
import android.net.Uri
import android.support.v7.widget.CardView
import android.support.v7.widget.RecyclerView
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.animation.Animation
import android.view.animation.AnimationUtils
import android.widget.ImageView
import android.widget.TextView
import com.bumptech.glide.Glide
import org.jetbrains.anko.find
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONException
import org.json.JSONObject
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R

class AppRecycleList(var name :JSONArray): RecyclerView.Adapter<AppRecycleList.ViewHolder>()
{
    lateinit var context1: Context
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {
        var azoomin = AnimationUtils.loadAnimation(context1, R.anim.azoomin)
        var azoomout = AnimationUtils.loadAnimation(context1, R.anim.azoomout)
        holder.appimage.animation = azoomin
        var mainJson :JSONObject = name.getJSONObject(position)
        try{
//            Picasso.with(context1).load(mainJson.getString("img")).fit().into(holder.appimage)
            Glide.with(context1).load(mainJson.getString("img")).into(holder.appimage)
            holder.appname.text = mainJson.getString("Name")

            holder.appmain.setOnClickListener{
                var pacakegname = mainJson.getString("packeg")
                try {
                    context1.startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id="+pacakegname)))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    context1.startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id="+pacakegname)))
                }

            }
        }catch (e: JSONException){
            context1.toast("Error while Requesting")
        }catch (e:NullPointerException){
            context1.toast("Error while Requesting")
        }catch (e:IllegalArgumentException){
            context1.toast("Error while Requesting")
        }catch (e:Exception){
            context1.toast("Error while Requesting")
        }

        azoomin.setAnimationListener(object : Animation.AnimationListener {

            override fun onAnimationStart(arg0: Animation) {
                // TODO Auto-generated method stub

            }

            override fun onAnimationRepeat(arg0: Animation) {
                // TODO Auto-generated method stub

            }

            override fun onAnimationEnd(arg0: Animation) {
                holder.appimage.startAnimation(azoomout)

            }
        })
        azoomout.setAnimationListener(object : Animation.AnimationListener {

            override fun onAnimationStart(arg0: Animation) {
                // TODO Auto-generated method stub

            }

            override fun onAnimationRepeat(arg0: Animation) {
                // TODO Auto-generated method stub

            }

            override fun onAnimationEnd(arg0: Animation) {
                holder.appimage.startAnimation(azoomin)

            }
        })

    }

    override fun getItemCount(): Int {
        return  name.length()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): AppRecycleList.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.applayout, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

        var appmain : CardView = itemView.find(R.id.applistmain)
        var appimage :ImageView  = itemView.find(R.id.appimage)
        var appname : TextView = itemView.find(R.id.appname)


        init {

        }
    }
}