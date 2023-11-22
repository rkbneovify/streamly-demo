package com.adaptivemedia.streamlytv.streamly.playervideo;

import android.Manifest;
import android.annotation.SuppressLint;
import android.app.Activity;
import android.app.ActivityManager;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationManager;
import android.net.Uri;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.support.v4.media.session.MediaSessionCompat;
import android.text.TextUtils;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;
import androidx.fragment.app.FragmentActivity;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProvider;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.streamly.ProgramGuideFragment;

import com.adaptivemedia.streamlytv.streamly.dialogs.CommonDialogZipCode;
import com.adaptivemedia.streamlytv.streamly.dialogs.DialogZipCodeClickListeners;
import com.adaptivemedia.streamlytv.streamly.entity.ProgramGuideSchedule;
import com.adaptivemedia.streamlytv.streamly.model.GetIp;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpDataModel;
import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
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
import com.google.android.exoplayer2.drm.FrameworkMediaDrm;
import com.google.android.exoplayer2.drm.HttpMediaDrmCallback;
import com.google.android.exoplayer2.drm.MediaDrmCallback;
import com.google.android.exoplayer2.source.MediaSource;
import com.google.android.exoplayer2.source.TrackGroupArray;
import com.google.android.exoplayer2.source.dash.DashMediaSource;
import com.google.android.exoplayer2.trackselection.AdaptiveTrackSelection;
import com.google.android.exoplayer2.trackselection.DefaultTrackSelector;
import com.google.android.exoplayer2.trackselection.TrackSelectionArray;
import com.google.android.exoplayer2.trackselection.TrackSelector;
import com.google.android.exoplayer2.ui.PlayerControlView;
import com.google.android.exoplayer2.ui.PlayerView;
//import com.google.android.exoplayer2.ui.StyledPlayerView;
import com.google.android.exoplayer2.upstream.BandwidthMeter;
import com.google.android.exoplayer2.upstream.DataSource;
import com.google.android.exoplayer2.upstream.DefaultBandwidthMeter;
import com.google.android.exoplayer2.upstream.DefaultDataSourceFactory;
import com.google.android.exoplayer2.upstream.DefaultHttpDataSourceFactory;
import com.google.android.exoplayer2.upstream.HttpDataSource;
import com.google.android.exoplayer2.util.Util;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.common.api.PendingResult;
import com.google.android.gms.common.api.ResultCallback;
import com.google.android.gms.common.api.Status;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.location.LocationSettingsRequest;
import com.google.android.gms.location.LocationSettingsResult;
import com.google.android.gms.location.LocationSettingsStatusCodes;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
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
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.zipcode.ZipCodeDialog;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiResponse;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import org.json.JSONArray;
import org.json.JSONObject;
import org.threeten.bp.LocalDate;

import java.io.IOException;
import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.UUID;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class PlayerActivity extends FragmentActivity {
    private static final String TAG = PlayerActivity.class.getSimpleName();
    private ProgressDialog dialog;
    private PlayerErrorDialog playerErrorDialog;
    String authToken, uniqueId, userId;
    SimpleExoPlayer player;
    PlayerView playerView;
    ImageView exoNext, exoPre, exoPlay;
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
    String close_flag = "0";
    String rvItemClick = "0";
    String channelId, height, width, callsign;
    int finalPosition;
    ProgressBar loader;
    String url;
    private MediaSessionCompat mediaSession;
    private PlayerControlView playerControlView;
    protected static final int REQUEST_CHECK_SETTINGS = 0x1;
//    private GoogleApiClient googleApiClient;
    LocationManager locationManager;
    double latitude;
    double longitude;
    private FusedLocationProviderClient fusedLocationClient;
    private GoogleApiClient googleApiClient;
    String postalCode;
    private static final int LOCATION_PERMISSION_REQUEST_CODE = 1001;

    private String timeOff = null;

    private JSONArray jArrayZipCode2;

    private  JSONArray jArrayZipCode;


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

    @SuppressLint({"MissingPermission", "MissingInflatedId"})
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_player);
        // Create player
        player = new SimpleExoPlayer.Builder(this).build();
        playerView = findViewById(R.id.playerView);
        logoImage = findViewById(R.id.logo);
        menuImage = findViewById(R.id.menu_icon);
        exoNext = findViewById(R.id.exo_next);
        exoPre = findViewById(R.id.exo_prev);
        exoPlay = findViewById(R.id.exo_play);
        drawerCloseImage = findViewById(R.id.drawer_close);
        drawerLayout = findViewById(R.id.player_channel_frame);
        allChannelRecyclerView = findViewById(R.id.player_channel_list);
        authToken = TokenStorage.readSharedToken(PlayerActivity.this, ConstantUtility.AUTH_TOKEN, null);
        userId = TokenStorage.readSharedToken(PlayerActivity.this, ConstantUtility.USER_ID, null);
        uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        dialog = CommonUtility.custom_loader(this);
        loader = findViewById(R.id.load);

        DisplayMetrics displayMetrics = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        height = String.valueOf(displayMetrics.heightPixels);
        width = String.valueOf(displayMetrics.widthPixels);
        Log.e(TAG, "height::>>" + height + width);
        locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        fusedLocationClient = LocationServices.getFusedLocationProviderClient(this);
//        if (!locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)) {
//            OnGPS();
//        } else {
//            Log.e("get loc", "loc::>> " + latitude + " ::: " + longitude);
//            getLocation();
//
//        }

        PackageManager packageManager = getPackageManager();
        boolean hasLocationFeature = packageManager.hasSystemFeature(PackageManager.FEATURE_LOCATION);
//        checkLocationEnabled();
//        logoImage.setVisibility(View.GONE);
//        menuImage.setVisibility(View.GONE);
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
//        callsign = getIntent().getStringExtra("callsign");
        System.out.println("Channel Name : " + channelId);
        if (getIntent().getStringExtra("callsign") != null) {
            callsign = getIntent().getStringExtra("callsign");
        }
        if (getIntent().getStringExtra("timeOff") != null) {
            timeOff = getIntent().getStringExtra("timeOff");
        }
        System.out.println("Channel Name : " + callsign);
        if (getIntent().getStringExtra("item") != null) {
            item = getIntent().getStringExtra("item");
        }

        params.putString("call_sign", channelId + "_Android");
        params.putString("inside_geofence", "false");
        mFirebaseAnalytics.setUserId(userId);


        Log.e("Player", "Token : " + authToken);

        //getIpAddress2();
        url = getIntent().getStringExtra("from");
        getDrmKey();
//        getIpAddress2();

        menuImage.setOnClickListener(view -> {
            menuImage.setVisibility(View.GONE);
            drawerLayout.setVisibility(View.VISIBLE);
            close_flag = "1";
        });


        drawerCloseImage.setOnClickListener(view -> {
            //    menuImage.setVisibility(View.VISIBLE);
            drawerLayout.setVisibility(View.GONE);
            close_flag = "0";
        });

    }

    private void getIpAddress2() {
        ApiInterface apiInterface = ApiClient.getIPAdd().create(ApiInterface.class);
        Call<GetIp> call = apiInterface.getDeviceIpAddress();
        call.enqueue(new Callback<GetIp>() {
            @Override
            public void onResponse(Call<GetIp> call, Response<GetIp> response) {
                String  ip = response.body().getIpAddress();
                Log.d(TAG, "onResponse: "+ip.toString());
                getLatLong(ip);
            }

            @Override
            public void onFailure(Call<GetIp> call, Throwable t) {
                Log.d(TAG, "onFailure: "+t.getMessage());
//                getDrmKey();
            }
        });
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
                            if (ipModel != null) {

                                double lati = ipModel.getLatitude();
                                double longi = ipModel.getLongitude();

                                latitude = lati;
                                longitude = longi;
                                postalCode = ipModel.getPostal();
                                getChannelListWithZipCodeOnCreate();

                                Log.e("latitude", "latitude--<<" + latitude);
                                Log.e("latitude", "latitude--<<" + postalCode);
//                                Log.e("latitude", "latitude--<<" + geocoder.toString());
                            }
                            Log.e(TAG, "Calling postGeo "+dbLat.toString());
                            Log.e(TAG, "Calling postGeo "+dblon.toString());
//                            postGeofenceCall(dbLat, dblon);
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

    private void getChannelListWithZipCodeOnCreate() {
//        arrayList.clear();
        StringRequest stringRequest = new StringRequest(Request.Method.GET, (
                ConstantUtility.channelZipCode)+ timeOff,
                new com.android.volley.Response.Listener<String>() {
                    @SuppressLint("LongLogTag")
                    @Override
                    public void onResponse(String response) {
                        Log.e(TAG, "::getChannelList>>>>>>>>>===" + response);

                        try {
                            JSONObject object = new JSONObject(response);
                            String message;
                            String success;
                            success = object.getString("success");
                            message = object.getString("message");

                            if (success.equals("true")) {
                                JSONArray jsonArray = object.getJSONArray("data");
                                for (int i = 0; i < jsonArray.length(); i++) {
//                                    channelBean = new ChannelBean();
                                    JSONObject innerObj = jsonArray.getJSONObject(i);
                                    String callSignApi = innerObj.getString("callSign");

                                    try {
                                        if(callSignApi.equals(callsign)){
                                            Log.e(TAG, "onResponse: call sign 111 "+callSignApi);
                                            Log.e(TAG, "onResponse: call sign 111 "+callsign);
                                            Log.e(TAG, "onResponse: call sign 111 "+postalCode);
                                            if(innerObj.getString("zip_code")!=null){
                                                jArrayZipCode2 = new JSONArray(innerObj.getString("zip_code"));
                                                if(jArrayZipCode2.toString().contains(postalCode)){
                                                    getDrmKey();
                                                    break;
                                                }else if(jArrayZipCode2.toString().contains("Null") ){
                                                    getDrmKey();
                                                    break;
                                                }
                                                else{
                                                    channelNotAvailableAtYourLocation("This Channel is not available in your location.");
                                                    break;
                                                }



                                            }else{
                                                channelNotAvailableAtYourLocation("This Channel is not available in your location.");
                                                Log.d(TAG, "onResponse: call sign null 1114");
                                            }

                                        }
                                    }catch (Exception e){
                                        Log.e(TAG, "\n::>>>response json Array exception: "+ e.toString() );
//                                        e.printStackTrace();
                                    }

                                }

//

                            } else {
//                                Utils.Companion.showAlert(PlayerNewActivity.this, getString(R.string.app_name), message);
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }, new com.android.volley.Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if(error.getMessage()!=null){
                    Toast.makeText(getApplicationContext(), error.getMessage().toString(), Toast.LENGTH_SHORT).show();
                }



            }
        }) {
            @SuppressLint("LongLogTag")
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
//                Log.e(TAG, "params ::>>>>>>>>" + params);
                return params;
            }

            @SuppressLint("LongLogTag")
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers = new HashMap<>();
                headers.put("Accept", "application/json");
                headers.put("Authorization", "Bearer " + authToken);
//                Log.e(TAG, "headerrr ::>>>>>>>>" + headers);
                return headers;
            }
        };
        RequestQueue queue = Volley.newRequestQueue(this);
        queue.getCache().clear();
        stringRequest.setRetryPolicy(new DefaultRetryPolicy(60000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(stringRequest);
    }


    void channelNotAvailableAtYourLocation(String message) {
        CommonDialogZipCode c = new CommonDialogZipCode(message, new DialogZipCodeClickListeners() {
            @Override
            public void ok() {
                finish();
            }
        });

        c.show(getSupportFragmentManager(), "ffnadjg");

    }
    private void checkLocationEnabled() {
        LocationManager locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
        boolean isLocationEnabled = locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER);
        Log.e("isLocationEnabled", "isLocationEnabled>>>>>" + isLocationEnabled);
        if (!isLocationEnabled) {
            // Location is not enabled, guide the user to enable it
            Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
            startActivity(intent);
        }else{
            Toast.makeText(getApplication(), "Location  here", Toast.LENGTH_SHORT).show();

        }
    }

    private void OnGPS() {
        final AlertDialog.Builder builder = new AlertDialog.Builder(this);
        builder.setMessage("Enable GPS").setCancelable(false).setPositiveButton("Yes", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                Intent intent = new Intent(Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                startActivity(intent);
            }
        }).setNegativeButton("No", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int which) {
                dialog.cancel();
                finish();

            }
        });
        final AlertDialog alertDialog = builder.create();
        alertDialog.show();
    }

    private void requestGPSSettings() {
        LocationRequest locationRequest = LocationRequest.create();
        locationRequest.setPriority(LocationRequest.PRIORITY_HIGH_ACCURACY);
        locationRequest.setInterval(10 * 1000);
        locationRequest.setFastestInterval(2 * 1000);
        LocationSettingsRequest.Builder builder = new LocationSettingsRequest.Builder().addLocationRequest(locationRequest);
        builder.setAlwaysShow(true);
        PendingResult<LocationSettingsResult> result = LocationServices.SettingsApi.checkLocationSettings(googleApiClient, builder.build());
        result.setResultCallback(new ResultCallback<LocationSettingsResult>() {
            @Override
            public void onResult(LocationSettingsResult result) {
                final Status status = result.getStatus();
                Log.e("", "All location settings are satisfied." + result.getStatus());
                switch (status.getStatusCode()) {
                    case LocationSettingsStatusCodes.SUCCESS:

                        Log.e("", "All location settings are satisfied.");
                        Toast.makeText(getApplication(), "GPS is already enable", Toast.LENGTH_SHORT).show();
                        break;
                    case LocationSettingsStatusCodes.RESOLUTION_REQUIRED:
                        Log.e("", "Location settings are not satisfied. Show the user a dialog to" + "upgrade location settings ");
                        try {
                            status.startResolutionForResult(PlayerActivity.this, REQUEST_CHECK_SETTINGS);
                        } catch (Exception e) {
                            Log.e("Applicationsett", e.toString());
                        }
                        break;
                    case LocationSettingsStatusCodes.SETTINGS_CHANGE_UNAVAILABLE:
                        Log.e("", "Location settings are inadequate, and cannot be fixed here. Dialog " + "not created.");
                        Toast.makeText(getApplication(), "Location settings are inadequate, and cannot be fixed here", Toast.LENGTH_SHORT).show();
                        break;
                }
            }
        });
    }

//    private void getLocation() {
//        Log.e("latitude", "latitude--<<>>" + latitude);
//
//            fusedLocationClient.getLastLocation()
//                    .addOnCompleteListener(this, new OnCompleteListener<Location>() {
//                        @Override
//                        public void onComplete(@NonNull Task<Location> task) {
//                            if (task.isSuccessful() && task.getResult() != null) {
//                                Location location = task.getResult();
//                                if (location != null) {
//
//                                    double lati = location.getLatitude();
//                                    double longi = location.getLongitude();
//                                    latitude = lati;
//                                    longitude = longi;
//                                    Log.e(TAG, "Your Location: " + "\n" + "Latitude: " + latitude + "\n" + "Longitude: " + longitude);
//                                    Geocoder geocoder;
//                                    List<Address> addresses;
//                                    geocoder = new Geocoder(PlayerActivity.this, Locale.getDefault());
////
//                                    Log.e("latitude", "latitude--<<" + latitude);
//
//                                    try {
//                                        addresses = geocoder.getFromLocation(lati, longi, 1);
//
//                                        if (addresses != null && addresses.size() > 0) {
//                                            String address = addresses.get(0).getAddressLine(0);
//                                            String city = addresses.get(0).getLocality();
//                                            String state = addresses.get(0).getAdminArea();
//                                            String country = addresses.get(0).getCountryName();
//                                            postalCode = addresses.get(0).getPostalCode();
//                                            String knownName = addresses.get(0).getFeatureName();
//
////                                            getChannelListWithZipCode();
//
//
//                                            Log.e(TAG, "address==>>" + address + " " + city + " " + country + "postalcode==>> " + postalCode);
//                                        }
//                                    } catch (IOException e) {
//                                        // TODO Auto-generated catch block
//                                        e.printStackTrace();
//                                    }
//                                } else {
////                                    getCurrentLocationUsingGps();
//                                    Log.e("latitude", "Unable to find location.");
//                                }
//
//
//                                // Now you can use latitude and longitude as needed
//                            } else {
////                                getCurrentLocationUsingGps();
////                                Toast.makeText(PlayerNewActivity.this, "Unable to find location.", Toast.LENGTH_SHORT).show();
//                                Log.e("latitude", "Unable to find location.");
//                                // Handle the case where location couldn't be retrieved
//                            }
//                        }
//                    });
//
//    }

    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {
            menuImage.setVisibility(View.VISIBLE);
        handler.removeCallbacksAndMessages(null);
        if (KeyEvent.KEYCODE_VOLUME_UP == keyCode || KeyEvent.KEYCODE_VOLUME_DOWN == keyCode) {
            // Handle volume key events here
            return true;
        }

//        new Handler().postDelayed(new Runnable() {
//            @Override
//            public void run() {
//                menuImage.setVisibility(View.GONE);
//            }
//        },10000);

        runnable = new Runnable() {
            @Override
            public void run() {
                menuImage.setVisibility(View.GONE);
                handler.postDelayed(this, 10000);
            }
        };
        handler.postDelayed(runnable, 10000);
        return super.onKeyDown(keyCode, event);
    }

    public void playNext(String channelN, String videoUrl, String channelId, String callSign, int position) {
        url = videoUrl;
        callsign = callSign;

        playerView.setShowBuffering(playerView.SHOW_BUFFERING_ALWAYS);

        Log.e(TAG, "playNext: 1 " + channelId);
        Log.e(TAG, "playNext: 2 " + videoUrl);
        Log.e(TAG, "playNext: 3 " + channelN);
        Log.e(TAG, "playNext: 4 " + position);
        rvItemClick = "1";
        finalPosition = position;
        checkStreamingPlying = true;
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
//        getChannelListWithZipCode(videoUrl, channelId, channelN);

        //    menuImage.setVisibility(View.VISIBLE);
        drawerLayout.setVisibility(View.GONE);


//        initializePlayer2("",videoUrl,channelN,channelId);
    }

    private void getChannelListWithZipCode(String videoUrl, String channelId, String channelN) {
//        arrayList.clear();
        StringRequest stringRequest = new StringRequest(Request.Method.GET, (
                ConstantUtility.channelZipCode)+ timeOff,
                new com.android.volley.Response.Listener<String>() {
                    @SuppressLint("LongLogTag")
                    @Override
                    public void onResponse(String response) {
                        Log.e(TAG, "::getChannelList>>>>>>>>>===" + response);

                        try {
                            JSONObject object = new JSONObject(response);
                            String message;
                            String success;
                            success = object.getString("success");
                            message = object.getString("message");

                            if (success.equals("true")) {
                                JSONArray jsonArray = object.getJSONArray("data");
                                for (int i = 0; i < jsonArray.length(); i++) {
//                                    channelBean = new ChannelBean();
                                    JSONObject innerObj = jsonArray.getJSONObject(i);
                                    String callSignApi = innerObj.getString("callSign");

                                    try {
                                        if(callSignApi.equals(callsign)){
                                            Log.e(TAG, "onResponse: call sign 111 "+callSignApi);
                                            Log.e(TAG, "onResponse: call sign 111 "+callsign);
                                            Log.e(TAG, "onResponse: call sign 111 "+postalCode);
                                            if(innerObj.getString("zip_code")!=null){
                                                jArrayZipCode = new JSONArray(innerObj.getString("zip_code"));
                                                if(jArrayZipCode.toString().contains(postalCode)){
                                                    getDrmKey2(videoUrl, channelId, channelN);
                                                    break;
                                                }else if(jArrayZipCode.toString().contains("Null") ){
                                                    getDrmKey2(videoUrl, channelId, channelN);
                                                    break;
                                                }
                                                else{
                                                    channelNotAvailableAtYourLocation("This Channel is not available in your location.");
                                                    break;
                                                }



                                            }else{
                                                channelNotAvailableAtYourLocation("This Channel is not available in your location.");
                                                Log.d(TAG, "onResponse: call sign null 1114");
                                            }

                                        }
                                    }catch (Exception e){
                                        Log.e(TAG, "\n::>>>response json Array exception: "+ e.toString() );
//                                        e.printStackTrace();
                                    }

                                }

//

                            } else {
//                                Utils.Companion.showAlert(PlayerNewActivity.this, getString(R.string.app_name), message);
                            }
                        } catch (Exception e) {
                            e.printStackTrace();
                        }
                    }
                }, new com.android.volley.Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                if(error.getMessage()!=null){
                    Toast.makeText(getApplicationContext(), error.getMessage().toString(), Toast.LENGTH_SHORT).show();
                }



            }
        }) {
            @SuppressLint("LongLogTag")
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
//                Log.e(TAG, "params ::>>>>>>>>" + params);
                return params;
            }

            @SuppressLint("LongLogTag")
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers = new HashMap<>();
                headers.put("Accept", "application/json");
                headers.put("Authorization", "Bearer " + authToken);
//                Log.e(TAG, "headerrr ::>>>>>>>>" + headers);
                return headers;
            }
        };
        RequestQueue queue = Volley.newRequestQueue(this);
        queue.getCache().clear();
        stringRequest.setRetryPolicy(new DefaultRetryPolicy(60000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(stringRequest);
    }


    public void getDrmKey() {
        DrmViewModel viewModel = new ViewModelProvider(this).get(DrmViewModel.class);
        viewModel.getData(PlayerActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                Log.e(TAG, "onChanged: " + apiResponse.toString());
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
                        if (checkStreamingPlying) {
                            checkStreamingPlying = false;
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
        //    logoImage.setVisibility(View.VISIBLE);
        //    menuImage.setVisibility(View.VISIBLE);

        playerView.setShowBuffering(playerView.SHOW_BUFFERING_ALWAYS);
        Log.e(TAG, "initializePlayer2: " + drmKey);
        Log.e(TAG, "initializePlayer2: " + wvUrl);
        Log.e(TAG, "initializePlayer2: " + channelN);
        Log.e(TAG, "initializePlayer2: " + channelId);
        String drmkey = drmKey;
        String wvurl = wvUrl;
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
        DrmSessionManager drmSessionManager = new DefaultDrmSessionManager.Builder()
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
        TrackSelector trackSelector = new DefaultTrackSelector(new AdaptiveTrackSelection.Factory());
        DefaultLoadControl loadControl = new DefaultLoadControl.Builder().setBufferDurationsMs(32 * 1024, 64 * 1024, 1024, 1024).createDefaultLoadControl();

        player = ExoPlayerFactory.newSimpleInstance(this, trackSelector, loadControl);


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

        playerView.setUseController(true);

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
                        new Handler().postDelayed(new Runnable() {
                            @Override
                            public void run() {
                                logoImage.setVisibility(View.GONE);
                                menuImage.setVisibility(View.GONE);
                            }
                        }, 3000);
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


//                    //    logoImage.setVisibility(View.VISIBLE);
//                    menuImage.setVisibility(View.GONE);

//
                    Log.e(TAG, "yes");
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
                    Log.e(TAG, "no");
//                    logoImage.setVisibility(View.GONE);
//                 //    menuImage.setVisibility(View.VISIBLE);

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
                switch (error.type) {
                    case ExoPlaybackException.TYPE_SOURCE:
                        Log.e(TAG, "TYPE_SOURCE: " + error.getSourceException().getMessage());
//                            String msg = error.getSourceException().getMessage();
                        if (!TextUtils.isEmpty(error.getSourceException().getMessage())) {
                            if (error.getSourceException().getMessage() == "Unable to connect") {
                                Log.e(TAG, "TYPE_SOURCE: if" + error.getSourceException().getMessage());
                                playerErrorDialog = new PlayerErrorDialog(PlayerActivity.this, new PlayerErrorDialog.PlayerDialogInterface() {
                                    @Override
                                    public void okButton() {
                                        stopPlayerAndBack();
                                    }

                                    @Override
                                    public void tryAgainButton() {
                                        playNext(channelList.get(finalPosition).getId().toString(), channelList.get(finalPosition).getUrl(), channelList.get(finalPosition).getId().toString(), channelList.get(finalPosition).getCallSign().toString(),finalPosition);
                                    }
                                });
                                playerErrorDialog.show();
                                playerErrorDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
                                    @Override
                                    public void onCancel(DialogInterface dialog) {
                                        stopPlayerAndBack();
                                    }
                                });
                            } else {
                                Log.e(TAG, "TYPE_SOURCE else NULL: " + error.getSourceException().getMessage());
                                CommonUtility.showToast(PlayerActivity.this, "Channel Error");
//                                initializePlayer(drmKey, wvUrl);

                            }
                        } else {
                            CommonUtility.showToast(PlayerActivity.this, "Channel Error");
//                            initializePlayer(drmKey, wvUrl);
                            Log.e(TAG, "TYPE_SOURCE: null check" + error.getSourceException().getMessage());

                        }
                        //Restart the playback
//                        showDialogOfError("Due to some technical reason, channel gets interrupted. Kindly try again later.");

                        break;
                }

//                playererror(callsign,error.toString(),"false",error.getSourceException().getMessage(),height + "*" + width);
//                playerErrorDialog = new PlayerErrorDialog(PlayerActivity.this, new PlayerErrorDialog.PlayerDialogInterface() {
//                    @Override
//                    public void okButton() {
//                        stopPlayerAndBack();
//                    }
//
//                    @Override
//                    public void tryAgainButton() {
//                        playNext(channelList.get(finalPosition).getId().toString() , channelList.get(finalPosition).getUrl(),channelList.get(finalPosition).getId().toString(),finalPosition);
//                    }
//                });
//                playerErrorDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                playerErrorDialog.show();
//                playerErrorDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
//                    @Override
//                    public void onCancel(DialogInterface dialog) {
//                        stopPlayerAndBack();
//                    }
//                });
                /*playerErrorDialog.setOnKeyListener(new DialogInterface.OnKeyListener() {
                    @Override
                    public boolean onKey(DialogInterface dialogInterface, int i, KeyEvent keyEvent) {
                        Log.e(TAG,"player_error_dialog_key " + KeyEvent.keyCodeToString(i));
                        goBackToHome(dialogInterface,i,keyEvent);
                        return true;
                    }
                });*/
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
            logoImage.setVisibility(View.VISIBLE);
            menuImage.setVisibility(View.VISIBLE);

//        dialog.show();
        playerView.setShowBuffering(playerView.SHOW_BUFFERING_ALWAYS);
        String drmkey = drmKey;
        String wvurl = wvUrl;
        Log.e(TAG, "initializePlayer: " + drmKey);
        Log.e(TAG, "initializePlayer: " + wvUrl);
        Log.e(
                TAG, "Open live player " + getIntent().getStringExtra("from")
        );
        final String streamUrl = getIntent().getStringExtra("from");

        // final String streamUrl = "https://channelstore1.streamlytv.com/DJCHP/index.mpd";


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
        DrmSessionManager drmSessionManager = new DefaultDrmSessionManager.Builder()
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
        TrackSelector trackSelector = new DefaultTrackSelector(new AdaptiveTrackSelection.Factory());
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

        playerView.setUseController(true);


        // Preapre the player with content
        player.prepare(mediaSource);

        player.addListener(new Player.EventListener() {


            @Override
            public void onTimelineChanged(Timeline timeline, int reason) {
                Log.e(TAG, "onTimelineChanged: 1 " + timeline);
                Log.e(TAG, "onTimelineChanged: 2 " + reason);
            }

            @Override
            public void onTimelineChanged(Timeline timeline, @Nullable Object manifest, int reason) {
                Log.e(TAG, "onTimelineChanged: 3 " + timeline);
                Log.e(TAG, "onTimelineChanged: 4 " + reason);
            }

            @Override
            public void onTracksChanged(TrackGroupArray trackGroups, TrackSelectionArray trackSelections) {
                Log.e(TAG, "onTracksChanged: " + trackGroups);
                Log.e(TAG, "onTracksChanged: " + trackSelections);
            }

            @Override
            public void onLoadingChanged(boolean isLoading) {
                Log.e(TAG, "onLoadingChanged: " + isLoading);
                if (isLoading) {
                    mFirebaseAnalytics.logEvent("video_play", params);
                }
            }

            @Override
            public void onPlayerStateChanged(boolean playWhenReady, int playbackState) {
                switch (playbackState) {
                    case ExoPlayer.STATE_IDLE:
                        Log.e(TAG, "onPlayerStateChanged: idle ");
                        break;
                    case ExoPlayer.STATE_READY:
                        new Handler().postDelayed(new Runnable() {
                            @Override
                            public void run() {
                                logoImage.setVisibility(View.GONE);
                                menuImage.setVisibility(View.GONE);
                            }
                        }, 7000);
                        Log.e(TAG, "onPlayerStateChanged: ready ");
                        break;
                    case ExoPlayer.STATE_BUFFERING:
                        Log.e(TAG, "onPlayerStateChanged: buffering ");
                        break;
                    case ExoPlayer.STATE_ENDED:
                        Log.e(TAG, "onPlayerStateChanged: STATE_ENDED  ");
                        break;
                }
            }

            @Override
            public void onPlaybackSuppressionReasonChanged(int playbackSuppressionReason) {
                Log.e(TAG, "onPlaybackSuppressionReasonChanged: ");

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
                    Log.e(TAG, "no ");
//                    logoImage.setVisibility(View.GONE);
//                 //    menuImage.setVisibility(View.VISIBLE);

                }

            }

            @Override
            public void onRepeatModeChanged(int repeatMode) {
                Log.e(TAG, "onRepeatModeChanged: else ");
            }

            @Override
            public void onShuffleModeEnabledChanged(boolean shuffleModeEnabled) {
                Log.e(TAG, "onShuffleModeEnabledChanged: ");

            }

            @Override
            public void onPlayerError(ExoPlaybackException error) {

                switch (error.type) {
                    case ExoPlaybackException.TYPE_SOURCE:
                        Log.e(TAG, "TYPE_SOURCE: " + error.getSourceException().getMessage());
//                            String msg = error.getSourceException().getMessage();
                        IOException sourceException = error.getSourceException();
                        if (sourceException instanceof HttpDataSource.HttpDataSourceException) {
                            CommonUtility.showToast(PlayerActivity.this, "Network down...");
                            // Handle network errors
                            // You can also access specific error information using error.getSourceException().getMessage()
                        }
                        if (!TextUtils.isEmpty(error.getSourceException().getMessage())) {
                            if (error.getSourceException().getMessage().equals("Unable to connect")) {
                                Log.e(TAG, "TYPE_SOURCE: if >>" + error.getSourceException().getMessage());
                                playerErrorDialog = new PlayerErrorDialog(PlayerActivity.this, new PlayerErrorDialog.PlayerDialogInterface() {
                                    @Override
                                    public void okButton() {
                                        stopPlayerAndBack();
                                    }

                                    @Override
                                    public void tryAgainButton() {
                                        playNext(channelList.get(finalPosition).getId().toString(), channelList.get(finalPosition).getUrl(), channelList.get(finalPosition).getId().toString(),channelList.get(finalPosition).getCallSign().toString(), finalPosition);
                                    }
                                });
                                playerErrorDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                playerErrorDialog.show();
                                playerErrorDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
                                    @Override
                                    public void onCancel(DialogInterface dialog) {
                                        stopPlayerAndBack();
                                    }
                                });
                            } else {
                                Log.e(TAG, "TYPE_SOURCE else NULL: " + error.getSourceException().getMessage());
//                                CommonUtility.showToast(PlayerActivity.this, "server down...");
                               //vikas comment
                                initializePlayer(drmKey, wvUrl);

                            }
                        } else {
//                            CommonUtility.showToast(PlayerActivity.this, "Network down...");
                            initializePlayer(drmKey, wvUrl);
                            Log.e(TAG, "TYPE_SOURCE: null check" + error.getSourceException().getMessage());

                        }
                        //Restart the playback
//                        showDialogOfError("Due to some technical reason, channel gets interrupted. Kindly try again later.");

                        break;
                }

//                playererror(callsign,error.toString(),"false",error.getSourceException().getMessage(),height + "*" + width);
//                playerErrorDialog = new PlayerErrorDialog(PlayerActivity.this, new PlayerErrorDialog.PlayerDialogInterface() {
//                    @Override
//                    public void okButton() {
//                        stopPlayerAndBack();
//                    }
//
//                    @Override
//                    public void tryAgainButton() {
//                        playNext(channelList.get(finalPosition).getId().toString() , channelList.get(finalPosition).getUrl(),channelList.get(finalPosition).getId().toString(),finalPosition);
//                    }
//                });
//                playerErrorDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                playerErrorDialog.show();
//                playerErrorDialog.setOnCancelListener(new DialogInterface.OnCancelListener() {
//                    @Override
//                    public void onCancel(DialogInterface dialog) {
//                        stopPlayerAndBack();
//                    }
//                });
                /*playerErrorDialog.setOnKeyListener(new DialogInterface.OnKeyListener() {
                    @Override
                    public boolean onKey(DialogInterface dialogInterface, int i, KeyEvent keyEvent) {
                        Log.e(TAG,"player_error_dialog_key " + KeyEvent.keyCodeToString(i));
                        goBackToHome(dialogInterface,i,keyEvent);
                        return true;
                    }
                });*/
            }

            @Override
            public void onPositionDiscontinuity(int reason) {
                Log.e(TAG, "onPositionDiscontinuity: ");

            }

            @Override
            public void onPlaybackParametersChanged(PlaybackParameters playbackParameters) {
                Log.e(TAG, "onPlaybackParametersChanged: ");

            }

            @Override
            public void onSeekProcessed() {
                Log.e(TAG, "onSeekProcessed: ");

            }

        });

        exoNext.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int nextPosition = finalPosition + 1;
                if (nextPosition < channelList.size()) {
                    playNext(channelList.get(nextPosition).getId().toString(), channelList.get(nextPosition).getUrl(), channelList.get(nextPosition).getId().toString(), channelList.get(nextPosition).getCallSign().toString(),nextPosition);
                }
            }
        });

        exoPre.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                int prePosition = finalPosition - 1;
                Log.e(TAG, "Position : " + prePosition);
                if (prePosition >= 0) {
                    playNext(channelList.get(prePosition).getId().toString(), channelList.get(prePosition).getUrl(), channelList.get(prePosition).getId().toString(), channelList.get(prePosition).getCallSign().toString(),prePosition);
                }
            }
        });

        player.setPlayWhenReady(true);
        postRecentViewChannel(channel_id);


    }


    private void goBackToHome(DialogInterface dialogInterface, int i, KeyEvent keyEvent) {
        if (i == KeyEvent.KEYCODE_BACK && keyEvent.getAction() == KeyEvent.ACTION_UP) {
            stopPlayerAndBack();
        }
    }

    public void stopPlayerAndBack() {
        handler.removeCallbacks(runnable);
        player.stop();
        finish();
    }


    @Override
    public void onBackPressed() {
        if (close_flag.equals("1")) {
            drawerLayout.setVisibility(View.GONE);
            //    menuImage.setVisibility(View.VISIBLE);
            close_flag = "0";
            allChannelAdapter.notifyDataSetChanged();
        } else {
            super.onBackPressed();
            Log.e(TAG, "Back");
            stopPlayerAndBack();
        }
        Log.e(TAG, "close_flag" + close_flag.toString());
    }


    @Override
    protected void onStop() {
        super.onStop();
        player.setPlayWhenReady(false);
        player.getPlaybackState();
    }

    private void playererror(String call_sign, String errcode, String inside_geofence, String errmsg, String sr) {

//        ShowProgressDialog(activity, getString(R.string.please_wait));
        StringRequest postRequest = new StringRequest(Request.Method.POST, ConstantUtility.PLAYER_ERROR,
                new com.android.volley.Response.Listener<String>() {
                    String error_msg;

                    @Override
                    public void onResponse(String response) {
                        Log.e("!_@@ playererror Resp:>", "playererror===>>>>" + response);
//                        hideProgressDialog();
                        try {
                            JSONObject object = new JSONObject(response);
                            String message;
                            String success;
                            success = object.getString("success");
                            message = object.getString("message");
                            Integer response_code = object.getInt("response_code");
//                            if (response_code == 403) {
//                                ApplicationHelper.INSTANCE.showToast(activity, Constants.SERVER_ISSUE);
//                            } else if (response_code == 500) {
//                                ApplicationHelper.INSTANCE.showToast(activity, Constants.SERVER_ISSUE);
//                            } else {
//
//                            }
//                            if (success.equals("true")) {
//
//                            } else
//                                Utils.Companion.showAlert(PlayerNewActivity.this, getString(R.string.app_name), message);

                        } catch (Exception e) {
                            e.printStackTrace();
                            Log.e("error..", ": " + e);
                        }
                    }
                }, new com.android.volley.Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("Errorlist", String.valueOf(error));
//                hideProgressDialog();
//                Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                Log.e(TAG, "error fail>>: " + error.getMessage());
            }
        }) {
            @Override
            public Map<String, String> getParams() {
                Map<String, String> params = new HashMap<String, String>();
                params.put("callsign", call_sign);
                params.put("errcode", errcode);
                params.put("inside_geofence", inside_geofence);
                params.put("errmsg", errmsg);
                params.put("sr", sr);
                Log.e("!_@@ Params :", "" + params);

                return params;
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                Map<String, String> headers = new HashMap<>();
                headers.put("User-Agent", System.getProperty("http.agent"));
                headers.put("Accept", "application/json");
                headers.put("Authorization", "Bearer " + authToken);
                Log.e("!_@@ headers :", "" + headers);
                return headers;
            }
        };
        RequestQueue queue = Volley.newRequestQueue(this);
        queue.getCache().clear();
        postRequest.setRetryPolicy(new DefaultRetryPolicy(60000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(postRequest);
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
//                        check for current channel name and store the position of the current channle and auto scroll
//                        that position.
                        int position = 0;
                        for (int i = 0; i < channelList.size(); i++) {
                            if (url.equals(channelList.get(i).getUrl())) {
                                channel_id = channelList.get(i).getId().toString();
                                Log.e(TAG, "onResponse: channel list id " + channelList.get(i).getId().toString());
                                Log.e(TAG, "onResponse: channel list id " + channelId);
                                Log.e(TAG, "onResponse: channel list id " + channelList.get(i).getId());
                            }
                            if (channelList.get(i).getId().toString().equals(channelId)) {
                                position = i;
                            } else if (channelList.get(i).getId().toString().equals(channel_id)) {
                                position = i;
                            }
                        }


                        if (rvItemClick.equals("1")) {
                            allChannelRecyclerView.setLayoutManager(new LinearLayoutManager(PlayerActivity.this, LinearLayoutManager.VERTICAL, false));
                            allChannelAdapter = new AllChannelAdapter(PlayerActivity.this, channelList, finalPosition);
                            allChannelRecyclerView.setAdapter(allChannelAdapter);

                        } else {

                            allChannelRecyclerView.setLayoutManager(new LinearLayoutManager(PlayerActivity.this, LinearLayoutManager.VERTICAL, false));
                            allChannelAdapter = new AllChannelAdapter(PlayerActivity.this, channelList, position);
                            allChannelRecyclerView.setAdapter(allChannelAdapter);

                            Log.e(TAG, "onResponse: channel position " + position);
                            finalPosition = position;
                        }
                        allChannelRecyclerView.post(new Runnable() {
                            @Override
                            public void run() {
//                                allChannelRecyclerView.setSelected(true);
//                                allChannelRecyclerView.setFocusable(true);
                                allChannelRecyclerView.scrollToPosition(finalPosition);
                            }
                        });

                        //    menuImage.setVisibility(View.VISIBLE);
                        if (rvItemClick.equals("1")) {
                            rvItemClick = "0";

                        } else {
                            loader.setVisibility(View.GONE);
                            initializePlayer(drmKey, wvUrl);

                        }


                    } else if (response.code() == 401) {
                        if (response.errorBody() != null) {
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
                /*AlertDialog.Builder dialog = new AlertDialog.Builder(PlayerActivity.this, R.style.AlertDialogCustom);
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
                dialog.setCancelable(true);*/
                String errorMsg = "Due to some technical reason, channel gets interrupted. Kindly try again later";
                CommonUtility.showToast(PlayerActivity.this, errorMsg);
                handler.removeCallbacks(runnable);
                player.stop();
                finish();
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
//                        List<Datum> datumList = response.body().getData();
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

    @SuppressLint("RestrictedApi")
    @Override
    public boolean dispatchKeyEvent(KeyEvent event) {
        playerView.dispatchKeyEvent(event);
        return super.dispatchKeyEvent(event);
    }

    @Override
    protected void onPause() {
        super.onPause();
        Log.e(TAG, "on Pause");
        player.setPlayWhenReady(false);
        player.getPlaybackState();
    }

    @Override
    protected void onResume() {
        super.onResume();
        Log.e(TAG, "on Resume");
        player.setPlayWhenReady(true);
        player.getPlaybackState();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        handler.removeCallbacks(runnable);
        player.stop();
    }

}