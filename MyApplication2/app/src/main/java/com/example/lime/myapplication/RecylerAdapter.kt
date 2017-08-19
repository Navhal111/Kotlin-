package com.example.lime.myapplication

import android.support.v7.widget.RecyclerView
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import org.jetbrains.anko.find

class RecylerAdapter(var name: List<String>): RecyclerView.Adapter<RecylerAdapter.ViewHolder>()
{


    override fun onBindViewHolder(holder:ViewHolder, position: Int) {

         holder.title.text=name[position]
    }

    override fun getItemCount(): Int {
        return name.size
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecylerAdapter.ViewHolder {

        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.listview, parent, false)

        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

       val title:TextView = itemView.find<TextView>(R.id.textViewUsername)

        init {

        }
    }
}