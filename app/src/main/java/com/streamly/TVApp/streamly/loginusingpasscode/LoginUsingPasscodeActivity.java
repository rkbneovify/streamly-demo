package com.streamly.TVApp.streamly.loginusingpasscode;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.FragmentActivity;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;

import com.google.gson.Gson;
import com.streamly.TVApp.streamly.MainActivity;
import com.streamly.TVApp.streamly.R;
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.log_in.LogInActivity;
import com.streamly.TVApp.streamly.log_in.LogInModel;
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient;
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface;
import com.streamly.TVApp.streamly.utilities.CommonUtility;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

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
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<LogInModel> call=apiInterface.postLoginUsingPasscode(et_passcode.getText().toString(),uniqueId);
        call.enqueue(new Callback<LogInModel>() {
            @Override
            public void onResponse(Call<LogInModel> call, Response<LogInModel> response) {
                try {
                    Log.e(TAG, "resCode "+response.code());
                    if (response.isSuccessful() && response.body()!=null){
                        String access_token=response.body().getAccessToken();
                        Long userId=response.body().getUser().getId();

                        TokenStorage.savedSharedToken(LoginUsingPasscodeActivity.this, ConstantUtility.USER_ID,userId.toString());
                        TokenStorage.savedSharedToken(LoginUsingPasscodeActivity.this, ConstantUtility.AUTH_TOKEN,access_token);
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,response.body().getMessage());
                        startActivity(new Intent(LoginUsingPasscodeActivity.this, MainActivity.class));
                        finish();

                        et_passcode.setText("");


                    }else if (response.code()==401){
                        LogInModel logInModel=new Gson().fromJson(response.errorBody().string(),LogInModel.class);
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,logInModel.getMessage());
                    } else if (response.code()==429){
//                    CommonUtility.showToast(LogInActivity.this,response.body().getMessage());
                        CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.MAX_USER_LOGIN);
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
            public void onFailure(Call<LogInModel> call, Throwable t) {
                Log.e(TAG,"failure: "+t.getMessage());
                Log.e(TAG,"failRequest: "+call.request());
                CommonUtility.showToast(LoginUsingPasscodeActivity.this,ConstantUtility.SERVER_ISSUE);
                dialog.dismiss();
            }
        });
    }
}