package com.adaptivemedia.streamlytv.streamly;

import android.content.Intent;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.provider.Settings;
import android.text.format.Formatter;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import androidx.leanback.widget.GuidedActionAdapter;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProvider;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.playervideo.PlayerActivity;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpDataModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.zipcode.ZipCodeDialog;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiResponse;
import com.google.firebase.analytics.FirebaseAnalytics;
import com.google.firebase.crashlytics.FirebaseCrashlytics;
import com.jakewharton.threetenabp.AndroidThreeTen;
import com.adaptivemedia.streamlytv.streamly.dialogs.NoPlanDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.epg.ChannelGenre;
import com.adaptivemedia.streamlytv.streamly.epg.GenereAdapter;
import com.adaptivemedia.streamlytv.streamly.epg.channel.ChannelFragment;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.search_channels.SearchActivity;
import com.adaptivemedia.streamlytv.streamly.settings.SettingsActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import java.net.InetAddress;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class MainActivity extends FragmentActivity implements View.OnClickListener {
    private static final String TAG = MainActivity.class.getSimpleName();
    String authToken, uniqueId;
    String item = "0";
    private LinearLayout imageSearch;
    //    private TextView textAll,textOthers,textFamily,textComedy,
//            textSports,textNews,textMovies,textKids,settingText,goBackText;
    private TextView settingText, goBackText;

    private FirebaseAnalytics mFirebaseAnalytics;
    private RecyclerView recyclerView;
//    List<Datum> recentList;

    ArrayList<String> genreList = new ArrayList<String>();

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        AndroidThreeTen.init(this);
        setContentView(R.layout.activity_main);

        initViews();
        uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        authToken = TokenStorage.readSharedToken(MainActivity.this, ConstantUtility.AUTH_TOKEN, null);
        ConstantUtility.isRemember = false;
        Log.e(TAG, "authToken " + authToken);
        Log.i(TAG, "authToken " + authToken);

        FirebaseCrashlytics.getInstance().log("Test Crash in the Main Activity");
        // Obtain the FirebaseAnalytics instance.
        mFirebaseAnalytics = FirebaseAnalytics.getInstance(this);

        Bundle bundle = new Bundle();
        bundle.putString(FirebaseAnalytics.Param.ITEM_ID, "id");
        bundle.putString(FirebaseAnalytics.Param.ITEM_NAME, "EPG Fragment");
        bundle.putString(FirebaseAnalytics.Param.CONTENT_TYPE, "EPG Channels");
        mFirebaseAnalytics.logEvent(FirebaseAnalytics.Event.SELECT_CONTENT, bundle);
//        getRecentList();



       // getLatLong();
        getChannelList();
    }

//    private void getRecentList() {
//        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
//        Call<ChannellistModel> call = apiInterface.getRecentList("Bearer " + authToken);
//        call.enqueue(new Callback<ChannellistModel>() {
//            @Override
//            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {
//                try {
//                    if (response.isSuccessful() && response.body() != null && response.code() != 401) {
//                        recentList = response.body().getData();
//                        Log.e(TAG, "onResponse: recent list " + response.body().getData());
//                    } else if (response.code() == 401) {
//                        if (response.errorBody() != null) {
//                            SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(MainActivity.this);
//                            sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                            sessionExpiredDialog.setCancelable(false);
//                            sessionExpiredDialog.show();
//                        }
//                    } else if (response.code() == 404) {
//                        TokenStorage.clearSharedToken(MainActivity.this);
//                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SESSION_EXPIRED);
//                    } else if (response.code() == 500) {
//                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
//                    } else if (response.code() == 402) {
//                        settingText.setVisibility(View.GONE);
//                        imageSearch.setVisibility(View.GONE);
//                        CommonUtility.showToast(MainActivity.this, "Payment Required. Kindly buy subscription");
//                    }
//                } catch (Exception ex) {
//                    ex.printStackTrace();
//                    Log.e(TAG, "Exception " + ex);
//                }
//            }
//
//            @Override
//            public void onFailure(Call<ChannellistModel> call, Throwable t) {
//                Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
//                CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
//            }
//        });
//    }

    public void fragment(String type) {


        getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new ChannelFragment(MainActivity.this, type))
                .commit();


    }

    public void getChannelList() {
        Log.e(TAG, "getChannelList: " + authToken);
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ChannelGenre> call = apiInterface.getChannelGenre("Bearer " + authToken);
        call.enqueue(new Callback<ChannelGenre>() {
            @Override
            public void onResponse(Call<ChannelGenre> call, Response<ChannelGenre> response) {
                try {

                    if (response.isSuccessful() && response.body() != null && response.code() != 401) {

                        List<String> datumList = response.body().getGenre();
                        genreList.add("All");
                        genreList.add("Recent");
                        genreList.addAll(datumList);
                        int postion = 0;
                        recyclerView.setLayoutManager(new LinearLayoutManager(MainActivity.this, LinearLayoutManager.HORIZONTAL, false));
                        GenereAdapter adapter = new GenereAdapter(MainActivity.this, genreList,postion);
                        recyclerView.setAdapter(adapter);
                        fragment(genreList.get(0));
                    } else if (response.code() == 401) {

                        if (response.errorBody() != null) {
                            SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(MainActivity.this);
                            sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            sessionExpiredDialog.setCancelable(false);
                            sessionExpiredDialog.show();


                        }


                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(MainActivity.this);
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SESSION_EXPIRED);


                    } else if (response.code() == 500) {
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        getChannelList();

                    } else if (response.code() == 402) {
                        settingText.setVisibility(View.GONE);
                        imageSearch.setVisibility(View.GONE);
                        //CommonUtility.showToast(MainActivity.this, "Payment Required. Kindly buy subscription");
                        NoPlanDialog noPlanDialog = new NoPlanDialog(MainActivity.this);
                        noPlanDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        noPlanDialog.setCancelable(false);
                        noPlanDialog.show();

                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                }
            }

            @Override
            public void onFailure(Call<ChannelGenre> call, Throwable t) {
                Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
                CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
            }
        });
    }


    private void initViews() {
        imageSearch = findViewById(R.id.image_search);
        recyclerView = findViewById(R.id.main_recycler_view);
//        textAll=findViewById(R.id.text_all);
//        textOthers=findViewById(R.id.text_others);
//        textFamily=findViewById(R.id.text_family);
//        textComedy=findViewById(R.id.text_comedy);
//        textSports=findViewById(R.id.text_sports);
//        textNews=findViewById(R.id.text_news);
//        textMovies=findViewById(R.id.text_movies);
//        textKids=findViewById(R.id.text_kids);
        settingText = findViewById(R.id.setting_text);
        goBackText = findViewById(R.id.go_back_text);
        imageSearch.setFocusable(true);
        imageSearch.setFocusableInTouchMode(true);
//        textAll.setFocusable(true);
//        textAll.setFocusableInTouchMode(true);
//        textOthers.setFocusable(true);
//        textOthers.setFocusableInTouchMode(true);
//        textFamily.setFocusable(true);
//        textFamily.setFocusableInTouchMode(true);
//        textComedy.setFocusable(true);
//        textComedy.setFocusableInTouchMode(true);
//        textSports.setFocusable(true);
//        textSports.setFocusableInTouchMode(true);
//        textNews.setFocusable(true);
//        textNews.setFocusableInTouchMode(true);
//        textMovies.setFocusable(true);
//        textMovies.setFocusableInTouchMode(true);
//        textKids.setFocusable(true);
//        textKids.setFocusableInTouchMode(true);
        settingText.setFocusable(true);
        settingText.setFocusableInTouchMode(true);
        goBackText.setFocusable(true);
        goBackText.setFocusableInTouchMode(true);


        imageSearch.setOnClickListener(this);
//        textAll.setOnClickListener(this);
//        textOthers.setOnClickListener(this);
//        textFamily.setOnClickListener(this);
//        textComedy.setOnClickListener(this);
//        textSports.setOnClickListener(this);
//        textNews.setOnClickListener(this);
//        textMovies.setOnClickListener(this);
//        textKids.setOnClickListener(this);
        settingText.setOnClickListener(this);
        goBackText.setOnClickListener(this);


    }

    @Override
    public void onClick(View v) {

        switch (v.getId()) {
            case R.id.image_search:
                startActivity(new Intent(MainActivity.this, SearchActivity.class));
                break;

//            case R.id.text_all:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new EpgFragment(MainActivity.this))
//                        .commit();
//                break;
//
//            case R.id.text_others:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new OtherFragment(MainActivity.this))
//                        .commit();
//
//                break;
//
//            case R.id.text_family:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new FamilyFragment(MainActivity.this))
//                        .commit();
//                break;
//
//            case R.id.text_sports:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new SportFragment(MainActivity.this))
//                        .commit();
//                break;
//
//            case R.id.text_news:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new NewsFragment(MainActivity.this))
//                        .commit();
//                break;
//
//            case R.id.text_comedy:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new ComedyFragment(MainActivity.this))
//                        .commit();
//                break;
//
//            case R.id.text_movies:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new MoviesFragment(MainActivity.this))
//                        .commit();
//                break;
//
//            case R.id.text_kids:
//                getSupportFragmentManager().beginTransaction()
//                        .replace(R.id.fragment_container, new KidsFragment(MainActivity.this))
//                        .commit();
//                break;

            case R.id.setting_text:
                startActivity(new Intent(MainActivity.this, SettingsActivity.class));
                break;

            case R.id.go_back_text:
                finish();
                break;
        }
    }

    private void getLatLong() {
        WifiManager wm = (WifiManager) getApplicationContext().getSystemService(WIFI_SERVICE);
        String ip = Formatter.formatIpAddress(wm.getConnectionInfo().getIpAddress());
        Log.e(TAG, "IP_Address: " + ip);
        ip = getIpAddress();
        Log.e(TAG, "IP_Address: " + ip);
        ip = "157.42.78.63";
//        157.42.78.63


        IpViewModel ipViewModel = new ViewModelProvider(this).get(IpViewModel.class);
        ipViewModel.getLatLong(ip, MainActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                IpDataModel ipModel = (IpDataModel) apiResponse.getApiResponse();
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        if (ipModel.getError()) {
                            CommonUtility.showToast(MainActivity.this, ipModel.getReason());
                            Log.e(TAG, ipModel.getReason());
                            finish();
                        } else {
                            Double dbLat = ipModel.getLatitude();
                            Double dblon = ipModel.getLongitude();
                            postGeofenceCall(dbLat, dblon);
                        }
                        break;
                    case 401:

                        CommonUtility.showToast(MainActivity.this, ipModel.getReason());
                        break;
                    case 403:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 404:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 429:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 500:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                }

            }
        });

    }

    public String getIpAddress() {
        WifiManager wifiManager = (WifiManager) getApplicationContext()
                .getSystemService(WIFI_SERVICE);

        String ipAddress = intToInetAddress(wifiManager.getDhcpInfo().ipAddress).toString();

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
        Map<String, Double> map = new HashMap<>();
        map.put("latitude", dbLat);
        map.put("longitude", dblon);
//        map.put("latitude",33.88768366489836);
//        map.put("longitude",-17.90419095752048);

        Log.e(TAG, "Add" + dbLat +" "+ dblon);
        GeofenceViewModel viewModel = new ViewModelProvider(this).get(GeofenceViewModel.class);
        viewModel.getData(map, MainActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                GeofenceModel geofenceModel = (GeofenceModel) apiResponse.getApiResponse();
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        getChannelList();
                        break;
                    case 401:
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(MainActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
                        break;
                    case 403:
                        if (item.equals("1")) {
                            getChannelList();
                            item = "0";
                        } else {
                            String popMessage = "I am away from your home zip code:  click here to continue viewing.";
                            ZipCodeDialog zipCodeDialog = new ZipCodeDialog(MainActivity.this, popMessage, uniqueId, dbLat + "", dblon + "", authToken, new ZipCodeDialog.DialogCallBack() {
                                @Override
                                public void onExitPress() {
//                                    calling onBackPress
                                    MainActivity.this.onBackPressed();
                                }

                                @Override
                                public void onContinuePress() {
//                                    do Nothing here
                                }
                            });
                            zipCodeDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            zipCodeDialog.show();
//                            getDrmKey();
                        }
                        break;
                    default:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                }
            }
        });

    }
}




