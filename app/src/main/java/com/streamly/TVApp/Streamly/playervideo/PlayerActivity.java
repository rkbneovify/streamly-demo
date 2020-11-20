package com.streamly.TVApp.Streamly.playervideo;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
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
import com.streamly.TVApp.Streamly.R;
import com.streamly.TVApp.Streamly.local_storage.TokenStorage;
import com.streamly.TVApp.Streamly.log_in.LogInActivity;
import com.streamly.TVApp.Streamly.retrofit_clients.ApiClient;
import com.streamly.TVApp.Streamly.retrofit_clients.ApiInterface;
import com.streamly.TVApp.Streamly.utilities.CommonUtility;
import com.streamly.TVApp.Streamly.utilities.ConstantUtility;
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
        getDrmKey();

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
        String streamUrl=getIntent().getStringExtra("from");

        // URL of the KeyOS MultiKey License API for Widevine
//        String wvUrl = "https://wv-keyos.licensekeyserver.com/";
//        String wvURL1="http://127.0.0.1:8000/api/generic?drm-type=widevine";
//        String wvURL1="https://streamlytv.com/api/generic?drm-type=widevine";
//        String wvUrl1="https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=widevine";
//        String streamUrl2="https://channelstore.streamlytv.com/streamly/AETVP.stream/manifest.mpd";

//        Uri dashUri = Uri.parse("https://path/to/your/stream.mpd");
        Uri dashUri = Uri.parse(streamUrl.trim());

        Log.e(TAG, "1) license_server: "+ wvUrl+"\n"
                +"2) DRM_key: "+drmKey+ "\n"
                +"3) Stream_URL: "+streamUrl+ "\n"
                + "4) dash_uri: "+dashUri);

        // Identifies the Widevine DRM
        UUID widevineUuid = UUID.fromString("edef8ba9-79d6-4ace-a3c8-27dcd51d21ed");

        // Security token required to acquire a license. Base64 encoded Authentication XML
        String[] keyRequestProperties = new String[] { "x-keyos-authorization", drmKey };
//        String[] keyRequestProperties = new String[] { "customdata", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTEwLTE5IDE0OjUxOjU1LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMTAtMTkgMTU6MDY6NTUuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZjEzNGMxNjBlYjU1ZTE5ZTJjMjUxODM1YjZlYjdjZDY8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+dS9YQVgxNnNxU2FvUU43UW9FKzY3TlNuTzY0UXFhbUVBOUFsYnNVSlozOWdUT1l3NEpDTW9md3NaYkFQWWVJaS9DKy9SMkJweHJwVEpuWS9ZRGx2dERoSFluYlNwYy9wMXNKcUxOdmREaE4yejEwS2wyT0hKekE1OTdxU0Urcnd2d3BHd09FSm1iVEFidm8zK3FTQVdsTnQ0dWJoMTg0M1VQU3FYMDVZNFEzdlJLWXdzSzR5SUxRdFJuTStENDJsWXcvcTlpc0pUR0pGVWRqY1hJVnh3QWdPRGpDNEdWQVIycXM1VXhHY1cyT0c0THd6ZWpkeUkwMENrK1FYZHpNcTVab3pUZ243a2ZJaGpPS0JETUx0L2VrNnhNYTZ2VjRTQlZEdWxkYnZCR1BjZW9CVzNYWDc1WFY4VjZxdUxJM29GM3FTM3FZQzUvNkJiRmNuQ0k2YjFRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="};

        // Create the DRM session manager and set required request parameters.
        MediaDrmCallback mediaDrmCallback = createMediaDrmCallback(wvUrl, keyRequestProperties);
        DrmSessionManager<ExoMediaCrypto> drmSessionManager = new DefaultDrmSessionManager.Builder()
                .setUuidAndExoMediaDrmProvider(widevineUuid, FrameworkMediaDrm.DEFAULT_PROVIDER)
                .build(mediaDrmCallback);

        // Create a data source factory.
        DataSource.Factory dataSourceFactory = getDefaultDataSourceFactory();

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