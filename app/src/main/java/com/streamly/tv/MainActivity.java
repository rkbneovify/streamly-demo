package com.streamly.tv;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import com.jakewharton.threetenabp.AndroidThreeTen;
import com.streamly.tv.settings.SettingsActivity;


public class MainActivity extends FragmentActivity {


    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Initialize the 310 backport lib. Usually you do this in your application object
        AndroidThreeTen.init(this);
        setContentView(R.layout.activity_main);



        getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, new EpgFragment())
                .commit();


        findViewById(R.id.setting_text).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(MainActivity.this, SettingsActivity.class));
                finish();

            }
        });


    }
}




