package com.streamly.TVApp.streamly.settings.device_signout;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;

import androidx.annotation.NonNull;

import com.streamly.TVApp.streamly.R;
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.log_in.LogInActivity;
import com.streamly.TVApp.streamly.log_in.LogInModel;
import com.streamly.TVApp.streamly.loginusingpasscode.LoginUsingPasscodeActivity;
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient;
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface;
import com.streamly.TVApp.streamly.utilities.CommonUtility;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SignOutDeviceDialog extends Dialog {
    private final String TAG= SignOutDeviceDialog.class.getSimpleName();
    private Context context;
    private Button btnNo,btnYes;
    private ProgressDialog dialog;
    String deviceId="";

    public SignOutDeviceDialog(@NonNull Context context, String deviceId) {
        super(context);
        this.context=context;
        this.deviceId=deviceId;
    }



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_device_signout);
        CommonUtility.checkInternetConnection(context);
        dialog= CommonUtility.custom_loader(context);

        initViews();


//        btnNo.requestFocus();
        btnNo.requestFocus();

        btnNo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });

        btnYes.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (ConstantUtility.isNetworkConnected) {
                    deviceSignOutCall();
                    cancel();
                }else {
                    CommonUtility.showToast(context,ConstantUtility.CHECK_INTERNET_CONNECTION);
                }

            }
        });
    }



    private void deviceSignOutCall() {
        dialog.show();
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<DeviceSignOutResponse> call=apiInterface.postDeviceLogOutCall(deviceId);
        call.enqueue(new Callback<DeviceSignOutResponse>() {
            @Override
            public void onResponse(Call<DeviceSignOutResponse> call, Response<DeviceSignOutResponse> response) {
                try{

                  if (response.isSuccessful() && response.body()!=null && response.code()==200){
                        context.startActivity(new Intent(context, LoginUsingPasscodeActivity.class));
                        CommonUtility.showToast(context,response.body().getMessage());
                        ((Activity)context).finish();
                    }

                    else if (response.code()==404){
                        CommonUtility.showToast(context,ConstantUtility.SESSION_EXPIRED);
                        context.startActivity(new Intent(context, LoginUsingPasscodeActivity.class));
                        ((Activity)context).finish();
                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(context,ConstantUtility.SERVER_ISSUE);
                    }

                }catch (Exception ex){
                    ex.printStackTrace();
                    Log.e(TAG, "Exception "+ex);
                }
                Log.e(TAG,"ResponCode "+response.code()+"");
                dialog.dismiss();
            }



            @Override
            public void onFailure(Call<DeviceSignOutResponse> call, Throwable t) {
                Log.e(TAG,"failure: "+call+" "+t.getLocalizedMessage());
                CommonUtility.showToast(context,ConstantUtility.SERVER_ISSUE);
                dialog.dismiss();
            }
        });
    }


    private void initViews() {
        btnNo=findViewById(R.id.btn_no_signout);
        btnYes=findViewById(R.id.btn_yes_signout);
    }
}
