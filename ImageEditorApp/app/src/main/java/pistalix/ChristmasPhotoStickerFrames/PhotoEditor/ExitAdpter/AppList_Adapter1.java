package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.ExitAdpter;

import android.app.Activity;
import android.content.Context;
import android.util.DisplayMetrics;
import android.util.SparseBooleanArray;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.bumptech.glide.Glide;

import java.util.ArrayList;

import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;


public class AppList_Adapter1 extends BaseAdapter {

    private Activity activity;
    private static LayoutInflater inflater = null;
    SparseBooleanArray mSparseBooleanArray;
    ArrayList<String> dLink = new ArrayList<String>();
    ArrayList<String> dName = new ArrayList<String>();
    ArrayList<String> dIcon = new ArrayList<String>();
    int width;

    public AppList_Adapter1(Activity dAct, ArrayList<String> dLinkUrl, ArrayList<String> dIcon, ArrayList<String> dName) {
        activity = dAct;
        this.dLink = dLinkUrl;
        this.dName = dName;
        this.dIcon = dIcon;

        inflater = (LayoutInflater) activity.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        mSparseBooleanArray = new SparseBooleanArray(dLink.size());
    }

    public int getCount() {
        return dLink.size();
    }

    public Object getItem(int position) {
        return position;
    }

    public long getItemId(int position) {
        return position;
    }

    public View getView(final int position, View convertView, ViewGroup parent) {

        View row = convertView;
        ViewHolder holder = null;
        DisplayMetrics metrics = activity.getResources().getDisplayMetrics();
        width = metrics.widthPixels;
        int height = metrics.heightPixels;

        if (row == null) {

            row = LayoutInflater.from(activity).inflate(R.layout.list_appstore1, parent, false);
            holder = new ViewHolder();
            holder.imgIcon = (ImageView) row.findViewById(R.id.imglogo);
            holder.txtname = (TextView) row.findViewById(R.id.txtname);
            row.setTag(holder);
        } else {
            holder = (ViewHolder) row.getTag();
        }

        holder.txtname.setText(dName.get(position));

        Glide.with(activity)
                .load(dIcon.get(position))
                .centerCrop()
                .placeholder(R.mipmap.ic_launcher)
                .crossFade()
                .into(holder.imgIcon);

        System.gc();
        return row;

    }

    static class ViewHolder {

        ImageView imgIcon;
        TextView txtname;

    }

}
