package com.streamly.TVApp.Streamly.splash;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import androidx.fragment.app.FragmentActivity;
import com.streamly.TVApp.Streamly.MainActivity;
import com.streamly.TVApp.Streamly.R;
import com.streamly.TVApp.Streamly.local_storage.TokenStorage;
import com.streamly.TVApp.Streamly.log_in.LogInActivity;
import com.streamly.TVApp.Streamly.utilities.ConstantUtility;
import com.streamly.TVApp.Streamly.utilities.NetworkUtils;


public class SplashActivity extends FragmentActivity {

    String auth_token;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
//        NetworkUtils network = new NetworkUtils(getApplicationContext());
//        network.registerNetworkCallback();

//        Log.e("auth_token ",""+TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null));
//        TokenStorage.clearSharedToken(SplashActivity.this);

        auth_token = TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null);
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                if (auth_token != null) {
                    startActivity(new Intent(SplashActivity.this, MainActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                    finish();
                }else {
                    startActivity(new Intent(SplashActivity.this, LogInActivity.class));
                    finish();
                }
            }
        },3000);

    }
}
