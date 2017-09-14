package com.example.lime.progress;

import java.util.ArrayList;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;

public class MainActivity extends Activity  {

    private ProgressBar firstBar = null;

    private ProgressBar secondBar = null;

    private Button myButton;

    private int i = 0;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);


        ProgressBar progressBar= (ProgressBar)findViewById(R.id.ProgressBar);
        ProgressDrawable bgProgress= new ProgressDrawable(0xdd00ff00,0x4400ff00);
        progressBar.setProgressDrawable(bgProgress);

        ProgressBar progressBar1= (ProgressBar)findViewById(R.id.ProgressBar1);
        ProgressBarDrawable bgProgress1= new ProgressBarDrawable(5);
        progressBar1.setProgressDrawable(bgProgress1);
    }


}