package pistalix.ChristmasPhotoStickerFrames.PhotoEditor

import android.content.Intent
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.v7.widget.GridLayoutManager
import android.view.MenuItem
import android.widget.Toast
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_back_button.*
import org.jetbrains.anko.toast
import org.json.JSONArray
import org.json.JSONException
import org.json.JSONObject
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Adepter.AppRecycleList

class BackButton : AppCompatActivity() {
    private var pressBackToast: Toast? = null
    private var mLastBackPress: Long = 0
    private val mBackPressThreshold: Long = 3500
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_back_button)
        overridePendingTransition(R.xml.backanim, R.xml.nathing)
        pressBackToast = Toast.makeText(applicationContext, R.string.press_back_again_to_exit,
                Toast.LENGTH_SHORT)
        try{
            supportActionBar!!.setDisplayHomeAsUpEnabled(true)
            supportActionBar!!.title = "More Apps"
            recyclerView.layoutManager = GridLayoutManager(this@BackButton,3)
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
            val Virsionquery = Volley.newRequestQueue(this@BackButton)
            val jsonobj2 = JsonObjectRequest(Request.Method.GET, url,null,
                    Response.Listener<JSONObject> {
                        response ->
                        try{
                            var mainJson : JSONArray = response.getJSONArray("applist")
//                            toast(mainJson.toString()
                            var Setjson  = JSONArray()
                            var i=0
                            while(i < mainJson.length()){
                                if(mainJson.getJSONObject(i)["packeg"] != "pistalix.ChristmasPhotoStickerFrames.PhotoEditor"){
                                    Setjson.put(mainJson.getJSONObject(i))
                                }
                                i++
                            }
                            recyclerView.adapter = AppRecycleList(Setjson)
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
        rateus.setOnClickListener{
            try {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.ChristmasPhotoStickerFrames.PhotoEditor")))
            } catch (anfe: android.content.ActivityNotFoundException) {
                startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.ChristmasPhotoStickerFrames.PhotoEditor")))
            }
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
    //    override fun onBackPressed(){
//        moveTaskToBack(true);
//        android.os.Process.killProcess(android.os.Process.myPid());
//        System.exit(1);
//    }
    override fun onBackPressed() {
        val currentTime = System.currentTimeMillis()
        if (Math.abs(currentTime - mLastBackPress) > mBackPressThreshold) {
            pressBackToast!!.show()
            mLastBackPress = currentTime
        } else {
            pressBackToast!!.cancel()
//            super.onBackPressed()
            moveTaskToBack(true);
            android.os.Process.killProcess(android.os.Process.myPid());
            System.exit(1);
        }
    }
}
