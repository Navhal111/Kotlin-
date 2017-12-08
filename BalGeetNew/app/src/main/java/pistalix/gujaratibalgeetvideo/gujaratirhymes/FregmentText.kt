package pistalix.gujaratibalgeetvideo.gujaratirhymes

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.app.Fragment
import android.support.v7.widget.LinearLayoutManager
import com.github.johnpersano.supertoasts.library.Style
import com.github.johnpersano.supertoasts.library.SuperActivityToast
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.google.android.gms.ads.InterstitialAd
import kotlinx.android.synthetic.main.activity_main.view.*
import org.json.JSONArray
import org.json.JSONException
import org.json.JSONObject

class FragmentText : Fragment() {
    private var mAdView: AdView? = null
    var mInterstitialAd: InterstitialAd? = null
    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        val rootView = inflater.inflate(R.layout.activity_main, container, false)

        val cls = DatabaseHelper(rootView.context)
        var adRequest: AdRequest
        mInterstitialAd = InterstitialAd(rootView.context)
        adRequest = AdRequest.Builder().build()
        val unitId = getString(R.string.interstial_ads)
        mInterstitialAd!!.setAdUnitId(unitId)
        mInterstitialAd!!.loadAd(adRequest)
try{
    var ListTitle =cls.getlist()
    var ListId = cls.getnum()
    var ListDes = cls.getdescptn()
    var i=0
    var MainJson = JSONArray()
    while(i<ListTitle.size){
        val SetJson = JSONObject()
        SetJson.put("Title",ListTitle[i])
        SetJson.put("Id",ListId[i])
        SetJson.put("Des",ListDes[i])
        i++
        MainJson.put(SetJson)
        rootView.recyclerView.layoutManager = LinearLayoutManager(rootView.context)
        rootView.recyclerView.adapter =RecycleTitle(MainJson, mInterstitialAd!!)
    }
}catch (E:JSONException){
    SuperActivityToast.create(rootView.context).setText("Something Went wrong").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
}catch (e:NullPointerException){
    SuperActivityToast.create(rootView.context).setText("Something Went wrong").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
}catch (e:IllegalArgumentException){
    SuperActivityToast.create(rootView.context).setText("Something Went wrong").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show()
}


        return rootView
    }


}