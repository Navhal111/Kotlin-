package com.example.lime.myapplication

import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v7.widget.LinearLayoutManager
import android.support.v7.widget.RecyclerView
import kotlinx.android.synthetic.main.activity_recycle.*
import org.json.JSONArray
import org.json.JSONObject

class Recycle : AppCompatActivity() {

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_recycle)

        val j = JSONObject()
        j.put("email", "ritesh@gmail.com")

        val j1 = JSONObject()
        j1.put("email", "milan@gmail.com")

        val j2 = JSONArray()
        j2.put(0, j)
        j2.put(1, j1)

        recyclerView.layoutManager = LinearLayoutManager(this)

//        recyclerView.adapter = RecylerAdapter(listOf<String>("ritesh","milan","seter"))

        recyclerView.adapter = RecyleJson(j2)

    }
}



