package com.streamly.tv.playervideo;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.widget.VideoView;

import androidx.fragment.app.FragmentActivity;

import com.google.android.exoplayer2.SimpleExoPlayer;
import com.google.android.exoplayer2.drm.DefaultDrmSessionManager;
import com.google.android.exoplayer2.drm.DrmSessionManager;
import com.google.android.exoplayer2.drm.ExoMediaCrypto;
import com.google.android.exoplayer2.drm.FrameworkMediaDrm;
import com.google.android.exoplayer2.drm.HttpMediaDrmCallback;
import com.google.android.exoplayer2.drm.MediaDrmCallback;
import com.google.android.exoplayer2.source.MediaSource;
import com.google.android.exoplayer2.source.dash.DashMediaSource;
import com.google.android.exoplayer2.ui.PlayerView;
import com.google.android.exoplayer2.upstream.DataSource;
import com.google.android.exoplayer2.upstream.DefaultHttpDataSourceFactory;
import com.google.android.exoplayer2.upstream.HttpDataSource;
import com.google.android.exoplayer2.util.Util;
import com.streamly.tv.R;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;

import java.util.UUID;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class PlayerActivity extends FragmentActivity {
    private static final String TAG=PlayerActivity.class.getSimpleName();
    String authToken;


    @Override
    protected void onStart() {
        super.onStart();
        getDrmKey();

    }

    private HttpDataSource.Factory getDefaultDataSourceFactory() {
        return new DefaultHttpDataSourceFactory(Util.getUserAgent(this, "sample-player"));
    }

    private HttpMediaDrmCallback createMediaDrmCallback(String licenseUrl, String[] keyRequestPropertiesArray) {
        HttpDataSource.Factory licenseDataSourceFactory = getDefaultDataSourceFactory();
        HttpMediaDrmCallback drmCallback = new HttpMediaDrmCallback(licenseUrl, licenseDataSourceFactory);
        if (keyRequestPropertiesArray != null) {
            for (int i = 0; i < keyRequestPropertiesArray.length - 1; i += 2) {
                drmCallback.setKeyRequestProperty(keyRequestPropertiesArray[i],
                        keyRequestPropertiesArray[i + 1]);
            }
        }
        return drmCallback;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_player);
         authToken= TokenStorage.readSharedToken(PlayerActivity.this, ConstantUtility.AUTH_TOKEN, null);

    }

    private void getDrmKey() {
       ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<DrmKeyModel> call=apiInterface.getDrmKey("Bearer "+authToken);
        call.enqueue(new Callback<DrmKeyModel>() {
            @Override
            public void onResponse(Call<DrmKeyModel> call, Response<DrmKeyModel> response) {
                try{
                    if (response.isSuccessful() && response.body()!=null){
                        CommonUtility.showToast(PlayerActivity.this,response.body().getMessage());

                        String drmKey=response.body().getData();
                        String wvUrl= response.body().getWidevinelicense();
                        Log.e(TAG, "wvUrl "+ wvUrl+"\n"+"drmKey "+drmKey );
                        initializePlayer(drmKey,wvUrl);

                    }else if (response.code()==401){
                        CommonUtility.showToast(PlayerActivity.this,response.body().getMessage());
                    }
                    else if (response.code()==404){
                        TokenStorage.clearSharedToken(PlayerActivity.this);
                        CommonUtility.showToast(PlayerActivity.this,ConstantUtility.SESSION_EXPIRED);
                        startActivity(new Intent(PlayerActivity.this, LogInActivity.class));
                        finish();
                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(PlayerActivity.this,ConstantUtility.SERVER_ISSUE);
                    }

                }catch (Exception ex){
                    ex.printStackTrace();
                    Log.e(TAG, "Exception "+ex);
                }
            }

            @Override
            public void onFailure(Call<DrmKeyModel> call, Throwable t) {
                CommonUtility.showToast(PlayerActivity.this,ConstantUtility.SERVER_ISSUE);
            }
        });
    }


    private void initializePlayer(String drmKey, String wvUrl) {
        String vid_url=getIntent().getStringExtra("from");
        Log.e(TAG,"video_URL "+vid_url);

        // Create a data source factory.
        DataSource.Factory dataSourceFactory = getDefaultDataSourceFactory();

//        Uri dashUri = Uri.parse("https://path/to/your/stream.mpd");
        Uri dashUri = Uri.parse(vid_url);

        // Identifies the Widevine DRM
        UUID widevineUuid = UUID.fromString("edef8ba9-79d6-4ace-a3c8-27dcd51d21ed");


        // URL of the KeyOS MultiKey License API for Widevine
//        String wvUrl = "https://wv-keyos.licensekeyserver.com/";


        // Security token required to acquire a license. Base64 encoded Authentication XML
//        String[] keyRequestProperties = new String[] { "x-keyos-authorization", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTA4LTE5IDExOjM1OjI3LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMDgtMTkgMTE6NTA6MjcuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+MjcxOTE3ODJlYzNlMjIxOWI1ZjgyNzlhOWYwNmI1ZTk8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+ckZ4d3huaU4yYU85YWZCNGVYT292dzIyMDZtZGMrTElwdHlMSTRWa1RySzJtRTJkckRMamEyNHVvekhGY0ZRb2NFRGNqc3ovQTRnU2FZWDB5TkpUam5TS3o5eVdjak1ad2NTcDJlVDFUaG10WW4xbjFORStGOHR1eVpSYk5QQWRFbGtkZC83TlBscW5rT1htVGxDb01pNVMrYWJuUGZ3WEJzVXVEMHg1VUdLYmp6bjI0WStiTkFzU0IrL3A1L1d1TjR3RVVwQjlSSFVlUWZKbFBhcTV2c2N5QTMxZXJTODFhVjhrdnkrTXJaVlYyTXlHT2d6NVdTRVlIV1V0QUdOOWZEemNYV3lJRFhScFQ3ZnNmRDVWYmd0aVZnOFNsOWU4U01BTUJ5VGF4aVJTUnBVdm9hV2NtMEhWYk1FdE5zZ0tkSy9FcjBER242U2VSZDdtM01qWlV3PT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg==" };
        String[] keyRequestProperties = new String[] { "x-keyos-authorization", drmKey };

        // Create the DRM session manager and set required request parameters.
        MediaDrmCallback mediaDrmCallback = createMediaDrmCallback(wvUrl, keyRequestProperties);
        DrmSessionManager<ExoMediaCrypto> drmSessionManager = new DefaultDrmSessionManager.Builder()
                .setUuidAndExoMediaDrmProvider(widevineUuid, FrameworkMediaDrm.DEFAULT_PROVIDER)
                .build(mediaDrmCallback);



        // Create a DASH media source pointing to a DASH manifest uri.
        MediaSource mediaSource = new DashMediaSource.Factory(dataSourceFactory)
                .setDrmSessionManager(drmSessionManager)
                .createMediaSource(dashUri);

        // Create player
        SimpleExoPlayer player = new SimpleExoPlayer.Builder(this).build();

        // Attach it to the view
        PlayerView playerView = findViewById(R.id.playerView);
        playerView.setPlayer(player);

        // Preapre the player with content
        player.prepare(mediaSource);

    }
}