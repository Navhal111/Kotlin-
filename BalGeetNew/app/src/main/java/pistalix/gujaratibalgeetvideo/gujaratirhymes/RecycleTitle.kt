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


    class RecycleTitle (private var name: JSONArray): RecyclerView.Adapter<RecycleTitle.ViewHolder>() {
        lateinit var context1: Context
        var mInterstitialAd: InterstitialAd? = null
        var add=0
        var set_possition =0
        val adRequest = AdRequest.Builder().build()
        override fun onBindViewHolder(holder:ViewHolder, position: Int) {
            set_possition = position

            if(set_possition%5==0 && set_possition !=0){
                holder.mAdView.visibility = VISIBLE
                holder.mAdView.loadAd(adRequest)
                holder.title.text= (position+1).toString()+". "+name.getJSONObject(position).getString("Title")
                holder.linear!!.setOnClickListener{
                    mInterstitialAd = InterstitialAd(context1)

                    // set the ad unit ID
                    mInterstitialAd!!.setAdUnitId("ca-app-pub-9611503142284796/5363933244")

                    val adRequest1 = AdRequest.Builder()
                            .build()

                    // Load ads into Interstitial Ads
                    mInterstitialAd!!.loadAd(adRequest1)

                    mInterstitialAd!!.adListener = object : AdListener() {
                        override fun onAdLoaded() {
                            showInterstitial()
                        }
                    }
                    val intent = Intent(context1, GeetText::class.java)
                    intent.putExtra("Des", name.getJSONObject(position).getString("Des"))
                    context1.startActivity(intent)
                }
            }else{
                holder.mAdView.visibility = GONE
                holder.title.text= (position+1).toString()+". "+name.getJSONObject(position).getString("Title")
                holder.linear!!.setOnClickListener{
                    mInterstitialAd = InterstitialAd(context1)

                    // set the ad unit ID
                    mInterstitialAd!!.setAdUnitId("ca-app-pub-9611503142284796/5363933244")

                    val adRequest1 = AdRequest.Builder()
                            .build()

                    // Load ads into Interstitial Ads
                    mInterstitialAd!!.loadAd(adRequest1)

                    mInterstitialAd!!.adListener = object : AdListener() {
                        override fun onAdLoaded() {
                            showInterstitial()
                        }
                    }
                    val intent = Intent(context1, GeetText::class.java)
                    intent.putExtra("Des", name.getJSONObject(position).getString("Des"))
                    context1.startActivity(intent)
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
        private fun showInterstitial() {
            if (mInterstitialAd!!.isLoaded()) {
                mInterstitialAd!!.show()
            }
        }
    }
