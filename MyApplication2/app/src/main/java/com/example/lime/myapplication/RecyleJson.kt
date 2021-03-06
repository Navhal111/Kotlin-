package com.example.lime.myapplication

import android.support.v7.widget.RecyclerView
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import org.jetbrains.anko.find
import org.json.JSONArray
import com.google.android.youtube.player.internal.i
import org.json.JSONObject



class RecyleJson (var name: JSONArray): RecyclerView.Adapter<RecyleJson.ViewHolder>()
{


    override fun onBindViewHolder(holder:ViewHolder, position: Int) {
        val json = name.getJSONObject(position)
        holder.title.text= json.getString("email")
    }

    override fun getItemCount(): Int {
        return name.length()
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecyleJson.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.listview, parent, false)

        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

        val title:TextView = itemView.find<TextView>(R.id.textViewUsername)

        init {

        }
    }
}