package com.example.lime.youtube;

import android.content.Context;
import android.util.Log;
import android.widget.ImageView;
import android.widget.ProgressBar;

import com.squareup.picasso.Picasso;


public class AppUtils {



    private static final String TAG = "AppUtils";

    public String setImage(final String imageUrl, final ImageView imageView, int placeholder, final Context context){

        Log.d(TAG, "setImage() called with: " + "imageUrl = [" + imageUrl + "], imageView = [" + imageView + "]");


        if(imageUrl != null && !imageUrl.equals("")){
            imageView.post(new Runnable() {
                @Override
                public void run() {
                    Picasso.with(context).load(imageUrl).fit().into( imageView);

                }
            });
        }
        else
        {

        }
        return imageUrl;
    }
    public String setImageHomeSlider11(final String imageUrl, final ImageView imageView, int placeholder, final Context context, final ProgressBar progressBar){

        Log.d(TAG, "setImage() called with: " + "imageUrl = [" + imageUrl + "], imageView = [" + imageView + "]");


        if(imageUrl != null && !imageUrl.equals("")){
            imageView.post(new Runnable() {
                @Override
                public void run() {
                    Picasso.with(context).load(imageUrl).fit().into( imageView);

                }
            });
        }else{

        }
        return imageUrl;
    }


}
