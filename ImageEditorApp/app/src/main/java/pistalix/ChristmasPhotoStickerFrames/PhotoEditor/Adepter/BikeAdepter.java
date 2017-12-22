package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Adepter;

import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;

import java.util.ArrayList;

import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;
import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.modul.BikeModul;


/**
 * Created by a on 7/1/2017.
 */

public class BikeAdepter extends BaseAdapter {

    private Context mContext;
    ArrayList<BikeModul> frames;
    private LayoutInflater inflater;

    public BikeAdepter(Context mContext, ArrayList<BikeModul> frames) {
        this.mContext = mContext;
        this.frames = frames;
    }

    @Override
    public int getCount() {
        return frames.size();
    }

    @Override
    public Object getItem(int i) {
        return frames.get(i);
    }

    @Override
    public long getItemId(int i) {
        return i;
    }

    @Override
    public View getView(int i, View view, ViewGroup viewGroup) {
        LayoutInflater inflater = (LayoutInflater) mContext.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        if (view == null) {
            view = inflater.inflate(R.layout.bikesphoto, null);
        }
        ImageView img = (ImageView) view.findViewById(R.id.bike_photos);
        Bitmap bitmap = null;
        int resource = frames.get(i).getBikethump();
        bitmap = BitmapFactory.decodeResource(mContext.getResources(), resource);
        img.setImageBitmap(bitmap);
        return view;
    }
}
