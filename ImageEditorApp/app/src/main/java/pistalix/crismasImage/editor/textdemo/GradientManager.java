package pistalix.crismasImage.editor.textdemo;

import android.content.Context;
import android.graphics.Color;
import android.graphics.LinearGradient;
import android.graphics.Point;
import android.graphics.RadialGradient;
import android.graphics.Shader;
import android.graphics.SweepGradient;

import java.util.Random;

/**
 * Created by Androtech on 3/22/2017.
 */

public class GradientManager {

    private Random mRandom = new Random();
    private Context mContext;
    private Point mSize;

    public GradientManager(Context context, Point size) {
        this.mContext = context;
        this.mSize = size;
    }

    public LinearGradient getRandomLinearGradient() {

        LinearGradient gradient = new LinearGradient(
                0,
                0,
                mSize.x,
                mSize.y,
                getRandomColorArray(), // Colors to draw the gradient
                null, // No position defined
                getRandomShaderTileMode() // Shader tiling mode
        );
        return gradient;
    }

    public RadialGradient getRandomRadialGradient() {
        RadialGradient gradient = new RadialGradient(
                mRandom.nextInt(mSize.x),
                mRandom.nextInt(mSize.y),
                mRandom.nextInt(mSize.x),
                getRandomColorArray(),
                null, // Stops position is undefined
                getRandomShaderTileMode() // Shader tiling mode

        );
        return gradient;
    }

    public SweepGradient getRandomSweepGradient() {
        SweepGradient gradient = new SweepGradient(
                mRandom.nextInt(mSize.x),
                mRandom.nextInt(mSize.y),
                getRandomColorArray(), // Colors to draw gradient
                null // Position is undefined
        );
        return gradient;
    }

    protected Shader.TileMode getRandomShaderTileMode() {
        Shader.TileMode mode;
        int indicator = mRandom.nextInt(3);
        if (indicator == 0) {
            mode = Shader.TileMode.CLAMP;
        } else if (indicator == 1) {
            mode = Shader.TileMode.MIRROR;
        } else {
            mode = Shader.TileMode.REPEAT;
        }
        return mode;
    }

    protected int[] getRandomColorArray() {
        int length = mRandom.nextInt(16 - 3) + 3;
        int[] colors = new int[length];
        for (int i = 0; i < length; i++) {
            colors[i] = getRandomHSVColor();
        }
        return colors;
    }

    protected int getRandomHSVColor() {
        int hue = mRandom.nextInt(361);
        float saturation = 1.0f;
        float value = 1.0f;
        int alpha = 255;
        int color = Color.HSVToColor(alpha, new float[]{hue, saturation, value});
        return color;
    }
}
