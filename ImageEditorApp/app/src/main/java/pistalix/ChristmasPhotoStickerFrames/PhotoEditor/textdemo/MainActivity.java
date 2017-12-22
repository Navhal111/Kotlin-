package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.EmbossMaskFilter;
import android.graphics.Point;
import android.graphics.Shader;
import android.graphics.Typeface;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioGroup;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import com.flask.colorpicker.ColorPickerView;
import com.flask.colorpicker.OnColorSelectedListener;
import com.flask.colorpicker.builder.ColorPickerClickListener;
import com.flask.colorpicker.builder.ColorPickerDialogBuilder;

import java.util.ArrayList;
import java.util.Random;

import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;



public class MainActivity extends AppCompatActivity implements View.OnClickListener {

    private ImageView iv_Text;
    private TextDailog textdailog;


    private EditText ET_text;
    private Spinner spinnerFont;
    ArrayList<Typeface> fontList;
    private TextView ed_done;
    private LinearLayout ll_Editlayer;
    public String str;
    private ImageView dailog_close, colorpic;
    private TextView TV_Text;
    public String etData;
    private FontList_Adapter adapterFont;
    private int currentBackgroundColor = 0xffffffff;
    private TextView btn;
    private RadioGroup mRG;
    private int mWidth;
    private int mHeight;
    private GradientManager mGradientManager;
    private Random mRandom = new Random();
    private Shader shader;
    private ImageView edittxt;
    private FrameLayout FLText;
    public Bitmap finalBitmapText;
    private ArrayList<View> mViews = new ArrayList<>();
    private FrameLayout mainFrame;
    private StickerView mCurrentView;
    private RelativeLayout mContentRootView;
    private LinearLayout setdata;
    private SeekBar size;
    int textSize = 30;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_mainn);
        Bind();

    }

    private void Bind() {
        mainFrame = (FrameLayout) findViewById(R.id.mainFrame);
        mainFrame.setOnClickListener(this);
        iv_Text = (ImageView) findViewById(R.id.iv_Text);
        iv_Text.setOnClickListener(this);

    }

    @Override
    public void onClick(View view) {
        switch (view.getId()) {
            case R.id.iv_Text:
                textdailog = new TextDailog(this);
                textdailog.getWindow().setBackgroundDrawable(new ColorDrawable(0));
                textdailog.setCanceledOnTouchOutside(true);
                textdailog.show();
                return;
            case R.id.mainFrame:
                onTouch.removeBorder();
                return;

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


    private Bitmap getbitmap(View view) {
        Bitmap createBitmap = Bitmap.createBitmap(view.getWidth(), view.getHeight(), Bitmap.Config.ARGB_8888);
        view.draw(new Canvas(createBitmap));
        createBitmap = CropBitmapTransparency(createBitmap);
        return createBitmap;

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

    private void colordailog() {
        ColorPickerDialogBuilder
                .with(this)
                .initialColor(currentBackgroundColor)
                .wheelType(ColorPickerView.WHEEL_TYPE.CIRCLE)
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
                        // changeBackgroundColor(selectedColor);
                        TV_Text.getPaint().setMaskFilter(null);
                        TV_Text.getPaint().setShader(null);
                        TV_Text.setTextColor(selectedColor);
                        if (allColors != null) {
                            StringBuilder sb = null;

                            for (Integer color : allColors) {
                                if (color == null)
                                    continue;
                                if (sb == null)
                                    sb = new StringBuilder("Color List:");
                                sb.append("\r\n#" + Integer.toHexString(color).toUpperCase());
                            }

                            if (sb != null) {
                            }
                            // Toast.makeText(getApplicationContext(), sb.toString(), Toast.LENGTH_SHORT).show();
                        }
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

    private void getDataText() {
        str = ET_text.getText().toString();
        TV_Text.setText(ET_text.getText().toString());
        ET_text.getText().clear();
    }

    public class TextDailog extends Dialog implements View.OnClickListener {
        public Activity activity;

        public TextDailog(Activity activity) {
            super(activity);
            this.activity = activity;
        }

        protected void onCreate(Bundle bundle) {
            super.onCreate(bundle);
            requestWindowFeature(1);
            setContentView(R.layout.custom_dailog);
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
            mRG = (RadioGroup) findViewById(R.id.rg);
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
            btn.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mWidth = TV_Text.getWidth();
                    mHeight = TV_Text.getHeight();
                    Point size = new Point(mWidth, mHeight);
                    mGradientManager = new GradientManager(activity, size);
                    int indicator = mRandom.nextInt(3);
                    if (indicator == 0) {
                        shader = mGradientManager.getRandomLinearGradient();
                        TV_Text.setText(str);
                    } else if (indicator == 1) {
                        shader = mGradientManager.getRandomRadialGradient();
                        TV_Text.setText(str);
                    } else {
                        shader = mGradientManager.getRandomSweepGradient();
                        TV_Text.setText(str);
                    }
                    TV_Text.setLayerType(View.LAYER_TYPE_SOFTWARE, null);
                    TV_Text.getPaint().setShader(shader);

                }
            });
            mRG.setOnCheckedChangeListener(new RadioGroup.OnCheckedChangeListener() {
                @Override
                public void onCheckedChanged(RadioGroup radioGroup, int i) {
                    if (i == R.id.rb_normal) {
                        TV_Text.getPaint().setMaskFilter(null);
                    } else if (i == R.id.rb_emboss) {
                        EmbossMaskFilter embossFilter = new EmbossMaskFilter(
                                new float[]{1f, 5f, 1f}, // direction of the light source
                                0.8f, // ambient light between 0 to 1
                                8, // specular highlights
                                7f // blur before applying lighting
                        );
                        TV_Text.getPaint().setMaskFilter(embossFilter);
                    } else if (i == R.id.rb_deboss) {
                        EmbossMaskFilter debossFilter = new EmbossMaskFilter(
                                new float[]{0f, -1f, 0.5f}, // direction of the light source
                                0.8f, // ambient light between 0 to 1
                                13, // specular highlights
                                7.0f // blur before applying lighting
                        );
                        TV_Text.getPaint().setMaskFilter(debossFilter);
                    }
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
                        colordailog();
                    }
                    return;
                case R.id.edittxt:
                    ll_Editlayer.setVisibility(View.VISIBLE);
                    dailog_close.setVisibility(View.GONE);
                    setdata.setVisibility(View.GONE);
                    return;
                case R.id.dailog_close:
                    finalBitmapText = getbitmap(FLText);

                    addStickerView();
                    dismiss();

                    return;
            }

        }
    }

    private void addStickerView() {
        final StickerView stickerView = new StickerView(this);
        stickerView.setBitmap(finalBitmapText);
        stickerView.setOperationListener(new StickerView.OperationListener() {
            @Override
            public void onDeleteClick() {
                mViews.remove(stickerView);
                mainFrame.removeView(stickerView);
            }

            @Override
            public void onEdit(StickerView stickerView) {
                mCurrentView.setInEdit(false);
                mCurrentView = stickerView;
                mCurrentView.setInEdit(true);
            }

            @Override
            public void onTop(StickerView stickerView) {
                int position = mViews.indexOf(stickerView);
                if (position == mViews.size() - 1) {
                    return;
                }
                StickerView stickerTemp = (StickerView) mViews.remove(position);
                mViews.add(mViews.size(), stickerTemp);
            }
        });
        FrameLayout.LayoutParams lp = new FrameLayout.LayoutParams(RelativeLayout.LayoutParams.MATCH_PARENT, RelativeLayout.LayoutParams.MATCH_PARENT);
        mainFrame.addView(stickerView, lp);
        mViews.add(stickerView);
        setCurrentEdit(stickerView);
    }

    OnTouch onTouch = new OnTouch() {
        @Override
        public void removeBorder() {
            if (mCurrentView != null) {
                mCurrentView.setInEdit(false);
            }
        }
    };


    private void setCurrentEdit(StickerView stickerView) {
        if (mCurrentView != null) {
            mCurrentView.setInEdit(false);
        }
        mCurrentView = stickerView;
        stickerView.setInEdit(true);
    }

}
