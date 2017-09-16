package com.example.lime.progress;

import android.graphics.Canvas;
import android.graphics.Color;
import android.graphics.ColorFilter;
import android.graphics.Paint;
import android.graphics.PixelFormat;
import android.graphics.Rect;
import android.graphics.RectF;
import android.graphics.drawable.Drawable;
import android.os.Build;
import android.support.annotation.RequiresApi;


public class ProgressDrawable extends Drawable {

    private static final int NUM_SEGMENTS = 6;
    private final int mForeground;
    private final int mBackground;
    private final Paint mPaint = new Paint();
    private final RectF mSegment = new RectF();

    private int separatorColor1 = Color.parseColor("#cf3812");
    private int separatorColor2 = Color.parseColor("#a7119e");
    private int separatorColor3 = Color.parseColor("#11a735");

    public ProgressDrawable(int fgColor, int bgColor) {
        mForeground = fgColor;
        mBackground = Color.parseColor("#d0d0d0");

    }

    @Override
    protected boolean onLevelChange(int level) {
        invalidateSelf();
        return true;
    }

    @RequiresApi(api = Build.VERSION_CODES.LOLLIPOP)
    @Override
    public void draw(Canvas canvas) {
        float level = getLevel() / 10000f;
        Rect b = getBounds();

        float gapWidth = b.height() / 2f;
        float segmentWidth = (b.width() - (NUM_SEGMENTS - 1) * gapWidth) / NUM_SEGMENTS;
        mSegment.set(0, 0, segmentWidth, b.height());
//        mSegment.set(50, 50, segmentWidth,  b.height());
//
//        canvas.drawRoundRect(mSegment,50,50,mPaint);canvas.drawRoundRect(mSegment, 10, 10, mPaint);
        mPaint.setColor(mForeground);

        for (int i = 0; i < NUM_SEGMENTS; i++) {
            float loLevel = i / (float) NUM_SEGMENTS;
            float hiLevel = (i + 1) / (float) NUM_SEGMENTS;

//            if(i>NUM_SEGMENTS){
//                mPaint.setColor(separatorColor3);
//            }else
            if (i == 0 || i == 1) {
                mPaint.setColor(separatorColor3);
            } else if (i == 2 || i == 3) {
                mPaint.setColor(separatorColor1);
            } else {
                mPaint.setColor(separatorColor2);
            }

//            Debug.e("level", loLevel + " " + level + " " + hiLevel);
            if (loLevel < level && hiLevel < level) {
                if(i==0 || i==5){
                    canvas.drawRoundRect(mSegment,50,50,mPaint);
                }else{
                    canvas.drawRect(mSegment,mPaint);
                }


            } else if (loLevel <= level && level <= hiLevel) {
                float middle = mSegment.left + NUM_SEGMENTS * segmentWidth * (level - loLevel);
                if(i==0 || i==5){

                    canvas.drawRoundRect(mSegment.left, mSegment.top, middle, mSegment.bottom,50,50,mPaint);
                }else{
                    canvas.drawRect(mSegment.left, mSegment.top, middle, mSegment.bottom, mPaint);
                }

                mPaint.setColor(mBackground);
                if(i==0||i==5){
                    canvas.drawRoundRect(middle, mSegment.top, mSegment.right, mSegment.bottom,50,50,mPaint);
                }else{
                    canvas.drawRect(middle, mSegment.top, mSegment.right, mSegment.bottom, mPaint);

                }

            } else {

                mPaint.setColor(mBackground);
                if(i==0 || i==5){
                    canvas.drawRoundRect(mSegment,50,50,mPaint);
                }else{
                    canvas.drawRect(mSegment,mPaint);
                }
            }

            mSegment.offset(mSegment.width() + gapWidth, 0);
        }

    }

    @Override
    public void setAlpha(int alpha) {
    }

    @Override
    public void setColorFilter(ColorFilter cf) {
    }

    @Override
    public int getOpacity() {
        return PixelFormat.TRANSLUCENT;
    }
}