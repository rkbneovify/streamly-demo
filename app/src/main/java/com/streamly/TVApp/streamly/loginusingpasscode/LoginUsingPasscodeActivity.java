package com.streamly.TVApp.streamly.loginusingpasscode;

import androidx.fragment.app.FragmentActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interfaces.JSONObjectRequestListener;
import com.google.gson.Gson;
import com.streamly.TVApp.streamly.MainActivity;
import com.streamly.TVApp.streamly.R;
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.log_in.LogInModel;
import com.streamly.TVApp.streamly.new_log_in.NewLogInModel;
import com.streamly.TVApp.streamly.new_log_in.NewLoginErrorResponse;
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient;
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface;
import com.streamly.TVApp.streamly.settings.device_signout.SignOutDeviceDialog;
import com.streamly.TVApp.streamly.user_room.UserRoomActivity;
import com.streamly.TVApp.streamly.utilities.CommonUtility;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;

import org.json.JSONObject;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

import static com.streamly.TVApp.streamly.utilities.ApiConfig.BASE_URL_TESTING;

public class LoginUsingPasscodeActivity extends FragmentActivity {
    private static final String TAG = LoginUsingPasscodeActivity.class.getSimpleName();
EditText et_passcode;
Button btn_sign_in_using_passcode;
    private ProgressDialog dialog;
    private  String uniqueId;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login_using_passcode);
        et_passcode=findViewById(R.id.et_passcode);
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


    private void signInCall() {
        dialog.show();
        ApiInterface apiInterface= ApiClient.getClientTesting().create(ApiInterface.class);
        Call<NewLogInModel> call=apiInterface.postLoginUsingPasscode(et_passcode.getText().toString(),uniqueId);
        call.enqueue(new Callback<NewLogInModel>() {
            @Override
            public void onResponse(Call<NewLogInModel> call, Response<NewLogInModel> response) {
                try {
                    Log.e(TAG, "resCode "+response.code());
                    Log.e(TAG, "resCode "+response.code());
                    if (response.isSuccessful() && response.body()!=null && response.code()!=429){
                        String access_token=response.body().getAccessToken();
                        Long userId=response.body().getUser().getId();
                        Log.i(TAG, "onResponse: LoginUsingPasscode 1 "+access_token);
                        Log.i(TAG, "onResponse: LoginUsingPasscode 2 "+userId);
//                        ArrayList<Room> set = new ArrayList<>();
//                        set.addAll(response.body().getRooms());
                        Gson gson = new Gson();
                        String json = gson.toJson(response.body().getRooms());

                        TokenStorage.savedRoomList(LoginUsingPasscodeActivity.this, ConstantUtility.ROOM_LIST,json);
                        TokenStorage.savedSharedToken(LoginUsingPasscodeActivity.this, ConstantUtility.USER_ID,userId.toString());
                        TokenStorage.savedSharedTokenPassCode(LoginUsingPasscodeActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE,access_token);
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,response.body().getMessage());
//                        startActivity(new Intent(LoginUsingPasscodeActivity.this, MainActivity.class));
//                        finish();
                        startActivity(new Intent(LoginUsingPasscodeActivity.this, UserRoomActivity.class));
                        finish();

                        et_passcode.setText("");


                    }
                    else if (response.code()==401){
                        LogInModel logInModel=new Gson().fromJson(response.errorBody().string(),LogInModel.class);
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,logInModel.getMessage());
                    }
                    else if (response.code()==429){
//                    CommonUtility.showToast(LogInActivity.this,response.body().getMessage());
//                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.MAX_USER_LOGIN);

                        NewLoginErrorResponse logInModel=new Gson().fromJson(response.errorBody().string(),NewLoginErrorResponse.class);
                        if(logInModel.getData() !=null && logInModel.getData().toString()!="")
                        {

                            Log.i(TAG, "onResponse:429  "+response.body());
                            Log.i(TAG, "onResponse:429  "+logInModel.getData().toString());
                            SignOutDeviceDialog signOutDeviceDialog = new SignOutDeviceDialog(LoginUsingPasscodeActivity.this,logInModel.getData().toString());
                            signOutDeviceDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                            signOutDeviceDialog.show();
                        }


                    }
                    else if (response.code()==500){
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.SERVER_ISSUE);
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
}