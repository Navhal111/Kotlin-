package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity;

import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;

import java.io.File;

import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile.Glob;


public class ShareActivity extends AppCompatActivity implements View.OnClickListener {
    private ImageView ivFinalImage, ivWhatsApp, ivFacebook, ivInstagram, ivHike, ivShareMore,tvback;
    private TextView tvFinalImagePath;
    private String strSavedImage;
    private LinearLayout llForMyCreation;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_share);

        strSavedImage = Image_Edit_Screen._url;

        bindView();
    }

    private void bindView() {
        llForMyCreation = (LinearLayout) findViewById(R.id.llForMyCreation);
        Animation animBlink = AnimationUtils.loadAnimation(ShareActivity.this, R.anim.zoominout);
        llForMyCreation.startAnimation(animBlink);
        llForMyCreation.setOnClickListener(this);

        tvFinalImagePath = (TextView) findViewById(R.id.tvFinalImagePath);
        tvFinalImagePath.setText(strSavedImage);

        ivFinalImage = (ImageView) findViewById(R.id.ivFinalImage);

//        ivFinalImage.setImageURI(Uri.parse(strSavedImage));
        Glide.with(this)
                .load(strSavedImage)
                .thumbnail(0.5f)
                .dontAnimate()
                .into(ivFinalImage);
        ivWhatsApp = (ImageView) findViewById(R.id.ivWhatsApp);
        ivWhatsApp.setOnClickListener(this);

        ivFacebook = (ImageView) findViewById(R.id.ivFacebook);
        ivFacebook.setOnClickListener(this);

        ivInstagram = (ImageView) findViewById(R.id.ivInstagram);
        ivInstagram.setOnClickListener(this);

        ivHike = (ImageView) findViewById(R.id.ivHike);
        ivHike.setOnClickListener(this);

        ivShareMore = (ImageView) findViewById(R.id.ivShareMore);
        ivShareMore.setOnClickListener(this);

        tvback = (ImageView)findViewById(R.id.ivBack);
        tvback.setOnClickListener(this);

    }



    public boolean isOnline() {
        ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo netInfo = cm.getActiveNetworkInfo();
        if (netInfo != null && netInfo.isConnectedOrConnecting()) {
            return true;
        }
        return false;
    }

    @Override
    public void onClick(View view) {
        Intent shareIntent = new Intent(Intent.ACTION_SEND);
        shareIntent.setType("image/*");
        shareIntent.putExtra(Intent.EXTRA_TEXT, Glob.app_name + " Created By : " + Glob.app_link);
        shareIntent.putExtra(Intent.EXTRA_STREAM, Uri.parse(strSavedImage));
        switch (view.getId()) {

            case R.id.ivWhatsApp:
                try {
                    shareIntent.setPackage("com.whatsapp");
                    startActivity(shareIntent);
                } catch (Exception e) {
                    Toast.makeText(this, "WhatsApp doesn't installed", Toast.LENGTH_LONG).show();
                }
                break;
            case R.id.ivFacebook:
                try {
                    shareIntent.setPackage("com.facebook.katana");
                    startActivity(shareIntent);
                } catch (Exception e) {
                    Toast.makeText(this, "Facebook doesn't installed", Toast.LENGTH_LONG).show();
                }
                break;
            case R.id.ivInstagram:
                try {
                    shareIntent.setPackage("com.instagram.android");
                    startActivity(shareIntent);
                } catch (Exception e) {
                    Toast.makeText(this, "Instagram doesn't installed", Toast.LENGTH_LONG).show();
                }
                break;
            case R.id.ivHike:
                try {
                    shareIntent.setPackage("com.bsb.hike");
                    startActivity(shareIntent);
                } catch (Exception e) {
                    Toast.makeText(this, "Hike doesn't installed", Toast.LENGTH_LONG).show();
                }
                break;
            case R.id.ivShareMore:
                Intent sharingIntent = new Intent(Intent.ACTION_SEND);
                sharingIntent.setType("image/*");
                sharingIntent.putExtra(Intent.EXTRA_TEXT, Glob.app_name + " Create By : " + Glob.app_link);
                sharingIntent.putExtra(Intent.EXTRA_STREAM, Uri.fromFile(new File(strSavedImage)));
                startActivity(Intent.createChooser(sharingIntent, "Share Image using"));
                break;
            case R.id.ivBack:
                finish();
                break;
        }
    }

}
