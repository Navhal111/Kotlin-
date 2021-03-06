package pistalix.hindi.shayari


import android.Manifest
import android.app.Activity
import android.content.Context
import android.support.v7.widget.RecyclerView
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import com.squareup.picasso.Picasso
import org.jetbrains.anko.find
import org.json.JSONArray
import org.json.JSONObject
import android.widget.Toast
import android.content.Intent
import android.content.pm.PackageManager
import android.net.Uri
import android.graphics.Bitmap
import android.graphics.drawable.BitmapDrawable
import android.os.Environment
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import com.google.android.gms.ads.AdListener
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.InterstitialAd
import java.io.File
import java.io.FileOutputStream
import java.io.IOException


class Recycle_Post (var name: JSONArray): RecyclerView.Adapter<Recycle_Post.ViewHolder>() {
    lateinit var context1: Context
    var mInterstitialAd: InterstitialAd? = null
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {

        var json_post:JSONObject

        json_post = name.getJSONObject(position)

//        if(json_post.get("__typename")=="GraphImage"){

        var url = json_post.getString("display_src")
        Picasso.with(context1).load(url).fit().into(holder.post_image_set);

        holder.button_share.setOnClickListener {
            val intent = Intent(android.content.Intent.ACTION_SEND)
            val uri = getLocalBitmapUri(holder.post_image_set)
            intent.type = "image/*"
            intent.putExtra(Intent.EXTRA_TEXT, "Stay inspired, motivated and full of positive energy. To get more visit at  https://goo.gl/x65DPQ");
            intent.putExtra(Intent.EXTRA_STREAM, uri)

            mInterstitialAd = InterstitialAd(context1)

            // set the ad unit ID
            mInterstitialAd!!.setAdUnitId("ca-app-pub-9611503142284796/6383118449")

            val adRequest1 = AdRequest.Builder()
                    .build()

            // Load ads into Interstitial Ads
            mInterstitialAd!!.loadAd(adRequest1)

            mInterstitialAd!!.adListener = object : AdListener() {
                override fun onAdLoaded() {
                    showInterstitial()
                }
            }
            context1.startActivity(Intent.createChooser(intent, "Share via"))

        }

//        holder.button_down.
        holder.button_down.setOnClickListener{

            mInterstitialAd = InterstitialAd(context1)

            // set the ad unit ID
            mInterstitialAd!!.setAdUnitId("ca-app-pub-9611503142284796/3688716506")

            val adRequest1 = AdRequest.Builder()
                    .build()

            mInterstitialAd!!.loadAd(adRequest1)

            mInterstitialAd!!.adListener = object : AdListener() {
                override fun onAdLoaded() {
                    showInterstitial()
                }
            }
            getLocalBitmapUri1(holder.post_image_set)
            Toast.makeText(context1,"image Downloaded in SD-Card/HindiShayari",Toast.LENGTH_SHORT).show();
        }




    }

    override fun getItemCount(): Int {
        return name.length()
    }



    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): Recycle_Post.ViewHolder {
        var  vh:RecyclerView.ViewHolder
        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.list_image, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

        val post_image_set: ImageView = itemView.find<ImageView>(R.id.post_image_view)
        val button_share:ImageView = itemView.find<ImageView>(R.id.ImageShare)
        val button_down:ImageView = itemView.find<ImageView>(R.id.ImageDownload)

        init {

        }
    }

    fun getLocalBitmapUri(imageView: ImageView): Uri? {
        // Extract Bitmap from ImageView drawable
        val drawable = imageView.drawable
        var bmp: Bitmap? = null
        if (drawable is BitmapDrawable) {
            bmp = (imageView.drawable as BitmapDrawable).bitmap

        } else {
            return null
        }
        // Store image to default external storage directory
        var bmpUri: Uri? = null
        try {

//            val myInput: InputStream
//            val sdpath = Environment.getExternalStorageDirectory()
//            // Set the output folder on the Scard
//            val directory = File(sdpath.path + "/Zaidan Clinic")
//            // Create the folder if it doesn't exist:
//            if (!directory.exists()) {
//                directory.mkdirs()
//            }
//
//            val file1 = File(directory, "share_image_" + System.currentTimeMillis() + ".png")

            val file = File(context1.getExternalFilesDir(Environment.DIRECTORY_PICTURES), "share_image_" + System.currentTimeMillis() + ".png")
            val out = FileOutputStream(file)
            bmp!!.compress(Bitmap.CompressFormat.JPEG, 100, out)
            out.close()
            // **Warning:** This will fail for API >= 24, use a FileProvider as shown below instead.
            bmpUri = Uri.fromFile(file)
        } catch (e: IOException) {
            e.printStackTrace()
        }

        return bmpUri
    }

    private fun showInterstitial() {
        if (mInterstitialAd!!.isLoaded()) {
            mInterstitialAd!!.show()
        }
    }

    fun getLocalBitmapUri1(imageView: ImageView): Uri? {
        // Extract Bitmap from ImageView drawable
        val drawable = imageView.drawable
        var bmp: Bitmap? = null
        if (drawable is BitmapDrawable) {
            bmp = (imageView.drawable as BitmapDrawable).bitmap

        } else {
            return null
        }
        // Store image to default external storage directory
        val REQUEST_WRITE_EXTERNAL_STORAGE = 1
        val bmpUri: Uri? = null
        try {

            var check = (ContextCompat.checkSelfPermission(context1, Manifest.permission.WRITE_EXTERNAL_STORAGE)== PackageManager.PERMISSION_GRANTED)
            if (!check) {

                ActivityCompat.requestPermissions(context1 as Activity,
                        arrayOf(Manifest.permission.WRITE_EXTERNAL_STORAGE),
                        REQUEST_WRITE_EXTERNAL_STORAGE)
            }

            val externalDirectory = Environment.getExternalStorageDirectory().toString()
            val folder = File(externalDirectory + "/HindiShayari")
            if (!folder.exists()) {
                folder.mkdir()
            }

            val file = File(folder, "share_image_" + System.currentTimeMillis() + ".png")
            val out = FileOutputStream(file)
            SingleMediaScanner(context1, file);
            bmp!!.compress(Bitmap.CompressFormat.JPEG, 100, out)
            out.close()
        } catch (e: IOException) {
            e.printStackTrace()
        }

        return bmpUri
    }
}