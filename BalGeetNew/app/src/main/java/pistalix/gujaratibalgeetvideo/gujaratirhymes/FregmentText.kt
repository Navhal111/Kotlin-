package pistalix.gujaratibalgeetvideo.gujaratirhymes

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.app.Fragment
import android.support.v7.widget.LinearLayoutManager
import com.google.android.gms.ads.AdView
import kotlinx.android.synthetic.main.activity_main.view.*
import org.json.JSONArray
import org.json.JSONObject

class FragmentText : Fragment() {
    private var mAdView: AdView? = null
    override fun onCreateView(inflater: LayoutInflater, container: ViewGroup?,
                              savedInstanceState: Bundle?): View? {
        val rootView = inflater.inflate(R.layout.activity_main, container, false)

        val cls = DatabaseHelper(rootView.context)

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
        }
        rootView.recyclerView.layoutManager = LinearLayoutManager(rootView.context)
        rootView.recyclerView.adapter =RecycleTitle(MainJson)
        return rootView
    }

}