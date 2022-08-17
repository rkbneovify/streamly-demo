package com.adaptivemedia.streamlytv.streamly.settings;

import static com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient.retrofitClient;

import android.app.AlertDialog;
import android.app.Dialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.Handler;
import android.preference.PreferenceManager;
import android.provider.Settings;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import androidx.fragment.app.FragmentActivity;
import androidx.lifecycle.Observer;
import androidx.lifecycle.ViewModelProvider;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.streamly.dialogs.CommonDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.DialogClickListeners;
import com.adaptivemedia.streamlytv.streamly.dialogs.NoPlanDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.SelectTimeZoneDialog;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.logoutdevices.LogoutDevicesActivity;
import com.adaptivemedia.streamlytv.streamly.model.GetIp;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLoginErrorResponse;
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.LoginDevicesModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.PlayerActivity;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpDataModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpViewModel;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiResponse;
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.DeviceSignOutResponse;
import com.adaptivemedia.streamlytv.streamly.settings.privacy.PrivacyDialog;
import com.adaptivemedia.streamlytv.streamly.settings.profile.profilemodel.ProfileModel;
import com.adaptivemedia.streamlytv.streamly.settings.registerdevice.RegisterDeviceDialog;
import com.adaptivemedia.streamlytv.streamly.settings.signout.SignOutDialog;
import com.adaptivemedia.streamlytv.streamly.settings.support.SupportActivity;
import com.adaptivemedia.streamlytv.streamly.settings.support.SupportDialog;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import com.vlstr.blurdialog.BlurDialog;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class SettingsActivity extends FragmentActivity implements View.OnClickListener {
    private static final String TAG = SettingsActivity.class.getSimpleName();
    private TextView textChannels;
    private LinearLayout account, registerDevice, support, privacy, signOut, timezone;

    //#neovifyissueresolved28sep
    private Dialog pDialog;
    private Button btnOk, deleteUser;
    private TextView userName,version, userEmail, buildDate, homeTimezone, currentTimezone;
    private ProgressBar progressBar;
    private BlurDialog blurView;
    private String auth_token;
    private Long id;
    private String userAgent;
    private String finalOffset;
    String authToken, uniqueId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        CommonUtility.checkInternetConnection(this);
        setContentView(R.layout.activity_settings);
        initViews();
        uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);
        authToken = TokenStorage.readSharedToken(SettingsActivity.this, ConstantUtility.AUTH_TOKEN, null);
        userAgent = System.getProperty("http.agent");
        initListeners();
        getIpAddress();

//        account.clearFocus();
//        account.requestFocus();
    }

    private void getIpAddress() {
        ApiInterface apiInterface = ApiClient.getIPAdd().create(ApiInterface.class);
        Call<GetIp> call = apiInterface.getDeviceIpAddress();
        call.enqueue(new Callback<GetIp>() {
            @Override
            public void onResponse(Call<GetIp> call, Response<GetIp> response) {
                String  ip = response.body().getIpAddress();
                Log.e("GetIp","ip...");
                getHoursOffset(ip);
            }

            @Override
            public void onFailure(Call<GetIp> call, Throwable t) {
                //add if fail (home zip)
            }
        });
    }

    private void getHoursOffset(String ip) {
        IpViewModel ipViewModel = new ViewModelProvider(this).get(IpViewModel.class);
        ipViewModel.getLatLong(ip, SettingsActivity.this).observe(this, new Observer<ApiResponse>() {
            @Override
            public void onChanged(ApiResponse apiResponse) {
                IpDataModel ipModel = (IpDataModel) apiResponse.getApiResponse();
                switch (apiResponse.getResponseCode()) {
                    case 200:
                        if (ipModel.getError()) {
                            CommonUtility.showToast(SettingsActivity.this, ipModel.getReason());
                            Log.e(TAG, ipModel.getReason());
                            finish();
                        } else {
                            String hourOffset = ipModel.getUtc_offset();
                            char two = ':';
                            Log.e("HoursOffset","Value : "+hourOffset.length());
                            finalOffset = hourOffset.substring(0,3)+two+hourOffset.substring(3);
                        }
                        break;
                    case 401:
                        CommonUtility.showToast(SettingsActivity.this, ipModel.getReason());
                        break;
                    case 403:
                        CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 404:
                        CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 429:
                        CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                    case 500:
                        CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);
                        break;
                }

            }
        });
    }


    private void initViews() {
        textChannels = findViewById(R.id.text_channels);
        account = findViewById(R.id.account);
        registerDevice = findViewById(R.id.register_device);
        support = findViewById(R.id.support);
        privacy = findViewById(R.id.privacy);
        signOut = findViewById(R.id.sign_out);
        timezone = findViewById(R.id.time_zone);

        SharedPreferences preferences = PreferenceManager.getDefaultSharedPreferences(this);
        boolean savedVisibleKey = TokenStorage.readBarcodeVisible(this, ConstantUtility.QRVISIBILITY_KEY_VALUE);
        if (savedVisibleKey == true) {
            registerDevice.setVisibility(View.VISIBLE);
            privacy.setVisibility(View.VISIBLE);
        } else {
            registerDevice.setVisibility(View.VISIBLE);
            privacy.setVisibility(View.VISIBLE);
        }

    }

    private void initListeners() {
        account.setOnClickListener(this);
        registerDevice.setOnClickListener(this);
        support.setOnClickListener(this);
        privacy.setOnClickListener(this);
        signOut.setOnClickListener(this);
        timezone.setOnClickListener(this);
        textChannels.setOnClickListener(this);
    }

    @Override
    public void onClick(View view) {
        switch (view.getId()) {
            case R.id.account:
                viewFocusAccount(view);
                break;

            case R.id.register_device:
                Intent intent = new Intent(SettingsActivity.this, LogoutDevicesActivity.class);
                intent.putExtra("isLogin", false);
                startActivity(intent);
                break;

            case R.id.support:
                /*SupportDialog supportDialog = new SupportDialog(this);
                supportDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                supportDialog.show();*/
                startActivity(new Intent(SettingsActivity.this, SupportActivity.class));
                break;

            case R.id.privacy:
                PrivacyDialog privacyDialog = new PrivacyDialog(this);
                privacyDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                privacyDialog.show();
                break;

            case R.id.sign_out:
                SignOutDialog signOutDialog = new SignOutDialog(SettingsActivity.this);
                signOutDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                signOutDialog.show();
                break;

            case R.id.time_zone:
                startActivity(new Intent(SettingsActivity.this, MainActivity.class));
                finish();
                /*SelectTimeZoneDialog selectTimeZoneDialog = new SelectTimeZoneDialog(SettingsActivity.this,"Please select your timezone",uniqueId,  "",  "", authToken, new SelectTimeZoneDialog.DialogCallBack(){

                    @Override
                    public void onCurrentPress() {
                        TokenStorage.saveCurrentTimeZoneOffset(SettingsActivity.this,ConstantUtility.HOMETIMEZONE,finalOffset);
                        //Calling current
                    }

                    @Override
                    public void onHomePress() {
                        //Calling home
                    }
                });
                selectTimeZoneDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                selectTimeZoneDialog.show();*/
                break;

            case R.id.text_channels:
                SettingsActivity.this.onBackPressed();
                break;
            //handle multiple view click events
        }
    }

    private void viewFocusAccount(View view) {
        try {
            // Check if no view has focus:
//            view = this.getCurrentFocus();
//            if (view != null) {
//                InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
//                imm.hideSoftInputFromWindow(view.getWindowToken(), 0);
//            }
            DisplayMetrics metrics = getResources().getDisplayMetrics();
            int screenWidth = (int) (metrics.widthPixels * 0.90);
            pDialog = new Dialog(SettingsActivity.this);
            pDialog.getWindow().setSoftInputMode(WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
            pDialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
//                    pDialog.getWindow().addFlags(WindowManager.LayoutParams.FLAG_BLUR_BEHIND);
            pDialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
            pDialog.setContentView(R.layout.dialog_profile_new);
            pDialog.getWindow().setLayout(screenWidth, RecyclerView.LayoutParams.WRAP_CONTENT);
            blurView = pDialog.findViewById(R.id.blurView);
            blurView.create(getWindow().getDecorView(), 20);
//                    blurView.setBackgroundColor(getResources().getColor(R.color.shaded_color));
//            blurView.setBackground(getResources().getDrawable(R.drawable.layout_focus));
            blurView.setBackground(getResources().getDrawable(R.drawable.layout_focus2));
            blurView.show();

            btnOk = pDialog.findViewById(R.id.btn_ok);
            deleteUser = pDialog.findViewById(R.id.btnDeleteUser);
            userName = pDialog.findViewById(R.id.text_name);
            userEmail = pDialog.findViewById(R.id.text_email);
            buildDate = pDialog.findViewById(R.id.text_updated_date);
            homeTimezone = pDialog.findViewById(R.id.text_home_timezone);
            currentTimezone = pDialog.findViewById(R.id.text_current_timezone);
            progressBar = pDialog.findViewById(R.id.progress_bar);
            version = pDialog.findViewById(R.id.text_version);


            CommonUtility.checkInternetConnection(SettingsActivity.this);
            auth_token = TokenStorage.readSharedToken(SettingsActivity.this, ConstantUtility.AUTH_TOKEN, null);
            if (ConstantUtility.isNetworkConnected) {
                getUserProfile();
            } else {
                CommonUtility.showToast(SettingsActivity.this, ConstantUtility.CHECK_INTERNET_CONNECTION);
            }
            btnOk.setOnClickListener(v -> pDialog.dismiss());
            deleteUser.setOnClickListener(v -> showDialog());
            pDialog.setCancelable(true);
            pDialog.show();

        } catch (Exception e) {
            e.printStackTrace();
        }
    }


    private void showDialog() {
        CommonDialog c = new CommonDialog("Are you sure you want to delete this account.", new DialogClickListeners() {
            @Override
            public void negative() {
            }

            @Override
            public void positive() {
                deleteUserAccount();
            }
        });

        c.show(getSupportFragmentManager(), "ffnadjg");
/*
        AlertDialog.Builder dialog = new AlertDialog.Builder(SettingsActivity.this, R.style.AlertDialogCustom);
*//*
        dialog.setTitle("Register Now");*//*
        dialog.setMessage("Are you sure you want to delete this account.");

        // Specifying a listener allows you to take an action before dismissing the dialog.
        // The dialog is automatically dismissed when a dialog button is clicked.
        dialog.setPositiveButton(getString(R.string.yes), new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int which) {
                deleteUserAccount();
                dialog.dismiss();


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

    private void deleteUserAccount() {
        progressBar.setVisibility(View.VISIBLE);
        //  Response<DeviceSignOutResponse> response = retrofitClient().deleteUserApi(id);
        Call<DeviceSignOutResponse> call = retrofitClient().deleteUserApi(userAgent, "Bearer " + auth_token, id);
        call.enqueue(new Callback<DeviceSignOutResponse>() {
            @Override
            public void onResponse(Call<DeviceSignOutResponse> call, Response<DeviceSignOutResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    progressBar.setVisibility(View.GONE);
                    DeviceSignOutResponse data = response.body();
                    CommonUtility.showToast(SettingsActivity.this, data.getMessage());
                    Intent intent = new Intent(SettingsActivity.this, LogInActivity.class);
                    intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                    startActivity(intent);
                } else {
                    progressBar.setVisibility(View.GONE);
                    CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);

                }
            }

            @Override
            public void onFailure(Call<DeviceSignOutResponse> call, Throwable t) {
                progressBar.setVisibility(View.GONE);
                CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);
            }
        });

    }

    //#neovifyissueresolved28sep
    private void getUserProfile() {
        String home = "Your Home Timezone GMT"+TokenStorage.readHomeTimeZoneOffset(getApplicationContext(),ConstantUtility.HOMETIMEZONE,null);
        String current = "Your Current Timezone GMT"+TokenStorage.readCurrentTimeZoneOffset(getApplicationContext(),ConstantUtility.CURRENTTIMEZONE,null);
        progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ProfileModel> call = apiInterface.getUserProfile("Bearer " + auth_token);
        call.enqueue(new Callback<ProfileModel>() {
            @Override
            public void onResponse(Call<ProfileModel> call, Response<ProfileModel> response) {
                try {

                    if (response.isSuccessful() && response.body() != null && response.code() != 401) {
                        userName.setText(response.body().getData().getName());
                        id = response.body().getData().getId();
                        userEmail.setText(response.body().getData().getEmail());
                        buildDate.setText(response.body().getData().getUpdatedAt());
                        homeTimezone.setText(home);
                        currentTimezone.setText(current);
                        version.setText("1.01");

                    } else if (response.code() == 401) {
                        if (response.errorBody() != null) {
                            SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(SettingsActivity.this);
                            sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            sessionExpiredDialog.setCancelable(false);
                            sessionExpiredDialog.show();


                        }

                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(SettingsActivity.this);
                        CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SESSION_EXPIRED);
                        SettingsActivity.this.startActivity(new Intent(SettingsActivity.this, LogInActivity.class));
                        SettingsActivity.this.finish();
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                }
                progressBar.setVisibility(View.INVISIBLE);
            }

            @Override
            public void onFailure(Call<ProfileModel> call, Throwable t) {
                Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
                CommonUtility.showToast(SettingsActivity.this, ConstantUtility.SERVER_ISSUE);
                progressBar.setVisibility(View.INVISIBLE);
            }
        });
    }

}
