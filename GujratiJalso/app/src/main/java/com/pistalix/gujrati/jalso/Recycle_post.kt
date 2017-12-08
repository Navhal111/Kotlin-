package com.pistalix.gujrati.jalso

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
import android.net.ConnectivityManager
import android.os.Environment
import android.support.v4.app.ActivityCompat
import android.support.v4.content.ContextCompat
import com.google.android.gms.ads.AdListener
import com.google.android.gms.ads.AdRequest
import com.google.android.gms.ads.AdView
import com.google.android.gms.ads.InterstitialAd
import com.squareup.picasso.Callback
import java.io.File
import java.io.FileOutputStream
import java.io.IOException

class Recycle_Post (var name: JSONArray,var PageName:String,var ads :InterstitialAd): RecyclerView.Adapter<Recycle_Post.ViewHolder>() {
    lateinit var context1: Context
    var mInterstitialAd: InterstitialAd? = null
    val adRequest = AdRequest.Builder().build()
    override fun onBindViewHolder(holder:ViewHolder, position: Int) {

        var json_post:JSONObject

        json_post = name.getJSONObject(position)
        ads.adListener = object : AdListener() {
            override fun onAdClosed() {
                val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
                ads.loadAd(adRequest)
            }
        }
//        if(json_post.get("__typename")=="GraphImage"){

        if(position%3==0 && position !=0){
            holder.mAdView.visibility = View.VISIBLE
            holder.mAdView.loadAd(adRequest)
        }else{
            holder.mAdView.visibility = View.GONE
        }
        try{
            var url = json_post.getString("display_src")
            Picasso.with(context1).load(url).into(holder.post_image_set);

            Picasso.with(context1).load(url).into(holder.post_image_set, object : Callback {
                override fun onSuccess() {
                }

                override fun onError() {
                    Toast.makeText(context1,"Network issue ",Toast.LENGTH_SHORT).show();
                }
            })
        }catch (e:NullPointerException){
            Toast.makeText(context1,"Problem on server",Toast.LENGTH_SHORT).show();
        }catch (e:IllegalArgumentException){
            Toast.makeText(context1,"Problem on server",Toast.LENGTH_SHORT).show();
        }catch (e:Exception){
            Toast.makeText(context1,"Problem on server",Toast.LENGTH_SHORT).show();
        }


        holder.button_share.setOnClickListener {

            val intent = Intent(android.content.Intent.ACTION_SEND)
            val uri = getLocalBitmapUri(holder.post_image_set)
            intent.type = "image/*"
            intent.putExtra(Intent.EXTRA_TEXT, "કેમ માજા આવીને ? વધારે આવી માજા માણવા, હમણાં જ Download કરો ગુજરાતી જલસો! https://goo.gl/vK5fRC");
            intent.putExtra(Intent.EXTRA_STREAM, uri)
            context1.startActivity(Intent.createChooser(intent, "Share via"))
            ads.show()
            val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
            ads.loadAd(adRequest)

        }

//        holder.button_down.
        holder.button_down.setOnClickListener{

            getLocalBitmapUri1(holder.post_image_set)
            Toast.makeText(context1,"image Downloaded in SD-Card/GujjuJalsa",Toast.LENGTH_SHORT).show();
            ads.show()
            val adRequest = AdRequest.Builder().addTestDevice(context1.getString(R.string.interstial_ads)).build()
            ads.loadAd(adRequest)
        }


    }

    override fun getItemCount(): Int {
        return name.length()
    }



    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): Recycle_Post.ViewHolder {
        val itemView: View = LayoutInflater.from(parent.context).inflate(R.layout.list_image, parent, false)
        context1=parent.context
        return ViewHolder(itemView)
    }


    class ViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {

        val post_image_set: ImageView = itemView.find<ImageView>(R.id.post_image_view)
        val button_share:ImageView = itemView.find<ImageView>(R.id.ImageShare)
        val button_down:ImageView = itemView.find<ImageView>(R.id.ImageDownload)
        var mAdView: AdView = itemView.find(R.id.adView)

        init {

        }
    }

    fun getLocalBitmapUri(imageView: ImageView): Uri? {
        // Extract Bitmap from ImageView drawable
        val drawable = imageView.drawable
        var bmp: Bitmap
        if (drawable is BitmapDrawable) {
            bmp = (imageView.drawable as BitmapDrawable).bitmap

        } else {
            return null
        }
        // Store image to default external storage directory
        var bmpUri: Uri? = null
        try {


            val file = File(context1.getExternalFilesDir(Environment.DIRECTORY_PICTURES), "share_image_" + System.currentTimeMillis() + ".png")
            val out = FileOutputStream(file)
            bmp.compress(Bitmap.CompressFormat.JPEG, 100, out)
            out.close()
            // **Warning:** This will fail for API >= 24, use a FileProvider as shown below instead.
            bmpUri = Uri.fromFile(file)
        } catch (e: IOException) {
            e.printStackTrace()
        }

        return bmpUri
    }


    fun getLocalBitmapUri1(imageView: ImageView): Uri? {
        // Extract Bitmap from ImageView drawable
        val drawable = imageView.drawable
        var bmp: Bitmap
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
            val folder = File(externalDirectory + "/GujjuJalsa")
            if (!folder.exists()) {
                folder.mkdir()
            }

            val file = File(folder, "share_image_" + System.currentTimeMillis() + ".png")
            val out = FileOutputStream(file)
            SingleMediaScanner(context1, file);
            bmp.compress(Bitmap.CompressFormat.JPEG, 100, out)
            out.close()
        } catch (e: IOException) {
            e.printStackTrace()
        }

        return bmpUri
    }

    private fun checkConnection(NamePage:String) {

        val connectivityManager = context1.getSystemService(Context.CONNECTIVITY_SERVICE) as ConnectivityManager
        val nwInfo = connectivityManager.activeNetworkInfo
        if (nwInfo != null && nwInfo.isConnectedOrConnecting) {

            val intent = Intent(context1, PostDisplay::class.java)
            intent.putExtra("keyName", NamePage)
            context1.startActivity(intent)


        }else{
            Toast.makeText(context1,"Check your Network Connection",Toast.LENGTH_SHORT).show();
        }
    }

}