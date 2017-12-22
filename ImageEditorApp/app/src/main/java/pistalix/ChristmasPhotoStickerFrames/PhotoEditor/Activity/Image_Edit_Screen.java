package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.EmbossMaskFilter;
import android.graphics.Point;
import android.graphics.Shader;
import android.graphics.Typeface;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.ColorDrawable;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.support.v7.app.AppCompatActivity;
import android.util.AttributeSet;
import android.util.Log;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.HorizontalScrollView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.flask.colorpicker.ColorPickerView;
import com.flask.colorpicker.OnColorSelectedListener;
import com.flask.colorpicker.builder.ColorPickerClickListener;
import com.flask.colorpicker.builder.ColorPickerDialogBuilder;
import com.google.android.gms.ads.AdRequest;

import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStream;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Random;

import it.sephiroth.android.library.widget.HListView;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Adepter.EffectAdapter;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.MainStriker;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.MyTouch.FrontOnMultitouch;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.StickerImageView;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.StickerTextView;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile.Effects;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile.Glob;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.modul.EffectModel;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo.FontFace;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo.FontList_Adapter;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo.GradientManager;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo.OnTouch;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo.StickerAdapter;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo.StickerView;


public class Image_Edit_Screen extends AppCompatActivity implements View.OnClickListener {

    private int currentBackgroundColor = 0xffffffff;
    private Integer stickerId,BeardId,capId;
    private EditText ET_text;
    public Bitmap finalBitmapText;

    private StickerAdapter stikerAdaptor,BeardAdapter,CapAdapter;
    private ArrayList<ImageView> striker_image = new ArrayList<>();
    private ArrayList<ImageView> bread_image = new ArrayList<>();
    private ArrayList<ImageView> cap_image = new ArrayList<>();
    private ArrayList<View> mViews = new ArrayList<>();
    private StickerView mCurrentView;
    public static Bitmap finalEditedBitmapImage3;
    private TextView TV_Text;
    public String str;
    private com.google.android.gms.ads.InterstitialAd mInterstitialAdMob;
    private StickerImageView MainTuchimage;
    private ArrayList<StickerImageView> MainTuchimageArray  = new ArrayList<StickerImageView>();
    private ArrayList<StickerTextView> MainArraytext = new ArrayList<StickerTextView>();
    public static String _url;

    private static final String TAG = "Image_Edit_Screen";

    ImageView image_view, bike_view, image_overlay, ivBack;

    ImageView btn_cancel1, btn_cancel2, btn_cancel3,beard_btn_cancel2,cap_btn_cancel2, btn_cancel4;

    ImageView ivNone, ivSetBackGroundColor, ivbb1, ivbb2, ivbb3, ivbb4, ivbb6, ivbb7, ivbb9, ivbb12, ivbb14, ivbb17, nt1, nt2, nt3, nt4, nt5, nt6, nt7, nt8, nt9, nt10, nt11, nt12, nt13, nt14, nt15, nt16, nt17, nt18, nt19;

    ImageView ef_original, ef1, ef4, ef5, ef6, ef7, ef13, ef14, ef15, ef16, ef17, ef18, ef19, ef20;

    ImageView open_effect, open_overlay, open_stickers, open_text, open_save, open_background,open_caps;

    HorizontalScrollView HL_Effact, hsvSetBackGround;

    HListView list_stickers, list_effect,list_beard,list_cap;

    LinearLayout ll_effect_list, lin_effects, overlay_show, sticker_show,beard_show,cap_show, background_show, llSetBackGround;

    RelativeLayout effects_thump, stickers_thump, overlay_thump,beard_thump,cap_thump;

    FrameLayout image_frame, main_frame;
    Bitmap bmpFinalEraseImageFromEraseActivity;
    private StickerView stickerView,BeardView,CapView;

    private ArrayList<EffectModel> effects;
    private ArrayList<Integer> stiker;
    private ArrayList<Integer> beards;
    private ArrayList<Integer> caps;
    ArrayList<Integer> stickerviewId = new ArrayList<>();
    public TextDailog textdailog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_image__edit__screen);

        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        binview();

        bindEffectIcon();

        image_view.setImageBitmap(Glob.finalBitmap);
//        image_view.setOnTouchListener(new MultiTouchListener());


    }

    private void bindEffectIcon() {
        ef_original = (ImageView) findViewById(R.id.ef_original);
        ef_original.setOnClickListener(this);
        ef1 = (ImageView) findViewById(R.id.ef1);
        ef1.setOnClickListener(this);
//        ef2 = (ImageView) findViewById(R.id.ef2);
//        ef2.setOnClickListener(this);
//        ef3 = (ImageView) findViewById(R.id.ef3);
//        ef3.setOnClickListener(this);
        ef4 = (ImageView) findViewById(R.id.ef4);
        ef4.setOnClickListener(this);
        ef5 = (ImageView) findViewById(R.id.ef5);
        ef5.setOnClickListener(this);
        ef6 = (ImageView) findViewById(R.id.ef6);
        ef6.setOnClickListener(this);
        ef7 = (ImageView) findViewById(R.id.ef7);
        ef7.setOnClickListener(this);
//        ef8 = (ImageView) findViewById(R.id.ef8);
//        ef8.setOnClickListener(this);
//        ef9 = (ImageView) findViewById(R.id.ef9);
//        ef9.setOnClickListener(this);
//        ef10 = (ImageView) findViewById(R.id.ef10);
//        ef10.setOnClickListener(this);
//        ef11 = (ImageView) findViewById(R.id.ef11);
//        ef11.setOnClickListener(this);
//        ef12 = (ImageView) findViewById(R.id.ef12);
//        ef12.setOnClickListener(this);
        ef13 = (ImageView) findViewById(R.id.ef13);
        ef13.setOnClickListener(this);
        ef14 = (ImageView) findViewById(R.id.ef14);
        ef14.setOnClickListener(this);
        ef15 = (ImageView) findViewById(R.id.ef15);
        ef15.setOnClickListener(this);
        ef16 = (ImageView) findViewById(R.id.ef16);
        ef16.setOnClickListener(this);
        ef17 = (ImageView) findViewById(R.id.ef17);
        ef17.setOnClickListener(this);
        ef18 = (ImageView) findViewById(R.id.ef18);
        ef18.setOnClickListener(this);
        ef19 = (ImageView) findViewById(R.id.ef19);
        ef19.setOnClickListener(this);
        ef20 = (ImageView) findViewById(R.id.ef20);
        ef20.setOnClickListener(this);
//        ef21 = (ImageView) findViewById(R.id.ef21);
//        ef21.setOnClickListener(this);
//        ef22 = (ImageView) findViewById(R.id.ef22);
//        ef22.setOnClickListener(this);
        Effects.applyEffectNone(ef_original);
        Effects.applyEffect1(ef1);
//        Effects.applyEffect2(ef2);
//        Effects.applyEffect3(ef3);
        Effects.applyEffect4(ef4);
        Effects.applyEffect5(ef5);
        Effects.applyEffect6(ef6);
        Effects.applyEffect7(ef7);
//        Effects.applyEffect8(ef8);
//        Effects.applyEffect9(ef9);
//        Effects.applyEffect10(ef10);
//        Effects.applyEffect11(ef11);
//        Effects.applyEffect12(ef12);
        Effects.applyEffect13(ef13);
        Effects.applyEffect14(ef14);
        Effects.applyEffect15(ef15);
        Effects.applyEffect16(ef16);
        Effects.applyEffect17(ef17);
        Effects.applyEffect18(ef18);
        Effects.applyEffect19(ef19);
        Effects.applyEffect20(ef20);
//        Effects.applyEffect21(ef21);
//        Effects.applyEffect22(ef22);
    }


    private void binview() {

        open_effect = (ImageView) findViewById(R.id.open_effect);
        open_effect.setOnClickListener(this);
        open_caps = (ImageView) findViewById(R.id.open_caps);
        open_caps.setOnClickListener(this);
        open_overlay = (ImageView) findViewById(R.id.open_overlay);
        open_overlay.setOnClickListener(this);
        open_stickers = (ImageView) findViewById(R.id.open_stickers);
        open_stickers.setOnClickListener(this);
        open_text = (ImageView) findViewById(R.id.open_text);
        open_text.setOnClickListener(this);
        open_save = (ImageView) findViewById(R.id.open_save);
        open_save.setOnClickListener(this);
//        open_background = (ImageView) findViewById(R.id.open_background);
//        open_background.setOnClickListener(this);

        bike_view = (ImageView) findViewById(R.id.bike_view);
        image_view = (ImageView) findViewById(R.id.image_view);
        ivBack = (ImageView) findViewById(R.id.ivBack);
        ivBack.setOnClickListener(this);
        btn_cancel1 = (ImageView) findViewById(R.id.btn_cancel1);
        btn_cancel1.setOnClickListener(this);
        beard_btn_cancel2 = (ImageView) findViewById(R.id.beard_btn_cancel2);
        beard_btn_cancel2.setOnClickListener(this);
        cap_btn_cancel2 = (ImageView) findViewById(R.id.cap_btn_cancel2);
        cap_btn_cancel2.setOnClickListener(this);
        btn_cancel2 = (ImageView) findViewById(R.id.btn_cancel2);
        btn_cancel2.setOnClickListener(this);
        btn_cancel3 = (ImageView) findViewById(R.id.btn_cancel3);
        btn_cancel3.setOnClickListener(this);
        image_overlay = (ImageView) findViewById(R.id.image_overlay);

        HL_Effact = (HorizontalScrollView) findViewById(R.id.HL_Effact);

        list_stickers = (HListView) findViewById(R.id.list_stickers);
        list_effect = (HListView) findViewById(R.id.list_effect);
        list_beard = (HListView)findViewById(R.id.list_beard);
        list_cap = (HListView)findViewById(R.id.list_cap);
        ll_effect_list = (LinearLayout) findViewById(R.id.ll_effect_list);
        lin_effects = (LinearLayout) findViewById(R.id.lin_effects);
        overlay_show = (LinearLayout) findViewById(R.id.overlay_show);
        sticker_show = (LinearLayout) findViewById(R.id.sticker_show);
        beard_show = (LinearLayout) findViewById(R.id.beard_show);
        cap_show = (LinearLayout)findViewById(R.id.cap_show);
        effects_thump = (RelativeLayout) findViewById(R.id.effects_thump);
        stickers_thump = (RelativeLayout) findViewById(R.id.stickers_thump);
        overlay_thump = (RelativeLayout) findViewById(R.id.overlay_thump);
        beard_thump = (RelativeLayout)findViewById(R.id.beard_thump);
        cap_thump = (RelativeLayout)findViewById(R.id.cap_thump);
        main_frame = (FrameLayout) findViewById(R.id.main_frame);
        main_frame.setOnClickListener(this);
    }

    @Override
    public void onClick(View view) {

        switch (view.getId()) {
            case R.id.main_frame:
                onTouch.removeBorder();
                if(!MainTuchimageArray.isEmpty()){
                    hidebordercap();
                }
                if(!MainArraytext.isEmpty()){
                    hidetext();
                }
//                MainTuchimage.setControlsVisibility(false);
                break;


            case R.id.open_effect:
                HL_Effact.setVisibility(View.VISIBLE);
                ll_effect_list.setVisibility(View.VISIBLE);
                effects_thump.setVisibility(View.VISIBLE);
                btn_cancel1.setVisibility(View.VISIBLE);
                btn_cancel2.setVisibility(View.GONE);

//                background_show.setVisibility(View.GONE);
//                hsvSetBackGround.setVisibility(View.GONE);
//                llSetBackGround.setVisibility(View.GONE);

                overlay_show.setVisibility(View.GONE);
                overlay_thump.setVisibility(View.GONE);
                list_effect.setVisibility(View.GONE);
                list_stickers.setVisibility(View.GONE);
                stickers_thump.setVisibility(View.GONE);
                btn_cancel3.setVisibility(View.GONE);
                sticker_show.setVisibility(View.GONE);


                beard_show.setVisibility(View.GONE);
                beard_btn_cancel2.setVisibility(View.GONE);
                beard_thump.setVisibility(View.GONE);
                list_beard.setVisibility(View.GONE);

                cap_show.setVisibility(View.GONE);
                cap_btn_cancel2.setVisibility(View.GONE);
                cap_thump.setVisibility(View.GONE);
                list_cap.setVisibility(View.GONE);
                break;

            case R.id.open_overlay:
                setArraylistforeffect();
                overlay_show.setVisibility(View.VISIBLE);
                overlay_thump.setVisibility(View.VISIBLE);
                list_effect.setVisibility(View.VISIBLE);
                btn_cancel3.setVisibility(View.VISIBLE);

//                background_show.setVisibility(View.GONE);
//                hsvSetBackGround.setVisibility(View.GONE);
//                llSetBackGround.setVisibility(View.GONE);

                HL_Effact.setVisibility(View.GONE);
                ll_effect_list.setVisibility(View.GONE);
                effects_thump.setVisibility(View.GONE);
                btn_cancel1.setVisibility(View.GONE);
                btn_cancel2.setVisibility(View.GONE);

                list_stickers.setVisibility(View.GONE);
                stickers_thump.setVisibility(View.GONE);
                sticker_show.setVisibility(View.GONE);

                beard_show.setVisibility(View.GONE);
                beard_btn_cancel2.setVisibility(View.GONE);
                beard_thump.setVisibility(View.GONE);
                list_beard.setVisibility(View.GONE);

                cap_show.setVisibility(View.GONE);
                cap_btn_cancel2.setVisibility(View.GONE);
                cap_thump.setVisibility(View.GONE);
                list_cap.setVisibility(View.GONE);
                list_effect.setOnItemClickListener(new it.sephiroth.android.library.widget.AdapterView.OnItemClickListener() {
                    @Override
                    public void onItemClick(it.sephiroth.android.library.widget.AdapterView<?> parent, View view, int position, long id) {
                        int eff = effects.get(position).getE_FrmId();
//                        bike_view.setImageResource(eff);
                        Glide.with(getApplicationContext())
                                .load(eff)
                                .thumbnail(0.5f)
                                .dontAnimate()
                                .into(bike_view);
                    }
                });


                break;

            case R.id.open_stickers:
                setstickerlist();

                sticker_show.setVisibility(View.VISIBLE);
                btn_cancel2.setVisibility(View.VISIBLE);
                stickers_thump.setVisibility(View.VISIBLE);
                list_stickers.setVisibility(View.VISIBLE);

//                background_show.setVisibility(View.GONE);
//                hsvSetBackGround.setVisibility(View.GONE);
//                llSetBackGround.setVisibility(View.GONE);

                HL_Effact.setVisibility(View.GONE);
                ll_effect_list.setVisibility(View.GONE);
                effects_thump.setVisibility(View.GONE);
                btn_cancel1.setVisibility(View.GONE);


                overlay_show.setVisibility(View.GONE);
                overlay_thump.setVisibility(View.GONE);
                list_effect.setVisibility(View.GONE);
                btn_cancel3.setVisibility(View.GONE);

                beard_show.setVisibility(View.GONE);
                beard_btn_cancel2.setVisibility(View.GONE);
                beard_thump.setVisibility(View.GONE);
                list_beard.setVisibility(View.GONE);

                cap_show.setVisibility(View.GONE);
                cap_btn_cancel2.setVisibility(View.GONE);
                cap_thump.setVisibility(View.GONE);
                list_cap.setVisibility(View.GONE);

                break;

            case R.id.open_text:
//                textdailog = new TextDailog(Image_Edit_Screen.this);
//                textdailog.getWindow().setBackgroundDrawable(new ColorDrawable(0));
//                textdailog.setCanceledOnTouchOutside(true);
//                textdailog.show();
                setBeardList();
                beard_show.setVisibility(View.VISIBLE);
                beard_btn_cancel2.setVisibility(View.VISIBLE);
                beard_thump.setVisibility(View.VISIBLE);
                list_beard.setVisibility(View.VISIBLE);

                sticker_show.setVisibility(View.GONE);
                btn_cancel2.setVisibility(View.GONE);
                stickers_thump.setVisibility(View.GONE);
                list_stickers.setVisibility(View.GONE);


                HL_Effact.setVisibility(View.GONE);
                ll_effect_list.setVisibility(View.GONE);
                effects_thump.setVisibility(View.GONE);
                btn_cancel1.setVisibility(View.GONE);


                overlay_show.setVisibility(View.GONE);
                overlay_thump.setVisibility(View.GONE);
                list_effect.setVisibility(View.GONE);
                btn_cancel3.setVisibility(View.GONE);

                cap_show.setVisibility(View.GONE);
                cap_btn_cancel2.setVisibility(View.GONE);
                cap_thump.setVisibility(View.GONE);
                list_cap.setVisibility(View.GONE);
                break;
            case R.id.open_caps:
                 setCapList();
                cap_show.setVisibility(View.VISIBLE);
                cap_btn_cancel2.setVisibility(View.VISIBLE);
                cap_thump.setVisibility(View.VISIBLE);
                list_cap.setVisibility(View.VISIBLE);

                beard_show.setVisibility(View.GONE);
                beard_btn_cancel2.setVisibility(View.GONE);
                beard_thump.setVisibility(View.GONE);
                list_beard.setVisibility(View.GONE);

                sticker_show.setVisibility(View.GONE);
                btn_cancel2.setVisibility(View.GONE);
                stickers_thump.setVisibility(View.GONE);
                list_stickers.setVisibility(View.GONE);

//                background_show.setVisibility(View.GONE);
//                hsvSetBackGround.setVisibility(View.GONE);
//                llSetBackGround.setVisibility(View.GONE);

                HL_Effact.setVisibility(View.GONE);
                ll_effect_list.setVisibility(View.GONE);
                effects_thump.setVisibility(View.GONE);
                btn_cancel1.setVisibility(View.GONE);


                overlay_show.setVisibility(View.GONE);
                overlay_thump.setVisibility(View.GONE);
                list_effect.setVisibility(View.GONE);
                btn_cancel3.setVisibility(View.GONE);

                break;
            case R.id.open_save:
                if(!MainTuchimageArray.isEmpty()){
                    hidebordercap();
                }
                if(!MainArraytext.isEmpty()){
                    hidetext();
                }
                onTouch.removeBorder();


                btn_cancel1.setVisibility(View.GONE);
                HL_Effact.setVisibility(View.GONE);
                ll_effect_list.setVisibility(View.GONE);

                btn_cancel2.setVisibility(View.GONE);
                sticker_show.setVisibility(View.GONE);

                btn_cancel3.setVisibility(View.GONE);
                overlay_show.setVisibility(View.GONE);

//                background_show.setVisibility(View.GONE);
//                hsvSetBackGround.setVisibility(View.GONE);
//                llSetBackGround.setVisibility(View.GONE);

                create_Save_Image();

                break;

            case R.id.btn_cancel1:
                onTouch.removeBorder();
                btn_cancel1.setVisibility(View.GONE);
                HL_Effact.setVisibility(View.GONE);
                ll_effect_list.setVisibility(View.GONE);
                break;

            case R.id.btn_cancel2:
                onTouch.removeBorder();
                btn_cancel2.setVisibility(View.GONE);
                sticker_show.setVisibility(View.GONE);
                break;

            case R.id.btn_cancel3:
                onTouch.removeBorder();
                btn_cancel3.setVisibility(View.GONE);
                overlay_show.setVisibility(View.GONE);
                break;
            case R.id.beard_btn_cancel2:
                onTouch.removeBorder();
                beard_btn_cancel2.setVisibility(View.GONE);
                beard_thump.setVisibility(View.GONE);
                break;
            case R.id.cap_btn_cancel2:
                onTouch.removeBorder();
                cap_btn_cancel2.setVisibility(View.GONE);
                cap_thump.setVisibility(View.GONE);
                break;
            case R.id.ef_original:
                Effects.applyEffectNone(image_view);
                break;

            case R.id.ef1:
                Effects.applyEffect1(image_view);
                break;

            case R.id.ef4:
                Effects.applyEffect4(image_view);
                break;

            case R.id.ef5:
                Effects.applyEffect5(image_view);
                break;

            case R.id.ef6:
                Effects.applyEffect6(image_view);
                break;

            case R.id.ef7:
                Effects.applyEffect7(image_view);
                break;

            case R.id.ef13:
                Effects.applyEffect13(image_view);
                break;

            case R.id.ef14:
                Effects.applyEffect14(image_view);
                break;

            case R.id.ef15:
                Effects.applyEffect15(image_view);
                break;

            case R.id.ef16:
                Effects.applyEffect16(image_view);
                break;

            case R.id.ef17:
                Effects.applyEffect17(image_view);
                break;

            case R.id.ef18:
                Effects.applyEffect18(image_view);
                break;

            case R.id.ef19:
                Effects.applyEffect19(image_view);
                break;

            case R.id.ef20:
                Effects.applyEffect20(image_view);
                break;


            case R.id.ivBack:
                finish();
                break;
        }

    }



    private com.google.android.gms.ads.InterstitialAd showAdmobFullAd() {
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
        this.mInterstitialAdMob.loadAd(new AdRequest.Builder().build());
    }

    private void showAdmobInterstitial() {
        if (this.mInterstitialAdMob != null && this.mInterstitialAdMob.isLoaded()) {
            this.mInterstitialAdMob.show();
        }
    }

    private void create_Save_Image() {
        Log.v("TAG", "saveImageInCache is called");
        Bitmap bm = null;
        main_frame.setDrawingCacheEnabled(true);
        main_frame.buildDrawingCache();
        bm = main_frame.getDrawingCache();
//        finalEditedBitmapImage3 = getMainFrameBitmap(main_frame);
//        if(finalEditedBitmapImage3 != null){
            saveImage(bm);
            main_frame.destroyDrawingCache();
            Intent i2 = new Intent(Image_Edit_Screen.this, ShareActivity.class);
            startActivity(i2);
//        }
//        Toast.makeText(this, "Something went Wrong", Toast.LENGTH_SHORT).show();
//        showAdmobInterstitial();
    }

    private Bitmap getMainFrameBitmap(View view) {
        Bitmap bitmap = null;
        try{
            bitmap = Bitmap.createBitmap(view.getWidth(), view.getHeight(), Bitmap.Config.RGB_565);
            view.draw(new Canvas(bitmap));
            bitmap = CropBitmapTransparency(bitmap);
        }catch (OutOfMemoryError e){
            Toast.makeText(this, "Something went Wrong", Toast.LENGTH_SHORT).show();
        }catch (Exception e){
            Toast.makeText(this, "Something went Wrong", Toast.LENGTH_SHORT).show();
        }
        return bitmap;
    }


    private void saveImage(Bitmap bitmap2) {
        // isAlreadySave = true;
        Log.v("TAG", "saveImageInCache is called");
        Bitmap bitmap;
        OutputStream output;

        // Retrieve the image from the res folder
        bitmap = bitmap2;

        File filepath = Environment.getExternalStorageDirectory();
        // Create a new folder in SD Card
        File dir = new File(filepath.getAbsolutePath() + "/" + Glob.Edit_Folder_name);
        dir.mkdirs();

        // Create a name for the saved image
        String ts = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String FileName = ts + ".jpeg";
        File file = new File(dir, FileName);
        file.renameTo(file);
        String _uri = "file://" + filepath.getAbsolutePath() + "/" + Glob.Edit_Folder_name + "/" + FileName;
        //for share image
        String _uri2 = filepath.getAbsolutePath() + "/" + Glob.Edit_Folder_name + "/" + FileName;
        _url = _uri2;//used in share image
        Log.d("cache uri=", _uri);
        try {

            output = new FileOutputStream(file);

            // Compress into png format image from 0% - 100%
            bitmap.compress(Bitmap.CompressFormat.JPEG, 100, output);
            output.flush();
            output.close();
            //finish();
            new SingleMediaScanner(this,file);
            sendBroadcast(new Intent(Intent.ACTION_MEDIA_SCANNER_SCAN_FILE, Uri.fromFile(new File(_uri))));
            Toast.makeText(getApplicationContext(), "Image Saved", Toast.LENGTH_LONG).show();

        } catch (Exception e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
    }

    Bitmap CropBitmapTransparency(Bitmap sourceBitmap) {
        int minX = sourceBitmap.getWidth();
        int minY = sourceBitmap.getHeight();
        int maxX = -1;
        int maxY = -1;
        for (int y = 0; y < sourceBitmap.getHeight(); y++) {
            for (int x = 0; x < sourceBitmap.getWidth(); x++) {
                int alpha = (sourceBitmap.getPixel(x, y) >> 24) & 255;
                if (alpha > 0)   // pixel is not 100% transparent
                {
                    if (x < minX)
                        minX = x;
                    if (x > maxX)
                        maxX = x;
                    if (y < minY)
                        minY = y;
                    if (y > maxY)
                        maxY = y;
                }
            }
        }
        if ((maxX < minX) || (maxY < minY))
            return null; // Bitmap is entirely transparent

        // crop bitmap to non-transparent area and return:
        return Bitmap.createBitmap(sourceBitmap, minX, minY, (maxX - minX) + 1, (maxY - minY) + 1);
    }

    private void setstickerlist() {
        setArraylistForSticker();
        stikerAdaptor = new StickerAdapter(this, stiker);
        list_stickers.setAdapter(stikerAdaptor);
        list_stickers.setOnItemClickListener(new it.sephiroth.android.library.widget.AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(it.sephiroth.android.library.widget.AdapterView<?> parent, View view, int position, long id) {
                stickerView = new StickerView(Image_Edit_Screen.this);
                stickerId = stiker.get(position);
                stickerView.setImageResource(stickerId);
//                Glide.with(getApplicationContext())
//                        .load(stickerId)
//                        .thumbnail(0.5f)
//                        .dontAnimate()
//                        .into(stickerView);
                striker_image.add(stickerView);
                MainTuchimage = new StickerImageView(Image_Edit_Screen.this);
                MainTuchimage.setImageResource(stickerId);
                MainTuchimageArray.add(MainTuchimage);
//                stickerView.setOperationListener(new StickerView.OperationListener() {
//                    @Override
//                    public void onDeleteClick() {
//                        if(striker_image.size() >0){
//                            mViews.remove(striker_image.get(striker_image.size()-1));
//                            main_frame.removeView(striker_image.get(striker_image.size()-1));
//                            striker_image.remove(striker_image.size()-1);
//                        }
////                        mViews.remove(stickerView);
////                        main_frame.removeView(stickerView);
//                    }
//
//                    @Override
//                    public void onEdit(StickerView stickerView) {
//                        mCurrentView.setInEdit(false);
//                        mCurrentView = stickerView;
//                        mCurrentView.setInEdit(true);
//                    }
//
//                    @Override
//                    public void onTop(StickerView stickerView) {
//                        int position = mViews.indexOf(stickerView);
//                        if (position == mViews.size() - 1) {
//                            return;
//                        }
//                        StickerView stickerTemp = (StickerView) mViews.remove(position);
//                        mViews.add(mViews.size(), stickerTemp);
//                    }
//                });
                FrameLayout.LayoutParams lp = new FrameLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
                main_frame.addView(MainTuchimageArray.get(MainTuchimageArray.size()-1));
                mViews.add(MainTuchimageArray.get(MainTuchimageArray.size()-1));
//                setCurrentEdit(stickerView);
                Log.e("mViews.size", String.valueOf(mViews.size()));
            }
        });
    }

    private void setCurrentEdit(StickerView stickerView) {
        if (mCurrentView != null) {
            mCurrentView.setInEdit(false);
        }
        mCurrentView = stickerView;
        stickerView.setInEdit(true);
    }

    OnTouch onTouch = new OnTouch() {
        @Override
        public void removeBorder() {
            if (mCurrentView != null) {
                mCurrentView.setInEdit(false);
            }
        }
    };

    private void getDataText() {
        str = ET_text.getText().toString();
        TV_Text.setText(ET_text.getText().toString());
        ET_text.getText().clear();
    }


    private void setArraylistForSticker() {
        stiker = new ArrayList<>();

        stiker.add(new Integer(R.drawable.stick_37));
        stiker.add(new Integer(R.drawable.stick_38));
        stiker.add(new Integer(R.drawable.stick_39));
        stiker.add(new Integer(R.drawable.stick_40));
        stiker.add(new Integer(R.drawable.stick_41));
        stiker.add(new Integer(R.drawable.stick_42));
        stiker.add(new Integer(R.drawable.stick_43));
        stiker.add(new Integer(R.drawable.stick_44));
        stiker.add(new Integer(R.drawable.stick_45));
        stiker.add(new Integer(R.drawable.stick_46));
        stiker.add(new Integer(R.drawable.stick_47));
        stiker.add(new Integer(R.drawable.stick_48));
        stiker.add(new Integer(R.drawable.stick_49));
        stiker.add(new Integer(R.drawable.stick_50));
        stiker.add(new Integer(R.drawable.stick_51));
        stiker.add(new Integer(R.drawable.stick_52));
        stiker.add(new Integer(R.drawable.stick_53));
        stiker.add(new Integer(R.drawable.stick_54));
        stiker.add(new Integer(R.drawable.stick_55));
        stiker.add(new Integer(R.drawable.stick_56));
        stiker.add(new Integer(R.drawable.stick_57));
        stiker.add(new Integer(R.drawable.stick_58));
        stiker.add(new Integer(R.drawable.stick_59));
        stiker.add(new Integer(R.drawable.stick_60));
        stiker.add(new Integer(R.drawable.stick_61));
        stiker.add(new Integer(R.drawable.stick_62));
        stiker.add(new Integer(R.drawable.stick_63));
        stiker.add(new Integer(R.drawable.stick_64));
        stiker.add(new Integer(R.drawable.stick_65));
        stiker.add(new Integer(R.drawable.stick_66));

        stiker.add(new Integer(R.drawable.stick_23));
        stiker.add(new Integer(R.drawable.stick_24));
        stiker.add(new Integer(R.drawable.stick_25));
        stiker.add(new Integer(R.drawable.stick_26));
        stiker.add(new Integer(R.drawable.stick_27));
        stiker.add(new Integer(R.drawable.stick_28));
        stiker.add(new Integer(R.drawable.stick_29));
        stiker.add(new Integer(R.drawable.stick_30));
        stiker.add(new Integer(R.drawable.stick_31));
        stiker.add(new Integer(R.drawable.stick_32));
        stiker.add(new Integer(R.drawable.stick_33));
        stiker.add(new Integer(R.drawable.stick_34));
        stiker.add(new Integer(R.drawable.stick_35));
        stiker.add(new Integer(R.drawable.stick_36));

        stiker.add(new Integer(R.drawable.stick_1));
        stiker.add(new Integer(R.drawable.stick_2));
        stiker.add(new Integer(R.drawable.stick_3));
        stiker.add(new Integer(R.drawable.stick_4));
        stiker.add(new Integer(R.drawable.stick_5));
        stiker.add(new Integer(R.drawable.stick_6));
        stiker.add(new Integer(R.drawable.stick_7));
        stiker.add(new Integer(R.drawable.stick_8));
        stiker.add(new Integer(R.drawable.stick_9));
        stiker.add(new Integer(R.drawable.stick_10));
        stiker.add(new Integer(R.drawable.stick_11));

        stiker.add(new Integer(R.drawable.stick_12));
        stiker.add(new Integer(R.drawable.stick_13));
        stiker.add(new Integer(R.drawable.stick_14));
        stiker.add(new Integer(R.drawable.stick_15));
        stiker.add(new Integer(R.drawable.stick_16));
        stiker.add(new Integer(R.drawable.stick_17));
        stiker.add(new Integer(R.drawable.stick_18));
        stiker.add(new Integer(R.drawable.stick_19));
        stiker.add(new Integer(R.drawable.stick_20));
        stiker.add(new Integer(R.drawable.stick_21));
        stiker.add(new Integer(R.drawable.stick_22));



    }
    private void setArrayBeard(){
        beards = new ArrayList<>();
        beards.add(new Integer(R.drawable.santa_beard_1));
        beards.add(new Integer(R.drawable.santa_beard_2));
        beards.add(new Integer(R.drawable.santa_beard_3));
        beards.add(new Integer(R.drawable.santa_beard_4));
        beards.add(new Integer(R.drawable.santa_beard_5));
        beards.add(new Integer(R.drawable.santa_beard_22));
        beards.add(new Integer(R.drawable.santa_beard_23));
        beards.add(new Integer(R.drawable.santa_beard_24));
        beards.add(new Integer(R.drawable.santa_beard_25));
        beards.add(new Integer(R.drawable.santa_beard_27));
        beards.add(new Integer(R.drawable.santa_beard_26));
        beards.add(new Integer(R.drawable.santa_beard_28));
        beards.add(new Integer(R.drawable.santa_beard_29));
        beards.add(new Integer(R.drawable.santa_beard_30));
        beards.add(new Integer(R.drawable.santa_beard_31));
        beards.add(new Integer(R.drawable.santa_beard_32));
        beards.add(new Integer(R.drawable.santa_beard_33));


    }

    private void  setBeardList(){
        setArrayBeard();
        BeardAdapter = new StickerAdapter(this, beards);
        list_beard.setAdapter(BeardAdapter);
        list_beard.setOnItemClickListener(new it.sephiroth.android.library.widget.AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(it.sephiroth.android.library.widget.AdapterView<?> parent, View view, int position, long id) {
                BeardView = new StickerView(Image_Edit_Screen.this);
                BeardId = beards.get(position);
                BeardView.setImageResource(BeardId);
                bread_image.add(BeardView);
                MainTuchimage = new StickerImageView(Image_Edit_Screen.this);
                MainTuchimage.setImageResource(BeardId);
                MainTuchimageArray.add(MainTuchimage);
//                BeardView.setOperationListener(new StickerView.OperationListener() {
//                    @Override
//                    public void onDeleteClick() {
//                        if(bread_image.size() >0){
//                            mViews.remove(bread_image.get(bread_image.size()-1));
//                            main_frame.removeView(bread_image.get(bread_image.size()-1));
//                            bread_image.remove(bread_image.size()-1);
//                        }
////                        mViews.remove(stickerView);
////                        main_frame.removeView(stickerView);
//                    }
//
//                    @Override
//                    public void onEdit(StickerView stickerView) {
//                        mCurrentView.setInEdit(false);
//                        mCurrentView = stickerView;
//                        mCurrentView.setInEdit(true);
//                    }
//
//                    @Override
//                    public void onTop(StickerView stickerView) {
//                        int position = mViews.indexOf(stickerView);
//                        if (position == mViews.size() - 1) {
//                            return;
//                        }
//                        StickerView stickerTemp = (StickerView) mViews.remove(position);
//                        mViews.add(mViews.size(), stickerTemp);
//                    }
//                });
                FrameLayout.LayoutParams lp = new FrameLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
                main_frame.addView(MainTuchimageArray.get(MainTuchimageArray.size()-1));
//                mViews.add(BeardView);
//                setCurrentEdit(BeardView);
                Log.e("mViews.size", String.valueOf(mViews.size()));
            }
        });

    }
    private void  setCapList(){
        setArrayCap();
        CapAdapter = new StickerAdapter(this, caps);
        list_cap.setAdapter(CapAdapter);
        list_cap.setOnItemClickListener(new it.sephiroth.android.library.widget.AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(it.sephiroth.android.library.widget.AdapterView<?> parent, View view, int position, long id) {
                CapView = new StickerView(Image_Edit_Screen.this);
                capId = caps.get(position);
                CapView.setImageResource(capId);
                cap_image.add(CapView);
                MainTuchimage = new StickerImageView(Image_Edit_Screen.this);
                 MainTuchimage.setImageResource(capId);
                MainTuchimageArray.add(MainTuchimage);

//                CapView.setOperationListener(new StickerView.OperationListener() {
//                    @Override
//                    public void onDeleteClick() {
////                        if(cap_image.size() >0){
////                            mViews.remove(cap_image.get(cap_image.size()-1));
////                            main_frame.removeView(cap_image.get(cap_image.size()-1));
////                            cap_image.remove(cap_image.size()-1);
////                        }
//                        mViews.remove(CapView);
//                        main_frame.removeView(CapView);
//                    }
//
//                    @Override
//                    public void onEdit(StickerView stickerView) {
//                        mCurrentView.setInEdit(false);
//                        mCurrentView = stickerView;
//                        mCurrentView.setInEdit(true);
//                    }
//
//                    @Override
//                    public void onTop(StickerView stickerView) {
//                        int position = mViews.indexOf(stickerView);
//                        if (position == mViews.size() - 1) {
//                            return;
//                        }
//                        StickerView stickerTemp = (StickerView) mViews.remove(position);
//                        mViews.add(mViews.size(), stickerTemp);
//                    }
//                });
                FrameLayout.LayoutParams lp = new FrameLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
                main_frame.addView(MainTuchimageArray.get(MainTuchimageArray.size()-1));
                mViews.add(MainTuchimageArray.get(MainTuchimageArray.size()-1));
                Log.e("mViews.size", String.valueOf(mViews.size()));
            }
        });

    }
    private void hidebordercap(){
        int i=0;
        while(i<MainTuchimageArray.size()){
            MainTuchimageArray.get(i).setControlItemsHidden(true);
            i++;
        }
    }

    private void hidetext(){
        int i=0;
        while(i<MainArraytext.size()){
            MainArraytext.get(i).setControlItemsHidden(true);
            i++;
        }

    }
    private void setArrayCap(){
        caps = new ArrayList<>();
        caps.add(new Integer(R.drawable.cap1));
        caps.add(new Integer(R.drawable.cap2));
        caps.add(new Integer(R.drawable.cap3));
        caps.add(new Integer(R.drawable.cap4));
        caps.add(new Integer(R.drawable.cap5));
        caps.add(new Integer(R.drawable.cap6));
        caps.add(new Integer(R.drawable.cap7));
        caps.add(new Integer(R.drawable.cap8));
        caps.add(new Integer(R.drawable.cap9));
        caps.add(new Integer(R.drawable.cap10));
        caps.add(new Integer(R.drawable.cap11));
        caps.add(new Integer(R.drawable.cap12));
        caps.add(new Integer(R.drawable.cap13));
        caps.add(new Integer(R.drawable.cap14));
        caps.add(new Integer(R.drawable.cap15));
        caps.add(new Integer(R.drawable.santa_beard_34));
        caps.add(new Integer(R.drawable.santa_beard_35));
        caps.add(new Integer(R.drawable.santa_beard_36));
        caps.add(new Integer(R.drawable.santa_beard_37));
        caps.add(new Integer(R.drawable.santa_beard_38));
        caps.add(new Integer(R.drawable.santa_beard_39));
        caps.add(new Integer(R.drawable.santa_beard_40));
        caps.add(new Integer(R.drawable.santa_beard_41));
        caps.add(new Integer(R.drawable.santa_beard_42));
        caps.add(new Integer(R.drawable.santa_beard_43));
        caps.add(new Integer(R.drawable.santa_beard_44));

    }
    private void setArraylistforeffect() {
        effects = new ArrayList<>();
        //for effectlayout
        effects.add(new EffectModel(R.drawable.none, R.drawable.trans));
        effects.add(new EffectModel(R.drawable.t1, R.drawable.o_1));
        effects.add(new EffectModel(R.drawable.t2, R.drawable.o_2));
        effects.add(new EffectModel(R.drawable.t3, R.drawable.o_3));
        effects.add(new EffectModel(R.drawable.t4, R.drawable.o_4));
        effects.add(new EffectModel(R.drawable.t5, R.drawable.o_5));
        effects.add(new EffectModel(R.drawable.t6, R.drawable.o_6));
        effects.add(new EffectModel(R.drawable.t7, R.drawable.o_7));
        effects.add(new EffectModel(R.drawable.t8, R.drawable.o_8));
        effects.add(new EffectModel(R.drawable.t9, R.drawable.o_9));
        effects.add(new EffectModel(R.drawable.t10, R.drawable.o_10));
        effects.add(new EffectModel(R.drawable.t11, R.drawable.o_11));
        effects.add(new EffectModel(R.drawable.t12, R.drawable.o_12));
        effects.add(new EffectModel(R.drawable.t13, R.drawable.o_13));
        effects.add(new EffectModel(R.drawable.t14, R.drawable.o_14));
        effects.add(new EffectModel(R.drawable.t15, R.drawable.o_15));

        EffectAdapter effectadepter1 = new EffectAdapter(Image_Edit_Screen.this, effects);
        list_effect.setAdapter(effectadepter1);
    }

    private Bitmap getbitmap(View view) {
        view.setPadding(10,10,10,10);
        Bitmap createBitmap = Bitmap.createBitmap(view.getWidth(), view.getHeight(), Bitmap.Config.ARGB_8888);
        view.draw(new Canvas(createBitmap));
        createBitmap = CropBitmapTransparency(createBitmap);
        return createBitmap;

    }

    private void addStickerView(TextView str) {
        StickerTextView MainTuchimagetext = new StickerTextView(this);
//        MainTuchimage.setImageBitmap(finalBitmapText);

        MainTuchimagetext.setText((String) str.getText(),str.getCurrentTextColor(),str.getTypeface());
        MainArraytext.add(MainTuchimagetext);
//        MainTuchimagetext.
 //        MainS
//        stickerView.setBitmap(finalBitmapText);
////        ImageView TextSet = new ImageView(Image_Edit_Screen.this);
////        TextSet.setImageBitmap(finalBitmapText);
////        TextSet.hig
////        FrameLayout.LayoutParams lp = new FrameLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
////        main_frame.addView(TextSet, lp);
////        mViews.add(TextSet);
////        TextSet.getLayoutParams().width = 150;
////        TextSet.getLayoutParams().height = 50;
////        TextSet.setOnTouchListener(new FrontOnMultitouch());
//        stickerView.setOperationListener(new StickerView.OperationListener() {
//            @Override
//            public void onDeleteClick() {
////                mViews.remove(stickerView);
////                main_frame.removeView(stickerView);
//            }
//
//            @Override
//            public void onEdit(StickerView stickerView) {
//                mCurrentView.setInEdit(false);
//                mCurrentView = stickerView;
//                mCurrentView.setInEdit(true);
//            }
//
//            @Override
//            public void onTop(StickerView stickerView) {
//                int position = mViews.indexOf(stickerView);
//                if (position == mViews.size() - 1) {
//                    return;
//                }
//                StickerView stickerTemp = (StickerView) mViews.remove(position);
//                mViews.add(mViews.size(), stickerTemp);
//            }
//        });
        FrameLayout.LayoutParams lp = new FrameLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
        main_frame.addView(MainArraytext.get(MainArraytext.size()-1));
//        mViews.add(setimage);
        Log.e("mViews.size1", String.valueOf(mViews.size()));
//        setCurrentEdit(stickerView);

    }

    private void colordailog() {

        ColorPickerDialogBuilder
                .with(Image_Edit_Screen.this)
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
                        image_overlay.setImageBitmap(null);
                        image_overlay.setBackgroundColor(selectedColor);
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


    public class TextDailog extends Dialog implements View.OnClickListener {

        private Spinner spinnerFont;
        ArrayList<Typeface> fontList;
        private TextView ed_done;
        private LinearLayout ll_Editlayer;

        private ImageView dailog_close, colorpic;

        public String etData;
        private FontList_Adapter adapterFont;
        private TextView btn;
        private RadioGroup mRG;
        private int mWidth;
        private int mHeight;
        private GradientManager mGradientManager;
        private Random mRandom = new Random();
        private Shader shader;
        private ImageView edittxt;
        private FrameLayout FLText;

        private ArrayList<View> mViews = new ArrayList<>();
        private StickerView mCurrentView;
        private LinearLayout setdata;

        private SeekBar size;
        int textSize = 30;
        public Activity activity;

        public TextDailog(Activity activity) {
            super(activity);
            this.activity = activity;
        }


        protected void onCreate(Bundle bundle) {
            super.onCreate(bundle);
            requestWindowFeature(1);
            setContentView(R.layout.textsetter);
            ET_text = (EditText) findViewById(R.id.ET_text);
            ll_Editlayer = (LinearLayout) findViewById(R.id.ll_Editlayer);
            ed_done = (TextView) findViewById(R.id.ed_done);
            ed_done.setOnClickListener(this);
            TV_Text = (TextView) findViewById(R.id.TV_Text);
            dailog_close = (ImageView) findViewById(R.id.dailog_close);
            colorpic = (ImageView) findViewById(R.id.colorpic);
            dailog_close.setOnClickListener(this);
            colorpic.setOnClickListener(this);
            edittxt = (ImageView) findViewById(R.id.edittxt);
            edittxt.setOnClickListener(this);
            btn = (TextView) findViewById(R.id.btn);
            FLText = (FrameLayout) findViewById(R.id.FLText);
            setdata = (LinearLayout) findViewById(R.id.setdata);
            setFontListForGrid();
            spinnerFont = (Spinner) findViewById(R.id.spinnerFont);
            adapterFont = new FontList_Adapter(activity, fontList, "Font");
            spinnerFont.setAdapter(adapterFont);
            spinnerFont.setOnItemSelectedListener(new AdapterView.OnItemSelectedListener() {
                @Override
                public void onItemSelected(AdapterView<?> adapterView, View view, int i, long l) {
                    if (i == 0) {
                        TV_Text.setTypeface(FontFace.f3(activity));
                    } else if (i == 1) {
                        TV_Text.setTypeface(FontFace.f4(activity));
                    } else if (i == 2) {
                        TV_Text.setTypeface(FontFace.f5(activity));
                    } else if (i == 3) {
                        TV_Text.setTypeface(FontFace.f6(activity));
                    } else if (i == 4) {
                        TV_Text.setTypeface(FontFace.f16(activity));
                    } else if (i == 5) {
                        TV_Text.setTypeface(FontFace.f18(activity));
                    } else if (i == 6) {
                        TV_Text.setTypeface(FontFace.f19(activity));
                    } else if (i == 7) {
                        TV_Text.setTypeface(FontFace.f20(activity));
                    } else if (i == 8) {
                        TV_Text.setTypeface(FontFace.f24(activity));
                    } else if (i == 9) {
                        TV_Text.setTypeface(FontFace.f26(activity));
                    } else if (i == 10) {
                        TV_Text.setTypeface(FontFace.f28(activity));
                    }
                }

                @Override
                public void onNothingSelected(AdapterView<?> adapterView) {

                }
            });
            etData = TV_Text.getText().toString();
            size = (SeekBar) findViewById(R.id.size);
            size.setMax(70);
            size.setProgress(30);
            size.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
                @Override
                public void onProgressChanged(SeekBar seekBar, int i, boolean b) {
                    textSize = i;
                    TV_Text.setTextSize(textSize);
                }

                @Override
                public void onStartTrackingTouch(SeekBar seekBar) {

                }

                @Override
                public void onStopTrackingTouch(SeekBar seekBar) {

                }
            });



        }


        @Override
        public void onClick(View view) {
            switch (view.getId()) {
                case R.id.ed_done:

                    if (ET_text.getText().toString().isEmpty()) {
                        ET_text.setError("Please Enter Text");
                    } else {
                        InputMethodManager imm = (InputMethodManager) activity.getSystemService(Context.INPUT_METHOD_SERVICE);
                        imm.hideSoftInputFromWindow(ed_done.getWindowToken(), InputMethodManager.RESULT_UNCHANGED_SHOWN);

                        ll_Editlayer.setVisibility(View.GONE);
                        dailog_close.setVisibility(View.VISIBLE);
                        setdata.setVisibility(View.VISIBLE);
                        getDataText();
                    }
                    return;
                case R.id.colorpic:
                    if (TV_Text.getText().toString().isEmpty()) {
                        Toast.makeText(activity, "Text Is Not Found, Please Insert Text First.", Toast.LENGTH_LONG);
                    } else {
                        ColorPickerDialogBuilder
                                .with(Image_Edit_Screen.this)
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
//                                        image_overlay.setImageBitmap(null);
                                        TV_Text.setTextColor(selectedColor);
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
//                        colordailog();
                    }
                    return;
                case R.id.edittxt:
                    ll_Editlayer.setVisibility(View.VISIBLE);
                    dailog_close.setVisibility(View.GONE);
                    setdata.setVisibility(View.GONE);
                    return;
                case R.id.dailog_close:
                    finalBitmapText = getbitmap(FLText);
                    addStickerView(TV_Text);
                    dismiss();
            }

        }


        private void setFontListForGrid() {
            fontList = new ArrayList<>();
            fontList.add(FontFace.f3(getApplicationContext()));
            fontList.add(FontFace.f4(getApplicationContext()));
            fontList.add(FontFace.f5(getApplicationContext()));
            fontList.add(FontFace.f6(getApplicationContext()));
            fontList.add(FontFace.f16(getApplicationContext()));
            fontList.add(FontFace.f18(getApplicationContext()));
            fontList.add(FontFace.f19(getApplicationContext()));
            fontList.add(FontFace.f20(getApplicationContext()));
            fontList.add(FontFace.f24(getApplicationContext()));
            fontList.add(FontFace.f26(getApplicationContext()));
            fontList.add(FontFace.f28(getApplicationContext()));
        }
    }


}

