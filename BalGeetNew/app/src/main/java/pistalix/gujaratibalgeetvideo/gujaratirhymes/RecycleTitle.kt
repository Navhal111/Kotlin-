package pistalix.gujaratibalgeetvideo.gujaratirhymes

    import android.content.Context
    import android.support.v7.widget.RecyclerView
    import android.view.LayoutInflater
    import android.view.View
    import android.view.ViewGroup
    import org.jetbrains.anko.find
    import org.json.JSONArray
    import android.content.Intent
    import android.view.View.GONE
    import android.view.View.VISIBLE
    import android.widget.LinearLayout
    import android.widget.TextView
    import com.google.android.gms.ads.AdListener
    import com.google.android.gms.ads.AdRequest
    import com.google.android.gms.ads.AdView
    import com.google.android.gms.ads.InterstitialAd


    class RecycleTitle (private var name: JSONArray,var ads :InterstitialAd): RecyclerView.Adapter<RecycleTitle.ViewHolder>() {
        lateinit var context1: Context
        var mInterstitialAd: InterstitialAd? = null
        var add=0
        var set_possition =0
        val adRequest = AdRequest.Builder().build()
        override fun onBindViewHolder(holder:ViewHolder, position: Int) {
            set_possition = position
            ads.adListener = object : AdListener() {
                override fun onAdClosed() {
                    val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
                    ads.loadAd(adRequest)
                }
            }
            if(set_possition%5==0 && set_possition !=0){
                holder.mAdView.visibility = VISIBLE
                holder.mAdView.loadAd(adRequest)
                holder.title.text= (position+1).toString()+". "+name.getJSONObject(position).getString("Title")
                holder.linear!!.setOnClickListener{
                    val intent = Intent(context1, GeetText::class.java)
                    intent.putExtra("Des", name.getJSONObject(position).getString("Des"))
                    context1.startActivity(intent)
                    ads.show()
                    val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
                    ads.loadAd(adRequest)
                }
            }else{
                holder.mAdView.visibility = GONE
                holder.title.text= (position+1).toString()+". "+name.getJSONObject(position).getString("Title")
                holder.linear!!.setOnClickListener{

                    val intent = Intent(context1, GeetText::class.java)
                    intent.putExtra("Des", name.getJSONObject(position).getString("Des"))
                    context1.startActivity(intent)
                    ads.show()
                    val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
                    ads.loadAd(adRequest)
                }
            }


        }

        override fun getItemCount(): Int {
            return name.length()
        }



        override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): RecycleTitle.ViewHolder {
            val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.list_title, parent, false)
            context1=parent.context
                return ViewHolder(itemView)

        }


        class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
            val title: TextView = itemView.find<TextView>(R.id.titletext)
            val linear: LinearLayout? = itemView.find<LinearLayout>(R.id.titleclick)
            var mAdView:AdView = itemView.find(R.id.adView)

            init {

            }
        }
    }
