package pistalix.gujaratibalgeetvideo.gujaratirhymes;

import android.app.DownloadManager;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.database.Cursor;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.preference.PreferenceManager;
import android.support.v7.widget.RecyclerView;
import android.util.SparseArray;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.daimajia.numberprogressbar.NumberProgressBar;
import com.github.johnpersano.supertoasts.library.Style;
import com.github.johnpersano.supertoasts.library.SuperActivityToast;
import com.github.johnpersano.supertoasts.library.utils.PaletteUtils;
import com.google.android.gms.ads.AdRequest;
import com.google.android.gms.ads.AdView;
import com.google.android.gms.ads.InterstitialAd;
import com.google.android.youtube.player.YouTubeBaseActivity;
import com.google.android.youtube.player.YouTubeInitializationResult;
import com.google.android.youtube.player.YouTubePlayer;
import com.google.android.youtube.player.YouTubePlayerView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.File;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.List;

import at.huber.youtubeExtractor.YouTubeUriExtractor;
import at.huber.youtubeExtractor.YtFile;

public class MainVideoView extends YouTubeBaseActivity implements YouTubePlayer.OnInitializedListener {
    String Title, VideoId, videoUrl, test,playlistId;
    TextView Test,VideoTitle;
    ImageView share_image,download_image,whatsapp,instagram,hike,fb,fbmsg,main_share;
    private AdView mAdView;
    NumberProgressBar DownloadBAr;
    int j = 0, set = 0,flagDownload=0;
    private static final int ITAG_FOR_AUDIO = 140;
    RecyclerView Cliaryty,suggetion;
    Boolean Downloding = true;
    String filename;
    InterstitialAd mInterstitialAd;
    private YouTubePlayerView youTubeView;
    private List<YtFragmentedVideo> formatsToShowList, MainSetVideos;
    private YtFragmentedVideo MainDownloadFile;
    private static final int RECOVERY_DIALOG_REQUEST = 1;

    String DEVELOPER_KEY = "AIzaSyDFaZ9yHK_TqYvAmNG9VGUZUinAwNlCyKs";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main_video_view);
        share_image =  (ImageView) findViewById(R.id.share_video);
        download_image =  (ImageView) findViewById(R.id.whatsapp_share);
        whatsapp =  (ImageView) findViewById(R.id.whatsapp);
        instagram =  (ImageView) findViewById(R.id.insta);
        hike =  (ImageView) findViewById(R.id.hike);
        fb =  (ImageView) findViewById(R.id.fb);
        fbmsg =  (ImageView) findViewById(R.id.fbmsg);
        main_share = (ImageView)findViewById(R.id.main_share);
        DownloadBAr = (NumberProgressBar)findViewById(R.id.DownloadBAr);
        try{
            String Title_create = getIntent().getStringExtra("Title");
            Title = getStringOfLettersOnly(Title_create);
            VideoId = getIntent().getStringExtra("videoid");
            playlistId = getIntent().getStringExtra("playlistId");


            mAdView = (AdView) findViewById(R.id.adView);
            AdRequest adRequest = new AdRequest.Builder()
                    .build();
            mAdView.loadAd(adRequest);

            SingelFunction downlodvideocheck = new SingelFunction();
            JSONArray videoIds = downlodvideocheck.DounloadVideos();
            ArrayList<File> files= downlodvideocheck.DounloadVideosName();

            int j=0;
            while(j<videoIds.length()){
                JSONObject videoidcheck = (JSONObject) videoIds.get(j);
                if(videoidcheck.get("Id").equals(VideoId)){
                    flagDownload =1;
                    filename = files.get(j).getAbsolutePath();
                    download_image.setImageResource(R.drawable.downloaded);
                }
                j++;
            }
            if(!isNetworkAvailable()){
                ToastMsgFail("Check your Network Connection");
            }
            youTubeView = (YouTubePlayerView) findViewById(R.id.youtubevideo);
//        Test = (TextView) findViewById(R.id.download);
            VideoTitle =  (TextView) findViewById(R.id.videotitle);
            VideoTitle.setText(Title_create);
//        Cliaryty = (RecyclerView) findViewById(R.id.recyclerViewVideosize);
//        suggetion = (RecyclerView) findViewById(R.id.recyclerViewVideosuggestion);
//        Cliaryty.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.HORIZONTAL, true));
//        suggetion.setLayoutManager(new LinearLayoutManager(this, LinearLayoutManager.HORIZONTAL, true));
            youTubeView.initialize(DEVELOPER_KEY, this);

        }catch (NullPointerException e){
            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
        }catch (IllegalArgumentException e){
            ToastMsgFail("Something Went Wrong");
        } catch (JSONException e) {
            ToastMsgFail("Something Went Wrong");
        }
        download_image.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(flagDownload == 1){
                    ToastMsgSuc("Allredy Download");
                }else{
                    ToastMsgSuc("Start Downloading ..");
                    DownloadBAr.setVisibility(View.VISIBLE);
                    try{
                        if(MainDownloadFile == null){
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }else{
                            Thread thread = new Thread(){
                                @Override
                                public void run() {
                                    try {
                                        Thread.sleep(3000);
                                        downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                    } catch (Exception e) {
                                        e.printStackTrace();
                                    }
                                }
                            };
                            thread.start();
//                            downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                        }

                    }
                    catch (NullPointerException e){
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }catch (IllegalArgumentException e){

                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }catch (Exception e){
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }


                }
            }
        });
        share_image.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(flagDownload == 1){
//                        String string_path = ;
                    Intent sharingIntent =  new Intent(android.content.Intent.ACTION_SEND);
                    sharingIntent.setType("video/*");
                    sharingIntent.putExtra(Intent.EXTRA_STREAM, Uri.parse(filename));
                    try {
                        startActivity(Intent.createChooser(sharingIntent, "Share via"));
                    } catch (android.content.ActivityNotFoundException e) {
                        ToastMsgSuc("First Install App...");
                    }
                }else{

                    try{
                        if(MainDownloadFile == null){
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }else{
                            DownloadBAr.setVisibility(View.VISIBLE);
                            Thread thread = new Thread(){
                                @Override
                                public void run() {
                                    try {
                                        Thread.sleep(3000);
                                        boolean set1 = downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                        if(set1){
                                            Intent sharingIntent =  new Intent(android.content.Intent.ACTION_SEND);
                                            sharingIntent.setType("video/*");
                                            sharingIntent.putExtra(Intent.EXTRA_STREAM, Uri.parse(filename));
                                            try {
                                                startActivity(sharingIntent);
                                            } catch (android.content.ActivityNotFoundException e) {
                                                ToastMsgSuc("First Install App...");
                                            }
                                        }
                                    } catch (Exception e) {
                                        e.printStackTrace();
                                    }
                                }
                            };
                            thread.start();
                        }

                    }
                    catch (NullPointerException e){
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }catch (IllegalArgumentException e){
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }catch (Exception e){
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }

                }
            }
        });

        whatsapp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                boolean isAppInstalled = appInstalledOrNot("com.whatsapp");
                if(!isAppInstalled){
                    ToastInstallAppBottom();
                }else{
                    if(flagDownload == 1){
                        share_via_app("com.whatsapp");
                    }else{
                        try{
                            if(MainDownloadFile == null){
                                ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                            }else{
                                DownloadBAr.setVisibility(View.VISIBLE);

                                Thread thread = new Thread(){
                                    @Override
                                    public void run() {
                                        try {
                                            Thread.sleep(3000);
                                            boolean set1 = downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                            if(set1){
                                                share_via_app("com.whatsapp");
                                            }
                                        } catch (Exception e) {
                                            e.printStackTrace();
                                        }
                                    }
                                };
                                thread.start();

                            }

                        }
                        catch (NullPointerException e){
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }catch (IllegalArgumentException e){

                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }catch (Exception e){
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }

                    }
                }

            }
        });
        instagram.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                boolean isAppInstalled = appInstalledOrNot("com.instagram.android");
                if (!isAppInstalled) {
                    ToastInstallAppBottom();
                } else {
                    if (flagDownload == 1) {
                        share_via_app("com.instagram.android");
                    } else {
                        try {
                            if (MainDownloadFile == null) {
                                ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                            } else {
                                DownloadBAr.setVisibility(View.VISIBLE);
                                Thread thread = new Thread(){
                                    @Override
                                    public void run() {
                                        try {
                                            Thread.sleep(3000);
                                            boolean set1 = downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                            if(set1){
                                                share_via_app("com.instagram.android");
                                            }
                                        } catch (Exception e) {
                                            e.printStackTrace();
                                        }
                                    }
                                };
                                thread.start();
                            }

                        } catch (NullPointerException e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        } catch (IllegalArgumentException e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        } catch (Exception e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }

                    }
                }
            }

        });
        fb.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                boolean isAppInstalled = appInstalledOrNot("com.facebook.katana");
                if (!isAppInstalled) {
                    ToastInstallAppBottom();
                } else {
                    if (flagDownload == 1) {
                        share_via_app("com.facebook.katana");
                    } else {
                        try {
                            if (MainDownloadFile == null) {
                                ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                            } else {
                                DownloadBAr.setVisibility(View.VISIBLE);
                                Thread thread = new Thread(){
                                    @Override
                                    public void run() {
                                        try {
                                            Thread.sleep(3000);
                                            boolean set1 = downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                            if(set1){
                                                share_via_app("com.instagram.android");
                                            }
                                        } catch (Exception e) {
                                            e.printStackTrace();
                                        }
                                    }
                                };
                                thread.start();
                            }

                        } catch (NullPointerException e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        } catch (IllegalArgumentException e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        } catch (Exception e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }

                    }
                }
            }

        });

        fbmsg.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                boolean isAppInstalled = appInstalledOrNot("com.facebook.orca");
                if (!isAppInstalled) {
                    ToastInstallAppBottom();
                } else {
                    if (flagDownload == 1) {
                        share_via_app("com.facebook.orca");
                    } else {
                        try {
                            if (MainDownloadFile == null) {
                                ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                            } else {
                                DownloadBAr.setVisibility(View.VISIBLE);
                                Thread thread = new Thread(){
                                    @Override
                                    public void run() {
                                        try {
                                            Thread.sleep(3000);
                                            boolean set1 = downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                            if(set1){
                                                share_via_app("com.instagram.android");
                                            }
                                        } catch (Exception e) {
                                            e.printStackTrace();
                                        }
                                    }
                                };
                                thread.start();
                            }

                        } catch (NullPointerException e) {
                            ToastMsgFail("Sorry Problem On Server1, Try Again After One Minute");
                        } catch (IllegalArgumentException e) {
                            ToastMsgFail("Sorry Problem On Server2, Try Again After One Minute");
                        } catch (Exception e) {
                            ToastMsgFail("Sorry Problem On Server3, Try Again After One Minute");
                        }

                    }
                }
            }

        });

        hike.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                boolean isAppInstalled = appInstalledOrNot("com.bsb.hike");
                if (!isAppInstalled) {
                    ToastInstallAppBottom();
                } else {
                    if (flagDownload == 1) {
                        share_via_app("com.bsb.hike");
                    } else {
                        try {
                            if (MainDownloadFile == null) {
                                ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                            } else {
                                DownloadBAr.setVisibility(View.VISIBLE);
                                Thread thread = new Thread(){
                                    @Override
                                    public void run() {
                                        try {
                                            Thread.sleep(3000);
                                            boolean set1 = downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                            if(set1){
                                                share_via_app("com.instagram.android");
                                            }
                                        } catch (Exception e) {
                                            e.printStackTrace();
                                        }
                                    }
                                };
                                thread.start();
                            }

                        } catch (NullPointerException e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        } catch (IllegalArgumentException e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        } catch (Exception e) {
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }

                    }
                }
            }

        });

        main_share.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if(flagDownload == 1){
//                        String string_path = ;
                    Intent sharingIntent =  new Intent(android.content.Intent.ACTION_SEND);
                    sharingIntent.setType("video/*");
                    sharingIntent.putExtra(Intent.EXTRA_STREAM, Uri.parse(filename));
                    try {
                        startActivity(Intent.createChooser(sharingIntent, "Share via"));
                    } catch (android.content.ActivityNotFoundException e) {
                        ToastMsgSuc("First Install App...");
                    }
                }else{

                    try{
                        if(MainDownloadFile == null){
                            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                        }else{
                            Thread thread = new Thread(){
                                @Override
                                public void run() {
                                    try {
                                        Thread.sleep(3000);
                                        boolean set1 = downloadFromUrl(MainDownloadFile.videoFile.getUrl(),"Sad Status",Title+" $ "+VideoId+"."+MainDownloadFile.videoFile.getFormat().getExt());
                                        if(set1){
                                            Intent sharingIntent =  new Intent(android.content.Intent.ACTION_SEND);
                                            sharingIntent.setType("video/*");
                                            sharingIntent.putExtra(Intent.EXTRA_STREAM, Uri.parse(filename));
                                            try {
                                                startActivity(sharingIntent);
                                            } catch (android.content.ActivityNotFoundException e) {
                                                ToastMsgSuc("First Install App...");
                                            }
                                        }
                                    } catch (Exception e) {
                                        e.printStackTrace();
                                    }
                                }
                            };
                            thread.start();
                        }

                    }
                    catch (NullPointerException e){
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }catch (IllegalArgumentException e){

                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }catch (Exception e){
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }

                }
            }
        });
        try{
            videoUrl = "https://www.youtube.com/watch?v=" + VideoId;
            getYoutubeDownloadUrl(videoUrl);
        }
        catch (NullPointerException e){
            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
        }catch (IllegalArgumentException e){
            ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
        }
        Suggetion();
    }

    private void getYoutubeDownloadUrl(String youtubeLink) {

        YouTubeUriExtractor ytEx = new YouTubeUriExtractor(getApplicationContext()) {
            @Override
            public void onUrisAvailable(String videoId, String videoTitle, SparseArray<YtFile> ytFiles) {

                if (ytFiles == null) {
                    ToastMsgFail("Sorry there is no any url to be download");
                    try{
                    }catch (NullPointerException e) {
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    } catch (IllegalArgumentException ex) {
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    } catch (Exception e) {
                        ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                    }


                }
                formatsToShowList = new ArrayList<>();
                try {
                    for (int i = 0, itag; i < ytFiles.size(); i++) {
                        itag = ytFiles.keyAt(i);
                        YtFile ytFile = ytFiles.get(itag);

                        if (ytFile.getMeta().getHeight() == -1 || ytFile.getMeta().getHeight() >= 360) {
                            addFormatToList(ytFile, ytFiles);
                        }
                    }
                    Collections.sort(formatsToShowList, new Comparator<YtFragmentedVideo>() {
                        @Override
                        public int compare(YtFragmentedVideo lhs, YtFragmentedVideo rhs) {
                            return lhs.height - rhs.height;
                        }
                    });
                    MainSetVideos = new ArrayList<>();
                    for (j = 0; j < formatsToShowList.size(); j++) {
                        if (formatsToShowList.get(j).height >= 240) {
                            if(formatsToShowList.get(j).height==360){

                                MainDownloadFile = formatsToShowList.get(j);
                                set=1;
                            }
                            MainSetVideos.add(formatsToShowList.get(j));
                        }
                    }
                    if(set ==0){
                        for (j = 0; j < formatsToShowList.size(); j++) {
                            if (formatsToShowList.get(j).height >= 240) {
                                if(formatsToShowList.get(j).height==240){

                                    MainDownloadFile = formatsToShowList.get(j);
                                    set=1;
                                }
                                MainSetVideos.add(formatsToShowList.get(j));
                            }
                        }
                    }
                } catch (NullPointerException e) {
                    ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                } catch (IllegalArgumentException ex) {
                    ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                } catch (Exception e) {
                    ToastMsgFail("Sorry Problem On Server, Try Again After One Minute");
                }

            }
        };
        ytEx.setIncludeWebM(false);
        ytEx.setParseDashManifest(true);
        ytEx.execute(youtubeLink);
    }
    private void addFormatToList(YtFile ytFile, SparseArray<YtFile> ytFiles) {
        int height = ytFile.getMeta().getHeight();
        if (height != -1) {
            for (YtFragmentedVideo frVideo : formatsToShowList) {
                if (frVideo.height == height && (frVideo.videoFile == null ||
                        frVideo.videoFile.getMeta().getFps() == ytFile.getMeta().getFps())) {
                    return;
                }
            }
        }
        YtFragmentedVideo frVideo = new YtFragmentedVideo();
        frVideo.height = height;
        if (ytFile.getMeta().isDashContainer()) {
            if (height > 0) {
                frVideo.videoFile = ytFile;
                frVideo.audioFile = ytFiles.get(ITAG_FOR_AUDIO);
            } else {
                frVideo.audioFile = ytFile;
            }
        } else {
            frVideo.videoFile = ytFile;
        }
        formatsToShowList.add(frVideo);
    }
    void ToastMsgFail(String str) {
        SuperActivityToast.create(this).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }
    void ToastMsgSuc(String str) {
        SuperActivityToast.create(this).setText(str).setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_KITKAT).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_GREEN)).setAnimations(Style.ANIMATIONS_POP).show();
    }
    void ToastInstallAppBottom(){

        SuperActivityToast.create(this).setText("First Install App...").setDuration(Style.DURATION_MEDIUM).setFrame(Style.FRAME_LOLLIPOP).setColor(PaletteUtils.getSolidColor(PaletteUtils.MATERIAL_RED)).setAnimations(Style.ANIMATIONS_POP).show();
    }

    @Override
    public void onInitializationSuccess(YouTubePlayer.Provider provider, YouTubePlayer youTubePlayer, boolean b) {
        if (!b) {

            // loadVideo() will auto play video
            youTubePlayer.setShowFullscreenButton(false);
            // Use cueVideo() method, if you don't want to play it automatically
            youTubePlayer.loadVideo(VideoId);
            // Hiding player controls
            youTubePlayer.setPlayerStyle(YouTubePlayer.PlayerStyle.MINIMAL);
        }
    }

    @Override
    public void onInitializationFailure(YouTubePlayer.Provider provider, YouTubeInitializationResult youTubeInitializationResult) {
        if (youTubeInitializationResult.isUserRecoverableError()) {
            youTubeInitializationResult.getErrorDialog(this, RECOVERY_DIALOG_REQUEST).show();
        } else {
            ToastMsgFail("Error On video");
        }
    }

    private boolean downloadFromUrl(String youtubeDlUrl, String downloadTitle, String fileName) {
        Uri uri = Uri.parse(youtubeDlUrl);
        Long m_id;
        int persantage;
        DownloadManager.Request request = new DownloadManager.Request(uri);
        request.setTitle(downloadTitle);

        request.setNotificationVisibility(DownloadManager.Request.VISIBILITY_VISIBLE_NOTIFY_COMPLETED);
        File downloadfolder = new File(Environment.getExternalStorageDirectory(), "Balvarta");
//        File downloadfolder = new File(Environment.getExternalStorageDirectory() +
//                File.separator + "SadStatus");
        if (!downloadfolder.exists()){
            downloadfolder.mkdirs();
        }
        request.setDestinationInExternalPublicDir("Balvarta", fileName);

        DownloadManager manager = (DownloadManager)getApplication(). getSystemService(Context.DOWNLOAD_SERVICE);

        m_id= manager.enqueue(request);

        try{
            while (Downloding){
                DownloadManager.Query query = new DownloadManager.Query();
                query.setFilterByStatus(DownloadManager.STATUS_FAILED | DownloadManager.STATUS_PAUSED |  DownloadManager.STATUS_SUCCESSFUL | DownloadManager.STATUS_RUNNING | DownloadManager.STATUS_PENDING);
                Cursor c;
                c=manager.query(query);
                if(c.moveToFirst()) {
                    int bytes_downloaded = c.getInt(c
                            .getColumnIndex(DownloadManager.COLUMN_BYTES_DOWNLOADED_SO_FAR));
                    int bytes_total = c.getInt(c.getColumnIndex(DownloadManager.COLUMN_TOTAL_SIZE_BYTES));
                    persantage = (bytes_downloaded*100)/bytes_total;

                    int status =c.getInt(c.getColumnIndex(DownloadManager.COLUMN_STATUS));
                    if (status==DownloadManager.STATUS_SUCCESSFUL) {
                        this.runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                download_image.setImageResource(R.drawable.downloaded);
                                DownloadBAr.setProgress(100);
                            }
                        });

                        filename =c.getString(c.getColumnIndex("local_uri"));
                        flagDownload =1;
                        Downloding = false;
                    }
                    if (status==DownloadManager.STATUS_FAILED) {
                        this.runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                ToastMsgSuc("Fail");
                            }
                        });

                        Downloding = false;
                    }
                    if(status == DownloadManager.STATUS_RUNNING){
                        final int finalPersantage = persantage;
                        this.runOnUiThread(new Runnable() {
                            @Override
                            public void run() {
                                DownloadBAr.setProgress(finalPersantage);
                            }
                        });

                    }
                }
                c.close();
            }

        }catch (IllegalArgumentException e){
            ToastMsgFail("Problem On download, Try Again After One Minute");
        }catch (NullPointerException e){
            ToastMsgFail("Problem On download, Try Again After One Minute");
        }catch (Exception e) {
            ToastMsgFail("Problem On download, Try Again After One Minute");
        }


        SharedPreferences sharedPreferences = PreferenceManager
                .getDefaultSharedPreferences(getApplication());
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putLong("Download_ID", m_id);
        editor.apply();
        return true;

    }

    private boolean isNetworkAvailable() {
        ConnectivityManager connectivityManager
                = (ConnectivityManager)getApplication(). getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
        return activeNetworkInfo != null && activeNetworkInfo.isConnected();
    }

    private void share_via_app(String packaeg){
        try {
            File fileshare  = new File(filename);
            Uri string_path = Uri.parse(filename);
            Intent sharingIntent =  new Intent(Intent.ACTION_SEND);
            sharingIntent.setType("video/*");
            sharingIntent.setPackage(packaeg);
            sharingIntent.putExtra(Intent.EXTRA_STREAM, string_path);
            startActivity(sharingIntent);
        } catch (android.content.ActivityNotFoundException e) {
            ToastInstallAppBottom();
        } catch (NullPointerException e){
            ToastMsgFail("Problem to read Your directory ");
        }catch (Exception e){
            ToastMsgFail("Problem to read Your directory ");
        }
    }

    private boolean appInstalledOrNot(String uri) {
        PackageManager pm = getPackageManager();
        try {
            pm.getPackageInfo(uri, PackageManager.GET_ACTIVITIES);
            return true;
        } catch (PackageManager.NameNotFoundException e) {
        }

        return false;
    }
    void Suggetion(){
        mInterstitialAd = new InterstitialAd(this);
        AdRequest adRequest = new AdRequest.Builder().build();
        String unitId = getString(R.string.interstial_ads);
        mInterstitialAd.setAdUnitId(unitId);
        mInterstitialAd.loadAd(adRequest);
        SingelFunction object =  new SingelFunction();
        object.suggetion_set(playlistId,this,VideoId,mInterstitialAd);

    }

    public String getStringOfLettersOnly(String s) {
        //using a StringBuilder instead of concatenate Strings
        StringBuilder sb = new StringBuilder();
        for(int i = 0; i < s.length(); i++) {
            if (Character.isLetter(s.charAt(i)) || s.charAt(i) == ' ' || s.charAt(i)=='$') {
                //adding data into the StringBuilder
                sb.append(s.charAt(i));
            }
        }
        //return the String contained in the StringBuilder
        return sb.toString();
    }
}
