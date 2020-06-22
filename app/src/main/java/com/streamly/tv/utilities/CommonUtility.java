package com.streamly.tv.utilities;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.widget.Toast;
import com.streamly.tv.R;
import com.streamly.tv.epg.ChannellistModel;
import com.streamly.tv.epg.Datum;
import com.streamly.tv.epg.SimpleChannel;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.settings.privacy.PrivacyPolicyModel;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class CommonUtility {
    public static final String TAG=CommonUtility.class.getSimpleName();

    public CommonUtility() {
        throw new IllegalStateException("Utility class");
    }

    public static ProgressDialog custom_loader(Context context) {
        final ProgressDialog dialog = new ProgressDialog(context, R.style.MyProgressDialog);
        dialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
        dialog.setMessage(ConstantUtility.LOADING);
        return dialog;
    }

    public static void checkInternetConnection(Context context){
        // Check network connection
        if (ConstantUtility.isNetworkConnected){
            //todo when Internet has Connected
        }else{
            // when internet do not Connected
           // Toast.makeText(context, ConstantUtility.CHECK_INTERNET_CONNECTION, Toast.LENGTH_SHORT).show();
        }
    }

    public static void showToast(Context context, String message){
        Toast.makeText(context, message, Toast.LENGTH_SHORT).show();
    }

}
