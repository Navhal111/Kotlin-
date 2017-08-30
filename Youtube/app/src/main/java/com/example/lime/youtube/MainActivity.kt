package com.example.lime.youtube

import android.support.design.widget.Snackbar
import android.support.v7.app.AppCompatActivity

import android.support.v4.app.Fragment
import android.support.v4.app.FragmentManager
import android.support.v4.app.FragmentPagerAdapter
import android.support.v4.view.ViewPager
import android.os.Bundle
import android.view.LayoutInflater
import android.view.Menu
import android.view.MenuItem
import android.view.View
import org.jetbrains.anko.toast
import android.view.ViewGroup
import android.widget.Toast
import android.widget.Toolbar
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley

import kotlinx.android.synthetic.main.activity_main.*
import kotlinx.android.synthetic.main.tab1.*
import org.json.JSONArray
import org.json.JSONObject


class MainActivity : AppCompatActivity() {

//

    /**
     * The [android.support.v4.view.PagerAdapter] that will provide
     * fragments for each of the sections. We use a
     * {@link FragmentPagerAdapter} derivative, which will keep every
     * loaded fragment in memory. If this becomes too memory intensive, it
     * may be best to switch to a
     * [android.support.v4.app.FragmentStatePagerAdapter].
     */
    private var mSectionsPagerAdapter: SectionsPagerAdapter? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        setSupportActionBar(toolbar)
        // Create the adapter that will return a fragment for each of the three
        // primary sections of the activity.
        mSectionsPagerAdapter = SectionsPagerAdapter(supportFragmentManager)

        // Set up the ViewPager with the sections adapter.
        container.adapter = mSectionsPagerAdapter

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
                }, Response.ErrorListener {
            toast("Errror")
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


                    toolbar.setTitle(views.getString("title"));


                }, Response.ErrorListener {
            toast("Errror")
        })

        queyj1.add(jsonobj1)

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
