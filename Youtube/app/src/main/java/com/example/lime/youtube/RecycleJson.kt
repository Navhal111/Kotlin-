package com.example.lime.youtube

import android.content.Context
import android.graphics.Bitmap
import android.graphics.BitmapFactory
import android.graphics.BitmapRegionDecoder
import android.net.Uri
import android.support.v7.widget.RecyclerView
import android.text.Layout
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.AdapterView
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import org.jetbrains.anko.find
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONObject
import java.net.URI
import java.net.URL
import java.util.Collections.replaceAll




//https://i.ytimg.com/vi/I4wkxIUQi1g/default.jpg
class RecyleJson (var name: JSONArray): RecyclerView.Adapter<RecyleJson.ViewHolder>()
{
    lateinit var context1:Context
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {
        var json1:JSONObject
        json1 = name.getJSONObject(position)
        holder.title.text= json1.getString("title")
        holder.view.text=json1.getString("view")
        holder.like.text=json1.getString("like")
//        var url: Uri=Uri.parse("https://i.ytimg.com/vi/"+json1.getString("id")+"/default.jpg")
//
//        holder.thummail.setImageURI(url)

        var url = "https://i.ytimg.com/vi/"+json1.getString("id")+"/default.jpg"
        val appUtils = AppUtils()
        appUtils.setImage(url, holder.thummail, 0, context1)

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
        val title:TextView = itemView.find<TextView>(R.id.title)
        val view:TextView = itemView.find<TextView>(R.id.view)
        val like:TextView = itemView.find<TextView>(R.id.like)
        val thummail:ImageView = itemView.find<ImageView>(R.id.imagethum)

        init {

        }
    }

}