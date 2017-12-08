package pistalix.sadvideostatus.sadvideosong

import android.content.Intent
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v7.widget.GridLayoutManager
import android.view.Gravity
import android.view.MenuItem
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.orhanobut.dialogplus.DialogPlus
import com.orhanobut.dialogplus.ViewHolder
import kotlinx.android.synthetic.main.activity_list_app.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONException
import org.json.JSONObject

class ListApp : AppCompatActivity() {
    lateinit var dailog: DialogPlus
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_list_app)
        overridePendingTransition(R.xml.backanim, R.xml.nathing)
        try{
            supportActionBar!!.setDisplayHomeAsUpEnabled(true)
            supportActionBar!!.title = "More Apps"
            recyclerView.layoutManager = GridLayoutManager(this,3)
        }catch (e: JSONException){
            toast("Error while Requesting")
        }catch (e:NullPointerException){
            toast("Error while Requesting")
        }catch (e:IllegalArgumentException){
            toast("Error while Requesting")
        }catch (e:Exception){
            toast("Error while Requesting")
        }

        try{
            val url =getString(R.string.applist)
            val Virsionquery = Volley.newRequestQueue(this@ListApp)
            val jsonobj2 = JsonObjectRequest(Request.Method.GET, url,null,
                    Response.Listener<JSONObject> {
                        response ->
                        try{
                            var mainJson :JSONArray = response.getJSONArray("applist")
//                            toast(mainJson.toString()
                            var Setjson  = JSONArray()
                            var i=0
                            while(i < mainJson.length()){

                                Setjson.put(mainJson.getJSONObject(i))
                                i++
                            }
                            recyclerView.adapter = AppRecycleList(mainJson)
                        }catch (e: JSONException){
                            toast("Error while Requesting")
                        }catch (e:NullPointerException){
                            toast("Error while Requesting")
                        }catch (e:IllegalArgumentException){
                            toast("Error while Requesting")
                        }catch (e:Exception){
                            toast("Error while Requesting")
                        }
                    }, Response.ErrorListener {
                toast("error")

            })
            Virsionquery.add(jsonobj2)
        }catch (e:NullPointerException){
            toast("Exception check yout network")
        }catch (e :IllegalArgumentException){
            toast("Exception check yout network")
        }catch (e:Exception){
            toast("Exception check yout network")
        }


    }

    override fun onBackPressed() {
            dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.activity_back_button)).setInAnimation(R.anim.abc_fade_in).create()
            try{

                var yes = dailog.findViewById(R.id.yes_button)
                var no = dailog.findViewById(R.id.no_button)
                var rate = dailog.findViewById(R.id.rate_app_back)
                yes.setOnClickListener{
                    try {
                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://developer?id=Pistalix%20Software%20Solutions")))
                    } catch (anfe: android.content.ActivityNotFoundException) {
                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/developer?id=Pistalix%20Software%20Solutions")))
                    }
                }
                no.setOnClickListener{

                    moveTaskToBack(true);
                    android.os.Process.killProcess(android.os.Process.myPid());
                    System.exit(1);

                }
                rate.setOnClickListener{
                    try {
                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.sadvideostatus.sadvideosong")))
                    } catch (anfe: android.content.ActivityNotFoundException) {
                        startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.sadvideostatus.sadvideosong")))
                    }
                }
                dailog.show()
            }catch (e :NullPointerException){


            }catch (e:IllegalArgumentException){

            }
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            android.R.id.home -> {
                finish()
                overridePendingTransition(R.xml.nathing, R.xml.exit)
                return true
            }
            else -> return super.onOptionsItemSelected(item)
        }
    }
}
