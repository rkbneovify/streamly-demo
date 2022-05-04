package com.adaptivemedia.streamlytv.streamly;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.R;
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

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class MainActivity extends FragmentActivity implements View.OnClickListener {
    private static final String TAG = MainActivity.class.getSimpleName();
    String authToken;
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

    private void getChannelList() {
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
                        recyclerView.setLayoutManager(new LinearLayoutManager(MainActivity.this, LinearLayoutManager.HORIZONTAL, false));
                        GenereAdapter adapter = new GenereAdapter(MainActivity.this, genreList);
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

                    } else if (response.code() == 402) {
                        settingText.setVisibility(View.GONE);
                        imageSearch.setVisibility(View.GONE);
                        CommonUtility.showToast(MainActivity.this, "Payment Required. Kindly buy subscription");
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
                finish();
                break;

            case R.id.go_back_text:
                finish();
                break;
        }
    }
}




