package com.adaptivemedia.streamlytv.streamly.settings.device_signout;

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

import com.google.gson.Gson;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLogInModel;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLoginErrorResponse;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.user_room.UserRoomActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SignOutDeviceWithQrCodeKeyDialog extends Dialog {
    private final String TAG= SignOutDeviceWithQrCodeKeyDialog.class.getSimpleName();
    private Context context;
    private Button btnNo,btnYes;
    private ProgressDialog dialog;
    String deviceId="";
    String userAgent;
    String key="";
    String newDeviceId="";

    public SignOutDeviceWithQrCodeKeyDialog(@NonNull Context context, String deviceId, String key, String newDeviceId) {
        super(context);
        this.context=context;
        this.deviceId=deviceId;
        this.key=key;
        this.newDeviceId=newDeviceId;
    }



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_device_signout_with_passcode);
        CommonUtility.checkInternetConnection(context);
        dialog= CommonUtility.custom_loader(context);
        userAgent = System.getProperty("http.agent");

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
        ApiInterface apiInterface= ApiClient.getClientHotelLogin().create(ApiInterface.class);
        Call<DeviceSignOutResponse> call=apiInterface.postDeviceLogOutCall("Bearer ",deviceId);
        call.enqueue(new Callback<DeviceSignOutResponse>() {
            @Override
            public void onResponse(Call<DeviceSignOutResponse> call, Response<DeviceSignOutResponse> response) {
                try{

                  if (response.isSuccessful() && response.body()!=null && response.code()==200){

                        CommonUtility.showToast(context,response.body().getMessage());
                      loginAgainWithPasscode();
                    }

                    else if (response.code()==404){
                        CommonUtility.showToast(context,ConstantUtility.SESSION_EXPIRED);
                        context.startActivity(new Intent(context, LogInActivity.class));
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

    private void loginAgainWithPasscode() {
        dialog.show();
        ApiInterface apiInterface= ApiClient.getClientHotelLogin().create(ApiInterface.class);
        Call<NewLogInModel> call=apiInterface.getHotelLogin(userAgent,key,newDeviceId);
        call.enqueue(new Callback<NewLogInModel>() {
            @Override
            public void onResponse(Call<NewLogInModel> call, Response<NewLogInModel> response) {
                try {
                    Log.e(TAG, "resCode "+response.code());
                    if (response.isSuccessful() && response.body()!=null && response.code()!=429){


                        String access_token=response.body().getAccessToken();
                        if(response.body().getUserRole().equals("tieruser"))
                        {
                            Long userId=response.body().getUser().getId();
                            Log.i(TAG, "onResponse: LoginUsingPasscode 1 "+access_token);
                            Log.i(TAG, "onResponse: LoginUsingPasscode 2 "+userId);
                            Gson gson = new Gson();
                            String json = gson.toJson(response.body().getRooms());

                            TokenStorage.savedRoomList(context, ConstantUtility.ROOM_LIST,json);
                            TokenStorage.savedSharedToken(context, ConstantUtility.USER_ID,userId.toString());
                            TokenStorage.savedSharedTokenPassCode(context, ConstantUtility.AUTH_TOKEN_PASSCODE,access_token);
                            CommonUtility.showToast(context,response.body().getMessage());
                            context.startActivity(new Intent(context, UserRoomActivity.class));
                            ((Activity)context).finish();
                        }

                        else if(response.body().getUserRole().equals("user")) {
                            CommonUtility.showToast(context,response.body().getMessage());
                            TokenStorage.savedSharedToken(context, ConstantUtility.AUTH_TOKEN, access_token);
                            context.startActivity(new Intent(context, MainActivity.class));
                            ((Activity)context).finish();
                        }

                    }
                    else if (response.code()==401){
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(context);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
                    } else if (response.code()==429){
//                    CommonUtility.showToast(LogInActivity.this,response.body().getMessage());
                        if(response.errorBody() !=null )
                        {
                            NewLoginErrorResponse logInModel=new Gson().fromJson(response.errorBody().string(),NewLoginErrorResponse.class);
                            if(logInModel.getData() !=null && logInModel.getData().toString()!="")
                            {

                                Log.i(TAG, "onResponse:429  "+response.body());
                                Log.i(TAG, "onResponse:429  "+logInModel.getData().toString());
//                                SignOutDeviceDialog signOutDeviceDialog = new SignOutDeviceDialog(HotelQRCodeActivity.this,logInModel.getData().toString(),edUserName.getText().toString(),
//                                        edPassword.getText().toString(),uniqueId);
//                                signOutDeviceDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                                signOutDeviceDialog.show();


//                                Log.i(TAG, "onResponse:429  "+response.body());
//                                Log.i(TAG, "onResponse:429  "+logInModel.getData().toString());
//                                SignOutDeviceWithQrCodeKeyDialog signOutDeviceWithQrCodeKeyDialog = new SignOutDeviceWithQrCodeKeyDialog(context,logInModel.getData().toString(),key,newDeviceId);
//                                signOutDeviceWithQrCodeKeyDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                                signOutDeviceWithQrCodeKeyDialog.show();
                            }

                        }
                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(context,ConstantUtility.SERVER_ISSUE);
                    }

                }catch (Exception e){
                    e.printStackTrace();
                    Log.e(TAG,"Exception: "+e.toString());
                }
                dialog.dismiss();
                Log.e(TAG,"ResponCode "+response.code()+"");
            }


            @Override
            public void onFailure(Call<NewLogInModel> call, Throwable t) {
                Log.e(TAG,"failure " +t);
               dialog.dismiss();
            }
        });

        }

    private void initViews() {
        btnNo=findViewById(R.id.btn_no_signout_with_passcode);
        btnYes=findViewById(R.id.btn_yes_signout_with_passcode);
    }
}
