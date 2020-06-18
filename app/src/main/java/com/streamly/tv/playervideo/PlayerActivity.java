package com.streamly.tv.playervideo;

import androidx.fragment.app.FragmentActivity;
import android.app.ProgressDialog;
import android.media.MediaPlayer;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.widget.MediaController;
import android.widget.VideoView;

import com.streamly.tv.R;

public class PlayerActivity extends FragmentActivity {
    private ProgressDialog pDialog;
    private VideoView videoview;
    //    private String vid_url ="http://66.210.244.230/live/test/index.m3u8";
    //    private String vid_url ="http://streamlytv.com/live/CMTV/interlink.m3u8";
    //    private String vid_url ="https://bitdash-a.akamaihd.net/content/sintel/hls/playlist.m3u8";
    private String vid_url ="http://channelstore.streamlytv.com/live/CMTV/interlink.m3u8";
    //    private String vid_url ="http://streamlytv.com/live/TNT/interlink.m3u8";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_player);

        videoview = findViewById(R.id.videoView);
        pDialog = new ProgressDialog(this);
        pDialog.setTitle("Video Stream");
        pDialog.setMessage("Buffering...");
        pDialog.setIndeterminate(false);
        pDialog.setCancelable(true);
        pDialog.show();
        try {
            MediaController mediacontroller = new MediaController(this);
            mediacontroller.setAnchorView(videoview);
            Uri video = Uri.parse(vid_url);
            videoview.setMediaController(mediacontroller);
            videoview.setVideoURI(video);
        } catch (Exception e) {
            Log.e("Error", e.getMessage());
            e.printStackTrace();
        }
        videoview.requestFocus();
        videoview.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
            public void onPrepared(MediaPlayer mp) {
                PlayerActivity.this.pDialog.dismiss();
                PlayerActivity.this.videoview.start();
            }
        });
        videoview.setOnCompletionListener(new MediaPlayer.OnCompletionListener() {
            @Override
            public void onCompletion(MediaPlayer mp) {
                finish();
            }
        });


    }
}