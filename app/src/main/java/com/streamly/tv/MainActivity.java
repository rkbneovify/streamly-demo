package com.streamly.tv;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import com.jakewharton.threetenabp.AndroidThreeTen;
import com.streamly.tv.epg.ChannellistModel;
import com.streamly.tv.epg.Datum;
import com.streamly.tv.epg.SimpleChannel;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.settings.SettingsActivity;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import com.streamly.tv.utilities.NetworkKeys;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class MainActivity extends FragmentActivity {
    private static final String TAG=MainActivity.class.getSimpleName();
    String auth_token;



    @Override
    protected void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        // Initialize the 310 backport lib. Usually you do this in your application object
        AndroidThreeTen.init(this);
        setContentView(R.layout.activity_main);
         auth_token = TokenStorage.readSharedToken(MainActivity.this, ConstantUtility.AUTH_TOKEN, null);


       /* getSupportFragmentManager().beginTransaction()
                .replace(R.id.fragment_container, new EpgFragment())
                .commit();*/


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



    public void getChannelList(){

        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<ChannellistModel> call=apiInterface.getChannelList(NetworkKeys.KEY_BEARER +auth_token,NetworkKeys.KEY_APP_JSON);

        call.enqueue(new Callback<ChannellistModel>() {
            @Override
            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {

                try {
                    if (response.isSuccessful() && response.body() != null) {


//                        List<SimpleChannel> stringList=new ArrayList<>();
                        List<Datum> list=new ArrayList<>();
                       list=response.body().getData();

                       for (int i=0;i<list.size();i++){

                           list.get(i).getCallSign();
                       }

                    } else if (response.code() == 401) {
                        CommonUtility.showToast(MainActivity.this, response.body().getMessage());
                        TokenStorage.clearSharedToken(MainActivity.this);
                        startActivity(new Intent(MainActivity.this, LogInActivity.class));
                        finish();
                    }else if (response.code()==404){
                        TokenStorage.clearSharedToken(MainActivity.this);
                        CommonUtility.showToast(MainActivity.this,ConstantUtility.SESSION_EXPIRED);
                        startActivity(new Intent(MainActivity.this, LogInActivity.class));
                        finish();
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(MainActivity.this, ConstantUtility.SERVER_ISSUE);
                    }
                }catch(Exception ex){
                    ex.printStackTrace();
                    Log.e(TAG, "Exception "+ex);
                }
                Log.e(TAG,"support_numbers "+response.code()+"");
            }

            @Override
            public void onFailure(Call<ChannellistModel> call, Throwable t) {
                Log.e(TAG,"Throwable "+t+"");
            }
        });



    }
}




