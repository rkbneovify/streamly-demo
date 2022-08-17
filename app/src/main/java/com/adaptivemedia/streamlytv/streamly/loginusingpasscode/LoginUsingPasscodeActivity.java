package com.adaptivemedia.streamlytv.streamly.loginusingpasscode;

import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.adaptivemedia.streamlytv.streamly.HotelQRCode.HotelQRCodeActivity;
import com.adaptivemedia.streamlytv.streamly.dialogs.CommonDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.DialogClickListeners;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.log_in.PersonalDeviceName;
import com.adaptivemedia.streamlytv.streamly.logoutdevices.LogoutDevicesActivity;
import com.google.gson.Gson;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.DeviceLimitDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLogInModel;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLoginErrorResponse;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.SignOutDeviceWithPasscodeDialog;
import com.adaptivemedia.streamlytv.streamly.user_room.UserRoomActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LoginUsingPasscodeActivity extends FragmentActivity {
    private static final String TAG = LoginUsingPasscodeActivity.class.getSimpleName();
EditText et_passcode;
Button btn_sign_in_using_passcode;
    private ProgressDialog dialog;
    private  String uniqueId;
    private  String userAgent;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login_using_passcode);
        userAgent = System.getProperty("http.agent");
        et_passcode=findViewById(R.id.et_passcode);
        //et_passcode.setText("rQWvyj8JoG");
        btn_sign_in_using_passcode=findViewById(R.id.btn_sign_in_using_passcode);
        uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        dialog= CommonUtility.custom_loader(LoginUsingPasscodeActivity.this);
        TokenStorage.clearSharedToken(LoginUsingPasscodeActivity.this);
        btn_sign_in_using_passcode.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (ConstantUtility.isNetworkConnected){
                    if (et_passcode.getText().toString().matches("")){
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.FILL_PASSCODE);


                    }else {
                        signInCall();
                    }

                }else {
                    CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.CHECK_INTERNET_CONNECTION);
                }

            }
        });



    }

    private void login() {
        if (ConstantUtility.isNetworkConnected) {
            if (et_passcode.getText().toString().matches("")) {
                CommonUtility.showToast(LoginUsingPasscodeActivity.this, ConstantUtility.FILL_PASSCODE);

            } else {
                signInCall();
            }

        } else {
            CommonUtility.showToast(LoginUsingPasscodeActivity.this, ConstantUtility.CHECK_INTERNET_CONNECTION);
        }
    }


    private void signInCall() {
        dialog.show();
        ApiInterface apiInterface= ApiClient.getClientTesting().create(ApiInterface.class);
        Call<NewLogInModel> call=apiInterface.postLoginUsingPasscode(userAgent,et_passcode.getText().toString(),uniqueId);
        call.enqueue(new Callback<NewLogInModel>() {
            @Override
            public void onResponse(Call<NewLogInModel> call, Response<NewLogInModel> response) {
                try {
                    if (response.isSuccessful() && response.body() != null && response.code() != 429) {
                        String access_token = response.body().getAccessToken();
                        String sessionid = response.body().getSessionid();
                        String personalDeviceId = response.body().getPersonalDeviceId();
                        String deviceType = response.body().getDeviceType();
                        boolean personaDeviceNamePopup = response.body().getPersonaldevicename_popup();
                        Log.e(TAG, "onResponse222: " + personaDeviceNamePopup);
                        Log.e(TAG, "onResponse222: " + response.body().getUserRole());
                        Log.e(TAG, "onResponse222: " + response.body().getDeviceType());
                        Log.e(TAG, "onResponse222: " + uniqueId);
                        Log.e(TAG, "onResponse222: " + response.body().getPersonalDeviceId());
                        if (response.body().getUserRole().equals("tieruser")) {
                            Long userId = response.body().getUser().getId();
//                        ArrayList<Room> set = new ArrayList<>();
//                        set.addAll(response.body().getRooms());
                            Gson gson = new Gson();
                            String json = gson.toJson(response.body().getRooms());
                            TokenStorage.savedRoomList(LoginUsingPasscodeActivity.this, ConstantUtility.ROOM_LIST, json);
                            TokenStorage.savedSharedToken(LoginUsingPasscodeActivity.this, ConstantUtility.USER_ID, userId.toString());
                            TokenStorage.savedSharedToken(LoginUsingPasscodeActivity.this, ConstantUtility.AUTH_TOKEN, access_token);
                            TokenStorage.savedSharedTokenPassCode(LoginUsingPasscodeActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, access_token);
                            TokenStorage.saveSessionId(LoginUsingPasscodeActivity.this, ConstantUtility.SESSION_ID, sessionid);
                            TokenStorage.savePersonalDeviceID(LoginUsingPasscodeActivity.this, ConstantUtility.PERSONAL_DEVICE_ID, personalDeviceId);
                            TokenStorage.saveDevicetype(LoginUsingPasscodeActivity.this, ConstantUtility.DEVICE_TYPE, deviceType);
                            TokenStorage.savePersonalDeviceNamePopup(LoginUsingPasscodeActivity.this, ConstantUtility.PERSONAL_DEVICE_POPUP, personaDeviceNamePopup);
                            TokenStorage.saveUserRole(LoginUsingPasscodeActivity.this, ConstantUtility.USER_ROLE, response.body().getUserRole());
                            TokenStorage.saveHomeTimeZoneOffset(LoginUsingPasscodeActivity.this,ConstantUtility.HOMETIMEZONE,response.body().getHouroffset());
                            CommonUtility.showToast(LoginUsingPasscodeActivity.this, response.body().getMessage());
//                        startActivity(new Intent(LoginUsingPasscodeActivity.this, MainActivity.class));
//                        finish();
                            if (personaDeviceNamePopup) {
                                startActivity(new Intent(LoginUsingPasscodeActivity.this, PersonalDeviceName.class));
                            } else {
                                startActivity(new Intent(LoginUsingPasscodeActivity.this, UserRoomActivity.class));
                            }
                            finish();
                        }
                        else if(response.body().getUserRole().equals("user")) {
                            CommonUtility.showToast(LoginUsingPasscodeActivity.this, response.body().getMessage());
                            TokenStorage.savedSharedToken(LoginUsingPasscodeActivity.this, ConstantUtility.AUTH_TOKEN, access_token);
                            TokenStorage.savedSharedTokenPassCode(LoginUsingPasscodeActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, access_token);
                            TokenStorage.saveSessionId(LoginUsingPasscodeActivity.this, ConstantUtility.SESSION_ID, sessionid);
                            TokenStorage.savePersonalDeviceID(LoginUsingPasscodeActivity.this, ConstantUtility.PERSONAL_DEVICE_ID, personalDeviceId);
                            TokenStorage.saveDevicetype(LoginUsingPasscodeActivity.this, ConstantUtility.DEVICE_TYPE, deviceType);
                            TokenStorage.savePersonalDeviceNamePopup(LoginUsingPasscodeActivity.this, ConstantUtility.PERSONAL_DEVICE_POPUP, personaDeviceNamePopup);
                            TokenStorage.saveUserRole(LoginUsingPasscodeActivity.this, ConstantUtility.USER_ROLE, response.body().getUserRole());
                            TokenStorage.saveHomeTimeZoneOffset(LoginUsingPasscodeActivity.this,ConstantUtility.HOMETIMEZONE,response.body().getHouroffset());
                            if (personaDeviceNamePopup) {
                                startActivity(new Intent(LoginUsingPasscodeActivity.this, PersonalDeviceName.class));
                            } else {
                                startActivity(new Intent(LoginUsingPasscodeActivity.this, MainActivity.class));
                            }
                            finish();
                        }


                    }else if (response.code() == 479) {
                        String s = response.errorBody().string();

                        NewLoginErrorResponse loginErrorResponse = new Gson().fromJson(s,
                                NewLoginErrorResponse.class);
                        if (loginErrorResponse.getLoggedindeviceinfolist() != null) {
                            showDialog(loginErrorResponse);
                        }
                    }
                    else if (response.code()==401){
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(LoginUsingPasscodeActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
                    }
                    else if (response.code()==429){
                       if(response.errorBody() !=null )
                       {
                           NewLoginErrorResponse logInModel=new Gson().fromJson(response.errorBody().string(),NewLoginErrorResponse.class);
                           if(logInModel.getData() !=null && logInModel.getData().toString()!="")
                           {

                               Log.i(TAG, "onResponse:429  "+response.body());
                               Log.i(TAG, "onResponse:429  "+logInModel.getData().toString());
                               SignOutDeviceWithPasscodeDialog signOutDeviceWithPasscodeDialog = new SignOutDeviceWithPasscodeDialog(LoginUsingPasscodeActivity.this,logInModel.getData().toString(),et_passcode.getText().toString(),uniqueId);
                               signOutDeviceWithPasscodeDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                               signOutDeviceWithPasscodeDialog.show();
                           }
                           if(logInModel.getMessage() !=null && !logInModel.getMessage().toString().equals("")){
                               if(logInModel.getMessage().contains("Your account is in use on")) {
                                   DeviceLimitDialog deviceLimitDialog = new DeviceLimitDialog(LoginUsingPasscodeActivity.this,logInModel.getMessage().toString());
                                   deviceLimitDialog.show();
                               }
                           }

                       }


                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.SERVER_ISSUE);
                    }
                    else if(response.code() == 404){
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,"Wrong Passcode");
                    }

                }catch (Exception e){
                    e.printStackTrace();
                    Log.e(TAG,"Exception: "+e.toString());
                } dialog.dismiss();
                Log.e(TAG,"ResponCode "+response.code()+"");
            }


            @Override
            public void onFailure(Call<NewLogInModel> call, Throwable t) {
                Log.e(TAG,"failure: "+t.getMessage());
                Log.e(TAG,"failRequest: "+call.request());
                CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.SERVER_ISSUE);
                dialog.dismiss();
            }
        });





//        AndroidNetworking.post(BASE_URL_TESTING+"/api/auth/login-with-passcode")
//                .addBodyParameter("passcode", et_passcode.getText().toString())
//                .addBodyParameter("deviceid", uniqueId)
//                .setPriority(Priority.HIGH)
//                .build()
//                .getAsJSONObject(new JSONObjectRequestListener() {
//                    @Override
//                    public void onResponse(JSONObject response) {
//                        try {
//                            Log.i(TAG, "onResponse: login passcode response "+response.toString());
//
////                            if (response.getInt("response_code") == 200) {
////                                Log.i(TAG, "onResponse: roomListData 6 ");
////                                CommonUtility.showToast(UserRoomActivity.this, response.getString("message").toString());
////                                TokenStorage.savedSharedToken(UserRoomActivity.this, ConstantUtility.AUTH_TOKEN, authToken);
////                                startActivity(new Intent(UserRoomActivity.this, MainActivity.class));
////                                finish();
////                            } else {
////                                Log.i(TAG, "onResponse: roomListData 7 ");
////                                CommonUtility.showToast(UserRoomActivity.this, response.getString("message").toString());
////                            }
//
//
//                        } catch (Exception e) {
//                            Log.e(TAG, "failure: catch " + " " + e.getLocalizedMessage());
//                            Log.e(TAG, "failure: catch " + " " + e.getMessage());
//
//                        }
//                        // do anything with response
//                    }
//
//                    @Override
//                    public void onError(ANError error) {
//                        Log.e(TAG, "failure: onError 1 " + " " + error.getLocalizedMessage());
//                        Log.e(TAG, "failure: onError 2 " + " " + error.getMessage());
//                        // handle error
//
//                    }
//                });
    }


    private void showDialog(NewLoginErrorResponse body) {
        Log.e(TAG, "showDialog: " + body);
        CommonDialog c = new CommonDialog(body.getMessage(), new DialogClickListeners() {

            @Override
            public void negative() {


            }

            @Override
            public void positive() {
                Intent intent = new Intent(LoginUsingPasscodeActivity.this, LogoutDevicesActivity.class);
                intent.putExtra("list", body.getLoggedindeviceinfolist());
                intent.putExtra("isLogin", true);
                startActivityForResult(intent, 123);
            }
        });

        c.show(getSupportFragmentManager(), "ffnadjg");
        /*     AlertDialog.Builder dialog = new AlertDialog.Builder(LogInActivity.this, R.style.AlertDialogCustom);
         *//*
        dialog.setTitle("Register Now");*//*
        dialog.setMessage(body.getMessage());

        // Specifying a listener allows you to take an action before dismissing the dialog.
        // The dialog is automatically dismissed when a dialog button is clicked.
        dialog.setPositiveButton(getString(R.string.yes), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int which) {



            }
        });
        dialog.setNegativeButton(getString(R.string.no), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int which) {
                // Continue with delete operation
                dialog.dismiss();


            }
        });
        dialog.show();
        dialog.setCancelable(true);*/
    }

    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == Activity.RESULT_OK) {
            login();
        }
    }
}