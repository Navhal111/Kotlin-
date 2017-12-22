package pistalix.ChristmasPhotoStickerFrames.PhotoEditor

import android.content.Context
import android.content.Intent
import android.net.ConnectivityManager
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.os.Handler
import android.view.WindowManager
import android.view.animation.AnimationUtils
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import kotlinx.android.synthetic.main.activity_splash.*
import org.jetbrains.anko.toast
import org.json.JSONException
import org.json.JSONObject

class Splash : AppCompatActivity() {
    private val SPLASH_TIME_OUT = 2000
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_splash)
        window.setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN)
        var  zoomout = AnimationUtils.loadAnimation(this@Splash, R.anim.zoomout)
        zoomout.restrictDuration(2000)
        splashlogo.startAnimation(zoomout)

        Handler().postDelayed(Runnable

        {
             if(!isOnline()){
                 val intent = Intent(this@Splash, pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity.MainActivity::class.java)
                 intent.putExtra("update", "0")
                 intent.putExtra("msg","sd")
                 startActivity(intent)
                 finish()
             }
            val connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
            val nwInfo = connectivityManager.activeNetworkInfo
            if (nwInfo != null && nwInfo.isConnectedOrConnecting) {
                try{
                    val url =getString(R.string.virsion_url)
                    val CurruntVirsion :Int = BuildConfig.VERSION_CODE
                    val Virsionquery = Volley.newRequestQueue(this@Splash)
                    val jsonobj2 = JsonObjectRequest(Request.Method.GET, url,null,
                            Response.Listener<JSONObject> {
                                response ->
                                try{
                                    val Newvirsion : JSONObject = response.getJSONObject("LinkAadhar")
                                    var virsion  =Newvirsion.getString("virsion")
                                    if(Integer.parseInt(virsion) > CurruntVirsion){
                                        val intent = Intent(this@Splash, pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity.MainActivity::class.java)
                                        intent.putExtra("update", "1")
                                        intent.putExtra("msg",Newvirsion.getString("msg"))
                                        startActivity(intent)
                                        finish()
                                    }else{

                                        val intent = Intent(this@Splash, pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity.MainActivity::class.java)
                                        intent.putExtra("update", "0")
                                        startActivity(intent)
                                        finish()
                                    }
                                }catch (e: JSONException){
                                    val intent = Intent(this@Splash, pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity.MainActivity::class.java)
                                    intent.putExtra("update", "0")
                                    startActivity(intent)
                                    finish()
                                }catch (e:NullPointerException){

                                }catch (e:IllegalArgumentException){

                                }catch (e:Exception){
                                }
                            }, Response.ErrorListener {
                        val intent = Intent(this@Splash, pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity.MainActivity::class.java)
                        intent.putExtra("update", "0")
                        startActivity(intent)
                        finish()
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
            }else{
                toast("Check Your Network ")
                val intent = Intent(this@Splash, pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity.MainActivity::class.java)
                intent.putExtra("update", "0")
                startActivity(intent)
                finish()
            }


            // close this activity
        }, SPLASH_TIME_OUT.toLong())
    }

    fun isOnline(): Boolean {
        val cm = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val netInfo = cm.activeNetworkInfo
         if (netInfo != null && netInfo.isConnectedOrConnecting) {
             return  true
        }

        return false
    }
}
