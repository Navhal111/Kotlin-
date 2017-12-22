package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BlurMaskFilter;
import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.Matrix;
import android.graphics.Paint;
import android.graphics.Path;
import android.graphics.Point;
import android.graphics.PorterDuff;
import android.graphics.PorterDuffXfermode;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.os.Handler;
import android.support.design.widget.Snackbar;
import android.support.v7.app.AppCompatActivity;
import android.util.DisplayMetrics;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.RelativeLayout.LayoutParams;
import android.widget.TextView;

import com.google.android.gms.ads.AdRequest;

import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile.FreeCropView;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile.Glob;


public class FreeCropActivity extends AppCompatActivity implements View.OnClickListener {

    private RelativeLayout crop_it;
    private LinearLayout reset, done, rotate;
    private ImageView CloseView, show, our_image;
    private RelativeLayout rootRelative, closeView;
    private Bitmap freecrop;
    private int width;
    private int height;
    private int dis_width;
    private int dis_height;
    private FreeCropView freecropview;
    private TextView tv_reset, tv_rotate, tv_done;
    private ImageView iv_rotate, iv_done, iv_reset;
    int F = 0;
    private ProgressDialog progrssdailog;
    private com.google.android.gms.ads.InterstitialAd mInterstitialAdMob;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_free_crop);
        freecrop = Glob.bitmap;
        mInterstitialAdMob =showAdmobFullAd();
        loadAdmobAd();
        Bind();
        width = freecrop.getWidth();
        height = freecrop.getHeight();
        DisplayMetrics displayMetrics = getResources().getDisplayMetrics();
        dis_width = displayMetrics.widthPixels;
        dis_height = displayMetrics.heightPixels;
        float density = getResources().getDisplayMetrics().density;
        int i = dis_width - ((int) density);
        int i2 = dis_height - ((int) (density * 60.0f));
        if (width >= i || height >= i2) {
            while (true) {
                if (width <= i && height <= i2) {
                    break;
                }
                width = (int) (((double) width) * 0.9d);
                height = (int) (((double) height) * 0.9d);
                System.out.println("mImageWidth" + width + "mImageHeight" + height);
            }
            freecrop = Bitmap.createScaledBitmap(freecrop, width, height, true);
            System.out.println("mImageWidth" + width + "mImageHeight" + height);
        } else {
            while (width < i - 20 && height < i2) {
                width = (int) (((double) width) * 1.1d);
                height = (int) (((double) height) * 1.1d);
                System.out.println("mImageWidth" + width + "mImageHeight" + height);
            }
            freecrop = Bitmap.createScaledBitmap(freecrop, width, height, true);
            System.out.println("mImageWidth" + width + "mImageHeight" + height);
        }
        setlayout();
//        LayoutParams layoutParams = (LayoutParams) crop_it.getLayoutParams();
//        layoutParams.height = freecrop.getHeight();
//        layoutParams.width = freecrop.getWidth();
//        crop_it.setLayoutParams(layoutParams);
//        freecropview = new FreeCropView(this, freecrop);
//        crop_it.addView(freecropview);


    }



    public boolean isOnline() {
        ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo netInfo = cm.getActiveNetworkInfo();
        if (netInfo != null && netInfo.isConnectedOrConnecting()) {
            return true;
        }
        return false;
    }

    private void Bind() {
        crop_it = (RelativeLayout) findViewById(R.id.crop_it);
        reset = (LinearLayout) findViewById(R.id.reset);
        reset.setOnClickListener(this);
        done = (LinearLayout) findViewById(R.id.done);
        done.setOnClickListener(this);
        closeView = (RelativeLayout) findViewById(R.id.closeView);
        closeView.setOnClickListener(this);
        CloseView = (ImageView) findViewById(R.id.CloseView);
        show = (ImageView) findViewById(R.id.show);
        our_image = (ImageView) findViewById(R.id.our_image);
        rotate = (LinearLayout) findViewById(R.id.rotate);
        rotate.setOnClickListener(this);
        rootRelative = (RelativeLayout) findViewById(R.id.rootRelative);
        rootRelative.setVisibility(View.VISIBLE);

        tv_reset = (TextView) findViewById(R.id.tv_reset);
        iv_reset = (ImageView) findViewById(R.id.iv_reset);

        tv_done = (TextView) findViewById(R.id.tv_done);
        iv_done = (ImageView) findViewById(R.id.iv_done);

        tv_rotate = (TextView) findViewById(R.id.tv_rotate);
        iv_rotate = (ImageView) findViewById(R.id.iv_rotate);

        callcolor();
    }

    private void callcolor() {
        iv_reset.setColorFilter(getResources().getColor(R.color.white));
        tv_reset.setTextColor(getResources().getColor(R.color.white));
        iv_rotate.setColorFilter(getResources().getColor(R.color.white));
        tv_rotate.setTextColor(getResources().getColor(R.color.white));
        iv_done.setColorFilter(getResources().getColor(R.color.white));
        tv_done.setTextColor(getResources().getColor(R.color.white));
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.closeView:
                closeView.setVisibility(View.GONE);
                return;
            case R.id.rotate:
                callcolor();
                iv_rotate.setColorFilter(getResources().getColor(R.color.custom_main));
                tv_rotate.setTextColor(getResources().getColor(R.color.custom_main));
                F = 90;
                freecrop = rotateset(freecrop, (float) F);
                our_image.setImageBitmap(null);
                setlayout();
                return;
            case R.id.reset:
                callcolor();
                iv_reset.setColorFilter(getResources().getColor(R.color.custom_main));
                tv_reset.setTextColor(getResources().getColor(R.color.custom_main));
                our_image.setImageBitmap(null);
                setlayout();
                return;
            case R.id.done:
                callcolor();
                iv_done.setColorFilter(getResources().getColor(R.color.custom_main));
                tv_done.setTextColor(getResources().getColor(R.color.custom_main));
                rootRelative.setVisibility(View.VISIBLE);
                if (freecropview.b.size() == 0) {
                    Snackbar snackbar = Snackbar
                            .make(rootRelative, "Please Crop it", Snackbar.LENGTH_SHORT);
                    View sbView = snackbar.getView();
                    TextView textView = (TextView) sbView.findViewById(android.support.design.R.id.snackbar_text);
                    textView.setTextColor(Color.WHITE);
                    snackbar.show();
                    return;
                }
                boolean a = freecropview.a();
                System.out.println("boolean_value" + a);
                b(a);
                saveImage();

                return;
        }
    }

    private void saveImage() {
        progrssdailog = ProgressDialog.show(this, "Please Wait", "Image is saving");
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                Glob.bitmap = getbitmap(rootRelative);
                startActivity(new Intent(FreeCropActivity.this, ImageEditActivity.class));
                showAdmobInterstitial();
                our_image.setImageBitmap(null);
                setlayout();
                progrssdailog.dismiss();

            }
        }, 100);

    }

    private Bitmap getbitmap(View view) {
        Bitmap createBitmap = Bitmap.createBitmap(view.getWidth(), view.getHeight(), Bitmap.Config.ARGB_8888);
        view.draw(new Canvas(createBitmap));
        return createBitmap;

    }

    public void b(boolean z) {
        System.out.println("ImageCrop=-=-=-=-=-");
        Bitmap createBitmap = Bitmap.createBitmap(dis_width, dis_height, freecrop.getConfig());
        Canvas canvas = new Canvas(createBitmap);
        Paint paint = new Paint();
        paint.setMaskFilter(new BlurMaskFilter(40.0f, BlurMaskFilter.Blur.NORMAL));
        paint.setAntiAlias(true);
        Path path = new Path();
        for (int i = 0; i < freecropview.b.size(); i++) {
            path.lineTo((float) ((Point) freecropview.b.get(i)).x, (float) ((Point) freecropview.b.get(i)).y);
        }
        System.out.println("points" + freecropview.b.size());
        canvas.drawPath(path, paint);
        if (z) {
            paint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.SRC_IN));
        } else {
            paint.setXfermode(new PorterDuffXfermode(PorterDuff.Mode.SRC_OUT));
        }
        canvas.drawBitmap(freecrop, 0.0f, 0.0f, paint);
        our_image.setImageBitmap(createBitmap);
    }


    public static Bitmap rotateset(Bitmap bitmap, float f) {
        Matrix matrix = new Matrix();
        matrix.postRotate(f);
        return Bitmap.createBitmap(bitmap, 0, 0, bitmap.getWidth(), bitmap.getHeight(), matrix, true);
    }

    private void setlayout() {
        LayoutParams layoutParams = (LayoutParams) crop_it.getLayoutParams();
        layoutParams.height = freecrop.getHeight();
        layoutParams.width = freecrop.getWidth();
        crop_it.setLayoutParams(layoutParams);
        freecropview = new FreeCropView(this, freecrop);
        crop_it.addView(freecropview);

    }

    protected void onDestroy() {
        super.onDestroy();
    }
    //AdMob InterstitialAds start

    public com.google.android.gms.ads.InterstitialAd showAdmobFullAd() {
        com.google.android.gms.ads.InterstitialAd interstitialAd = new com.google.android.gms.ads.InterstitialAd(this);
        interstitialAd.setAdUnitId(getString(R.string.interstitial_full_screen));
        interstitialAd.setAdListener(new com.google.android.gms.ads.AdListener() {
            @Override
            public void onAdClosed() {
                loadAdmobAd();
            }

            @Override
            public void onAdLoaded() {

            }

            @Override
            public void onAdOpened() {
                //   super.onAdOpened();
            }
        });
        return interstitialAd;
    }

    private void loadAdmobAd() {
        mInterstitialAdMob.loadAd(new AdRequest.Builder().build());
    }

    private void showAdmobInterstitial() {
        if (mInterstitialAdMob != null && mInterstitialAdMob.isLoaded()) {
            mInterstitialAdMob.show();
        }
    }


    //AdMob InterstitialAds End
}
