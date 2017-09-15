package com.example.lime.progress;

import java.util.ArrayList;

import android.app.Activity;
import android.graphics.drawable.Drawable;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.SeekBar;

import static android.content.ContentValues.TAG;

public class MainActivity extends Activity  {


    private int i = 0;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        SeekBar progressBar= (SeekBar)findViewById(R.id.ProgressBar);
        ProgressDrawable bgProgress= new ProgressDrawable(0xdd00ff00,0x4400ff00);
        progressBar.setProgressDrawable(bgProgress);
        progressBar.setProgress(20);
        progressBar.setMax(100);
//        LinearLayout ll = new LinearLayout(this);
//        ll.setOrientation(LinearLayout.VERTICAL);
//
//        final ProgressBar pb = new ProgressBar(this, null, android.R.attr.progressBarStyleHorizontal);
//        Drawable d = new Probar();
//        pb.setProgressDrawable(d);
//        pb.setPadding(20, 20, 20, 0);
//        ll.addView(pb);
//
//        SeekBar.OnSeekBarChangeListener l = new SeekBar.OnSeekBarChangeListener() {
//            @Override
//            public void onStopTrackingTouch(SeekBar seekBar) {
//            }
//
//            @Override
//            public void onStartTrackingTouch(SeekBar seekBar) {
//            }
//
//            @Override
//            public void onProgressChanged(SeekBar seekBar, int progress, boolean fromUser) {
//                int newProgress = pb.getMax() * progress / seekBar.getMax();
//                Log.d(TAG, "onProgressChanged " + newProgress);
//                pb.setProgress(newProgress);
//            }
//        };
//
//        int[] maxs = {4, 10, 60, 110};
//        for (int i = 0; i < maxs.length; i++) {
//            SeekBar sb = new SeekBar(this);
//            sb.setMax(maxs[i]);
//            sb.setOnSeekBarChangeListener(l);
//            sb.setPadding(20, 20, 20, 0);
//            ll.addView(sb);
//        }
//
//        setContentView(ll);


    }

}