package com.example.lime.youtube

import android.annotation.SuppressLint
import android.app.ProgressDialog
import android.support.v7.app.AppCompatActivity

import android.support.v4.app.Fragment
import android.support.v4.app.FragmentManager
import android.support.v4.app.FragmentPagerAdapter
import android.os.Bundle
import android.support.v7.widget.LinearLayoutManager
import android.view.LayoutInflater
import android.view.Menu
import android.view.MenuItem
import android.view.View
import org.jetbrains.anko.toast
import android.view.ViewGroup
import android.widget.Button
import android.widget.Toast
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley

import kotlinx.android.synthetic.main.activity_main.*
import kotlinx.android.synthetic.main.tab1.*
import kotlinx.android.synthetic.main.tab1.view.*
import kotlinx.android.synthetic.main.tab2.*
import org.json.JSONArray
import org.json.JSONObject


class MainActivity : AppCompatActivity() {


    private var mSectionsPagerAdapter: SectionsPagerAdapter? = null
    private var progress:ProgressDialog? = null
    @SuppressLint("WrongViewCast", "ResourceType")
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        mSectionsPagerAdapter = SectionsPagerAdapter(supportFragmentManager)

        // Set up the ViewPager with the sections adapter.
        container.adapter = mSectionsPagerAdapter

        download(this@MainActivity)

//        https://www.googleapis.com/youtube/v3/videos?part=snippet&id=EhNWzcUqGbI&key=
//        https://www.googleapis.com/youtube/v3/search?key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY&channelId=UCx8g6OKTHAyIsmEJr6FPl5w&part=snippet,id&order=date&maxResults=20
        //www.googleapis.com/youtube/v3/videos?part=statistics&id=hqepb5hzuB0&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY
        val queyj = Volley.newRequestQueue(this@MainActivity)
        val jsonobj = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=UCx8g6OKTHAyIsmEJr6FPl5w&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY",null,

                Response.Listener<JSONObject>
                {
                    response ->
//                    toast(response.toString())
                    val setert:JSONArray = response.get("items") as JSONArray
                    val views:JSONObject = setert.getJSONObject(0).getJSONObject("statistics")

                    viewcount.text=views.getString("viewCount")
                    subcount.text=views.getString("subscriberCount")
                    videocount.text=views.getString("videoCount")
                    toast("Set Values")
                    progress!!.cancel()
                }, Response.ErrorListener {
            toast("Network issue")
        })

        queyj.add(jsonobj)

        val queyj1 = Volley.newRequestQueue(this@MainActivity)
        val jsonobj1 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/channels?part=snippet&id=UCx8g6OKTHAyIsmEJr6FPl5w&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY",null,

                Response.Listener<JSONObject>
                {
                    response ->
                    //                    toast(response.toString())
                    val setert:JSONArray = response.get("items") as JSONArray
                    val views:JSONObject = setert.getJSONObject(0).getJSONObject("snippet")


                    header1.text=views.getString("title")
                    header2.text=views.getString("title")


                }, Response.ErrorListener {
            toast("Network issue")
        })

        queyj1.add(jsonobj1)



//
//            val queyj2 = Volley.newRequestQueue(this@MainActivity)
//            val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/search?key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY&channelId=UCx8g6OKTHAyIsmEJr6FPl5w&part=snippet,id&order=date&maxResults=20",null,
//
//                    Response.Listener<JSONObject>
//                    {
//                        response ->
//                        //                    toast(response.toString())
//                        val setert:JSONArray = response.get("items") as JSONArray
//                        val j2 = JSONArray()
//                        var j1 =JSONObject()
//                        var j3 =JSONObject()
////                        toast(setert.get(0).toString())
//                        var i=0
//                        while(i<setert.length()-1){
//                            j1= setert.get(i) as JSONObject
//                            j3=j1.get("snippet") as JSONObject
//                            j2.put(i,j3.get("title"))
//                         i++
//                        }
////                        toast(j2.toString())
//                        recyclerView.layoutManager = LinearLayoutManager(this@MainActivity)
//
//                        recyclerView.adapter = RecyleJson(j2)
//
//
//                    }, Response.ErrorListener {
//                toast("Network issue")
//            })
//
//            queyj2.add(jsonobj2)
    }

    fun download(view: MainActivity) {
        progress = ProgressDialog(this)
        progress!!.setMessage("Get Your data")
        progress!!.setProgressStyle(ProgressDialog.STYLE_SPINNER)
        progress!!.isIndeterminate = true
        progress!!.progress = 0
        progress!!.show()
        val totalProgressTime = 100
        val t = object : Thread() {
            override fun run() {
                var jumpTime = 0

                while (jumpTime < totalProgressTime) {
                    try {
                        Thread.sleep(200)
                        jumpTime += 5
                        progress!!.setProgress(jumpTime)
                    } catch (e: InterruptedException) {
                        // TODO Auto-generated catch block
                        e.printStackTrace()
                    }

                }
            }
        }
        t.start()

    }

    override fun onCreateOptionsMenu(menu: Menu): Boolean {
        // Inflate the menu; this adds items to the action bar if it is present.
        menuInflater.inflate(R.menu.menu_main, menu)
        return true
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        val id = item.itemId

//        if (id == R.id.action_settings) {
//            return true
//        }

        return super.onOptionsItemSelected(item)
    }


    /**
     * A [FragmentPagerAdapter] that returns a fragment corresponding to
     * one of the sections/tabs/pages.
     */
    inner class SectionsPagerAdapter(fm: FragmentManager) : FragmentPagerAdapter(fm) {

        override fun getItem(position: Int): Fragment {
            // getItem is called to instantiate the fragment for the given page.
            // Return a PlaceholderFragment (defined as a static inner class below).
            if(position==1){
                return PlaceholderFragment1.newInstance(position + 1)
            }
            if(position==2){
                return PlaceholderFragment.newInstance(position + 1)
            }else{

                return PlaceholderFragment.newInstance(position + 1)
            }


        }

        override fun getCount(): Int {
            // Show 3 total pages.
            return 2
        }
    }

    /**
     * A placeholder fragment containing a simple view.
     */
    class PlaceholderFragment : Fragment() {

        override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                                  savedInstanceState: Bundle?): View? {
            val rootView = inflater.inflate(R.layout.tab1, container, false)

              rootView.ref1.setOnClickListener{
                  val queyj = Volley.newRequestQueue(context)
                  val jsonobj = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/channels?part=statistics&id=UCx8g6OKTHAyIsmEJr6FPl5w&key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY",null,

                          Response.Listener<JSONObject>
                          {
                              response ->
                              //                    toast(response.toString())
                              val setert: JSONArray = response.get("items") as JSONArray
                              val views: JSONObject = setert.getJSONObject(0).getJSONObject("statistics")

                              viewcount.text=views.getString("viewCount")
                              subcount.text=views.getString("subscriberCount")
                              videocount.text=views.getString("videoCount")
                              Toast.makeText(context,"Refress Values",Toast.LENGTH_SHORT).show();
                          }, Response.ErrorListener {
                      Toast.makeText(context,"Network issue",Toast.LENGTH_SHORT).show();
                  })
                  queyj.add(jsonobj)

              }
//            rootView.section_label.text = getString(R.string.section_format, arguments.getInt(ARG_SECTION_NUMBER))
            return rootView
        }

        companion object {
            /**
             * The fragment argument representing the section number for this
             * fragment.
             */
            private val ARG_SECTION_NUMBER = "section_number"

            /**
             * Returns a new instance of this fragment for the given section
             * number.
             */
            fun newInstance(sectionNumber: Int): PlaceholderFragment {
                val fragment = PlaceholderFragment()
                val args = Bundle()
                args.putInt(ARG_SECTION_NUMBER, sectionNumber)
                fragment.arguments = args
                return fragment
            }
        }

    }
    class PlaceholderFragment1 : Fragment() {

        override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                                  savedInstanceState: Bundle?): View? {
            val rootView = inflater.inflate(R.layout.tab2, container, false)

            val queyj2 = Volley.newRequestQueue(context)
            val jsonobj2 = JsonObjectRequest(Request.Method.GET, "https://www.googleapis.com/youtube/v3/search?key=AIzaSyA6n4XwynMfe8n7bzZZsQjxquEU4o7MELY&channelId=UCx8g6OKTHAyIsmEJr6FPl5w&part=snippet,id&order=date&maxResults=20",null,

                    Response.Listener<JSONObject>
                    {
                        response ->
                        //                    toast(response.toString())
                        val setert:JSONArray = response.get("items") as JSONArray
                        val j2 = JSONArray()
                        var j1 =JSONObject()
                        var j3 =JSONObject()
//                        toast(setert.get(0).toString())
                        var i=0
                        while(i<setert.length()-1){
                            j1= setert.get(i) as JSONObject
                            j3=j1.get("snippet") as JSONObject
                            j2.put(i,j3.get("title"))
                            i++
                        }
//                        toast(j2.toString())
                        recyclerView.layoutManager = LinearLayoutManager(context)

                        recyclerView.adapter = RecyleJson(j2)


                    }, Response.ErrorListener {

            })

            queyj2.add(jsonobj2)


//            rootView.section_label.text = getString(R.string.section_format, arguments.getInt(ARG_SECTION_NUMBER))
            return rootView
        }

        companion object {
            /**
             * The fragment argument representing the section number for this
             * fragment.
             */
            private val ARG_SECTION_NUMBER = "section_number"

            /**
             * Returns a new instance of this fragment for the given section
             * number.
             */
            fun newInstance(sectionNumber: Int): PlaceholderFragment1 {
                val fragment = PlaceholderFragment1()
                val args = Bundle()
                args.putInt(ARG_SECTION_NUMBER, sectionNumber)
                fragment.arguments = args
                return fragment
            }
        }
    }
}
