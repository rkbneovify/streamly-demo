package com.adaptivemedia.streamlytv.streamly.splash;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.util.Log;

import androidx.fragment.app.FragmentActivity;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.log_in.PersonalDeviceName;
import com.adaptivemedia.streamlytv.streamly.onboardingtour.OnBoardingActivity;
import com.adaptivemedia.streamlytv.streamly.user_room.UserRoomActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ExtentionsKt;

import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;


public class SplashActivity extends FragmentActivity {

    String auth_token;
    String auth_token_passcode;
    String personDeviceName,room;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_splash);

//        NetworkUtils network = new NetworkUtils(getApplicationContext());
//        network.registerNetworkCallback();

//        Log.e("auth_token ",""+TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null));
//        TokenStorage.clearSharedToken(SplashActivity.this);
        Log.e("uniqueId", "uniqueId "+ Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID));
        Date c = Calendar.getInstance().getTime();
        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
        String reloaddate = df.format(c);
        TokenStorage.saveddate(SplashActivity.this, ConstantUtility.DATE, reloaddate);

        auth_token = TokenStorage.readSharedToken(SplashActivity.this, ConstantUtility.AUTH_TOKEN, null);
        auth_token_passcode = TokenStorage.readSharedTokenPasscode(SplashActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, null);
        personDeviceName = TokenStorage.readPersonalDeviceName(SplashActivity.this, ConstantUtility.PERSONAL_DEVICE_NAME, null);
        room = TokenStorage.readRoom(SplashActivity.this,ConstantUtility.ROOM,null);
        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
                String tour = TokenStorage.readOnboardingtour(SplashActivity.this, ConstantUtility.BOARDINGTOUR, null);
                if(tour == null){
                    startActivity(new Intent(SplashActivity.this, OnBoardingActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                    finish();
                }else {

                    if (room == null && ConstantUtility.isRemember == true) {
                        startActivity(new Intent(SplashActivity.this, UserRoomActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                        finish();
                    } else if (auth_token_passcode != null && personDeviceName != null && ConstantUtility.isRemember == false) {
                        startActivity(new Intent(SplashActivity.this, LogInActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                        finish();
                    } else if (auth_token_passcode == null && personDeviceName == null) {
                        startActivity(new Intent(SplashActivity.this, LogInActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                        finish();
                    } else if (auth_token_passcode != null && auth_token != null) {
                        startActivity(new Intent(SplashActivity.this, MainActivity.class));
//                    startActivity(new Intent(SplashActivity.this, SettingsActivity.class));
                        finish();
                    } else {
                        startActivity(new Intent(SplashActivity.this, LogInActivity.class));
                        finish();
                    }
                }
            }
        },3000);

    }
}
