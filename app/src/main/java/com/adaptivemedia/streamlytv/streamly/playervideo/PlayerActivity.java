package com.adaptivemedia.streamlytv.streamly.playervideo;

import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.Uri;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.text.format.Formatter;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;

import androidx.annotation.Nullable;
import androidx.core.widget.ContentLoadingProgressBar;
import androidx.fragment.app.FragmentActivity;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProvider;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.streamly.dialogs.CustomDialog;
import com.adaptivemedia.streamlytv.streamly.log_in.QrVisibility;
import com.adaptivemedia.streamlytv.streamly.model.GetIp;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpDataModel;
import com.google.android.exoplayer2.DefaultLoadControl;
import com.google.android.exoplayer2.ExoPlaybackException;
import com.google.android.exoplayer2.ExoPlayer;
import com.google.android.exoplayer2.ExoPlayerFactory;
import com.google.android.exoplayer2.PlaybackParameters;
import com.google.android.exoplayer2.Player;
import com.google.android.exoplayer2.SimpleExoPlayer;
import com.google.android.exoplayer2.Timeline;
import com.google.android.exoplayer2.drm.DefaultDrmSessionManager;
import com.google.android.exoplayer2.drm.DrmSessionManager;
import com.google.android.exoplayer2.drm.ExoMediaCrypto;
import com.google.android.exoplayer2.drm.FrameworkMediaDrm;
import com.google.android.exoplayer2.drm.HttpMediaDrmCallback;
import com.google.android.exoplayer2.drm.MediaDrmCallback;
import com.google.android.exoplayer2.source.BehindLiveWindowException;
import com.google.android.exoplayer2.source.MediaSource;
import com.google.android.exoplayer2.source.TrackGroupArray;
import com.google.android.exoplayer2.source.dash.DashMediaSource;
import com.google.android.exoplayer2.trackselection.AdaptiveTrackSelection;
import com.google.android.exoplayer2.trackselection.DefaultTrackSelector;
import com.google.android.exoplayer2.trackselection.TrackSelectionArray;
import com.google.android.exoplayer2.trackselection.TrackSelector;
import com.google.android.exoplayer2.ui.PlayerView;
import com.google.android.exoplayer2.upstream.BandwidthMeter;
import com.google.android.exoplayer2.upstream.DataSource;
import com.google.android.exoplayer2.upstream.DefaultBandwidthMeter;
import com.google.android.exoplayer2.upstream.DefaultDataSourceFactory;
import com.google.android.exoplayer2.upstream.DefaultHttpDataSourceFactory;
import com.google.android.exoplayer2.upstream.HttpDataSource;
import com.google.android.exoplayer2.util.Util;
import com.google.firebase.analytics.FirebaseAnalytics;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.epg.ChannellistModel;
import com.adaptivemedia.streamlytv.streamly.epg.Datum;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.playervideo.allchannel.AllChannelAdapter;
import com.adaptivemedia.streamlytv.streamly.playervideo.drm.DrmKeyModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.drm.DrmViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.zipcode.ZipCodeDialog;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiResponse;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.net.InetAddress;
import java.net.NetworkInterface;
import java.net.SocketException;
import java.net.UnknownHostException;
import java.util.Collections;
import java.util.Enumeration;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.UUID;

import okhttp3.OkHttpClient;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class PlayerActivity extends FragmentActivity {
    private static final String TAG = PlayerActivity.class.getSimpleName();
    private ProgressDialog dialog;
    String authToken, uniqueId, userId;
    SimpleExoPlayer player;
    PlayerView playerView;
    boolean checkStreamingPlying;
    private FirebaseAnalytics mFirebaseAnalytics;
    Bundle params;
    Handler handler = new Handler();
    final int delay = 10000; //millisecondsaddto
    Runnable runnable;
    RecyclerView allChannelRecyclerView;
    List<Datum> channelList;
    AllChannelAdapter allChannelAdapter;
    ImageView logoImage, menuImage, drawerCloseImage;
    LinearLayout drawerLayout;
    String channel_id = "";
    String item = "0";
    String rvItemClick = "0";
    String channelId;
    int finalPosition;
    ProgressBar loader;

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
        // Create player
        player = new SimpleExoPlayer.Builder(this).build();
        playerView = findViewById(R.id.playerView);
        logoImage = findViewById(R.id.logo);
        menuImage = findViewById(R.id.menu_icon);
        drawerCloseImage = findViewById(R.id.drawer_close);
        drawerLayout = findViewById(R.id.player_channel_frame);
        allChannelRecyclerView = findViewById(R.id.player_channel_list);
        authToken = TokenStorage.readSharedToken(PlayerActivity.this, ConstantUtility.AUTH_TOKEN, null);
        userId = TokenStorage.readSharedToken(PlayerActivity.this, ConstantUtility.USER_ID, null);
        uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        playerView.setUseController(false);
        dialog = CommonUtility.custom_loader(this);
        loader = findViewById(R.id.load);




//        playerView.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
//            @Override
//            public void onPrepared(MediaPlayer mediaPlayer) {
//                mediaPlayer.setScreenOnWhilePlaying(false);
//            }
//        });
        loader.setVisibility(View.VISIBLE);
        playerView.setShowBuffering(playerView.SHOW_BUFFERING_ALWAYS);
        // Obtain the FirebaseAnalytics instance.
        mFirebaseAnalytics = FirebaseAnalytics.getInstance(this);
        params = new Bundle();
        channelId = getIntent().getStringExtra("id");
        System.out.println("Channel Name : "+ channelId);
        if (getIntent().getStringExtra("item") != null) {
            item = getIntent().getStringExtra("item");
        }

        params.putString("call_sign", channelId + "_Android");
        params.putString("inside_geofence", "false");
        mFirebaseAnalytics.setUserId(userId);


        Log.e("Player","Token : "+authToken);

        getIpAddress2();
        //getDrmKey();

        menuImage.setOnClickListener(view -> {
            menuImage.setVisibility(View.GONE);
            drawerLayout.setVisibility(View.VISIBLE);
        });


        drawerCloseImage.setOnClickListener(view -> {
            menuImage.setVisibility(View.VISIBLE);
            drawerLayout.setVisibility(View.GONE);
        });

    }






    public void playNext(String channelN, String videoUrl, String channelId, int position) {

        playerView.setShowBuffering(playerView.SHOW_BUFFERING_ALWAYS);
        Log.e(TAG, "playNext: 1 " + channelId);
        Log.e(TAG, "playNext: 2 " + videoUrl);
        Log.e(TAG, "playNext: 3 " + channelN);
        rvItemClick = "1";
        finalPosition = position;
        checkStreamingPlying=true;
//        Intent i = new Intent(this , PlayerActivity.class);
//        i.putExtra("from", videoUrl);
//        i.putExtra("channel_name", channelN);
//        i.putExtra("channel_id", channelId);
//        i.putExtra("item", "1");
//        startActivity(i);
//        finish();
//
//        handler.removeCallbacks(runnable);
//        player.stop();
//        Log.e(TAG,"back_press_called");
//        finish();

        player.stop();


        getDrmKey2(videoUrl, channelId, channelN);

        menuImage.setVisibility(View.VISIBLE);
        drawerLayout.setVisibility(View.GONE);



//        initializePlayer2("",videoUrl,channelN,channelId);
    }


    private void getLatLong(String ip) {
       /* WifiManager wm = (WifiManager) getApplicationContext().getSystemService(WIFI_SERVICE);
        String ip = Formatter.formatIpAddress(wm.getConnectionInfo().getIpAddress());
        Log.e(TAG, "Calling ip address ");
        Log.e(TAG, "IP_Address: " + ip);
        ip = getIpAddress();
        Log.e(TAG, "IP_Address: " + ip);
  //     ip = "157.42.78.63";
//        157.42.78.63*/

        Log.e(TAG,"IP "+ip);


        IpViewModel ipViewModel = new ViewModelProvider(this).get(IpViewModel.class);
        ipViewModel.getLatLong(ip, PlayerActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                IpDataModel ipModel = (IpDataModel) apiResponse.getApiResponse();
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        if (ipModel.getError()) {
                            CommonUtility.showToast(PlayerActivity.this, ipModel.getReason());
                            Log.e(TAG, ipModel.getReason());
                            finish();
                        } else {
                            Double dbLat = ipModel.getLatitude();
                            Double dblon = ipModel.getLongitude();
                            Log.e(TAG, "Calling postGeo ");
                            postGeofenceCall(dbLat, dblon);
                        }
                        break;
                    case 401:

                        CommonUtility.showToast(PlayerActivity.this, ipModel.getReason());
                        break;
                    case 403:
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 404:
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 429:
                        getDrmKey();
                        break;
                    case 500:
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                }

            }
        });

    }

    private void getIpAddress2() {
        ApiInterface apiInterface = ApiClient.getIPAdd().create(ApiInterface.class);
        Call<GetIp> call = apiInterface.getDeviceIpAddress();
        call.enqueue(new Callback<GetIp>() {
            @Override
            public void onResponse(Call<GetIp> call, Response<GetIp> response) {
               String  ip = response.body().getIpAddress();
               getLatLong(ip);
            }

            @Override
            public void onFailure(Call<GetIp> call, Throwable t) {
                Log.e(TAG, "Ip Fail");
            }
        });
    }


    public String getIpAddress() {
        WifiManager wifiManager = (WifiManager) getApplicationContext()
                .getSystemService(WIFI_SERVICE);

        String ipAddress = intToInetAddress(wifiManager.getDhcpInfo().ipAddress).toString();
        Log.e(TAG, "Calling intToInet ");

        ipAddress = ipAddress.substring(1);

        return ipAddress;
    }

    public InetAddress intToInetAddress(int hostAddress) {
        byte[] addressBytes = { (byte)(0xff & hostAddress),
                (byte)(0xff & (hostAddress >> 8)),
                (byte)(0xff & (hostAddress >> 16)),
                (byte)(0xff & (hostAddress >> 24)) };

        try {
            return InetAddress.getByAddress(addressBytes);
        } catch (UnknownHostException e) {
            throw new AssertionError();
        }
    }


    private void postGeofenceCall(Double dbLat, Double dblon) {
        // CommonUtility.showToast(PlayerActivity.this, "lat" + lat + " long " + lon);

        String[] LatLong = TokenStorage.readHomeZipCodeName(getApplicationContext());
        String lat = LatLong[0];
        String lon = LatLong[1];

        if ((lat != "" && lon != "") && (lat != dbLat.toString() && lon != dblon.toString())) {
            getDrmKey();
        } else {

        TokenStorage.saveHomeZipCodeName(getApplicationContext(), dbLat, dblon);
        Log.e(TAG, "new " + dbLat + " " + dblon);
        Map<String, Double> map = new HashMap<>();
        map.put("latitude", dbLat);
        map.put("longitude", dblon);
//        map.put("latitude",33.88768366489836);
//        map.put("longitude",-17.90419095752048);

        GeofenceViewModel viewModel = new ViewModelProvider(this).get(GeofenceViewModel.class);
        viewModel.getData(map, PlayerActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                GeofenceModel geofenceModel = (GeofenceModel) apiResponse.getApiResponse();
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        getDrmKey();
                        Log.e(TAG, "Calling getDrm 200 ");
                        break;
                    case 401:
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(PlayerActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
                        break;
                    case 403:
                        if (item.equals("1")) {
                            getDrmKey();
                            Log.e(TAG, "Calling getDrm 403 ");
                            item = "0";
                        } else {
                            ZipCodeDialog zipCodeDialog = new ZipCodeDialog(PlayerActivity.this, geofenceModel.getPopupmsg(), uniqueId, dbLat + "", dblon + "", authToken, new ZipCodeDialog.DialogCallBack() {
                                @Override
                                public void onExitPress() {
//                                    calling onBackPress
                                    Log.e(TAG, "Calling back ");
                                    PlayerActivity.this.onBackPressed();
                                }

                                @Override
                                public void onContinuePress() {
                                    Log.e(TAG, "Calling continue ");
//                                    do Nothing here
                                }
                            });
                            zipCodeDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            zipCodeDialog.show();
//                            getDrmKey();
                        }
                        break;
                    case 429:
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    default:
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
                }
            }
        });
    }
        }

    public void getDrmKey() {
        DrmViewModel viewModel = new ViewModelProvider(this).get(DrmViewModel.class);
        viewModel.getData(PlayerActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                Log.i(TAG, "onChanged: "+apiResponse.toString());
                DrmKeyModel drmKeyModel = (DrmKeyModel) apiResponse.getApiResponse();
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        String drmKey = drmKeyModel.getData();
                        String wvUrl = drmKeyModel.getWidevinelicense();
                            Log.e(TAG, "onChanged: getDrmKey " + wvUrl);
                        Log.e(TAG, "onChanged: getDrmKey " + drmKey);
                        //call all channel api....
                        getChannelList(drmKey, wvUrl);
                        break;
                    case 401:
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(PlayerActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
                        break;
                    case 404:
                        TokenStorage.clearSharedToken(PlayerActivity.this);
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SESSION_EXPIRED);
                        startActivity(new Intent(PlayerActivity.this, LogInActivity.class));
                        finish();
                        break;
                    default:
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                }
            }
        });

    }

    private void getDrmKey2(String videoUrl, String channelId, String channelN) {
        DrmViewModel viewModel = new ViewModelProvider(this).get(DrmViewModel.class);
        viewModel.getData(PlayerActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                DrmKeyModel drmKeyModel = (DrmKeyModel) apiResponse.getApiResponse();
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        String drmKey = drmKeyModel.getData();
                        String wvUrl = drmKeyModel.getWidevinelicense();
                        //call all channel api....
//                        getChannelList(drmKey,wvUrl);
                        Log.e(TAG, "onChanged: getDrmKey2 " + wvUrl);
                        Log.e(TAG, "onChanged: getDrmKey2 " + drmKey);
                        if(checkStreamingPlying)
                        {
                            checkStreamingPlying=false;
                            initializePlayer2(drmKey, wvUrl, channelN, channelId, videoUrl);
                        }

                        break;
                    case 401:
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(PlayerActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
                        break;
                    case 404:
                        TokenStorage.clearSharedToken(PlayerActivity.this);
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SESSION_EXPIRED);
                        startActivity(new Intent(PlayerActivity.this, LogInActivity.class));
                        finish();
                        break;
                    default:
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                }
            }
        });

    }

    private void initializePlayer2(String drmKey, String wvUrl, String channelN, String channelId, String videoUrl) {
        playerView.setShowBuffering(playerView.SHOW_BUFFERING_ALWAYS);
        Log.e(TAG, "initializePlayer2: " + drmKey);
        Log.e(TAG, "initializePlayer2: " + wvUrl);
        Log.e(TAG, "initializePlayer2: " + channelN);
        Log.e(TAG, "initializePlayer2: " + channelId);
        String streamUrl = videoUrl;

        String channelIdIntent = channelId;
        if (channelIdIntent != null) {
            channel_id = channelIdIntent;
        }

        // URL of the KeyOS MultiKey License API for Widevine
//        String wvUrl = "https://wv-keyos.licensekeyserver.com/";
//        String wvURL1="http://127.0.0.1:8000/api/generic?drm-type=widevine";
//        String wvURL1="https://streamlytv.com/api/generic?drm-type=widevine";
//        String wvUrl1="https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=widevine";
//        String streamUrl2="https://channelstore.streamlytv.com/streamly/AETVP.stream/manifest.mpd";
//        Uri dashUri = Uri.parse("https://path/to/your/stream.mpd");

        Uri dashUri = Uri.parse(streamUrl.trim());
//        Uri dashUri = Uri.parse("https://cdn.streamlytv.com/espn/index.m3u8");
//        Uri dashUri = Uri.parse("https://dash.akamaized.net/envivio/EnvivioDash3/manifest.mpd");

        Log.e(TAG, "1) license_server: " + wvUrl + "\n"
                + "2) DRM_key: " + drmKey + "\n"
                + "3) Stream_URL: " + streamUrl + "\n"
                + "4) dash_uri: " + dashUri
                + "5) channel_id: " + channelIdIntent
        );

        // Identifies the Widevine DRM
        UUID widevineUuid = UUID.fromString("edef8ba9-79d6-4ace-a3c8-27dcd51d21ed");

        // Security token required to acquire a license. Base64 encoded Authentication XML
        String[] keyRequestProperties = new String[]{"x-keyos-authorization", drmKey};
//        String[] keyRequestProperties = new String[] { "customdata", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTEwLTE5IDE0OjUxOjU1LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMTAtMTkgMTU6MDY6NTUuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZjEzNGMxNjBlYjU1ZTE5ZTJjMjUxODM1YjZlYjdjZDY8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+dS9YQVgxNnNxU2FvUU43UW9FKzY3TlNuTzY0UXFhbUVBOUFsYnNVSlozOWdUT1l3NEpDTW9md3NaYkFQWWVJaS9DKy9SMkJweHJwVEpuWS9ZRGx2dERoSFluYlNwYy9wMXNKcUxOdmREaE4yejEwS2wyT0hKekE1OTdxU0Urcnd2d3BHd09FSm1iVEFidm8zK3FTQVdsTnQ0dWJoMTg0M1VQU3FYMDVZNFEzdlJLWXdzSzR5SUxRdFJuTStENDJsWXcvcTlpc0pUR0pGVWRqY1hJVnh3QWdPRGpDNEdWQVIycXM1VXhHY1cyT0c0THd6ZWpkeUkwMENrK1FYZHpNcTVab3pUZ243a2ZJaGpPS0JETUx0L2VrNnhNYTZ2VjRTQlZEdWxkYnZCR1BjZW9CVzNYWDc1WFY4VjZxdUxJM29GM3FTM3FZQzUvNkJiRmNuQ0k2YjFRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="};

        // Create the DRM session manager and set required request parameters.
        MediaDrmCallback mediaDrmCallback = createMediaDrmCallback(wvUrl, keyRequestProperties);
        DrmSessionManager<ExoMediaCrypto> drmSessionManager = new DefaultDrmSessionManager.Builder()
                .setUuidAndExoMediaDrmProvider(widevineUuid, FrameworkMediaDrm.DEFAULT_PROVIDER)
                .build(mediaDrmCallback);


        DataSource.Factory dataSourceFactory = new DefaultDataSourceFactory(PlayerActivity.this,
                Util.getUserAgent(this, "StreamlyTV"), null);
        //todo HLs for the testing purpose (m3u8)
//        HlsMediaSource mediaSource =new HlsMediaSource.Factory(dataSourceFactory)
//                .setDrmSessionManager(drmSessionManager)
//                .createMediaSource(dashUri);

        //todo DASH for the testing purpose (mpd)
        MediaSource mediaSource = new DashMediaSource.Factory(dataSourceFactory)
                .setDrmSessionManager(drmSessionManager)
                .createMediaSource(dashUri);

//        DashMediaSource mediaSource = new DashMediaSource(dashUri, dataSourceFactory,
//                new DefaultDashChunkSource.Factory(dataSourceFactory),
//                null, null);

        BandwidthMeter bandwidthMeter = new DefaultBandwidthMeter();
        TrackSelector trackSelector = new DefaultTrackSelector(new AdaptiveTrackSelection.Factory(bandwidthMeter));
        DefaultLoadControl loadControl = new DefaultLoadControl.Builder().setBufferDurationsMs(32*1024, 64*1024, 1024, 1024).createDefaultLoadControl();

        player = ExoPlayerFactory.newSimpleInstance(this, trackSelector,loadControl);


////////////////////////////////////////////////////////////////////////////////////////////////////
        //todo for the DRM protected streaming
        // Create a data source factory.
//        DataSource.Factory dataSourceFactory = getDefaultDataSourceFactory();
        // Create a DASH media source pointing to a DASH manifest uri.
//        MediaSource mediaSource = new DashMediaSource.Factory(dataSourceFactory)
//                .setDrmSessionManager(drmSessionManager)
//                .createMediaSource(dashUri);
/////////////////////////////////////////////////////////////////////////////////////////////////////
        // Attach it to the view
        playerView.setPlayer(player);

        // Preapre the player with content
        player.prepare(mediaSource);

        player.addListener(new Player.EventListener() {
            @Override
            public void onTimelineChanged(Timeline timeline, int reason) {

            }

            @Override
            public void onTimelineChanged(Timeline timeline, @Nullable Object manifest, int reason) {

            }

            @Override
            public void onTracksChanged(TrackGroupArray trackGroups, TrackSelectionArray trackSelections) {

            }

            @Override
            public void onLoadingChanged(boolean isLoading) {
                /*if (isLoading){
                    mFirebaseAnalytics.logEvent("video_play", params);
                }*/
            }

            @Override
            public void onPlayerStateChanged(boolean playWhenReady, int playbackState) {
                switch (playbackState) {
                    case ExoPlayer.STATE_IDLE:
                        break;

                    case ExoPlayer.STATE_READY:
                        break;

                    case ExoPlayer.STATE_BUFFERING:
                        break;

                    case ExoPlayer.STATE_ENDED:
                        break;
                }


            }

            @Override
            public void onPlaybackSuppressionReasonChanged(int playbackSuppressionReason) {

            }

            @Override
            public void onIsPlayingChanged(boolean isPlaying) {

                if (isPlaying) {


//                    logoImage.setVisibility(View.VISIBLE);
//                    menuImage.setVisibility(View.GONE);

//
                    Log.e(TAG, "yes" );
                    mFirebaseAnalytics.logEvent("video_play", params);
//
                    runnable = new Runnable() {
                        @Override
                        public void run() {
                            //do something
                            params.putString("livestream_seconds_watched", "15");
                            mFirebaseAnalytics.logEvent("video_livestream_duration", params);
                            handler.postDelayed(this, delay);
                        }
                    };
                    handler.postDelayed(runnable, delay);
                } else {
                    Log.e(TAG, "no" );
//                    logoImage.setVisibility(View.GONE);
//                    menuImage.setVisibility(View.VISIBLE);

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
                /*CustomDialog customDialog = new CustomDialog(PlayerActivity.this,"Due to some technical reason, channel gets interrupted. Kindly try again later","StreamlyTV");
                customDialog.show();*/
                AlertDialog.Builder dialog = new AlertDialog.Builder(PlayerActivity.this, R.style.AlertDialogCustom);
                dialog.setTitle("StreamlyTV");
                dialog.setMessage("Due to some technical reason, channel gets interrupted. Kindly try again later");

                // Specifying a listener allows you to take an action before dismissing the dialog.
                // The dialog is automatically dismissed when a dialog button is clicked.
                dialog.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // Continue with delete operation
                        dialog.dismiss();
                        handler.removeCallbacks(runnable);
                        player.stop();
                        finish();


                    }
                });
                dialog.show();
                dialog.setCancelable(true);
                Log.e(TAG, "error_msg" + error.toString());
                params.putString("error_color", "red");
                params.putString("error_msg", error.getSourceException().toString());
                mFirebaseAnalytics.logEvent("player_error", params);
//                Log.e(TAG,"error_msg2 "+error.getSourceException());

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
//        player.setRepeatMode(Player.REPEAT_MODE_ALL);
        postRecentViewChannel(channel_id);

    }

    private void initializePlayer(String drmKey, String wvUrl) {
//        dialog.show();
        playerView.setShowBuffering(playerView.SHOW_BUFFERING_ALWAYS);
        Log.i(TAG, "initializePlayer: "+drmKey);
        Log.i(TAG, "initializePlayer: "+wvUrl);
        final String streamUrl = getIntent().getStringExtra("from");

        String channelIdIntent = getIntent().getStringExtra("channel_id");
        if (channelIdIntent != null) {
            channel_id = channelIdIntent;
        }

        // URL of the KeyOS MultiKey License API for Widevine
//        String wvUrl = "https://wv-keyos.licensekeyserver.com/";
//        String wvURL1="http://127.0.0.1:8000/api/generic?drm-type=widevine";
//        String wvURL1="https://streamlytv.com/api/generic?drm-type=widevine";
//        String wvUrl1="https://demo.keyos.com/roman/logging-proxy/generic-neovix.php?drm-type=widevine";
//        String streamUrl2="https://channelstore.streamlytv.com/streamly/AETVP.stream/manifest.mpd";
//        Uri dashUri = Uri.parse("https://path/to/your/stream.mpd");

        Uri dashUri = Uri.parse(streamUrl.trim());
//        Uri dashUri = Uri.parse("https://cdn.streamlytv.com/espn/index.m3u8");
//        Uri dashUri = Uri.parse("https://5db313b643fd8.streamlock.net:443/ZerounoTVEventi/ZerounoTVEventi/playlist.m3u8");
//        Uri dashUri = Uri.parse("https://dash.akamaized.net/envivio/EnvivioDash3/manifest.mpd");

        Log.e(TAG, "1) license_server: " + wvUrl + "\n"
                + "2) DRM_key: " + drmKey + "\n"
                + "3) Stream_URL: " + streamUrl + "\n"
                + "4) dash_uri: " + dashUri
                + "5) channel_id: " + channelIdIntent
        );

        // Identifies the Widevine DRM
        UUID widevineUuid = UUID.fromString("edef8ba9-79d6-4ace-a3c8-27dcd51d21ed");

        // Security token required to acquire a license. Base64 encoded Authentication XML
        String[] keyRequestProperties = new String[]{"x-keyos-authorization", drmKey};
//        String[] keyRequestProperties = new String[] { "customdata", "PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDIwLTEwLTE5IDE0OjUxOjU1LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjAtMTAtMTkgMTU6MDY6NTUuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZjEzNGMxNjBlYjU1ZTE5ZTJjMjUxODM1YjZlYjdjZDY8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+dS9YQVgxNnNxU2FvUU43UW9FKzY3TlNuTzY0UXFhbUVBOUFsYnNVSlozOWdUT1l3NEpDTW9md3NaYkFQWWVJaS9DKy9SMkJweHJwVEpuWS9ZRGx2dERoSFluYlNwYy9wMXNKcUxOdmREaE4yejEwS2wyT0hKekE1OTdxU0Urcnd2d3BHd09FSm1iVEFidm8zK3FTQVdsTnQ0dWJoMTg0M1VQU3FYMDVZNFEzdlJLWXdzSzR5SUxRdFJuTStENDJsWXcvcTlpc0pUR0pGVWRqY1hJVnh3QWdPRGpDNEdWQVIycXM1VXhHY1cyT0c0THd6ZWpkeUkwMENrK1FYZHpNcTVab3pUZ243a2ZJaGpPS0JETUx0L2VrNnhNYTZ2VjRTQlZEdWxkYnZCR1BjZW9CVzNYWDc1WFY4VjZxdUxJM29GM3FTM3FZQzUvNkJiRmNuQ0k2YjFRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg=="};

        // Create the DRM session manager and set required request parameters.
        MediaDrmCallback mediaDrmCallback = createMediaDrmCallback(wvUrl, keyRequestProperties);
        DrmSessionManager<ExoMediaCrypto> drmSessionManager = new DefaultDrmSessionManager.Builder()
                .setUuidAndExoMediaDrmProvider(widevineUuid, FrameworkMediaDrm.DEFAULT_PROVIDER)
                .build(mediaDrmCallback);


        DataSource.Factory dataSourceFactory = new DefaultDataSourceFactory(PlayerActivity.this,
                Util.getUserAgent(this, "StreamlyTV"), null);
        //todo HLs for the testing purpose (m3u8)
//        HlsMediaSource mediaSource =new HlsMediaSource.Factory(dataSourceFactory)
//                .setDrmSessionManager(drmSessionManager)
//                .createMediaSource(dashUri);

        //todo DASH for the testing purpose (mpd)
        MediaSource mediaSource = new DashMediaSource.Factory(dataSourceFactory)
                .setDrmSessionManager(drmSessionManager)
                .createMediaSource(dashUri);

//        DashMediaSource mediaSource = new DashMediaSource(dashUri, dataSourceFactory,
//                new DefaultDashChunkSource.Factory(dataSourceFactory),
//                null, null);

        BandwidthMeter bandwidthMeter = new DefaultBandwidthMeter();
        TrackSelector trackSelector = new DefaultTrackSelector(new AdaptiveTrackSelection.Factory(bandwidthMeter));
//        DefaultLoadControl loadControl = new DefaultLoadControl.Builder().setBufferDurationsMs(32*1024, 64*1024, 1024, 1024).createDefaultLoadControl();
//        DefaultLoadControl loadControl = new DefaultLoadControl.Builder().setBufferDurationsMs(99*1024, 120*1024, 1024, 1024).createDefaultLoadControl();
        player = ExoPlayerFactory.newSimpleInstance(this, trackSelector);


////////////////////////////////////////////////////////////////////////////////////////////////////
        //todo for the DRM protected streaming
        // Create a data source factory.
//        DataSource.Factory dataSourceFactory = getDefaultDataSourceFactory();
        // Create a DASH media source pointing to a DASH manifest uri.
//        MediaSource mediaSource = new DashMediaSource.Factory(dataSourceFactory)
//                .setDrmSessionManager(drmSessionManager)
//                .createMediaSource(dashUri);
/////////////////////////////////////////////////////////////////////////////////////////////////////
        // Attach it to the view

        playerView.setPlayer(player);

        // Preapre the player with content
        player.prepare(mediaSource);



        player.addListener(new Player.EventListener() {
            @Override
            public void onTimelineChanged(Timeline timeline, int reason) {
                Log.i(TAG, "onTimelineChanged: 1 "+timeline);
                Log.i(TAG, "onTimelineChanged: 2 "+reason);
            }

            @Override
            public void onTimelineChanged(Timeline timeline, @Nullable Object manifest, int reason) {
                Log.i(TAG, "onTimelineChanged: 3 "+timeline);
                Log.i(TAG, "onTimelineChanged: 4 "+reason);
            }

            @Override
            public void onTracksChanged(TrackGroupArray trackGroups, TrackSelectionArray trackSelections) {
                Log.i(TAG, "onTracksChanged: "+trackGroups);
                Log.i(TAG, "onTracksChanged: "+trackSelections);
            }

            @Override
            public void onLoadingChanged(boolean isLoading) {
                Log.i(TAG, "onLoadingChanged: "+isLoading);
                /*if (isLoading){
                    mFirebaseAnalytics.logEvent("video_play", params);
                }*/
            }

            @Override
            public void onPlayerStateChanged(boolean playWhenReady, int playbackState) {
                switch (playbackState) {
                    case ExoPlayer.STATE_IDLE:
                        Log.i(TAG, "onPlayerStateChanged: idle ");
                        break;
                    case ExoPlayer.STATE_READY:
                        Log.i(TAG, "onPlayerStateChanged: ready ");
                        break;
                    case ExoPlayer.STATE_BUFFERING:
                        Log.i(TAG, "onPlayerStateChanged: buffering ");
                        break;
                    case ExoPlayer.STATE_ENDED:
                        Log.i(TAG, "onPlayerStateChanged: STATE_ENDED  ");
                        break;
                }
            }

            @Override
            public void onPlaybackSuppressionReasonChanged(int playbackSuppressionReason) {
                Log.i(TAG, "onPlaybackSuppressionReasonChanged: " );

            }



            @Override
            public void onIsPlayingChanged(boolean isPlaying) {

                if (isPlaying) {
                    Log.e(TAG, "yes");
                    mFirebaseAnalytics.logEvent("video_play", params);

                    runnable = new Runnable() {
                        @Override
                        public void run() {
                            //do something
                            params.putString("livestream_seconds_watched", "15");
                            mFirebaseAnalytics.logEvent("video_livestream_duration", params);
                            handler.postDelayed(this, delay);
                        }
                    };
                    handler.postDelayed(runnable, delay);
                } else {
                    Log.i(TAG, "no ");
//                    logoImage.setVisibility(View.GONE);
//                    menuImage.setVisibility(View.VISIBLE);

                }

            }

            @Override
            public void onRepeatModeChanged(int repeatMode) {
                Log.i(TAG, "onRepeatModeChanged: else " );
            }

            @Override
            public void onShuffleModeEnabledChanged(boolean shuffleModeEnabled) {
                Log.i(TAG, "onShuffleModeEnabledChanged: " );

            }

            @Override
            public void onPlayerError(ExoPlaybackException error) {
                /*CustomDialog customDialog = new CustomDialog(PlayerActivity.this,"Due to some technical reason, channel gets interrupted. Kindly try again later", "StreamlyTV");
                customDialog.show();*/
                AlertDialog.Builder dialog = new AlertDialog.Builder(PlayerActivity.this, R.style.AlertDialogCustom);
                dialog.setTitle("StreamlyTV");
                dialog.setMessage("Due to some technical reason, channel gets interrupted. Kindly try again later");

                // Specifying a listener allows you to take an action before dismissing the dialog.
                // The dialog is automatically dismissed when a dialog button is clicked.
                dialog.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // Continue with delete operation
                        dialog.dismiss();
                        handler.removeCallbacks(runnable);
                        player.stop();
                        finish();


                    }
                });
                dialog.show();
                dialog.setCancelable(true);

                Log.i(TAG, "error_msg" + error.toString());
                params.putString("error_color", "red");
                params.putString("error_msg", error.getSourceException().toString());
                mFirebaseAnalytics.logEvent("player_error", params);
//                Log.e(TAG,"error_msg2 "+error.getSourceException());

            }

            @Override
            public void onPositionDiscontinuity(int reason) {
                Log.i(TAG, "onPositionDiscontinuity: " );

            }

            @Override
            public void onPlaybackParametersChanged(PlaybackParameters playbackParameters) {
                Log.i(TAG, "onPlaybackParametersChanged: " );

            }

            @Override
            public void onSeekProcessed() {
                Log.i(TAG, "onSeekProcessed: " );

            }
        });

        player.setPlayWhenReady(true);
        postRecentViewChannel(channel_id);


    }

    @Override
    public void onBackPressed() {
            super.onBackPressed();
            handler.removeCallbacks(runnable);
            player.stop();
            Log.e(TAG, "back_press_called");
            finish();
    }


    @Override
    protected void onStop() {
        super.onStop();
        handler.removeCallbacks(runnable);
        Log.e(TAG, "on_stop_called");
        player.stop();
    }

    private void getChannelList(String drmKey, String wvUrl) {

        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ChannellistModel> call = apiInterface.getChannelList("Bearer " + authToken);
        call.enqueue(new Callback<ChannellistModel>() {
            @Override
            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {
                try {



                    if (response.isSuccessful() && response.body() != null && response.code() != 401) {

                        channelList = response.body().getData();
                        Log.e("channelList-------", channelList.get(0).getUrl());
                        Log.e("channelList-------", channelList.get(0).getId().toString());
                        Log.e("channelList-------", wvUrl);
                        String url = getIntent().getStringExtra("from");
//                        check for current channel name and store the position of the current channle and auto scroll
//                        that position.
                        int position = 0;
                        for (int i = 0; i < channelList.size(); i++) {
                            if (url.equals(channelList.get(i).getUrl())) {
                                channel_id = channelList.get(i).getId().toString();
                                Log.e(TAG, "onResponse: channel list id " + channelList.get(i).getId().toString());
                            }
                            if(channelList.get(i).getId().toString().equals(channelId)){
                                position = i;
                            }
                        }



                        if(rvItemClick.equals("1")){
                            allChannelRecyclerView.setLayoutManager(new LinearLayoutManager(PlayerActivity.this, LinearLayoutManager.VERTICAL, false));
                            allChannelAdapter = new AllChannelAdapter(PlayerActivity.this, channelList, finalPosition);
                            allChannelRecyclerView.setAdapter(allChannelAdapter);

                        }else {

                            allChannelRecyclerView.setLayoutManager(new LinearLayoutManager(PlayerActivity.this, LinearLayoutManager.VERTICAL, false));
                            allChannelAdapter = new AllChannelAdapter(PlayerActivity.this, channelList, position);
                            allChannelRecyclerView.setAdapter(allChannelAdapter);

                             finalPosition = position;
                        }
                        allChannelRecyclerView.post(new Runnable() {
                            @Override
                            public void run() {
                                allChannelRecyclerView.setSelected(true);
                                allChannelRecyclerView.setFocusable(true);
                                allChannelRecyclerView.scrollToPosition(finalPosition);
                            }
                        });

                        menuImage.setVisibility(View.VISIBLE);
                        if (rvItemClick.equals("1")) {
                            rvItemClick = "0";

                        } else {
                            loader.setVisibility(View.GONE);
                            initializePlayer(drmKey, wvUrl);
                        }


                    } else if (response.code() == 401) {
                        if(response.errorBody()!=null){
                            SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(PlayerActivity.this);
                            sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            sessionExpiredDialog.setCancelable(false);
                            sessionExpiredDialog.show();


                        }

                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(PlayerActivity.this);
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SESSION_EXPIRED);


                    } else if (response.code() == 500) {
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);

                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                }
            }

            @Override
            public void onFailure(Call<ChannellistModel> call, Throwable t) {
                Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
                AlertDialog.Builder dialog = new AlertDialog.Builder(PlayerActivity.this, R.style.AlertDialogCustom);
                dialog.setTitle("StreamlyTV");
                dialog.setMessage("Due to some technical reason, channel gets interrupted. Kindly try again later");

                // Specifying a listener allows you to take an action before dismissing the dialog.
                // The dialog is automatically dismissed when a dialog button is clicked.
                dialog.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                    public void onClick(DialogInterface dialog, int which) {
                        // Continue with delete operation
                        dialog.dismiss();
                        handler.removeCallbacks(runnable);
                        player.stop();
                        finish();


                    }
                });
                dialog.show();
                dialog.setCancelable(true);
                CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
            }
        });
    }

    private void postRecentViewChannel(String channelId) {
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ChannellistModel> call = apiInterface.postRecentList("Bearer " + authToken, channelId);
        call.enqueue(new Callback<ChannellistModel>() {
            @Override
            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {
                try {

                    if (response.isSuccessful() && response.body() != null) {

//                        recentList = response.body().getData();
                        Log.e(TAG, "onResponse: recent list " + response.body().getData());


                    } else if (response.code() == 401) {
                        CommonUtility.showToast(PlayerActivity.this, response.body().getMessage());

                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(PlayerActivity.this);
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SESSION_EXPIRED);


                    } else if (response.code() == 500) {
                        CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);

                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                }
            }

            @Override
            public void onFailure(Call<ChannellistModel> call, Throwable t) {
                Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
                CommonUtility.showToast(PlayerActivity.this, ConstantUtility.SERVER_ISSUE);
            }
        });
    }

}