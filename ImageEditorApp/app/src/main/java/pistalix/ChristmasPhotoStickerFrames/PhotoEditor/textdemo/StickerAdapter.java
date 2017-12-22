package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.textdemo;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;

import com.bumptech.glide.Glide;

import java.util.ArrayList;

import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;



public class StickerAdapter extends BaseAdapter {

    private ArrayList<Integer> stiker;
    private Context context;

    public StickerAdapter(Context context, ArrayList<Integer> stiker) {
        this.context = context;
        this.stiker = stiker;

    }

    @Override
    public int getCount() {
        return stiker.size();
    }

    @Override
    public Object getItem(int position) {
        return stiker.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        if (convertView == null) {
            convertView = LayoutInflater.from(context).
                    inflate(R.layout.item_sticker, parent, false);
        }
        ImageView img = (ImageView)
                convertView.findViewById(R.id.iv_list_stker);
        int resource = stiker.get(position);
//        img.setImageResource(resource);
        Glide.with(convertView.getContext())
                .load(resource)
                .thumbnail(0.5f)
                .dontAnimate()
                .into(img);
        return convertView;
    }
}