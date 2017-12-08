package pistalix.gujaratibalgeetvideo.gujaratirhymes
import android.Manifest
import android.app.Activity
import android.app.Fragment
import android.content.Context
import android.content.Intent
import android.content.pm.PackageManager
import android.net.ConnectivityManager
import android.net.Uri
import android.os.Build
import android.support.v7.app.AppCompatActivity
import android.os.Bundle
import android.support.annotation.RequiresApi
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import android.view.Gravity
import android.view.View
import android.widget.TextView
import com.android.volley.Request
import com.android.volley.Response
import com.android.volley.toolbox.JsonObjectRequest
import com.android.volley.toolbox.Volley
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.orhanobut.dialogplus.DialogPlus
import com.orhanobut.dialogplus.ViewHolder
import kotlinx.android.synthetic.main.activity_main_fregment.*
import org.jetbrains.anko.toast
import org.json.JSONException
import org.json.JSONObject

class MainFregment : AppCompatActivity() {
    var fragmentTab1: Fragment = VideosList()
    var fragmentTab2: Fragment = FragmentText()
    var fragmentTab3: Fragment = DownloadVideos()
    var fragmentTab4: Fragment = HomeFregment()
    lateinit var dailog: DialogPlus
    private val REQUEST_WRITE_EXTERNAL_STORAGE = 1
    private var mAdView: AdView? = null
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main_fregment)
        val check1 = (ContextCompat.checkSelfPermission(applicationContext, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check1) {
            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }
        val check = (ContextCompat.checkSelfPermission(applicationContext, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
        if (!check) {
            ActivityCompat.requestPermissions(this as Activity,
                    arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                    REQUEST_WRITE_EXTERNAL_STORAGE)
        }
        val connectivityManager = getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val nwInfo = connectivityManager.activeNetworkInfo
        if (nwInfo != null && nwInfo.isConnectedOrConnecting) {
            try{
                val url =getString(R.string.virsion_url)
                val CurruntVirsion :Int = BuildConfig.VERSION_CODE
                val Virsionquery = Volley.newRequestQueue(this@MainFregment)
                val jsonobj2 = JsonObjectRequest(Request.Method.GET, url,null,
                        Response.Listener<JSONObject> {
                            response ->
                            try{
                                val Newvirsion : JSONObject = response.getJSONObject("BallGeet")
                                var virsion  =Newvirsion.getString("virsion")
                                if(Integer.parseInt(virsion) > CurruntVirsion){
                                    update_app(Newvirsion.getString("msg"))
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
        }
        bottomBar.setOnTabSelectListener { tabId ->
            if (tabId == R.id.tab_calls) {

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab4)
                transaction.addToBackStack(null)
                transaction.commit()

            } else if (tabId == R.id.tab_groups) {

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab2)
                transaction.addToBackStack(null)
                transaction.commit()

            } else if (tabId == R.id.tab_chats) {

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab1)
                transaction.addToBackStack(null)
                transaction.commit()

            }else if(tabId == R.id.tab_down){

                var transaction = fragmentManager.beginTransaction()
                transaction.replace(R.id.fragment_container,fragmentTab3)
                transaction.addToBackStack(null)
                transaction.commit()
            }


        }
    }

    override fun onBackPressed(){
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
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.gujaratibalgeetvideo.gujaratirhymes")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.gujaratibalgeetvideo.gujaratirhymes")))
                }
            }
            dailog.show()
        }catch (e :NullPointerException){


        }catch (e:IllegalArgumentException){

        }catch (e:Exception){

        }

    }

    fun update_app(str:String){

        dailog = DialogPlus.newDialog(this).setGravity(Gravity.CENTER).setContentHolder(ViewHolder(R.layout.update_app)).setInAnimation(R.anim.abc_fade_in).create()
        try{

            var yes = dailog.findViewById(R.id.yes_button)
            var no = dailog.findViewById(R.id.no_button)
            var msg : TextView = dailog.findViewById(R.id.update_msg) as TextView
            msg.text =str
            yes.setOnClickListener{
                try {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("market://details?id=pistalix.gujaratibalgeetvideo.gujaratirhymes")))
                } catch (anfe: android.content.ActivityNotFoundException) {
                    startActivity(Intent(Intent.ACTION_VIEW, Uri.parse("https://play.google.com/store/apps/details?id=pistalix.gujaratibalgeetvideo.gujaratirhymes")))
                }
            }
            no.setOnClickListener{

                dailog.dismiss()

            }
            dailog.show()
        }catch (e :NullPointerException){

            toast("error")
        }catch (e:IllegalArgumentException){
            toast("error")
        }

    }
}

