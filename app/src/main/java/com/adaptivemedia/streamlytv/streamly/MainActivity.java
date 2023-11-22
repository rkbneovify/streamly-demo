package com.adaptivemedia.streamlytv.streamly;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.wifi.WifiManager;
import android.os.Bundle;
import android.provider.Settings;
import android.text.format.Formatter;
import android.util.Log;
import android.view.KeyEvent;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.OnBackPressedCallback;
import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import androidx.leanback.widget.GuidedActionAdapter;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProvider;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.HotelQRCode.HotelQRCodeActivity;
import com.adaptivemedia.streamlytv.streamly.dialogs.SelectTimeZoneDialog;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInModel;
import com.adaptivemedia.streamlytv.streamly.model.GetIp;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLoginErrorResponse;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpDataModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpViewModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.zipcode.ZipCodeDialog;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiResponse;
import com.adaptivemedia.streamlytv.streamly.splash.SplashActivity;
import com.google.firebase.analytics.FirebaseAnalytics;
import com.google.firebase.crashlytics.FirebaseCrashlytics;
import com.google.gson.Gson;
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

import org.json.JSONObject;

import java.net.InetAddress;
import java.net.UnknownHostException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.GregorianCalendar;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;
import java.util.Map;
import java.util.TimeZone;

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
    String offset;
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
        TimeZone tz = TimeZone.getDefault();
        Calendar cal = GregorianCalendar.getInstance(tz);
        int offsetInMillis = tz.getOffset(cal.getTimeInMillis());

         offset = String.format("%02d:%02d", Math.abs(offsetInMillis / 3600000), Math.abs((offsetInMillis / 60000) % 60));
        offset = (offsetInMillis >= 0 ? "+" : "-") + offset;

        Log.e(TAG, "offset<<>>> " + offset);
       // getLatLong();
        getChannelList(offset);
//        getIpAddress();

//        OnBackPressedCallback callback = new OnBackPressedCallback(true /* enabled by default */) {
//            @Override
//            public void handleOnBackPressed() {
//                MainActivity.super.onBackPressed();
//                // Handle the back button event
//                Log.e(TAG, "Back");
//            }
//        };
//        MainActivity.this.getOnBackPressedDispatcher().addCallback(this, callback);
    }

    private void getIpAddress() {
        ApiInterface apiInterface = ApiClient.getIPAdd().create(ApiInterface.class);
        Call<GetIp> call = apiInterface.getDeviceIpAddress();
        call.enqueue(new Callback<GetIp>() {
            @Override
            public void onResponse(Call<GetIp> call, Response<GetIp> response) {
                String  ip = response.body().getIpAddress();
                Log.e("GetIp","ip...");
                getHoursOffset(ip);
            }

            @Override
            public void onFailure(Call<GetIp> call, Throwable t) {
                String hometimezone = TokenStorage.readHomeTimeZoneOffset(MainActivity.this,ConstantUtility.HOMETIMEZONE,null);
                getChannelList(hometimezone);
                //add if fail (home zip)
            }
        });
    }

    private void getHoursOffset(String ip) {
        IpViewModel ipViewModel = new ViewModelProvider(this).get(IpViewModel.class);
        ipViewModel.getLatLong(ip, MainActivity.this).observe(this, new Observer<ApiResponse>() {

            @Override
            public void onChanged(ApiResponse apiResponse) {
                IpDataModel ipModel = (IpDataModel) apiResponse.getApiResponse();
                String hometimezone = TokenStorage.readHomeTimeZoneOffset(MainActivity.this,ConstantUtility.HOMETIMEZONE,null);
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        if (ipModel.getError()) {
                            getChannelList(hometimezone);
                            CommonUtility.showToast(MainActivity.this, ipModel.getReason());
                            Log.e(TAG, ipModel.getReason());
                            finish();
                        } else {
                            String hourOffset = ipModel.getUtc_offset();
                            if(hourOffset.length() >= 2) {
                                char two = ':';
                                Log.e("HoursOffset", "Value : " + hourOffset);
                                String currenttimezone = hourOffset.substring(0, 3) + two + hourOffset.substring(3);
                                Log.e("HoursOffset", "Current : " + currenttimezone);
                                TokenStorage.saveCurrentTimeZoneOffset(MainActivity.this, ConstantUtility.CURRENTTIMEZONE, currenttimezone);
                                if (currenttimezone != null) {
                                    getChannelList(currenttimezone);
                                } else {
                                    getChannelList(hometimezone);
                                }
                            }else{
                                TokenStorage.saveCurrentTimeZoneOffset(MainActivity.this, ConstantUtility.CURRENTTIMEZONE, hourOffset);
                                if (hourOffset != null) {
                                    getChannelList(hourOffset);
                                } else {
                                    getChannelList(hometimezone);
                                }
                            }

                            /*SelectTimeZoneDialog selectTimeZoneDialog = new SelectTimeZoneDialog(MainActivity.this,"Please select your timezone",uniqueId,  "",  "", authToken, new SelectTimeZoneDialog.DialogCallBack(){

                                @Override
                                public void onCurrentPress() {
                                    TokenStorage.saveCurrentTimeZoneOffset(MainActivity.this,ConstantUtility.HOMETIMEZONE,cblogName);
                                    //Calling current
                                }

                                @Override
                                public void onHomePress() {
                                    //Calling home
                                }
                            });
                            selectTimeZoneDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            selectTimeZoneDialog.show();*/

                        }
                        break;
                    case 401:
//                        CommonUtility.showToast(MainActivity.this, ipModel.getReason());
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(MainActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
                        break;
                    case 403:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 404:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 429:
                        getChannelList(hometimezone);
                        break;
                    case 500:
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                }

            }
        });
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


        getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new ChannelFragment(MainActivity.this, type, offset))
                .commit();


    }
    @Override
    public boolean onKeyDown(int keyCode, KeyEvent event) {

//        Log.d("debug", "we are here" + TokenStorage.readdate(MainActivity.this,ConstantUtility.DATE,null));

        switch (keyCode) {
            case KeyEvent.KEYCODE_DPAD_UP:{
                break;
            }
            case KeyEvent.KEYCODE_DPAD_DOWN:{
                break;

            }
            case KeyEvent.KEYCODE_DPAD_RIGHT:{
                break;

            }
            case KeyEvent.KEYCODE_DPAD_LEFT:{
                break;

            }
            case KeyEvent.KEYCODE_BACK:{
                Log.e(TAG, "Back>>>");
                finish();
                break;

            }

            case KeyEvent.KEYCODE_ESCAPE:{

            }
                Date c = Calendar.getInstance().getTime();
                SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
                String reloaddate = df.format(c);
                String saveddate = TokenStorage.readdate(MainActivity.this,ConstantUtility.DATE,null);
                Log.d(TAG,
                        "refresh if>>>" + saveddate
                );
                if(!saveddate.equals(reloaddate)){
                    Log.e("MAX_CHANNEL_END_TIME","refresh >>>elsecurrent>>" + System.currentTimeMillis() );
                    getChannelList(offset);
                }else{
                    Log.d(TAG,
                            "refresh else>>>" + reloaddate
                    );
                }
                Log.d("OnKey", "key pressed!" +TokenStorage.readdate(MainActivity.this,ConstantUtility.DATE,null));
        }
        return false;
    }
    public void getChannelList(String offset) {
        genreList.clear();
        Log.e(TAG, "getChannelList: >>" + authToken);
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ChannelGenre> call = apiInterface.getChannelGenre("Bearer " + authToken);
        call.enqueue(new Callback<ChannelGenre>() {
            @Override
            public void onResponse(Call<ChannelGenre> call, Response<ChannelGenre> response) {
                try {
//                    Log.e(TAG,"error_msg>>>>>" + response.errorBody().charStream());

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
                        String hourOffset = TokenStorage.readHomeTimeZoneOffset(MainActivity.this,ConstantUtility.HOMETIMEZONE,null);
                        getChannelList(hourOffset);

                    }
                    else if (response.code() == 402) {
                        settingText.setVisibility(View.GONE);
                        imageSearch.setVisibility(View.GONE);
                        //CommonUtility.showToast(MainActivity.this, "Payment Required. Kindly buy subscription");
//                        NoPlanDialog noPlanDialog = new NoPlanDialog(MainActivity.this);
//                        noPlanDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                        noPlanDialog.setCancelable(false);
//                        noPlanDialog.show();
                        String remoteResponse= response.errorBody().string();

                        JSONObject jObjError = new JSONObject(remoteResponse.trim());

                        Log.e(TAG,"error_msg>>>>>" + jObjError.getString("message"));
                        final AlertDialog.Builder builder = new AlertDialog.Builder(MainActivity.this);
                        builder.setMessage(jObjError.getString("message")).setCancelable(false).setPositiveButton("Ok", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                sigOutCall();
                                dialog.cancel();
                            }
                        });
                        builder.show().getButton(DialogInterface.BUTTON_POSITIVE).requestFocus();
                        final AlertDialog alertDialog = builder.create();
                        alertDialog.show();
                    }
                    else if (response.code() == 410) {
                        settingText.setVisibility(View.GONE);
                        imageSearch.setVisibility(View.GONE);
                        //CommonUtility.showToast(MainActivity.this, "Payment Required. Kindly buy subscription");
//                        NoPlanDialog noPlanDialog = new NoPlanDialog(MainActivity.this);
//                        noPlanDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                        noPlanDialog.setCancelable(false);
//                        noPlanDialog.show();
                        String remoteResponse= response.errorBody().string();

                        JSONObject jObjError = new JSONObject(remoteResponse.trim());

                        Log.e(TAG,"error_msg>>>>>" + jObjError.getString("message"));
                        final AlertDialog.Builder builder = new AlertDialog.Builder(MainActivity.this);
                        builder.setMessage(jObjError.getString("message")).setCancelable(false).setPositiveButton("Ok", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                sigOutCall();
                                dialog.cancel();
                            }
                        });
                        builder.show().getButton(DialogInterface.BUTTON_POSITIVE).requestFocus();
                        final AlertDialog alertDialog = builder.create();
                        alertDialog.show();
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

    private void sigOutCall() {
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<LogInModel> call=apiInterface.postLogOutCall("Bearer "+authToken);
        call.enqueue(new Callback<LogInModel>() {
            @Override
            public void onResponse(Call<LogInModel> call, Response<LogInModel> response) {
                try{

                    if (response.isSuccessful() && response.body()!=null && response.code()!=401){
                        CommonUtility.showToast(MainActivity.this,response.body().getMessage());
                        TokenStorage.clearSharedToken(MainActivity.this);
                       startActivity(new Intent(MainActivity.this, LogInActivity.class));
                        finish();
                    }
                    else if (response.code()==401){
                        TokenStorage.clearSharedToken(MainActivity.this);
                       startActivity(new Intent(MainActivity.this, LogInActivity.class));
                      finish();


                    }else if (response.code()==402){
                        TokenStorage.clearSharedToken(MainActivity.this);
                        startActivity(new Intent(MainActivity.this, LogInActivity.class));
                        finish();
                    }

                    else if (response.code()==404){
                        TokenStorage.clearSharedToken(MainActivity.this);
                        CommonUtility.showToast(MainActivity.this,ConstantUtility.SESSION_EXPIRED);
                        startActivity(new Intent(MainActivity.this, LogInActivity.class));
                        finish();
                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(MainActivity.this,ConstantUtility.SERVER_ISSUE);
                    }

                }catch (Exception ex){
                    ex.printStackTrace();
                    Log.e(TAG, "Exception "+ex);
                }
                Log.e(TAG,"ResponCode "+response.code()+"");
            }
            @Override
            public void onFailure(Call<LogInModel> call, Throwable t) {
                Log.e(TAG,"failure: "+call+" "+t.getLocalizedMessage());
                startActivity(new Intent(MainActivity.this, LogInActivity.class));
                finish();
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

    @Override
    public void onBackPressed() {
        Log.e(TAG, "Back");
        super.onBackPressed();
    }


}




