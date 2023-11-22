package com.adaptivemedia.streamlytv.streamly.HotelQRCode;

import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.ProgressBar;

import com.adaptivemedia.streamlytv.streamly.QRCode.QRCodeActivity;
import com.adaptivemedia.streamlytv.streamly.dialogs.CommonDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.DialogClickListeners;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.log_in.PersonalDeviceName;
import com.adaptivemedia.streamlytv.streamly.loginusingpasscode.LoginUsingPasscodeActivity;
import com.adaptivemedia.streamlytv.streamly.logoutdevices.LogoutDevicesActivity;
import com.adaptivemedia.streamlytv.streamly.settings.SettingsActivity;
import com.bumptech.glide.Glide;
import com.google.gson.Gson;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.streamly.QRCode.HotelQRModel;
import com.adaptivemedia.streamlytv.streamly.QRCode.QRScanStatusModel;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.DeviceLimitDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLogInModel;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLoginErrorResponse;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.SignOutDeviceWithQrCodeKeyDialog;
import com.adaptivemedia.streamlytv.streamly.user_room.UserRoomActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HotelQRCodeActivity extends FragmentActivity {
    private static final String TAG = "HotelQRCodeActivity";
    private ProgressBar progressBar;
    private  String uniqueId;
    private ImageView imgQrCode;
    private String userAgent;
    private String key;
    private Handler mHandler = new Handler();
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_hotel_qrcode1);
        CommonUtility.checkInternetConnection(HotelQRCodeActivity.this);
        userAgent = System.getProperty("http.agent");
        uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        initViews();
        getQRCode();
    }

    private void getHotelLogin() {
        key = TokenStorage.readHotelKey(HotelQRCodeActivity.this, ConstantUtility.KEY, null);
        progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface= ApiClient.getClientHotelLogin().create(ApiInterface.class);
        Call<NewLogInModel> call=apiInterface.getHotelLogin(userAgent,key,uniqueId);
        call.enqueue(new Callback<NewLogInModel>() {
            @Override
            public void onResponse(Call<NewLogInModel> call, Response<NewLogInModel> response) {
                try {
                    Log.e(TAG, "resCode "+response.code());
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
                            TokenStorage.savedRoomList(HotelQRCodeActivity.this, ConstantUtility.ROOM_LIST, json);
                            TokenStorage.savedSharedToken(HotelQRCodeActivity.this, ConstantUtility.USER_ID, userId.toString());
                            TokenStorage.savedSharedToken(HotelQRCodeActivity.this, ConstantUtility.AUTH_TOKEN, access_token);
                            TokenStorage.savedSharedTokenPassCode(HotelQRCodeActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, access_token);
                            TokenStorage.saveSessionId(HotelQRCodeActivity.this, ConstantUtility.SESSION_ID, sessionid);
                            TokenStorage.savePersonalDeviceID(HotelQRCodeActivity.this, ConstantUtility.PERSONAL_DEVICE_ID, personalDeviceId);
                            TokenStorage.saveDevicetype(HotelQRCodeActivity.this, ConstantUtility.DEVICE_TYPE, deviceType);
                            TokenStorage.savePersonalDeviceNamePopup(HotelQRCodeActivity.this, ConstantUtility.PERSONAL_DEVICE_POPUP, personaDeviceNamePopup);
                            TokenStorage.saveUserRole(HotelQRCodeActivity.this, ConstantUtility.USER_ROLE, response.body().getUserRole());
                            TokenStorage.saveHomeTimeZoneOffset(HotelQRCodeActivity.this,ConstantUtility.HOMETIMEZONE,response.body().gettimezone());
                            CommonUtility.showToast(HotelQRCodeActivity.this, response.body().getMessage());
//                        startActivity(new Intent(LoginUsingPasscodeActivity.this, MainActivity.class));
//                        finish();
                            if (personaDeviceNamePopup) {
                                startActivity(new Intent(HotelQRCodeActivity.this, PersonalDeviceName.class));
                            } else {
                                startActivity(new Intent(HotelQRCodeActivity.this, UserRoomActivity.class));
                            }
                            finish();
                        } else if (response.body().getUserRole().equals("user")) {
                            CommonUtility.showToast(HotelQRCodeActivity.this, response.body().getMessage());
                            TokenStorage.savedSharedToken(HotelQRCodeActivity.this, ConstantUtility.AUTH_TOKEN, access_token);
                            TokenStorage.savedSharedTokenPassCode(HotelQRCodeActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, access_token);
                            TokenStorage.saveSessionId(HotelQRCodeActivity.this, ConstantUtility.SESSION_ID, sessionid);
                            TokenStorage.savePersonalDeviceID(HotelQRCodeActivity.this, ConstantUtility.PERSONAL_DEVICE_ID, personalDeviceId);
                            TokenStorage.saveDevicetype(HotelQRCodeActivity.this, ConstantUtility.DEVICE_TYPE, deviceType);
                            TokenStorage.savePersonalDeviceNamePopup(HotelQRCodeActivity.this, ConstantUtility.PERSONAL_DEVICE_POPUP, personaDeviceNamePopup);
                            TokenStorage.saveUserRole(HotelQRCodeActivity.this, ConstantUtility.USER_ROLE, response.body().getUserRole());
                            TokenStorage.saveHomeTimeZoneOffset(HotelQRCodeActivity.this,ConstantUtility.HOMETIMEZONE,response.body().gettimezone());
                            if (personaDeviceNamePopup) {
                                startActivity(new Intent(HotelQRCodeActivity.this, PersonalDeviceName.class));
                            } else {
                                startActivity(new Intent(HotelQRCodeActivity.this, MainActivity.class));
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
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(HotelQRCodeActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
//                    CommonUtility.showToast(HotelQRCodeActivity.this,response.body().getMessage());
                    } else if (response.code()==429){
                        if(response.errorBody() !=null )
                        {
                            NewLoginErrorResponse logInModel=new Gson().fromJson(response.errorBody().string(),NewLoginErrorResponse.class);
                            if(logInModel.getData() !=null && logInModel.getData().toString()!="")
                            {
                                SignOutDeviceWithQrCodeKeyDialog signOutDeviceWithQrCodeKeyDialog = new SignOutDeviceWithQrCodeKeyDialog(HotelQRCodeActivity.this,logInModel.getData().toString(),key,uniqueId);
                                signOutDeviceWithQrCodeKeyDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                signOutDeviceWithQrCodeKeyDialog.show();
                            }

                            if(logInModel.getMessage() !=null && !logInModel.getMessage().toString().equals("")){
                                if(logInModel.getMessage().contains("Your account is in use on")) {
                                    DeviceLimitDialog deviceLimitDialog = new DeviceLimitDialog(HotelQRCodeActivity.this,logInModel.getMessage().toString());
                                    deviceLimitDialog.show();
                                }
                            }

                        }
                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(HotelQRCodeActivity.this,ConstantUtility.SERVER_ISSUE);
                    }

                }
                catch (Exception e){
                    e.printStackTrace();
                    Log.e(TAG,"Exception: "+e.toString());
                }
                progressBar.setVisibility(View.INVISIBLE);
                Log.e(TAG,"ResponCode "+response.code()+"");
            }


            @Override
            public void onFailure(Call<NewLogInModel> call, Throwable t) {
                Log.e(TAG,"failure " +t);
                progressBar.setVisibility(View.INVISIBLE);
            }
        });

    }
    private Runnable mRunnable = new Runnable() {
        @Override
        public void run() {
            getQRCodeScanStatus();
            mHandler.postDelayed(this, 5000);
        }
    };

    public void startRepeating() {
        mRunnable.run();
    }

    public void stopRepeating() {
        mHandler.removeCallbacks(mRunnable);
    }

    private void getQRCodeScanStatus() {
        key = TokenStorage.readHotelKey(HotelQRCodeActivity.this, ConstantUtility.KEY, null);

        ApiInterface apiInterface= ApiClient.getClientHotelLogin().create(ApiInterface.class);
        Call<QRScanStatusModel> call=apiInterface.getQrCodeScanStatus(key);
        call.enqueue(new Callback<QRScanStatusModel>() {
            @Override
            public void onResponse(Call<QRScanStatusModel> call, Response<QRScanStatusModel> response) {
                try {

                    if (response.isSuccessful() && response.body()!=null && response.body().getStatus() ==1){
                        stopRepeating();
                        getHotelLogin();

                    }
                    else if(response.body().getStatus() ==2) {

                        stopRepeating();
                        getQRCode();
                    }


                }catch (Exception e){
                    e.printStackTrace();
                    Log.e(TAG,"Exception: "+e.toString());
                }
                Log.e(TAG, "resCode  getQRCodeScanStatus "+response.body().getStatus());


            }


            @Override
            public void onFailure(Call<QRScanStatusModel> call, Throwable t) {
                Log.e(TAG,"failure " +t);

            }
        });

    }

    private void initViews(){
        progressBar=findViewById(R.id.progress_bar);
        imgQrCode=findViewById(R.id.img_hotel_qr_code);

    }

    private void getQRCode() {
        progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface= ApiClient.getClientHotelLogin().create(ApiInterface.class);
        Call<HotelQRModel> call=apiInterface.getHotelQrCode();
        call.enqueue(new Callback<HotelQRModel>() {
            @Override
            public void onResponse(Call<HotelQRModel> call, Response<HotelQRModel> response) {
                try {

                    if (response.isSuccessful() && response.body() != null && response.body().getStatus() == 1) {
                        String qrImage=response.body().getMsg();
                        String key=""+response.body().getKey();
                        Log.e(TAG, "onResponse: image "+qrImage );
                        setQRImage(qrImage);
                        TokenStorage.savedHotelKey(HotelQRCodeActivity.this, ConstantUtility.KEY,key);
                        Log.e(TAG,"getQRCode key : "+TokenStorage.readHotelKey(HotelQRCodeActivity.this, ConstantUtility.KEY, null));
                        startRepeating();
                    } else if (response.body().getStatus()  == 500) {
                        CommonUtility.showToast(HotelQRCodeActivity.this, ConstantUtility.SERVER_ISSUE);
                    }
                    progressBar.setVisibility(View.INVISIBLE);
                }catch (Exception e){
                    e.printStackTrace();
                    Log.e(TAG,"Exception: "+e.toString());
                }   progressBar.setVisibility(View.INVISIBLE);

            }

            @Override
            public void onFailure(Call<HotelQRModel> call, Throwable t) {
                Log.e(TAG,"failure " +t);
                progressBar.setVisibility(View.INVISIBLE);
            }
        });
    }

    private void showDialog(NewLoginErrorResponse body) {
        Log.e(TAG, "showDialog: " + body);
        CommonDialog c = new CommonDialog(body.getMessage(), new DialogClickListeners() {

            @Override
            public void negative() {


            }

            @Override
            public void positive() {
                Intent intent = new Intent(HotelQRCodeActivity.this, LogoutDevicesActivity.class);
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

    private void setQRImage(String qrCode_Link) {
        Glide.with(HotelQRCodeActivity.this).load(qrCode_Link).into(imgQrCode);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        stopRepeating();
    }

    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == Activity.RESULT_OK) {
            getHotelLogin();
        }
    }
}