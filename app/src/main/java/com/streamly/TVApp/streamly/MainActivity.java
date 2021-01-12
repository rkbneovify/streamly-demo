package com.streamly.TVApp.streamly;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import com.google.firebase.analytics.FirebaseAnalytics;
import com.google.firebase.crashlytics.FirebaseCrashlytics;
import com.jakewharton.threetenabp.AndroidThreeTen;
import com.streamly.TVApp.streamly.epg.comedy.ComedyFragment;
import com.streamly.TVApp.streamly.epg.family.FamilyFragment;
import com.streamly.TVApp.streamly.epg.kids.KidsFragment;
import com.streamly.TVApp.streamly.epg.movies.MoviesFragment;
import com.streamly.TVApp.streamly.epg.news.NewsFragment;
import com.streamly.TVApp.streamly.epg.others.OtherFragment;
import com.streamly.TVApp.streamly.epg.sports.SportFragment;
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.search_channels.SearchActivity;
import com.streamly.TVApp.streamly.settings.SettingsActivity;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;


public class MainActivity extends FragmentActivity implements View.OnClickListener {
    private static final String TAG=MainActivity.class.getSimpleName();
    String authToken;
    private LinearLayout imageSearch;
    private TextView textAll,textOthers,textFamily,textComedy,
            textSports,textNews,textMovies,textKids,settingText,goBackText;

    private FirebaseAnalytics mFirebaseAnalytics;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        AndroidThreeTen.init(this);
        setContentView(R.layout.activity_main);
        initViews();
        authToken = TokenStorage.readSharedToken(MainActivity.this, ConstantUtility.AUTH_TOKEN, null);

         Log.e(TAG,"authToken "+authToken);

        FirebaseCrashlytics.getInstance().log("Test Crash in the Main Activity");
        // Obtain the FirebaseAnalytics instance.
        mFirebaseAnalytics = FirebaseAnalytics.getInstance(this);

        Bundle bundle = new Bundle();
        bundle.putString(FirebaseAnalytics.Param.ITEM_ID, "id");
        bundle.putString(FirebaseAnalytics.Param.ITEM_NAME, "EPG Fragment");
        bundle.putString(FirebaseAnalytics.Param.CONTENT_TYPE, "EPG Channels");
        mFirebaseAnalytics.logEvent(FirebaseAnalytics.Event.SELECT_CONTENT, bundle);



        getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container, new EpgFragment(MainActivity.this))
                .commit();

    }

    private void initViews() {
        imageSearch=findViewById(R.id.image_search);
        textAll=findViewById(R.id.text_all);
        textOthers=findViewById(R.id.text_others);
        textFamily=findViewById(R.id.text_family);
        textComedy=findViewById(R.id.text_comedy);
        textSports=findViewById(R.id.text_sports);
        textNews=findViewById(R.id.text_news);
        textMovies=findViewById(R.id.text_movies);
        textKids=findViewById(R.id.text_kids);
        settingText=findViewById(R.id.setting_text);
        goBackText=findViewById(R.id.go_back_text);
        imageSearch.setOnClickListener(this);
        textAll.setOnClickListener(this);
        textOthers.setOnClickListener(this);
        textFamily.setOnClickListener(this);
        textComedy.setOnClickListener(this);
        textSports.setOnClickListener(this);
        textNews.setOnClickListener(this);
        textMovies.setOnClickListener(this);
        textKids.setOnClickListener(this);
        settingText.setOnClickListener(this);
        goBackText.setOnClickListener(this);

    }

    @Override
    public void onClick(View v) {

        switch (v.getId()){


            case R.id.image_search:
                startActivity(new Intent(MainActivity.this, SearchActivity.class));
                break;

            case R.id.text_all:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new EpgFragment(MainActivity.this))
                        .commit();
                break;

            case R.id.text_others:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new OtherFragment(MainActivity.this))
                        .commit();

                break;

            case R.id.text_family:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new FamilyFragment(MainActivity.this))
                        .commit();
                break;

            case R.id.text_sports:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new SportFragment(MainActivity.this))
                        .commit();
                break;

            case R.id.text_news:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new NewsFragment(MainActivity.this))
                        .commit();
                break;

            case R.id.text_comedy:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new ComedyFragment(MainActivity.this))
                        .commit();
                break;

            case R.id.text_movies:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new MoviesFragment(MainActivity.this))
                        .commit();
                break;

            case R.id.text_kids:
                getSupportFragmentManager().beginTransaction()
                        .replace(R.id.fragment_container, new KidsFragment(MainActivity.this))
                        .commit();
                break;

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




