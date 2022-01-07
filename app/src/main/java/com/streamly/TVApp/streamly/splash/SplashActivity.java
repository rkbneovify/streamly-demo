package com.streamly.TVApp.streamly.splash;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.util.Log;

import androidx.fragment.app.FragmentActivity;
import com.streamly.TVApp.streamly.MainActivity;
import com.streamly.TVApp.streamly.R;
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.log_in.LogInActivity;
import com.streamly.TVApp.streamly.log_in.PersonalDeviceName;
import com.streamly.TVApp.streamly.user_room.UserRoomActivity;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;


public class SplashActivity extends FragmentActivity {

    String auth_token;
    String auth_token_passcode;
    String personDeviceName;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);
//        NetworkUtils network = new NetworkUtils(getApplicationContext());
//        network.registerNetworkCallback();

//        Log.e("auth_token ",""+TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null));
//        TokenStorage.clearSharedToken(SplashActivity.this);
        Log.e("uniqueId", "uniqueId "+ Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID));

        auth_token = TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null);
        auth_token_passcode = TokenStorage.readSharedTokenPasscode(SplashActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, null);
        personDeviceName = TokenStorage.readPersonalDeviceName(SplashActivity.this, ConstantUtility.PERSONAL_DEVICE_NAME, null);
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                if (auth_token != null) {
                    startActivity(new Intent(SplashActivity.this, MainActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                    finish();
                }
                else if (auth_token_passcode != null && personDeviceName != null) {
                    startActivity(new Intent(SplashActivity.this, UserRoomActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                    finish();
                }else if(personDeviceName == null){
                    startActivity(new Intent(SplashActivity.this, PersonalDeviceName.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                    finish();
                } else {
                    startActivity(new Intent(SplashActivity.this, LogInActivity.class));
                    finish();
                }
            }
        },3000);

    }
}
