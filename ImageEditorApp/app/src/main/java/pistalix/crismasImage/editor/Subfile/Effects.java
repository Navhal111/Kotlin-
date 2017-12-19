package pistalix.crismasImage.editor.Subfile;

import android.graphics.ColorFilter;
import android.graphics.ColorMatrix;
import android.graphics.ColorMatrixColorFilter;
import android.widget.ImageView;

public class Effects {

    public static ImageView applyEffectNone(ImageView img) {

        //grayscale
        float[] colorMatrix = {
                1, 0, 0, 0, 0,
                0, 1, 0, 0, 0,
                0, 0, 1, 0, 0,
                0, 0, 0, 1, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);

        img.setColorFilter(cf);
        return img;

    }

    public static ImageView applyEffect1(ImageView img) {

        //grayscale
        float[] colorMatrix = {
                0.213f, 0.715f, 0.072f, 0, 0,
                0.213f, 0.715f, 0.072f, 0, 0,
                0.213f, 0.715f, 0.072f, 0, 0,
                0, 0, 0, 1, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;

    }

    public static ImageView applyEffect2(ImageView img) {

        //sepia
        ColorFilter cf = new ColorMatrixColorFilter(getColorMatrixSepia());
        img.setColorFilter(cf);
        return img;
    }

    private static ColorMatrix getColorMatrixSepia() {
        ColorMatrix colorMatrix = new ColorMatrix();
        colorMatrix.setSaturation(0);
        ColorMatrix colorScale = new ColorMatrix();
        colorScale.setScale(1, 1, 0.8f, 1);
        // Convert to grayscale, then apply brown color
        colorMatrix.postConcat(colorScale);

        return colorMatrix;
    }

//    public static ImageView applyEffect3(ImageView img) {
//
//        float[] colorMatrix = {
//                -1.0f, 0, 0, 0, 255, //red
//                0, -1.0f, 0, 0, 255, //green
//                0, 0, -1.0f, 0, 255, //blue
//                0, 0, 0, 1.0f, 0 //alpha
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }
//
//    public static ImageView applyEffect4(ImageView img) {
//
//        float[] colorMatrix = {
//                1, 0, 0, 0, -90,
//                0, 1, 0, 0, -90,
//                0, 0, 1, 0, -90,
//                0, 0, 0, 1, 0
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }

    public static ImageView applyEffect3(ImageView img) {

        float[] colorMatrix = {
                0.5f, 0, 0, 0, 0,
                0, 0.2f, 0, 0, 0,
                0, 0, -1.8f, 0, 0,
                -1f, 0, 0, 1, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect4(ImageView img) {

        float scale = 2.f + 1.2f;
        float translate = (-.5f * scale + .5f) * 255.f;
        float[] colorMatrix = {
                scale, 0, 0, 0, translate,
                0, scale, 0, 0, translate,
                0, 0, scale, 0, translate,
                0, 0, 0, 1, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect5(ImageView img) {

        float scale = 0.5f + 1.f;
        float translate = (-.5f * scale + .5f) * 255.f;
        float[] colorMatrix = {
                scale, 0, 0, 0, translate,
                0, scale, 0, 0, translate,
                0, 0, scale, 0, translate,
                0, 0, 0, 1, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect6(ImageView img) {

        float[] colorMatrix = {
                2, 0, 0, 0, 0,
                0, 2, 0, 0, 0,
                0, 0, 2, 0, 0,
                0, 0, 0, 0.5f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect7(ImageView img) {

        float[] colorMatrix = {
                2, 0, 0, 0, 0,
                0, 2, 0, 0, 0,
                0, 0, 2, 0, 0,
                0, 0, 0, 1, 0,
                -0.1f, -0.1f, -0.1f, 0, 1
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect8(ImageView img) {

        float[] colorMatrix = {
                0, 0, 0, 0, 60,
                0, 0, 0, 0, 60,
                0, 0, 0, 0, 90,
                -0.213f, -0.715f, -0.072f, 0, 255
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

//    public static ImageView applyEffect11(ImageView img) {
//
//        float[] colorMatrix = {
//                1.0703125f, 0, 0, 0, 10,
//                0, 1.453125f, 0, 0, 10,
//                0, 0, 1.328125f, 0, -60,
//                0, 0, 0, 1.7109375f, 0
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }
//
//    public static ImageView applyEffect12(ImageView img) {
//
//        float[] colorMatrix = {
//                1.4296875f, 0, 0, 0, -60,
//                0, 1.5078125f, 0, 0, -60,
//                0, 0, 1.3515625f, 0, -60,
//                0, 0, 0, 1.078125f, 0
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }

    public static ImageView applyEffect9(ImageView img) {
        float[] colorMatrix = {
                1, 0, 0, 0, -60,
                0, 1, 0, 0, -60,
                0, 0, 1, 0, -90,
                0, 0, 0, 1, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect10(ImageView img) {

        float[] colorMatrix = {
                1, 0, 0, 0, -60,
                0, 1, 0, 0, -60,
                0, 0, 1, 0, -90,
                0.213f, 0.715f, 0.072f, 0, 255
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect11(ImageView img) {

        float[] colorMatrix = {
                1, 0, 0, 0, -60,
                0, 1, 0, 0, -60,
                0, 0, 1, 0, -90,
                -0.213f, -0.715f, -0.072f, 0, 255
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

//    public static ImageView applyEffect16(ImageView img) {
//
//        float[] colorMatrix = {
//                1, 0, 0, 0, 60,
//                0, 1, 0, 0, 60,
//                0, 0, 1, 0, 90,
//                -0.213f, -0.715f, -0.072f, 0, 255
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }

    public static ImageView applyEffect12(ImageView img) {

        float[] colorMatrix = {
                1, 0, 0, 0, 0,
                0, 1, 0, 0, 0,
                0, 0, 1, 0, 90,
                0.213f, 0.715f, 0.072f, 0, 255
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

//    public static ImageView applyEffect18(ImageView img) {
//
//        float[] colorMatrix = {
//                192, 0, 0, 0, 0,
//                0, 192, 0, 0, 0,
//                0, 0, 192, 0, 0,
//                0, 0, 0, 1, 0
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }

    public static ImageView applyEffect13(ImageView img) {

        float[] colorMatrix = {
                0, 0, 0, 0, 0,
                0, 0, 0, 0, 0,
                0, 0, 0, 0, 0,
                1, 0, 0, 0, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

//    public static ImageView applyEffect20(ImageView img) {
//
//        float[] colorMatrix = {
//                1, 0, 0, 1, 253,
//                0, 0, 0, 1, 253,
//                1, 0, 0, 1, 253,
//                0, 0, 0, 1, 253
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }

    public static ImageView applyEffect14(ImageView img) {

        float[] colorMatrix = {
                1.375f, 0, 0, 0, 0,
                0, 1.390625f, 0, 0, 0,
                0, 0, 1.1640625f, 0, 0,
                0, 0, 0, 1.6796875f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect15(ImageView img) {

        float[] colorMatrix = {
                1.5234375f, 0, 0, 0, 0,
                0, 1.203125f, 0, 0, 0,
                0, 0, 1.015625f, 0, 0,
                0, 0, 0, 1.28125f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

//    public static ImageView applyEffect23(ImageView img) {
//
//        float[] colorMatrix = {
//                1.09375f, 0, 0, 0, 0,
//                0, 1.59375f, 0, 0, 0,
//                0, 0, 0.3984375f, 0, 0,
//                0, 0, 0, 1.67968f, 0
//        };
//        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
//        img.setColorFilter(cf);
//        return img;
//    }

    public static ImageView applyEffect16(ImageView img) {

        float[] colorMatrix = {
                1.0390625f, 0, 0, 0, 0,
                0, 1.3671875f, 0, 0, 0,
                0, 0, 1.5f, 0, 0,
                0, 0, 0, 1.4921875f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect17(ImageView img) {

        float[] colorMatrix = {
                1.5625f, 0, 0, 0, 0,
                0, 1.565625f, 0, 0, 0,
                0, 0, 1.0f, 0, 0,
                0, 0, 0, 1.5234375f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect18(ImageView img) {

        float[] colorMatrix = {
                0.9609375f, 0, 0, 0, 0,
                0, 1.6171875f, 0, 0, 0,
                0, 0, 1.40625f, 0, 0,
                0, 0, 0, 0.8046875f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect19(ImageView img) {


        float[] colorMatrix = {
                0.7109375f, 0, 0, 0, 0,
                0, 1.34375f, 0, 0, 0,
                0, 0, 1.35625f, 0, 0,
                0, 0, 0, 1.9921875f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect20(ImageView img) {

        float[] colorMatrix = {
                1.0703125f, 0, 0, 0, 0,
                0, 1.25f, 0, 0, 0,
                0, 0, 1.140625f, 0, 0,
                0, 0, 0, 1.4140625f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect21(ImageView img) {

        float[] colorMatrix = {
                1.1953125f, 0, 0, 0, 0,
                0, 0.671875f, 0, 0, 0,
                0, 0, 0.3984375f, 0, 0,
                0, 0, 0, 0.7265625f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

    public static ImageView applyEffect22(ImageView img) {

        float[] colorMatrix = {
                1.1953125f, 0, 0, 0, 0,
                0, 0.671875f, 0, 0, 0,
                0, 0, 0.3984375f, 0, 0,
                0, 0, 0, 1.8671875f, 0
        };
        ColorFilter cf = new ColorMatrixColorFilter(colorMatrix);
        img.setColorFilter(cf);
        return img;
    }

}
