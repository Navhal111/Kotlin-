package status.coummen.pistalix.facedemo.Activity;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;


import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.Point;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.support.v7.app.AppCompatActivity;
import android.util.SparseArray;
import android.widget.ImageView;
import android.widget.TextView;


import com.google.android.gms.vision.Frame;
import com.google.android.gms.vision.face.Face;
import com.google.android.gms.vision.face.FaceDetector;
import com.isseiaoki.simplecropview.CropImageView;
import com.tzutalin.dlib.Constants;
import com.tzutalin.dlib.FaceDet;
import com.tzutalin.dlib.VisionDetRet;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.RandomAccessFile;
import java.nio.MappedByteBuffer;
import java.nio.channels.FileChannel;
import java.util.ArrayList;
import java.util.List;

import status.coummen.pistalix.facedemo.R;


public class CropActivity extends AppCompatActivity {
    private CropImageView mCropView;
    private TextView tv_free, tv_one11, tv_fitImage, tv_34, tv_43, tv_916, tv_169, tv_custom, tv_circle, tv_squrecircle;
    private ImageView iv_free, iv_one11, iv_fitImage, iv_34, iv_43, iv_916, iv_169, iv_custom, iv_circle, iv_squrecircle;
    private Uri selectedImage;
    private Bitmap OriginalImages;
    private Uri savedImageUri;
    private ProgressDialog progressDialog;
    private Canvas canvas;
    private Paint paint = new Paint();
    private FaceDetector detector;
    Bitmap editedBitmap;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_crop);
        mCropView = (CropImageView) findViewById(R.id.cropImageView);
        mCropView.setCropMode(CropImageView.CropMode.SQUARE);
        selectedImage = Uri.parse(getIntent().getStringExtra("image_Uri"));
        InputStream image_stream = null;
        try {
            image_stream = getContentResolver().openInputStream(this.selectedImage);
        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
        detector = new FaceDetector.Builder(getApplicationContext())
                .setTrackingEnabled(false)
                .setLandmarkType(FaceDetector.ALL_LANDMARKS)
                .setClassificationType(FaceDetector.ALL_CLASSIFICATIONS)
                .build();
        BitmapFactory.Options opt = new BitmapFactory.Options();
        opt.inMutable = true;
        OriginalImages = BitmapFactory.decodeStream(image_stream);
        editedBitmap = Bitmap.createBitmap(OriginalImages.getWidth(), OriginalImages
                .getHeight(), OriginalImages.getConfig());
        canvas = new Canvas(editedBitmap);
        Frame frame = new Frame.Builder().setBitmap(editedBitmap).build();
        SparseArray<Face> faces = detector.detect(frame);

        mCropView.setImageBitmap(editedBitmap);
    }

    public static Bitmap convertToMutable(Bitmap imgIn) {
        try {
            //this is the file going to use temporally to save the bytes.
            // This file will not be a image, it will store the raw image data.
            File file = new File(Environment.getExternalStorageDirectory() + File.separator + "temp.tmp");

            //Open an RandomAccessFile
            //Make sure you have added uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE"
            //into AndroidManifest.xml file
            RandomAccessFile randomAccessFile = new RandomAccessFile(file, "rw");

            // get the width and height of the source bitmap.
            int width = imgIn.getWidth();
            int height = imgIn.getHeight();
            Bitmap.Config type = imgIn.getConfig();

            //Copy the byte to the file
            //Assume source bitmap loaded using options.inPreferredConfig = Config.ARGB_8888;
            FileChannel channel = randomAccessFile.getChannel();
            MappedByteBuffer map = channel.map(FileChannel.MapMode.READ_WRITE, 0, imgIn.getRowBytes()*height);
            imgIn.copyPixelsToBuffer(map);
            //recycle the source bitmap, this will be no longer used.
            imgIn.recycle();
            System.gc();// try to force the bytes from the imgIn to be released

            //Create a new bitmap to load the bitmap again. Probably the memory will be available.
            imgIn = Bitmap.createBitmap(width, height, type);
            map.position(0);
            //load it back from temporary
            imgIn.copyPixelsFromBuffer(map);
            //close the temporary file and channel , then delete that also
            channel.close();
            randomAccessFile.close();

            // delete the temp file
            file.delete();

        } catch (FileNotFoundException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }

        return imgIn;
    }

}
