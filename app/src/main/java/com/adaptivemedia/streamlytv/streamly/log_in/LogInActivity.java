package com.adaptivemedia.streamlytv.streamly.log_in;

import static com.adaptivemedia.streamlytv.streamly.utilities.ExtentionsKt.showDialogs;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.provider.Settings;
import android.text.InputType;
import android.text.method.HideReturnsTransformationMethod;
import android.text.method.PasswordTransformationMethod;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.CheckBox;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;

import com.adaptivemedia.streamlytv.streamly.dialogs.CommonDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.CustomDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.DialogClickListeners;
import com.adaptivemedia.streamlytv.streamly.dialogs.ToastDialog;
import com.adaptivemedia.streamlytv.streamly.logoutdevices.LogoutDevicesActivity;
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.LoginDevicesModel;
import com.adaptivemedia.streamlytv.streamly.registration.RegistrationActivity;
import com.adaptivemedia.streamlytv.streamly.settings.SettingsActivity;
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.DeviceSignOutResponse;
import com.adaptivemedia.streamlytv.streamly.settings.support.SupportActivity;
import com.google.gson.Gson;
import com.adaptivemedia.streamlytv.streamly.HotelQRCode.HotelQRCodeActivity;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.streamly.QRCode.QRCodeActivity;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.DeviceLimitDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.loginusingpasscode.LoginUsingPasscodeActivity;
import com.adaptivemedia.streamlytv.streamly.model.CheckDeviceType;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLogInModel;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLoginErrorResponse;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.SignOutDeviceDialog;
import com.adaptivemedia.streamlytv.streamly.settings.support.SupportDialog;
import com.adaptivemedia.streamlytv.streamly.termsofuses.TermsOfUses;
import com.adaptivemedia.streamlytv.streamly.user_room.UserRoomActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import com.google.gson.JsonObject;

import org.json.JSONObject;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LogInActivity extends FragmentActivity {
    private static final String TAG = com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity.class.getSimpleName();
    private ProgressBar dialog;
    private String uniqueId;
    private String userAgent;
    private EditText edUserName, edPassword;
    private TextView txtNeedHelp;
    private Button btnSignIn, btnRegisterView, btnTermsUses, btnHotelLogin, btnLoginUsingPasscode;
    private CheckBox rememberMeCheck;
    private ImageView passwordTog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        CommonUtility.checkInternetConnection(LogInActivity.this);
        setContentView(R.layout.activity_log_in);
        initViews();

        userAgent = System.getProperty("http.agent");
//        getUserAgentType();
        Log.i(TAG, "onResponse: user agent " + userAgent);

        uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        Log.i(TAG, "onCreate: tv uniqueId " + uniqueId);

        dialog = findViewById(R.id.login_progress_bar);
        TokenStorage.clearSharedToken(LogInActivity.this);


        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<QrVisibility> call = apiInterface.getVisibility();
        call.enqueue(new Callback<QrVisibility>() {
            @Override
            public void onResponse(Call<QrVisibility> call, Response<QrVisibility> response) {
                boolean savedVisibleKey = response.body().getVisible();
                TokenStorage.savedVisible(LogInActivity.this, ConstantUtility.QRVISIBILITY_KEY_VALUE, savedVisibleKey);


                if (response.body() != null && response.body().getVisible()) {
                    btnHotelLogin.setVisibility(View.GONE);
                    btnLoginUsingPasscode.setVisibility(View.GONE);
                    //  btnTermsUses.setVisibility(View.GONE);
                } else {
                    btnHotelLogin.setVisibility(View.VISIBLE);
                    btnLoginUsingPasscode.setVisibility(View.VISIBLE);
                    //   btnTermsUses.setVisibility(View.VISIBLE);
                }
            }

            @Override
            public void onFailure(Call<QrVisibility> call, Throwable t) {

            }
        });

        txtNeedHelp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                SupportDialog supportDialog = new SupportDialog(LogInActivity.this);
                supportDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                supportDialog.show();
            }
        });

        edUserName.setText("vikas.saini1@oodles.io");
        // edUserName.setText("kallusingh.rajput@oodles.io");
        //edUserName.setText("aashish.kumar@oodles.io");
        //    edUserName.setText("mina@neovix.com");
        //  edUserName.setText("pm13426@gmail.com");
        //   edPassword.setText("Piyush123456789");
        //  edUserName.setText("sahil.kumar@oodlestechnologies.com");
        //   edPassword.setText("sahil123456789");
//        edUserName.setText("sahilkashyap64@gmail.com");
        //     edUserName.setText("streamly1@yopmail.com");
        // edPassword.setText("123456789");
        edPassword.setText("123456789");


        btnSignIn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                login();

            }
        });

        btnRegisterView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (TokenStorage.readBarcodeVisible(LogInActivity.this, ConstantUtility.QRVISIBILITY_KEY_VALUE)) {
                    startActivity(new Intent(LogInActivity.this, QRCodeActivity.class));
                } else {
                  /*  AlertDialog.Builder dialog = new AlertDialog.Builder(LogInActivity.this, R.style.AlertDialogCustom);
                            dialog.setTitle("Register Now");
                            dialog.setMessage(getString(R.string.open_link_browser) + "\n" + "https://streamlytv.com/");

                            // Specifying a listener allows you to take an action before dismissing the dialog.
                            // The dialog is automatically dismissed when a dialog button is clicked.
                            dialog.setPositiveButton(android.R.string.yes, new DialogInterface.OnClickListener() {
                                public void onClick(DialogInterface dialog, int which) {
                                    // Continue with delete operation
                                    dialog.dismiss();


                                }
                            });
                            dialog.show();
                    dialog.setCancelable(true);*/
                    startActivity(new Intent(LogInActivity.this, RegistrationActivity.class));
                }


                /*Toast.makeText(LogInActivity.this, "Please go to "+ ApiConfig.BASE_URL
                        +" for creating new account", Toast.LENGTH_SHORT).show();*/
            }
        });

        btnLoginUsingPasscode.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                startActivity(new Intent(LogInActivity.this, LoginUsingPasscodeActivity.class));
                // finish();
                /*Toast.makeText(LogInActivity.this, "Please go to "+ ApiConfig.BASE_URL
                        +" for creating new account", Toast.LENGTH_SHORT).show();*/
            }
        });


        btnTermsUses.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                TermsOfUses terms = new TermsOfUses(LogInActivity.this);
                terms.show();
            }
        });
        btnHotelLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startActivity(new Intent(LogInActivity.this, HotelQRCodeActivity.class));
                // finish();


            }
        });
        passwordTog.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (edPassword.getTransformationMethod().equals(HideReturnsTransformationMethod.getInstance())) {
                    edPassword.setTransformationMethod(PasswordTransformationMethod.getInstance());
                    passwordTog.setImageResource(R.drawable.ic_baseline_visibility_off_24);
                } else {
                    edPassword.setTransformationMethod(HideReturnsTransformationMethod.getInstance());
                    passwordTog.setImageResource(R.drawable.ic_baseline_visibility_24);
                }
            }
        });
    }


    void toastDialog(String message) {
        ToastDialog t = new ToastDialog(message);
        t.show(getSupportFragmentManager(), "Tag");
    }

    private void login() {
        if (ConstantUtility.isNetworkConnected) {
            if (edUserName.getText().toString().matches("")) {
                CommonUtility.showToast(LogInActivity.this, ConstantUtility.FILL_EMAIL);

            } else if (edUserName.getText().toString().matches(ConstantUtility.EMAIL_REGEX) == false) {
                CommonUtility.showToast(LogInActivity.this, ConstantUtility.WRONG_EMAIL);

            } else if (edPassword.getText().toString().matches("")) {
                CommonUtility.showToast(LogInActivity.this, ConstantUtility.FILL_PASSW);

            } else {
                signInCall();
            }

        } else {
            CommonUtility.showToast(LogInActivity.this, ConstantUtility.CHECK_INTERNET_CONNECTION);
        }
    }


    private void getUserAgentType() {
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<CheckDeviceType> call = apiInterface.getDeviceType(userAgent);
        call.enqueue(new Callback<CheckDeviceType>() {
            @Override
            public void onResponse(Call<CheckDeviceType> call, Response<CheckDeviceType> response) {
                if (response.isSuccessful()) {

                }
            }

            @Override
            public void onFailure(Call<CheckDeviceType> call, Throwable t) {

            }
        });
    }


    private void initViews() {
        edUserName = findViewById(R.id.ed_user_name);
        edPassword = findViewById(R.id.ed_password);
        txtNeedHelp = findViewById(R.id.txt_need_help);
        btnSignIn = findViewById(R.id.btn_sign_in);
        btnHotelLogin = findViewById(R.id.btn_hotel_sign_in);
        btnRegisterView = findViewById(R.id.btn_register_view);
        btnTermsUses = findViewById(R.id.btn_terms_uses);
        btnLoginUsingPasscode = findViewById(R.id.btn_login_using_passcode_uses);
        rememberMeCheck = findViewById(R.id.checkbox_remember);
        passwordTog = findViewById(R.id.password_toggle);
    }

    private void signInCall() {
        dialog.setVisibility(View.VISIBLE);
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<NewLogInModel> call = apiInterface.postLogInCall(userAgent, edUserName.getText().toString(),
                edPassword.getText().toString(), uniqueId);
        call.enqueue(new Callback<NewLogInModel>() {
            @Override
            public void onResponse(Call<NewLogInModel> call, Response<NewLogInModel> response) {
                try {
                    Log.e(TAG, "resCode " + response.code());
                    Log.e(TAG, "resCode " + response.body());

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
                            TokenStorage.savedRoomList(LogInActivity.this, ConstantUtility.ROOM_LIST, json);
                            TokenStorage.savedSharedToken(LogInActivity.this, ConstantUtility.USER_ID, userId.toString());
                            TokenStorage.savedSharedToken(LogInActivity.this, ConstantUtility.AUTH_TOKEN, access_token);
                            TokenStorage.savedSharedTokenPassCode(LogInActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, access_token);
                            TokenStorage.saveSessionId(LogInActivity.this, ConstantUtility.SESSION_ID, sessionid);
                            TokenStorage.savePersonalDeviceID(LogInActivity.this, ConstantUtility.PERSONAL_DEVICE_ID, personalDeviceId);
                            TokenStorage.saveDevicetype(LogInActivity.this, ConstantUtility.DEVICE_TYPE, deviceType);
                            TokenStorage.savePersonalDeviceNamePopup(LogInActivity.this, ConstantUtility.PERSONAL_DEVICE_POPUP, personaDeviceNamePopup);
                            TokenStorage.saveUserRole(LogInActivity.this, ConstantUtility.USER_ROLE, response.body().getUserRole());
                            CommonUtility.showToast(LogInActivity.this, response.body().getMessage());
//                        startActivity(new Intent(LoginUsingPasscodeActivity.this, MainActivity.class));
//                        finish();
                            if (personaDeviceNamePopup) {
                                startActivity(new Intent(LogInActivity.this, PersonalDeviceName.class));
                            } else {
                                startActivity(new Intent(LogInActivity.this, UserRoomActivity.class));
                            }
                            finish();
                            if (!rememberMeCheck.isChecked()) {
                                TokenStorage.savedSharedTokenPassCode(LogInActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, null);
                                ConstantUtility.isRemember = false;
                            } else {
                                ConstantUtility.isRemember = true;
                            }
                        } else if (response.body().getUserRole().equals("user")) {
                            CommonUtility.showToast(LogInActivity.this, response.body().getMessage());
                            TokenStorage.savedSharedToken(LogInActivity.this, ConstantUtility.AUTH_TOKEN, access_token);
                            TokenStorage.savedSharedTokenPassCode(LogInActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, access_token);
                            TokenStorage.saveSessionId(LogInActivity.this, ConstantUtility.SESSION_ID, sessionid);
                            TokenStorage.savePersonalDeviceID(LogInActivity.this, ConstantUtility.PERSONAL_DEVICE_ID, personalDeviceId);
                            TokenStorage.saveDevicetype(LogInActivity.this, ConstantUtility.DEVICE_TYPE, deviceType);
                            TokenStorage.savePersonalDeviceNamePopup(LogInActivity.this, ConstantUtility.PERSONAL_DEVICE_POPUP, personaDeviceNamePopup);
                            TokenStorage.saveUserRole(LogInActivity.this, ConstantUtility.USER_ROLE, response.body().getUserRole());
                            if (personaDeviceNamePopup) {
                                startActivity(new Intent(LogInActivity.this, PersonalDeviceName.class));
                            } else {
                                startActivity(new Intent(LogInActivity.this, MainActivity.class));
                            }
                            finish();
                            if (!rememberMeCheck.isChecked()) {
                                TokenStorage.savedSharedTokenPassCode(LogInActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, null);
                            }
                        }
                    } else if (response.code() == 479) {
                        String s = response.errorBody().string();

                        NewLoginErrorResponse loginErrorResponse = new Gson().fromJson(s,
                                NewLoginErrorResponse.class);
                        if (loginErrorResponse.getLoggedindeviceinfolist() != null) {
                            showDialog(loginErrorResponse);
                        }
                    } else if (response.code() == 401) {
                        Log.i(TAG, "onResponse:401  " + response.isSuccessful());
                        if (!response.isSuccessful()) {
                            toastDialog("Invalid credentials");
                            // CommonUtility.showToast(LogInActivity.this, "Invalid credentials");
                        }
                        /*SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(LogInActivity.this);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();*/
                    } else if (response.code() == 429) {
                        Log.i(TAG, "onResponse:429  " + response.body());
                        if (response.errorBody() != null) {
                            NewLoginErrorResponse logInModel = new Gson().fromJson(response.errorBody().string(), NewLoginErrorResponse.class);
                            if (logInModel.getData() != null && !logInModel.getData().toString().equals("")) {
                                Log.i(TAG, "onResponse:429  " + response.body());
                                Log.i(TAG, "onResponse:429  " + logInModel.getData().toString());
//                                SignOutDeviceDialog signOutDeviceDialog = new SignOutDeviceDialog(LogInActivity.this, logInModel.getData().toString(), edUserName.getText().toString(),
//                                        edPassword.getText().toString(), uniqueId);
//                                signOutDeviceDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
//                                signOutDeviceDialog.show();
                                deviceSignOutCall(logInModel.getData().toString());
                            }
                            if (logInModel.getMessage() != null && !logInModel.getMessage().toString().equals("")) {
                                if (logInModel.getMessage().contains("Your account is in use on")) {
                                    DeviceLimitDialog deviceLimitDialog = new DeviceLimitDialog(LogInActivity.this, logInModel.getMessage().toString());
                                    deviceLimitDialog.show();
                                }
                            }
//
                        }
                    } else if (response.code() == 500) {
                        toastDialog(response.message());
                        // CommonUtility.showToast(LogInActivity.this, response.message());
                    }
                } catch (Exception e) {
                    e.printStackTrace();
                    Log.e(TAG, "Exception: " + e.toString());
                }
                dialog.setVisibility(View.GONE);
                Log.e(TAG, "ResponCode " + response.code() + "");
            }

            @Override
            public void onFailure(Call<NewLogInModel> call, Throwable t) {
                Log.e(TAG, "failure: " + t.getMessage());
                Log.e(TAG, "failRequest: " + call.request());
                toastDialog(ConstantUtility.SERVER_ISSUE);
                // CommonUtility.showToast(LogInActivity.this, ConstantUtility.SERVER_ISSUE);
                dialog.setVisibility(View.GONE);
            }
        });
    }


    private void errorResponse(JSONObject jsonObject) {
        Log.e(TAG, "errorResponse:" + jsonObject.optString("message"));
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Log.e(TAG, "onBackPressed: login activity");
        finish();
    }


    private void deviceSignOutCall(String deviceId) {
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<DeviceSignOutResponse> call = apiInterface.postDeviceLogOutCall(deviceId);
        call.enqueue(new Callback<DeviceSignOutResponse>() {
            @Override
            public void onResponse(Call<DeviceSignOutResponse> call, Response<DeviceSignOutResponse> response) {
                try {
                    if (response.isSuccessful() && response.body() != null && response.code() == 200) {
                        //   CommonUtility.showToast(LogInActivity.this,response.body().getMessage());
                        signInCall();
                    } else if (response.code() == 404) {
                        CommonUtility.showToast(LogInActivity.this, ConstantUtility.SESSION_EXPIRED);
                        LogInActivity.this.startActivity(new Intent(LogInActivity.this, LogInActivity.class));
                        ((Activity) LogInActivity.this).finish();
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(LogInActivity.this, ConstantUtility.SERVER_ISSUE);
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                }
            }


            @Override
            public void onFailure(Call<DeviceSignOutResponse> call, Throwable t) {
                Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
                CommonUtility.showToast(LogInActivity.this, ConstantUtility.SERVER_ISSUE);
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
                Intent intent = new Intent(LogInActivity.this, LogoutDevicesActivity.class);
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


    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (resultCode == Activity.RESULT_OK) {
            login();
        }
    }
}
