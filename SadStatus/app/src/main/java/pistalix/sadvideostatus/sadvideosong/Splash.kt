package pistalix.sadvideostatus.sadvideosong

import android.content.Context
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.content.Intent
import android.net.ConnectivityManager
import android.os.Handler
import android.view.animation.AnimationUtils
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import kotlinx.android.synthetic.main.activity_splash.*
import org.jetbrains.anko.toast
import org.json.JSONException
import org.json.JSONObject


class Splash : AppCompatActivity() {
    private val SPLASH_TIME_OUT = 4000
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_splash)
        var  zoomout = AnimationUtils.loadAnimation(this@Splash, R.anim.zoomout)
        splashlogo.animation = zoomout
        splashlogo.startAnimation(zoomout)
        Handler().postDelayed(Runnable

        {
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
                                    val Newvirsion : JSONObject = response.getJSONObject("SadStatus")
                                    var virsion  =Newvirsion.getString("virsion")
                                    if(Integer.parseInt(virsion) > CurruntVirsion){
                                        val intent = Intent(this@Splash, MainActivity::class.java)
                                        intent.putExtra("update", "1")
                                        intent.putExtra("msg",Newvirsion.getString("msg"))
                                        startActivity(intent)
                                        finish()
                                    }else{

                                        val intent = Intent(this@Splash, MainActivity::class.java)
                                        intent.putExtra("update", "0")
                                        startActivity(intent)
                                        finish()
                                    }
                                }catch (e: JSONException){

                                }catch (e:NullPointerException){

                                }catch (e:IllegalArgumentException){

                                }catch (e:Exception){
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
            }else{
                SuperActivityToast.create(this).setText("Check Your Network Connection").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
                val i = Intent(this@Splash, MainActivity::class.java)
                intent.putExtra("update", "0")
                startActivity(i)
                finish()
            }


            // close this activity
        }, SPLASH_TIME_OUT.toLong())
    }
}
