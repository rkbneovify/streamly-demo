package com.streamly.tv.splash;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;

import androidx.fragment.app.FragmentActivity;
import com.streamly.tv.MainActivity;
import com.streamly.tv.R;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.settings.SettingsActivity;
import com.streamly.tv.utilities.ConstantUtility;
import com.streamly.tv.utilities.NetworkUtils;


public class SplashActivity extends FragmentActivity {

    String auth_token;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
        NetworkUtils network = new NetworkUtils(getApplicationContext());
        network.registerNetworkCallback();

//        Log.e("auth_token ",""+TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null));

//        TokenStorage.clearSharedToken(SplashActivity.this);
        auth_token = TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null);
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                if (auth_token != null) {
//                    startActivity(new Intent(SplashActivity.this, MainActivity.class));
                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                    finish();
                }else {
                    startActivity(new Intent(SplashActivity.this, LogInActivity.class));
                    finish();
                }
            }
        },3000);





    }
}
