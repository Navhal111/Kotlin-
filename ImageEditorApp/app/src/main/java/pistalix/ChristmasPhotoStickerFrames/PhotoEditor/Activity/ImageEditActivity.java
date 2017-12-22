package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity;

import android.annotation.SuppressLint;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Matrix;
import android.graphics.Paint;
import android.graphics.Rect;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.support.annotation.IdRes;
import android.support.v7.app.AppCompatActivity;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.flask.colorpicker.ColorPickerView;
import com.flask.colorpicker.OnColorSelectedListener;
import com.flask.colorpicker.builder.ColorPickerClickListener;
import com.flask.colorpicker.builder.ColorPickerDialogBuilder;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.InputStream;
import java.io.OutputStream;
import java.text.SimpleDateFormat;
import java.util.Date;
;import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.MyTouch.FrontOnMultitouch;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.MyTouch.MultiTouchListener;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile.Glob;


public class ImageEditActivity extends AppCompatActivity implements View.OnClickListener {

    private ImageView imagef1_1;
    public static Bitmap Edit_bit;
    public static int width;
    public static int hight;
    private int d_hight;
    private int d_width;
    private Boolean BIKE = false;
    private Bitmap bit;
    private Boolean settuch = true;
    private ImageView close_zoom_pan;
    private Bitmap bit1;
    private ImageView background;
    private RelativeLayout ZoomPan_Layout, rootRelative;
    private LinearLayout backgrounds;
    private LinearLayout backgrond_button;
    private LinearLayout flip, eraser;
    private LinearLayout  save;
    private RelativeLayout bottomitem;
    private int currentBackgroundColor = 0xffffffff;
    private ImageView iv_bg, iv_flip, iv_erase, iv_save;
    private TextView ttbg, ttflip, tterase, ttsave;
    private InputStream inputStream;
    private com.facebook.ads.InterstitialAd interstitialAdFB;
    RelativeLayout radiobar;
    RadioGroup myradiogroup;
    RadioButton photoradio;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_image_edit);
//        imagef1_1.setOnClickListener(new FrontOnMultitouch());
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        Edit_bit = Glob.bitmap;
        Bind();
        width = Edit_bit.getWidth();
        hight = Edit_bit.getHeight();
        DisplayMetrics displayMetrics = getResources().getDisplayMetrics();
        d_width = displayMetrics.widthPixels;
        d_hight = displayMetrics.heightPixels;
        bit = bitmap();
        bit1 = prescale(bit);
        setImage();
//        myradiogroup.check(R.id.photoradio);

    }

    private Bitmap prescale(Bitmap bitmap) {
        Matrix matrix = new Matrix();
        matrix.preScale(-1.0f, 1.0f);
        Bitmap createBitmap = Bitmap.createBitmap(bitmap, 0, 0, bitmap.getWidth(), bitmap.getHeight(), matrix, false);
        createBitmap.setDensity(160);
        return createBitmap;

    }

    private void setImage() {
        imagef1_1 = (ImageView) findViewById(R.id.imagef1_1);
        imagef1_1.setImageBitmap(bit);
        imagef1_1.setOnTouchListener(new FrontOnMultitouch());
//        Glide.with(this)
//                .load(bit)
//                .thumbnail(0.5f)
//                .dontAnimate()
//                .into(imagef1_1);
//


    }

    private Bitmap bitmap() {
        System.out.println();
        Bitmap createBitmap = Bitmap.createBitmap((int) (((double) width) * 0.8d), (int) (((double) hight) * 0.7d), Bitmap.Config.ARGB_8888);
        new Canvas(createBitmap).drawBitmap(Edit_bit, null, new Rect(0, 0, (int) (((double) width) * 0.9d), (int) (((double) hight) * 0.9d)), new Paint());
        return createBitmap;
    }

    private void Bind() {
//        radiobar = (RelativeLayout) findViewById(R.id.radiobar);
//        myradiogroup = (RadioGroup) findViewById(R.id.myradiogrup);
//        photoradio = (RadioButton) findViewById(R.id.photoradio);
        close_zoom_pan = (ImageView) findViewById(R.id.close_zoom_pan);
        close_zoom_pan.setOnClickListener(this);
        background = (ImageView) findViewById(R.id.background);
        background.setImageResource(R.drawable.nt4);
        background.setOnClickListener(this);
        rootRelative = (RelativeLayout) findViewById(R.id.rootRelative);
        ZoomPan_Layout = (RelativeLayout) findViewById(R.id.ZoomPan_Layout);
//        ZoomPan_Layout.setOnTouchListener(new MultiTouchListener());
        backgrounds = (LinearLayout) findViewById(R.id.backgrounds);
        eraser = (LinearLayout) findViewById(R.id.eraser);
        eraser.setOnClickListener(this);
        flip = (LinearLayout) findViewById(R.id.flip);
        flip.setOnClickListener(this);
        bottomitem = (RelativeLayout) findViewById(R.id.bottomitem);
        backgrond_button = (LinearLayout) findViewById(R.id.backgrond_button);
        backgrond_button.setOnClickListener(this);
        save = (LinearLayout) findViewById(R.id.save);
        save.setOnClickListener(this);
//        ZoomPan_Layout
        callmenu();


    }

    private void callmenu() {
        iv_bg = (ImageView) findViewById(R.id.iv_bg);
        ttbg = (TextView) findViewById(R.id.ttbg);
        iv_flip = (ImageView) findViewById(R.id.iv_flip);
        ttflip = (TextView) findViewById(R.id.ttflip);
        iv_erase = (ImageView) findViewById(R.id.iv_erase);
        tterase = (TextView) findViewById(R.id.tterase);
        iv_save = (ImageView) findViewById(R.id.iv_save);
        ttsave = (TextView) findViewById(R.id.ttsave);
    }

    private void callcolor() {
        iv_bg.setColorFilter(getResources().getColor(R.color.white));
        ttbg.setTextColor(getResources().getColor(R.color.white));
        iv_flip.setColorFilter(getResources().getColor(R.color.white));
        ttflip.setTextColor(getResources().getColor(R.color.white));
        iv_erase.setColorFilter(getResources().getColor(R.color.white));
        tterase.setTextColor(getResources().getColor(R.color.white));
        iv_save.setColorFilter(getResources().getColor(R.color.white));
        ttsave.setTextColor(getResources().getColor(R.color.white));
    }

    @Override
    public void onClick(View v) {
        Animation translateAnimation = new TranslateAnimation(0.0f, 0.0f, 200.0f, 0.0f);
        switch (v.getId()) {

            case R.id.background:
                backgrounds.setVisibility(View.VISIBLE);
                return;
            case R.id.close_zoom_pan:
                close_zoom_pan.setVisibility(View.INVISIBLE);
                return;
            case R.id.save:
                if (BIKE == false) {
                    callcolor();
                    iv_save.setColorFilter(getResources().getColor(R.color.custom_main));
                    ttsave.setTextColor(getResources().getColor(R.color.custom_main));
//                callvisibility();
                    create_Save_Image();
                } else {
                    Toast.makeText(ImageEditActivity.this, "please select bike", Toast.LENGTH_LONG).show();
                }

                return;
            case R.id.backgrond_button:
                callcolor();
                iv_bg.setColorFilter(getResources().getColor(R.color.custom_main));
                ttbg.setTextColor(getResources().getColor(R.color.custom_main));
                callvisibility();
                if (backgrounds.getVisibility() == 4) {
                    backgrounds.setVisibility(0);
                    translateAnimation.setDuration(500);
                    translateAnimation.setFillAfter(true);
                    bottomitem.startAnimation(translateAnimation);
                } else {
                    backgrounds.setVisibility(4);
                }

                return;
            case R.id.flip:
                callcolor();
                iv_flip.setColorFilter(getResources().getColor(R.color.custom_main));
                ttflip.setTextColor(getResources().getColor(R.color.custom_main));
//                callvisibility();
                bit = prescale(bit);
                bit1 = prescale(bit1);
                setImage();
                return;
            case R.id.eraser:
                callcolor();
                iv_erase.setColorFilter(getResources().getColor(R.color.custom_main));
                tterase.setTextColor(getResources().getColor(R.color.custom_main));
//                callvisibility();
                startActivityForResult(new Intent(this, EraseActivity.class), 1);
                return;
        }
    }

    private void callvisibility() {
        backgrounds.setVisibility(4);
    }

    private void create_Save_Image() {
        Glob.finalBitmap = getbitmap(rootRelative);
        saveImage(Glob.finalBitmap);
        startActivity(new Intent(this, Image_Edit_Screen.class));
//        showFBInterstitial();
    }

//

    private void saveImage(Bitmap bitmap2) {
        Log.v("TAG", "saveImageInCache is called");
        Bitmap bitmap;
        OutputStream output;

        // Retrieve the image from the res folder
        bitmap = bitmap2;

        File filepath = Environment.getExternalStorageDirectory();
        // Create a new folder in SD Card
        File dir = new File(filepath.getAbsolutePath() + "/" + Glob.Edit_Folder_name1);
        dir.mkdirs();

        // Create a name for the saved image
        String ts = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String FileName = ts + ".jpeg";
        File file = new File(dir, FileName);
        file.renameTo(file);
        String _uri = "file://" + filepath.getAbsolutePath() + "/" + Glob.Edit_Folder_name1 + "/" + FileName;
        //for share image
        String _uri2 = filepath.getAbsolutePath() + "/" + Glob.Edit_Folder_name1 + "/" + FileName;
        Glob.shareuri = _uri2;//used in share image
        Log.d("cache uri=", _uri);
        try {

            output = new FileOutputStream(file);

            // Compress into png format image from 0% - 100%
            bitmap.compress(Bitmap.CompressFormat.JPEG, 100, output);
            output.flush();
            output.close();
            //finish();
            sendBroadcast(new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE, Uri.fromFile(new File(_uri))));

        } catch (Exception e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    private Bitmap getbitmap(View view) {
        width = view.getWidth();
        hight = view.getHeight();
        Bitmap createBitmap = Bitmap.createBitmap(view.getWidth(), view.getHeight(), Bitmap.Config.ARGB_8888);
        view.draw(new Canvas(createBitmap));
        return createBitmap;

    }


    public void SetBackground(View view) {
        switch (view.getId()) {
            case R.id.ivNone1:
//                background.setImageResource(R.drawable.trans);
                imageset(R.drawable.trans,background);
                break;
            case R.id.backgrond0:
                colordailog();
                break;
            case R.id.backgrond1:
//                background.setImageResource(R.drawable.g1);
                imageset(R.drawable.g1,background);
                return;
            case R.id.backgrond2:
//                background.setImageResource(R.drawable.g2);
                imageset(R.drawable.g2,background);
                return;
            case R.id.backgrond3:
//                background.setImageResource(R.drawable.g3);
                imageset(R.drawable.g3,background);
                return;
            case R.id.backgrond4:
//                background.setImageResource(R.drawable.g4);
                imageset(R.drawable.g4,background);
                return;
//            case R.id.backgrond5:
//                background.setImageResource(R.drawable.g5);
//                return;
            case R.id.backgrond6:
//                background.setImageResource(R.drawable.g6);
                imageset(R.drawable.g6,background);
                return;
            case R.id.backgrond7:
//                background.setImageResource(R.drawable.g7);
                imageset(R.drawable.g7,background);
                return;

            case R.id.backgrond9:
//                background.setImageResource(R.drawable.g9);
                imageset(R.drawable.g9,background);
                return;

            case R.id.backgrond12:
//                background.setImageResource(R.drawable.g12);
                imageset(R.drawable.g12,background);
                return;

            case R.id.backgrond14:
//                background.setImageResource(R.drawable.g14);
                imageset(R.drawable.g14,background);
                return;
            case R.id.backgrond17:
//                background.setImageResource(R.drawable.g17);
                imageset(R.drawable.g17,background);
                break;
            case R.id.backgrond21:
//                background.setImageResource(R.drawable.nt1);
                imageset(R.drawable.nt1,background);
                break;

            case R.id.backgrond22:
//                background.setImageResource(R.drawable.nt2);
                imageset(R.drawable.nt2,background);
                break;

            case R.id.backgrond23:
//                background.setImageResource(R.drawable.nt3);
                imageset(R.drawable.nt3,background);
                break;

            case R.id.backgrond24:
//                background.setImageResource(R.drawable.nt4);
                imageset(R.drawable.nt4,background);
                break;

            case R.id.backgrond25:
//                background.setImageResource(R.drawable.nt5);
                imageset(R.drawable.nt5,background);
                break;

            case R.id.backgrond26:
//                background.setImageResource(R.drawable.nt6);
                imageset(R.drawable.nt6,background);
                break;

            case R.id.backgrond27:
//                background.setImageResource(R.drawable.nt7);
                imageset(R.drawable.nt7,background);
                break;
            default:
                return;
        }
    }
    private void imageset(int idimage,ImageView imageview){
        Glide.with(getApplicationContext())
                .load(idimage)
                .thumbnail(0.5f)
                .dontAnimate()
                .into(imageview);
        }
    private void colordailog() {

        ColorPickerDialogBuilder
                .with(ImageEditActivity.this)
                .initialColor(currentBackgroundColor)
                .wheelType(ColorPickerView.WHEEL_TYPE.FLOWER)
                .density(12)
                .setOnColorSelectedListener(new OnColorSelectedListener() {
                    @Override
                    public void onColorSelected(int selectedColor) {
                        // toast("onColorSelected: 0x" + Integer.toHexString(selectedColor));
                    }
                })
                .setPositiveButton("ok", new ColorPickerClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int selectedColor, Integer[] allColors) {
                        background.setImageBitmap(null);
                        background.setBackgroundColor(selectedColor);
                    }
                })
                .setNegativeButton("cancel", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                    }
                })
                .showColorEdit(true)
                .setColorEditTextColor(getResources().getColor(R.color.colorPrimary))
                .build()
                .show();
    }

    protected void onActivityResult(int i, int i2, Intent intent) {
        super.onActivityResult(i, i2, intent);
        switch (i) {
            case 1:
                bit = bitmap();
                bit1 = prescale(bit);
                setImage();
                return;
            case 2:
                try {
                    inputStream = getContentResolver().openInputStream(intent.getData());
                } catch (FileNotFoundException e2) {
                    e2.printStackTrace();
                }
                Bitmap decodeStream = BitmapFactory.decodeStream(inputStream);
                int width = decodeStream.getWidth();
                int height = decodeStream.getHeight();
                if (width > height) {
                    while (true) {
                        if (width > this.d_width || height > this.d_hight) {
                            width = (int) (((double) width) * 0.9d);
                            height = (int) (((double) height) * 0.9d);
                        } else {
                            background.setImageBitmap(Bitmap.createScaledBitmap(decodeStream, (width / width) * this.d_width, (height / height) * this.hight, true));
                            return;
                        }
                    }
                }
                Toast.makeText(this, "Incompatable Image !!! Width should be greater that height.", Toast.LENGTH_SHORT).show();

                return;
        }
    }






}
