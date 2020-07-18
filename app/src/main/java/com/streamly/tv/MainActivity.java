package com.streamly.tv;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import androidx.annotation.Nullable;
import androidx.databinding.DataBindingUtil;
import androidx.fragment.app.FragmentActivity;
import com.jakewharton.threetenabp.AndroidThreeTen;
import com.streamly.tv.databinding.ActivityMainBinding;
import com.streamly.tv.epg.kids.KidsFragment;
import com.streamly.tv.epg.movies.MoviesFragment;
import com.streamly.tv.epg.news.NewsFragment;
import com.streamly.tv.epg.sports.SportFragment;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.search_channels.SearchActivity;
import com.streamly.tv.settings.SettingsActivity;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;


public class MainActivity extends FragmentActivity implements View.OnClickListener {
    private static final String TAG=MainActivity.class.getSimpleName();
    String authToken;
    ActivityMainBinding mainBinding;

    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        AndroidThreeTen.init(this);
//        setContentView(R.layout.activity_main);
         mainBinding= DataBindingUtil.setContentView(this, R.layout.activity_main);
        authToken = TokenStorage.readSharedToken(MainActivity.this, ConstantUtility.AUTH_TOKEN, null);

         Log.e(TAG,"authToken "+authToken);

         initViews();

        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, new EpgFragment(MainActivity.this))
                .commit();



    }

    private void initViews() {
        mainBinding.imageSearch.setOnClickListener(this);
        mainBinding.textAll.setOnClickListener(this);
        mainBinding.textRecent.setOnClickListener(this);
        mainBinding.textMyChannels.setOnClickListener(this);
        mainBinding.textSports.setOnClickListener(this);
        mainBinding.textNews.setOnClickListener(this);
        mainBinding.textMovies.setOnClickListener(this);
        mainBinding.textKids.setOnClickListener(this);
        mainBinding.settingText.setOnClickListener(this);
        mainBinding.goBackText.setOnClickListener(this);


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

            case R.id.text_recent:
                CommonUtility.showToast(MainActivity.this,"Work in Progress");
                break;

            case R.id.text_my_channels:
                CommonUtility.showToast(MainActivity.this,"Work in Progress");
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




