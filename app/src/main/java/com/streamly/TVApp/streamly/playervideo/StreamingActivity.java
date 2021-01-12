/*
package com.streamly.tv.playervideo;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.widget.ProgressBar;

import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import com.google.android.exoplayer2.ExoPlaybackException;
import com.google.android.exoplayer2.ExoPlayer;
import com.google.android.exoplayer2.ExoPlayerFactory;
import com.google.android.exoplayer2.PlaybackParameters;
import com.google.android.exoplayer2.SimpleExoPlayer;
import com.google.android.exoplayer2.Timeline;
import com.google.android.exoplayer2.ext.rtmp.RtmpDataSourceFactory;
import com.google.android.exoplayer2.source.ExtractorMediaSource;
import com.google.android.exoplayer2.source.MediaSource;
import com.google.android.exoplayer2.source.TrackGroupArray;
import com.google.android.exoplayer2.source.hls.HlsMediaSource;
import com.google.android.exoplayer2.trackselection.AdaptiveTrackSelection;
import com.google.android.exoplayer2.trackselection.DefaultTrackSelector;
import com.google.android.exoplayer2.trackselection.TrackSelection;
import com.google.android.exoplayer2.trackselection.TrackSelectionArray;
import com.google.android.exoplayer2.trackselection.TrackSelector;
import com.google.android.exoplayer2.ui.PlayerView;
import com.google.android.exoplayer2.upstream.DataSource;
import com.google.android.exoplayer2.upstream.DefaultDataSourceFactory;
import com.google.android.exoplayer2.util.Util;
import com.streamly.tv.R;
import com.streamly.tv.utilities.ConstantUtility;

import okhttp3.Response;

public class StreamingActivity extends FragmentActivity {
    private static final String TAG = StreamingActivity.class.getSimpleName();
    MediaSource videoSource;
    SimpleExoPlayer player;
    Handler handler = new Handler();
    final int delay = 10000; //milliseconds
    Runnable runnable;
    ProgressBar progressBar;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_player);
        PlayerView playerView = findViewById(R.id.simple_player);
        progressBar = findViewById(R.id.progressBar);

        String url = getIntent().getStringExtra("form");
        String access_id = getIntent().getStringExtra(ConstantUtility.ACCESS_ID);

        playerView.setUseController(false);
        //RTMP Url



        TrackSelection.Factory videoTrackSelectionFactory = new AdaptiveTrackSelection.Factory();
        TrackSelector trackSelector = new DefaultTrackSelector(videoTrackSelectionFactory);
        player = ExoPlayerFactory.newSimpleInstance(this, trackSelector);

        playerView.setPlayer(player);

        */
/*
          Create RTMP Data Source
         *//*

        if (url.contains(ConstantUtility.RTMP)) {
            RtmpDataSourceFactory rtmpDataSourceFactory = new RtmpDataSourceFactory();
            videoSource = new ExtractorMediaSource.Factory(rtmpDataSourceFactory).createMediaSource(Uri.parse(url));
        } else if (url.contains(ConstantUtility.RTSP)) {
            RtmpDataSourceFactory rtmpDataSourceFactory = new RtmpDataSourceFactory();
            videoSource = new ExtractorMediaSource.Factory(rtmpDataSourceFactory).createMediaSource(Uri.parse(url));
        } else {
            DataSource.Factory dataSourceFactory = new DefaultDataSourceFactory(StreamingActivity.this, Util.getUserAgent(this, "BKDEC"), null);
            videoSource = new HlsMediaSource(Uri.parse(url), dataSourceFactory, 1, null, null);
        }

        player.prepare(videoSource);
        player.addListener(new ExoPlayer.EventListener() {
            @Override
            public void onTimelineChanged(Timeline timeline, @Nullable Object manifest, int reason) {

            }

            @Override
            public void onTracksChanged(TrackGroupArray trackGroups, TrackSelectionArray trackSelections) {

            }

            @Override
            public void onLoadingChanged(boolean isLoading) {

            }

            @Override
            public void onPlayerStateChanged(boolean playWhenReady, int playbackState) {

                switch (playbackState) {
                    case ExoPlayer.STATE_IDLE:
                        progressBar.setVisibility(View.VISIBLE);
                        break;

                    case ExoPlayer.STATE_READY:
                        progressBar.setVisibility(View.INVISIBLE);
                        break;
                }

            }

            @Override
            public void onRepeatModeChanged(int repeatMode) {

            }

            @Override
            public void onShuffleModeEnabledChanged(boolean shuffleModeEnabled) {

            }

            @Override
            public void onPlayerError(ExoPlaybackException error) {
                Log.v(TAG, "Listener-onPlayerError...");
                player.stop();
                player.setPlayWhenReady(true);

            }

            @Override
            public void onPositionDiscontinuity(int reason) {

            }

            @Override
            public void onPlaybackParametersChanged(PlaybackParameters playbackParameters) {

            }

            @Override
            public void onSeekProcessed() {

            }
        });
        player.setPlayWhenReady(true);


        runnable = new Runnable() {
            @Override
            public void run() {
                //do something
                getStreamUrl(access_id);
                handler.postDelayed(this, delay);
            }
        };
        handler.postDelayed(runnable, delay);

    }


    private void getStreamUrl(final String access_ID) {

        try {
        AndroidNetworking.get(ApiConfig.BASE_URL + access_ID)
                .setPriority(Priority.HIGH)
                .build()
                .getAsOkHttpResponseAndString(new OkHttpResponseAndStringRequestListener() {
                    @Override
                    public void onResponse(Response okHttpResponse, String response) {

                        if (response.isEmpty()) {
                            handler.removeCallbacks(runnable);
                            player.stop();
                            startActivity(new Intent(StreamingActivity.this, StandByActivity.class)
                                    .putExtra(ConstantUtility.ACCESS, ConstantUtility.EMPTY)
                                    .putExtra(ConstantUtility.ACCESS_ID, access_ID));
                            Log.e(TAG, "response " + response);
                            finish();
                        }
                    }

                    @Override
                    public void onError(ANError anError) {
                        Log.e(TAG, "anError " + anError.getErrorCode());
                    }
                });
        }catch (Exception ex){
            Log.e(TAG,"Exception "+ex);
        }
    }


    @Override
    public void onBackPressed() {
        handler.removeCallbacks(runnable);
        player.stop();
        finish();
    }

    @Override
    protected void onStop() {
        super.onStop();
        handler.removeCallbacks(runnable);
        player.stop();
    }

}*/
