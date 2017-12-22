package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Adepter;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.WallpaperManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.media.MediaScannerConnection;
import android.net.Uri;
import android.util.DisplayMetrics;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.GridView;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;

import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;

public class GallaryAdapter extends BaseAdapter {

    private Activity activity;
    private static LayoutInflater inflater = null;
    ArrayList<String> imagegallary = new ArrayList<String>();

    public GallaryAdapter(Activity dAct, ArrayList<String> dUrl) {
        activity = dAct;
        this.imagegallary = dUrl;
        inflater = (LayoutInflater) activity.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    @Override
    public int getCount() {
        return imagegallary.size();
    }

    @Override
    public Object getItem(int position) {
        return position;
    }

    @Override
    public long getItemId(int position) {
        return position ;
    }

    @Override
    public View getView(final int position, final View convertView, ViewGroup parent) {

        View row = convertView;
        ViewHolder holder = null;
        DisplayMetrics metrics = activity.getResources().getDisplayMetrics();
        int width = metrics.widthPixels;
        if (row == null) {

            row = LayoutInflater.from(activity).inflate(R.layout.details_list_img, parent, false);
            holder = new ViewHolder();
            row.setLayoutParams(new GridView.LayoutParams(width, ViewGroup.LayoutParams.WRAP_CONTENT));
            row.setPadding(8, 8, 8, 8);
            holder.imgIcon = (ImageView) row.findViewById(R.id.Iv_details_img);
            holder.imgDelete = (ImageView) row.findViewById(R.id.Iv_deledt_img_list);
            holder.imgShare = (ImageView) row.findViewById(R.id.Iv_share_img_list);
            holder.imgSetAs = (ImageView) row.findViewById(R.id.Iv_set_as_img_list);

            row.setTag(holder);
        } else {
            holder = (ViewHolder) row.getTag();
        }

        holder.imgShare.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                AlertDialog.Builder alertDialog = new AlertDialog.Builder(activity);

                // Setting Dialog Title
                alertDialog.setTitle("Share File...");

                // Setting Dialog Message
                alertDialog.setMessage("Do you want to share this file?");

                // Setting Icon to Dialog
                alertDialog.setIcon(R.drawable.ic_share);

                // Setting Positive "Yes" Button
                alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // User pressed YES button. Write Logic Here
                        shareImage("", imagegallary.get(position));

                        Toast.makeText(activity, "You clicked on YES",
                               Toast.LENGTH_SHORT).show();
                    }
                });

                // Setting Negative "NO" Button
                alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // User pressed No button. Write Logic Here
                        Toast.makeText(activity, "You clicked on NO", Toast.LENGTH_SHORT).show();
                    }
                });

                // Setting Netural "Cancel" Button
                alertDialog.setNeutralButton("Cancel", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // User pressed Cancel button. Write Logic Here
                        Toast.makeText(activity, "You clicked on Cancel",
                                Toast.LENGTH_SHORT).show();
                    }
                });

                // Showing Alert Message
                alertDialog.show();

            }
        });

        holder.imgSetAs.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

//
               //setWallpaper("", imagegallary.get(position));
                AlertDialog.Builder alertDialog = new AlertDialog.Builder(activity);

                // Setting Dialog Title
                alertDialog.setTitle("Set As Wallpaper...");

                // Setting Dialog Message
                alertDialog.setMessage("Do you want to Set As Wallpaper??");

                // Setting Icon to Dialog
                alertDialog.setIcon(R.drawable.ic_set_as);

                // Setting Positive "Yes" Button
                alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {

                        setWallpaper("", imagegallary.get(position));

//                        imagegallary.remove(position);

                        notifyDataSetChanged();

                    }
                });

                // Setting Negative "NO" Button
                alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {

                        dialog.cancel();
                    }
                });

                // Showing Alert Message
                alertDialog.show();


            }
        });


        holder.imgDelete.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                AlertDialog.Builder alertDialog = new AlertDialog.Builder(activity);

                // Setting Dialog Title
                alertDialog.setTitle("Confirm Delete...");

                // Setting Dialog Message
                alertDialog.setMessage("Are you sure you want delete this?");

                // Setting Icon to Dialog
                alertDialog.setIcon(R.drawable.ic_delete);

                // Setting Positive "Yes" Button
                alertDialog.setPositiveButton("YES", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        File fD = new File(imagegallary.get(position));
//
                        if (fD.exists()) {
//                    Utils.deleteDirectory(fD);
                            fD.delete();
                        }

                        imagegallary.remove(position);

                        notifyDataSetChanged();
                        if (imagegallary.size() == 0) {

                            Toast.makeText(activity, "No Image Found..", Toast.LENGTH_LONG).show();

                        }

                    }
                });

                // Setting Negative "NO" Button
                alertDialog.setNegativeButton("NO", new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {

                        dialog.cancel();
                    }
                });


                alertDialog.show();


            }
        });

        Glide.with(activity)
                .load(imagegallary.get(position))
                .centerCrop()
                .crossFade()
                .into(holder.imgIcon);


        System.gc();
        return row;

    }

   static class ViewHolder {

        ImageView imgIcon, imgDelete, imgShare, imgSetAs;
        TextView txtTitle, txtDuration;

    }

    private void setWallpaper(String androidtech, String s) {
        WallpaperManager wallpaperManager
                = WallpaperManager.getInstance(activity);
        DisplayMetrics metrics = new DisplayMetrics();
        activity.getWindowManager().getDefaultDisplay().getMetrics(metrics);
        // get the height and width of screen
        int height = metrics.heightPixels;
        int width = metrics.widthPixels;
        try {
//            int position2 = viewPager.getCurrentItem();
            BitmapFactory.Options options = new BitmapFactory.Options();
            options.inPreferredConfig = Bitmap.Config.ARGB_8888;
            Bitmap bitmap = BitmapFactory.decodeFile(s, options);
            wallpaperManager.setBitmap(bitmap);

            wallpaperManager.suggestDesiredDimensions(width / 2, height / 2);
            Toast.makeText(activity, "Wallpaper Set", Toast.LENGTH_LONG).show();

        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void shareImage(final String title, String path) {
        MediaScannerConnection.scanFile(activity, new String[]{path},
                null, new MediaScannerConnection.OnScanCompletedListener() {
                    public void onScanCompleted(String path, Uri uri) {
                        Intent shareIntent = new Intent(Intent.ACTION_SEND);
                        shareIntent.setType("video/*");
                        shareIntent.putExtra(Intent.EXTRA_SUBJECT, title);
                        shareIntent.putExtra(Intent.EXTRA_TEXT, "https://play.google.com/store/apps/details?id=stylishphoto.bikephotoeditor&hl=en");
                        shareIntent.putExtra(Intent.EXTRA_STREAM, uri);
                        shareIntent
                                .addFlags(Intent.FLAG_ACTIVITY_CLEAR_WHEN_TASK_RESET);
                        activity.startActivity(Intent.createChooser(shareIntent, "Share Video"));

                    }
                });
    }

}
