package pistalix.crismasImage.editor.Activity;

import android.app.ProgressDialog;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.TranslateAnimation;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.SeekBar;
import android.widget.TextView;

import pistalix.crismasImage.editor.MyTouch.MultiTouchListener;
import pistalix.crismasImage.editor.R;
import pistalix.crismasImage.editor.Subfile.DrawingView;
import pistalix.crismasImage.editor.Subfile.ImageUtils;


public class EraseActivity extends AppCompatActivity implements View.OnClickListener {
    private ImageView iv_Back, iv_save;
    public LinearLayout iv_Zoom, iv_Restore, ic_Cut, iv_Auto, iv_Manual;
    private RelativeLayout main_rel, inside_cut_lay, outside_cut_lay, offset_seekbar_lay;
    private LinearLayout lay_offset_seek, lay_threshold_seek, lay_lasso_cut;
    private SeekBar offset_seekbar, radius_seekbar, threshold_seekbar, offset_seekbar1;
    private ImageView iv_Redo, iv_Undo;
    public static Bitmap bitmap;
    private String filename;
    private DrawingView dv;
    private DrawingView dv1;
    private ImageView image_restore, image_zoom, image_auto, image_manual;
    private TextView ttrestore, ttzoom, ttauto, tt_manual;
    private FrameLayout bootmlayer;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_erase1);

        Bind();
    }

    private void Bind() {
        iv_Back = (ImageView) findViewById(R.id.iv_Back);
        iv_Back.setOnClickListener(this);
        iv_save = (ImageView) findViewById(R.id.iv_save);
        iv_save.setOnClickListener(this);
        iv_Zoom = (LinearLayout) findViewById(R.id.iv_Zoom);
        iv_Zoom.setOnClickListener(this);
        iv_Restore = (LinearLayout) findViewById(R.id.iv_Restore);
        iv_Restore.setOnClickListener(this);
        iv_Auto = (LinearLayout) findViewById(R.id.iv_Auto);
        iv_Auto.setOnClickListener(this);
        iv_Manual = (LinearLayout) findViewById(R.id.iv_Manual);
        iv_Manual.setOnClickListener(this);
        iv_Redo = (ImageView) findViewById(R.id.iv_Redo);
        iv_Redo.setOnClickListener(this);
        iv_Undo = (ImageView) findViewById(R.id.iv_Undo);
        iv_Undo.setOnClickListener(this);
        lay_offset_seek = (LinearLayout) findViewById(R.id.lay_offset_seek);
        radius_seekbar = (SeekBar) findViewById(R.id.radius_seekbar);
        offset_seekbar = (SeekBar) findViewById(R.id.offset_seekbar);
        lay_threshold_seek = (LinearLayout) findViewById(R.id.lay_threshold_seek);
        threshold_seekbar = (SeekBar) findViewById(R.id.threshold_seekbar);
        offset_seekbar1 = (SeekBar) findViewById(R.id.offset_seekbar1);
        SeekBar.OnSeekBarChangeListener seek = new SeekBar.OnSeekBarChangeListener() {
            public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
                dv.setOffset(progress - 300);

                dv.invalidate();
            }

            public void onStartTrackingTouch(SeekBar seekBar) {
            }

            public void onStopTrackingTouch(SeekBar seekBar) {
            }
        };
        offset_seekbar1.setOnSeekBarChangeListener(seek);
        offset_seekbar.setOnSeekBarChangeListener(seek);

        main_rel = (RelativeLayout) findViewById(R.id.main_rel);
        this.main_rel.post(new Runnable() {
            public void run() {
                importImageFromBitmap(ImageEditActivity.Edit_bit);
            }
        });
        radius_seekbar.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
            public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
                dv.setRadius(progress + 10);
                dv.invalidate();
            }

            public void onStartTrackingTouch(SeekBar seekBar) {
            }

            public void onStopTrackingTouch(SeekBar seekBar) {
            }
        });


        this.threshold_seekbar.setOnSeekBarChangeListener(new SeekBar.OnSeekBarChangeListener() {
            public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
                dv.setThreshold(seekBar.getProgress() + 10);
            }

            public void onStartTrackingTouch(SeekBar seekBar) {
            }

            public void onStopTrackingTouch(SeekBar seekBar) {

            }
        });


        image_restore = (ImageView) findViewById(R.id.image_restore);
        ttrestore = (TextView) findViewById(R.id.ttrestore);
        image_zoom = (ImageView) findViewById(R.id.image_zoom);
        ttzoom = (TextView) findViewById(R.id.ttzoom);
        image_auto = (ImageView) findViewById(R.id.image_auto);
        ttauto = (TextView) findViewById(R.id.ttauto);
        image_manual = (ImageView) findViewById(R.id.image_manual);
        tt_manual = (TextView) findViewById(R.id.tt_manual);
        bootmlayer=(FrameLayout)findViewById(R.id.bootmlayer);
        callcolor();
    }

    private void importImageFromBitmap(Bitmap bit) {
        dv = new DrawingView(this);
        dv1 = new DrawingView(this);
        Bitmap b;
        b = bit;
        b = ImageUtils.resizeBitmap(b, this.main_rel.getWidth(), this.main_rel.getHeight());
        this.dv.setImageBitmap(b);
//        this.dv1.setImageBitmap(getGreenLayerBitmap(b));
        this.dv.enableTouchClear(false);
        this.dv.setMODE(0);
        this.dv.invalidate();
        this.offset_seekbar.setProgress(500);
        this.radius_seekbar.setProgress(18);
        this.threshold_seekbar.setProgress(20);
        RelativeLayout rlp = (RelativeLayout) findViewById(R.id.main_rel_parent);
        FrameLayout.LayoutParams _rootLayoutParams = new FrameLayout.LayoutParams(rlp.getWidth(), rlp.getHeight());
        this.main_rel.removeAllViews();
        this.main_rel.setScaleX(1.0f);
        this.main_rel.setScaleY(1.0f);
        this.main_rel.addView(this.dv1);
        this.main_rel.addView(this.dv);
        rlp.setLayoutParams(_rootLayoutParams);
        this.dv1.setMODE(5);
        this.dv1.enableTouchClear(false);
        this.dv.invalidate();
        this.dv1.setVisibility(8);

    }

    public Bitmap getGreenLayerBitmap(Bitmap b) {
        Bitmap resultBitmap = Bitmap.createBitmap(b.getWidth(), b.getHeight(), b.getConfig());
        Canvas c = new Canvas(resultBitmap);
        Paint p = new Paint();
        p.setColor(-16711936);
        p.setAlpha(90);
        c.drawBitmap(b, 0.0f, 0.0f, null);
        c.drawPaint(p);
        return resultBitmap;
    }

    @Override
    public void onClick(View v) {
        Animation animation = new TranslateAnimation(0.0f, 0.0f, 0.0f, 200.0f);
        Animation translateAnimation = new TranslateAnimation(0.0f, 0.0f, 200.0f, 0.0f);
        switch (v.getId()) {
            case R.id.iv_Back:
                callcolor();
                callvisibility();
                this.dv1.setVisibility(View.GONE);
                finish();
                break;

            case R.id.iv_save:
                callcolor();
                callvisibility();
                ImageEditActivity.Edit_bit = dv.getFinalBitmap();
//                saveBitmap(true);
                this.dv1.setVisibility(View.GONE);
                finish();
                break;

            case R.id.iv_Manual:
                callcolor();
                callvisibility();
                image_manual.setColorFilter(getResources().getColor(R.color.custom_main));
                tt_manual.setTextColor(getResources().getColor(R.color.custom_main));
                this.dv1.setVisibility(View.GONE);
                offset_seekbar.setProgress(this.dv.getOffset() + 300);
                dv.enableTouchClear(true);
                main_rel.setOnTouchListener(null);
                dv.setMODE(1);
                dv.invalidate();
                lay_offset_seek.setVisibility(View.VISIBLE);
                translateAnimation.setDuration(500);
                translateAnimation.setFillAfter(true);
                bootmlayer.startAnimation(translateAnimation);

                break;

            case R.id.iv_Auto:
                callcolor();
                callvisibility();
                image_auto.setColorFilter(getResources().getColor(R.color.custom_main));
                ttauto.setTextColor(getResources().getColor(R.color.custom_main));
                this.dv1.setVisibility(View.GONE);
                offset_seekbar1.setProgress(this.dv.getOffset() + 300);

                lay_threshold_seek.setVisibility(View.VISIBLE);
                translateAnimation.setDuration(500);
                translateAnimation.setFillAfter(true);
                bootmlayer.startAnimation(translateAnimation);
                dv.enableTouchClear(true);
                main_rel.setOnTouchListener(null);
                dv.setMODE(2);
                dv.invalidate();
                break;


            case R.id.iv_Undo:
                final ProgressDialog ringProgressDialog = ProgressDialog.show(this, "", getString(R.string.undoing) + "...", true);
                ringProgressDialog.setCancelable(false);
                new Thread(new Runnable() {
                    public void run() {
                        try {
                            runOnUiThread(new Runnable() {
                                public void run() {
                                    dv.undoChange();
                                }
                            });
                            Thread.sleep(500);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                        ringProgressDialog.dismiss();
                    }
                }).start();

                break;
            case R.id.iv_Redo:
                final ProgressDialog ringProgressDialog1 = ProgressDialog.show(this, "", getString(R.string.redoing) + "...", true);
                ringProgressDialog1.setCancelable(false);
                new Thread(new Runnable() {
                    public void run() {
                        try {
                            runOnUiThread(new Runnable() {
                                public void run() {
                                    dv.redoChange();
                                }
                            });
                            Thread.sleep(500);
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                        ringProgressDialog1.dismiss();
                    }
                }).start();

                break;
            case R.id.iv_Restore:
                callcolor();
                callvisibility();
                image_restore.setColorFilter(getResources().getColor(R.color.custom_main));
                ttrestore.setTextColor(getResources().getColor(R.color.custom_main));
                this.dv1.setVisibility(View.VISIBLE);
                offset_seekbar.setProgress(this.dv.getOffset() + 300);
                lay_offset_seek.setVisibility(View.VISIBLE);
                translateAnimation.setDuration(500);
                translateAnimation.setFillAfter(true);
                bootmlayer.startAnimation(translateAnimation);
                dv.enableTouchClear(true);
                main_rel.setOnTouchListener(null);
                dv.setMODE(4);
                dv.invalidate();

                break;
            case R.id.iv_Zoom:
                callcolor();
                callvisibility();
                image_zoom.setColorFilter(getResources().getColor(R.color.custom_main));
                ttzoom.setTextColor(getResources().getColor(R.color.custom_main));
                this.dv1.setVisibility(View.GONE);
                this.dv.enableTouchClear(false);
                MultiTouchListener touchListener = new MultiTouchListener();
                this.main_rel.setOnTouchListener(touchListener);
//                    setSelected(R.id.iv_Zoom, true);
                this.dv.setMODE(0);
                this.dv.invalidate();

                break;

        }

    }

    private void callvisibility() {
        lay_offset_seek.setVisibility(View.GONE);
        lay_threshold_seek.setVisibility(View.GONE);
    }

    private void callcolor() {
        image_manual.setColorFilter(getResources().getColor(R.color.white));
        tt_manual.setTextColor(getResources().getColor(R.color.white));
        image_auto.setColorFilter(getResources().getColor(R.color.white));
        ttauto.setTextColor(getResources().getColor(R.color.white));
        image_restore.setColorFilter(getResources().getColor(R.color.white));
        ttrestore.setTextColor(getResources().getColor(R.color.white));
        image_zoom.setColorFilter(getResources().getColor(R.color.white));
        ttzoom.setTextColor(getResources().getColor(R.color.white));

    }


}
