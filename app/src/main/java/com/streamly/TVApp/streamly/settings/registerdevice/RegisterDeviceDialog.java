package com.streamly.TVApp.streamly.settings.registerdevice;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.google.gson.Gson;
import com.streamly.TVApp.streamly.MainActivity;
import com.streamly.TVApp.streamly.R;
import com.streamly.TVApp.streamly.dialogs.SessionExpiredDialog;
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.log_in.LogInActivity;
import com.streamly.TVApp.streamly.model.error_model.ErrorResponseModel;
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient;
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface;
import com.streamly.TVApp.streamly.utilities.CommonUtility;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;
import com.streamly.TVApp.streamly.utilities.NetworkKeys;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class RegisterDeviceDialog extends Dialog {
    private static final String TAG=RegisterDeviceDialog.class.getSimpleName();
    private Button btnOK;
    private RecyclerView rvDeviceModel;
    private Context context;
    private ProgressBar progressBar;
    private String auth_token;
    List<RegisterDeviceType> list=new ArrayList<RegisterDeviceType>();


    public RegisterDeviceDialog(@NonNull Context context) {
        super(context);
        this.context=context;
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_register_device);
        CommonUtility.checkInternetConnection(context);
        initViews();
        auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
        if (ConstantUtility.isNetworkConnected) {
            connectedDevice();
        }else {
            CommonUtility.showToast(context,ConstantUtility.CHECK_INTERNET_CONNECTION);
        }

        btnOK.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });

    }

    private void connectedDevice() {
        progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<ConnectedDeviceModel> call=apiInterface.getConnectedDevice(NetworkKeys.KEY_BEARER +auth_token);
        call.enqueue(new Callback<ConnectedDeviceModel>() {
            @Override
            public void onResponse(Call<ConnectedDeviceModel> call, Response<ConnectedDeviceModel> response) {

                try {
                    if (response.isSuccessful() && response.body() != null && response.code() != 401) {

                       List<Datum> datalist= response.body().getData();
                        Log.i(TAG, "onResponse: connectedDevice "+response.body().getData().toString());
                        String userAgent = System.getProperty("http.agent");
                        Log.i(TAG, "onResponse: user agent "+userAgent);


                       for (int i=0;i<datalist.size();i++){
                           list.add(new RegisterDeviceType( datalist.get(i).getDeviceName(),datalist.get(i).getDeviceId(),datalist.get(i).getDeviceImg()));
                       }
                        RegisterDeviceAdapter deviceAdapter=new RegisterDeviceAdapter(context,list);
                        rvDeviceModel.setLayoutManager(new LinearLayoutManager(context, RecyclerView.VERTICAL,false));
                        rvDeviceModel.setAdapter(deviceAdapter);



                    }
                    else if (response.code() == 401) {
                        if(response.errorBody()!=null){
                            SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(context);
                            sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            sessionExpiredDialog.setCancelable(false);
                            sessionExpiredDialog.show();


                        }
                    }else if (response.code()==404){
                        TokenStorage.clearSharedToken(context);
                        CommonUtility.showToast(context,ConstantUtility.SESSION_EXPIRED);
                        context.startActivity(new Intent(context, LogInActivity.class));
                        ((Activity)context).finish();
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE);
                    }
                }catch(Exception ex){
                    ex.printStackTrace();
                    Log.e(TAG, "Exception "+ex);
                }
                Log.e(TAG,"connected Devices "+response.code()+"");
                progressBar.setVisibility(View.INVISIBLE);
                btnOK.setVisibility(View.VISIBLE);
            }

            @Override
            public void onFailure(Call<ConnectedDeviceModel> call, Throwable t) {
                Log.e(TAG,"failure: "+call+" "+t.getLocalizedMessage());
                CommonUtility.showToast(context,ConstantUtility.SERVER_ISSUE);
                progressBar.setVisibility(View.INVISIBLE);
                btnOK.setVisibility(View.VISIBLE);
            }
        });
    }


    private void initViews() {
        btnOK=findViewById(R.id.btn_ok);
        rvDeviceModel=findViewById(R.id.rv_device_model);
        progressBar=findViewById(R.id.progress_bar);
    }


}
