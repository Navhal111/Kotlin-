package pistalix.crismasImage.editor.Activity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.Display;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;

import com.isseiaoki.simplecropview.CropImageView;

import java.io.FileNotFoundException;
import java.io.InputStream;

import pistalix.crismasImage.editor.R;
import pistalix.crismasImage.editor.Subfile.Glob;


public class CropActivity extends AppCompatActivity {
    private CropImageView mCropView;
    private TextView tv_free, tv_one11, tv_fitImage, tv_34, tv_43, tv_916, tv_169, tv_custom, tv_circle, tv_squrecircle;
    private ImageView iv_free, iv_one11, iv_fitImage, iv_34, iv_43, iv_916, iv_169, iv_custom, iv_circle, iv_squrecircle;
    private Uri selectedImage;
    private Bitmap OriginalImages;
    private Uri savedImageUri;
    private ProgressDialog progressDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_crop);
        bindViews();
        mCropView.setCropMode(CropImageView.CropMode.SQUARE);
        selectedImage = Uri.parse(getIntent().getStringExtra("image_Uri"));
        InputStream image_stream = null;
        try {
            image_stream = getContentResolver().openInputStream(this.selectedImage);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
        OriginalImages = BitmapFactory.decodeStream(image_stream);


        mCropView.setImageBitmap(OriginalImages);
    }

    private void bindViews() {
        mCropView = (CropImageView) findViewById(R.id.cropImageView);
        findViewById(R.id.buttonDone).setOnClickListener(btnListener);
        findViewById(R.id.Iv_back_crop).setOnClickListener(btnListener);
        findViewById(R.id.buttonRotateLeft).setOnClickListener(btnListener);
        findViewById(R.id.buttonRotateRight).setOnClickListener(btnListener);
        findViewById(R.id.buttonDone).setOnClickListener(btnListener);
        findViewById(R.id.buttonFitImage).setOnClickListener(btnListener);
        findViewById(R.id.button1_1).setOnClickListener(btnListener);
        findViewById(R.id.button3_4).setOnClickListener(btnListener);
        findViewById(R.id.button4_3).setOnClickListener(btnListener);
        findViewById(R.id.button9_16).setOnClickListener(btnListener);
        findViewById(R.id.button16_9).setOnClickListener(btnListener);
        findViewById(R.id.buttonFree).setOnClickListener(btnListener);
        findViewById(R.id.buttonRotateLeft).setOnClickListener(btnListener);
        findViewById(R.id.buttonRotateRight).setOnClickListener(btnListener);
        findViewById(R.id.buttonCustom).setOnClickListener(btnListener);
        findViewById(R.id.buttonCircle).setOnClickListener(btnListener);
        findViewById(R.id.buttonShowCircleButCropAsSquare).setOnClickListener(btnListener);


        tv_free = (TextView) findViewById(R.id.tv_free);
        iv_free = (ImageView) findViewById(R.id.iv_free);
        tv_one11 = (TextView) findViewById(R.id.tv_one11);
        iv_one11 = (ImageView) findViewById(R.id.iv_one11);
        tv_fitImage = (TextView) findViewById(R.id.tv_fitImage);
        iv_fitImage = (ImageView) findViewById(R.id.iv_fitImage);
        tv_34 = (TextView) findViewById(R.id.tv_34);
        iv_34 = (ImageView) findViewById(R.id.iv_34);
        tv_43 = (TextView) findViewById(R.id.tv_43);
        iv_43 = (ImageView) findViewById(R.id.iv_43);
        tv_916 = (TextView) findViewById(R.id.tv_916);
        iv_916 = (ImageView) findViewById(R.id.iv_916);
        tv_169 = (TextView) findViewById(R.id.tv_169);
        iv_169 = (ImageView) findViewById(R.id.iv_169);
        tv_custom = (TextView) findViewById(R.id.tv_custom);
        iv_custom = (ImageView) findViewById(R.id.iv_custom);
        tv_circle = (TextView) findViewById(R.id.tv_circle);
        iv_circle = (ImageView) findViewById(R.id.iv_circle);
        tv_squrecircle = (TextView) findViewById(R.id.tv_squrecircle);
        iv_squrecircle = (ImageView) findViewById(R.id.iv_squrecircle);

    }

    private void Callallothers() {
        iv_free.setColorFilter(getResources().getColor(R.color.white));
        iv_one11.setColorFilter(getResources().getColor(R.color.white));
        iv_fitImage.setColorFilter(getResources().getColor(R.color.white));
        iv_34.setColorFilter(getResources().getColor(R.color.white));
        iv_43.setColorFilter(getResources().getColor(R.color.white));
        iv_916.setColorFilter(getResources().getColor(R.color.white));
        iv_169.setColorFilter(getResources().getColor(R.color.white));
        iv_custom.setColorFilter(getResources().getColor(R.color.white));
        iv_circle.setColorFilter(getResources().getColor(R.color.white));
        iv_squrecircle.setColorFilter(getResources().getColor(R.color.white));
        tv_free.setTextColor(getResources().getColor(R.color.white));
        tv_one11.setTextColor(getResources().getColor(R.color.white));
        tv_fitImage.setTextColor(getResources().getColor(R.color.white));
        tv_43.setTextColor(getResources().getColor(R.color.white));
        tv_34.setTextColor(getResources().getColor(R.color.white));
        tv_custom.setTextColor(getResources().getColor(R.color.white));
        tv_circle.setTextColor(getResources().getColor(R.color.white));
        tv_squrecircle.setTextColor(getResources().getColor(R.color.white));
        tv_916.setTextColor(getResources().getColor(R.color.white));
        tv_169.setTextColor(getResources().getColor(R.color.white));
    }

    private final View.OnClickListener btnListener = new View.OnClickListener() {
        public ProgressDialog progressDialog;

        @Override
        public void onClick(View v) {
            switch (v.getId()) {
                case R.id.buttonDone:
                    Callallothers();
                    saveImage();
                case R.id.Iv_back_crop:
                    Callallothers();
                    finish();
                    break;
                case R.id.buttonRotateLeft:
                    Callallothers();
                    mCropView.rotateImage(CropImageView.RotateDegrees.ROTATE_M90D);
                    break;
                case R.id.buttonRotateRight:
                    Callallothers();
                    mCropView.rotateImage(CropImageView.RotateDegrees.ROTATE_90D);
                    break;
                case R.id.buttonFitImage:
                    Callallothers();
                    iv_fitImage.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_fitImage.setTextColor(getResources().getColor(R.color.custom_main));

                    mCropView.setCropMode(CropImageView.CropMode.FIT_IMAGE);
                    break;
                case R.id.button1_1:
                    Callallothers();
                    iv_one11.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_one11.setTextColor(getResources().getColor(R.color.custom_main));

                    mCropView.setCropMode(CropImageView.CropMode.SQUARE);
                    break;
                case R.id.button3_4:
                    Callallothers();
                    iv_34.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_34.setTextColor(getResources().getColor(R.color.custom_main));

                    mCropView.setCropMode(CropImageView.CropMode.RATIO_3_4);
                    break;
                case R.id.button4_3:
                    Callallothers();
                    iv_43.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_43.setTextColor(getResources().getColor(R.color.custom_main));
                    mCropView.setCropMode(CropImageView.CropMode.RATIO_4_3);
                    break;
                case R.id.button9_16:
                    Callallothers();
                    iv_916.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_916.setTextColor(getResources().getColor(R.color.custom_main));
                    mCropView.setCropMode(CropImageView.CropMode.RATIO_9_16);
                    break;
                case R.id.button16_9:
                    Callallothers();
                    iv_169.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_169.setTextColor(getResources().getColor(R.color.custom_main));
                    mCropView.setCropMode(CropImageView.CropMode.RATIO_16_9);
                    break;
                case R.id.buttonCustom:
                    Callallothers();
                    iv_custom.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_custom.setTextColor(getResources().getColor(R.color.custom_main));
                    mCropView.setCustomRatio(7, 5);
                    break;
                case R.id.buttonFree:
                    Callallothers();
                    iv_free.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_free.setTextColor(getResources().getColor(R.color.custom_main));
                    mCropView.setCropMode(CropImageView.CropMode.FREE);
                    break;
                case R.id.buttonCircle:
                    Callallothers();
                    iv_circle.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_circle.setTextColor(getResources().getColor(R.color.custom_main));
                    mCropView.setCropMode(CropImageView.CropMode.CIRCLE);
                    break;
                case R.id.buttonShowCircleButCropAsSquare:
                    Callallothers();
                    iv_squrecircle.setColorFilter(getResources().getColor(R.color.custom_main));
                    tv_squrecircle.setTextColor(getResources().getColor(R.color.custom_main));
                    mCropView.setCropMode(CropImageView.CropMode.CIRCLE_SQUARE);
                    break;
            }
        }
    };

    public void saveImage() {
        Glob.bitmap = mCropView.getCroppedBitmap();
        Display display = getWindowManager().getDefaultDisplay();
        int w = display.getWidth();
        int h = display.getHeight();
        if (Glob.bitmap.getHeight() > Glob.bitmap.getWidth()) {
            if (Glob.bitmap.getHeight() > h) {
                Glob.bitmap = Bitmap.createScaledBitmap(Glob.bitmap, (Glob.bitmap.getWidth() * h) / Glob.bitmap.getHeight(), h, false);
            }
            if (Glob.bitmap.getWidth() > w) {
                Glob.bitmap = Bitmap.createScaledBitmap(Glob.bitmap, w, (Glob.bitmap.getHeight() * w) / Glob.bitmap.getWidth(), false);
            }
        } else {
            if (Glob.bitmap.getWidth() > w) {
                Glob.bitmap = Bitmap.createScaledBitmap(Glob.bitmap, w, (Glob.bitmap.getHeight() * w) / Glob.bitmap.getWidth(), false);
            }
            if (Glob.bitmap.getHeight() > h) {
                Glob.bitmap = Bitmap.createScaledBitmap(Glob.bitmap, (Glob.bitmap.getWidth() * h) / Glob.bitmap.getHeight(), h, false);
            }
        }

        startActivity(new Intent(CropActivity.this, FreeCropActivity.class));


    }


}
