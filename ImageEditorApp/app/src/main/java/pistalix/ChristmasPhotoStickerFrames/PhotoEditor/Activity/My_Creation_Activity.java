//package pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Activity;
//
//import android.app.Dialog;
//import android.content.Context;
//import android.content.Intent;
//import android.net.ConnectivityManager;
//import android.net.NetworkInfo;
//import android.net.Uri;
//import android.os.Bundle;
//import android.os.Environment;
//import android.support.v7.app.AppCompatActivity;
//import android.util.DisplayMetrics;
//import android.util.Log;
//import android.view.View;
//import android.widget.AdapterView;
//import android.widget.GridView;
//import android.widget.ImageView;
//
//import java.io.File;
//import java.util.ArrayList;
//
//import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Adepter.GallaryAdapter;
//import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.MainActivity;
//import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.R;
//import pistalix.ChristmasPhotoStickerFrames.PhotoEditor.Subfile.Glob;
//
//
//public class My_Creation_Activity extends AppCompatActivity {
//    GallaryAdapter gallaryAdapter;
//    private GridView lv_my_creation;
//
//    public static int pos;
//    public static ArrayList<String> IMAGEALLARY = new ArrayList<>();
//    private ImageView Iv_back_creation;
//
//
//    @Override
//    protected void onCreate(Bundle savedInstanceState) {
//        super.onCreate(savedInstanceState);
//        setContentView(R.layout.activity_my__creation);
//
//        lv_my_creation = (GridView) findViewById(R.id.lv_my_creation);
//        gallaryAdapter = new GallaryAdapter(My_Creation_Activity.this, IMAGEALLARY);
//        IMAGEALLARY.clear();
//        listAllImages(new File(Environment.getExternalStorageDirectory().getPath() + "/" + Glob.Edit_Folder_name + "/"));
//
//        lv_my_creation.setAdapter(gallaryAdapter);
//        lv_my_creation.setOnItemClickListener(new AdapterView.OnItemClickListener() {
//            @Override
//            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
//                gallaryAdapter.getItemId(position);
//                pos = position;
//                openimagedialog();
//            }
//
//        });
//
//        Iv_back_creation = (ImageView) findViewById(R.id.Iv_back_creation);
//        Iv_back_creation.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                Intent intent = new Intent(My_Creation_Activity.this, MainActivity.class);
//                startActivity(intent);
//            }
//        });
//    }
//
//    private void openimagedialog() {
//        Dialog dialog = new Dialog(My_Creation_Activity.this, 16973839);
//        DisplayMetrics displayMetrics = new DisplayMetrics();
//        getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
//        int i = (int) (((double) displayMetrics.heightPixels) * 1.0d);
//        int i2 = (int) (((double) displayMetrics.widthPixels) * 1.0d);
//        dialog.requestWindowFeature(1);
//        dialog.getWindow().setFlags(1024, 1024);
//        dialog.setContentView(R.layout.activity_full_screen_view);
//        dialog.getWindow().setLayout(i2, i);
//        dialog.setCanceledOnTouchOutside(true);
//        ((ImageView) dialog.findViewById(R.id.iv_image)).setImageURI(Uri.parse(My_Creation_Activity.IMAGEALLARY.get(My_Creation_Activity.pos)));
//        dialog.show();
//    }
//
//    //
//
//
//    public boolean isOnline() {
//        ConnectivityManager cm = (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
//        NetworkInfo netInfo = cm.getActiveNetworkInfo();
//        if (netInfo != null && netInfo.isConnectedOrConnecting()) {
//            return true;
//        }
//        return false;
//    }
//
//
//    private void listAllImages(File filepath) {
//        File[] files = filepath.listFiles();
//        if (files != null) {
//
//            for (int j = files.length - 1; j >= 0; j--) {
//                String ss = files[j].toString();
//                File check = new File(ss);
//                Log.d("" + check.length(), "" + check.length());
//                if (check.length() > 1024) {
//                    if (check.toString().contains(".jpg") || check.toString().contains(".png") || check.toString().contains(".jpeg")) {
//                        IMAGEALLARY.add(ss);
//                    }
//                } else {
//                    Log.e("Invalid Image", "Delete Image");
//                }
//                System.out.println(ss);
//            }
//        } else
//
//        {
//            System.out.println("Empty Folder");
//        }
//    }
//
//    @Override
//    public void onBackPressed() {
//        Intent intent = new Intent(My_Creation_Activity.this, MainActivity.class);
//        startActivity(intent);
//    }
//}
//
