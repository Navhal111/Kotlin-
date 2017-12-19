package pistalix.crismasImage.editor.Subfile;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.res.Resources;
import android.database.Cursor;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.BitmapFactory.Options;
import android.graphics.Canvas;
import android.graphics.Matrix;
import android.net.Uri;
import android.os.Build.VERSION;
import android.util.Log;

import java.io.FileOutputStream;

public class ImageUtils {
    public static void resampleImageAndSaveToNewLocation(String pathInput, String pathOutput) throws Exception {
        resampleImage(pathInput, 800).compress(Bitmap.CompressFormat.PNG, 100, new FileOutputStream(pathOutput));
    }


//    public static Bitmap getResampleImageBitmap(Bitmap uri, Context context) throws IOException {
//        String pathInput = getRealPathFromURI(uri, context);
//        try {
//            return resampleImage(pathInput, 800);
//        } catch (Exception e) {
//            e.printStackTrace();
//            return Media.getBitmap(context.getContentResolver(), Uri.parse(pathInput));
//        }
//    }

    @SuppressLint({"UseValueOf"})
    public static Bitmap resampleImage(String path, int maxDim) throws Exception {
        Options bfo = new Options();
        bfo.inJustDecodeBounds = true;
        BitmapFactory.decodeFile(path, bfo);
        Options optsDownSample = new Options();
        optsDownSample.inSampleSize = getClosestResampleSize(bfo.outWidth, bfo.outHeight, maxDim);
        Bitmap bmpt = BitmapFactory.decodeFile(path, optsDownSample);
        Matrix m = new Matrix();
        if (bmpt.getWidth() > maxDim || bmpt.getHeight() > maxDim) {
            Options optsScale = getResampling(bmpt.getWidth(), bmpt.getHeight(), maxDim);
            m.postScale(((float) optsScale.outWidth) / ((float) bmpt.getWidth()), ((float) optsScale.outHeight) / ((float) bmpt.getHeight()));
        }
        if (new Integer(VERSION.SDK).intValue() > 4) {
            int rotation = ExifUtils.getExifRotation(path);
            if (rotation != 0) {
                m.postRotate((float) rotation);
            }
        }
        return Bitmap.createBitmap(bmpt, 0, 0, bmpt.getWidth(), bmpt.getHeight(), m, true);
    }

    private static Options getResampling(int cx, int cy, int max) {
        float scaleVal;
        Options bfo = new Options();
        if (cx > cy) {
            scaleVal = ((float) max) / ((float) cx);
        } else if (cy > cx) {
            scaleVal = ((float) max) / ((float) cy);
        } else {
            scaleVal = ((float) max) / ((float) cx);
        }
        bfo.outWidth = (int) ((((float) cx) * scaleVal) + 0.5f);
        bfo.outHeight = (int) ((((float) cy) * scaleVal) + 0.5f);
        return bfo;
    }

    private static int getClosestResampleSize(int cx, int cy, int maxDim) {
        int max = Math.max(cx, cy);
        int resample = 1;
        while (resample < Integer.MAX_VALUE) {
            if (resample * maxDim > max) {
                resample--;
                break;
            }
            resample++;
        }
        return resample > 0 ? resample : 1;
    }

    public static Options getBitmapDims(String path) throws Exception {
        Options bfo = new Options();
        bfo.inJustDecodeBounds = true;
        BitmapFactory.decodeFile(path, bfo);
        return bfo;
    }

    public static String getRealPathFromURI(Uri contentURI, Context context) {
        try {
            Cursor cursor = context.getContentResolver().query(contentURI, null, null, null, null);
            if (cursor == null) {
                return contentURI.getPath();
            }
            cursor.moveToFirst();
            String result = cursor.getString(cursor.getColumnIndex("_data"));
            cursor.close();
            return result;
        } catch (Exception e) {
            e.printStackTrace();
            return contentURI.toString();
        }
    }

    public static Bitmap resizeBitmap(Bitmap bit, int width, int height) {
        float wr = (float) width;
        float hr = (float) height;
        float wd = (float) bit.getWidth();
        float he = (float) bit.getHeight();
        Log.i("testings", wr + "  " + hr + "  and  " + wd + "  " + he);
        float rat1 = wd / he;
        float rat2 = he / wd;
        if (wd > wr) {
            wd = wr;
            he = wd * rat2;
            Log.i("testings", "if (wd > wr) " + wd + "  " + he);
            if (he > hr) {
                he = hr;
                wd = he * rat1;
                Log.i("testings", "  if (he > hr) " + wd + "  " + he);
            } else {
                wd = wr;
                he = wd * rat2;
                Log.i("testings", " in else " + wd + "  " + he);
            }
        } else if (he > hr) {
            he = hr;
            wd = he * rat1;
            Log.i("testings", "  if (he > hr) " + wd + "  " + he);
            if (wd > wr) {
                wd = wr;
                he = wd * rat2;
            } else {
                Log.i("testings", " in else " + wd + "  " + he);
            }
        } else if (rat1 > 0.75f) {
            wd = wr;
            he = wd * rat2;
            Log.i("testings", " if (rat1 > .75f) ");
        } else if (rat2 > 1.5f) {
            he = hr;
            wd = he * rat1;
            Log.i("testings", " if (rat2 > 1.5f) ");
        } else {
            wd = wr;
            he = wd * rat2;
            Log.i("testings", " in else ");
        }
        return Bitmap.createScaledBitmap(bit, (int) wd, (int) he, false);
    }

    public static int dpToPx(Context c, int dp) {
        float f = (float) dp;
        c.getResources();
        return (int) (f * Resources.getSystem().getDisplayMetrics().density);
    }

    public static Bitmap mergelogo(Bitmap bitmap, Bitmap logo) {
        Bitmap result = Bitmap.createBitmap(bitmap.getWidth(), bitmap.getHeight(), bitmap.getConfig());
        float wr = (float) bitmap.getWidth();
        float hr = (float) bitmap.getHeight();
        float wd = (float) logo.getWidth();
        float he = (float) logo.getHeight();
        float rat1 = wd / he;
        float rat2 = he / wd;
        if (wd > wr) {
            wd = wr;
            logo = Bitmap.createScaledBitmap(logo, (int) wd, (int) (wd * rat2), false);
        } else if (he > hr) {
            he = hr;
            logo = Bitmap.createScaledBitmap(logo, (int) (he * rat1), (int) he, false);
        }
        Canvas canvas = new Canvas(result);
        canvas.drawBitmap(bitmap, 0.0f, 0.0f, null);
        canvas.drawBitmap(logo, (float) (bitmap.getWidth() - logo.getWidth()), (float) (bitmap.getHeight() - logo.getHeight()), null);
        return result;
    }

    public static Bitmap CropBitmapTransparency(Bitmap sourceBitmap) {
        int minX = sourceBitmap.getWidth();
        int minY = sourceBitmap.getHeight();
        int maxX = -1;
        int maxY = -1;
        for (int y = 0; y < sourceBitmap.getHeight(); y++) {
            for (int x = 0; x < sourceBitmap.getWidth(); x++) {
                if (((sourceBitmap.getPixel(x, y) >> 24) & 255) > 0) {
                    if (x < minX) {
                        minX = x;
                    }
                    if (x > maxX) {
                        maxX = x;
                    }
                    if (y < minY) {
                        minY = y;
                    }
                    if (y > maxY) {
                        maxY = y;
                    }
                }
            }
        }
        if (maxX < minX || maxY < minY) {
            return null;
        }
        return Bitmap.createBitmap(sourceBitmap, minX, minY, (maxX - minX) + 1, (maxY - minY) + 1);
    }
}