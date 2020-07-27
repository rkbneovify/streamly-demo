package com.streamly.tv.log_in;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.provider.Settings;
import android.util.Log;
import android.view.View;
import android.widget.Toast;
import androidx.databinding.DataBindingUtil;
import androidx.fragment.app.FragmentActivity;
import com.streamly.tv.MainActivity;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.settings.support.SupportDialog;
import com.streamly.tv.utilities.ApiConfig;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import com.streamly.tv.R;
import com.streamly.tv.databinding.ActivityLogInBinding;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LogInActivity extends FragmentActivity {
    private static final String TAG = LogInActivity.class.getSimpleName();
    private ActivityLogInBinding logInBinding;
    private ProgressDialog dialog;
    private  String uniqueId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        CommonUtility.checkInternetConnection(LogInActivity.this);
        logInBinding= DataBindingUtil.setContentView(this, R.layout.activity_log_in);
         uniqueId = Settings.Secure.getString(getContentResolver(), Settings.Secure.ANDROID_ID);


        dialog= CommonUtility.custom_loader(LogInActivity.this);
        TokenStorage.clearSharedToken(LogInActivity.this);

        logInBinding.txtNeedHelp.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                SupportDialog supportDialog=new SupportDialog(LogInActivity.this);
                supportDialog.show();

            }
        });

        logInBinding.edUserName.setText("arun@gmail.com");
        logInBinding.edPassword.setText("123456789");
        logInBinding.btnSignIn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                if (ConstantUtility.isNetworkConnected){
                    if (logInBinding.edUserName.getText().toString().matches("")){
                        CommonUtility.showToast(LogInActivity.this,ConstantUtility.FILL_EMAIL);

                    }else if (logInBinding.edUserName.getText().toString().matches(ConstantUtility.EMAIL_REGEX)==false){
                        CommonUtility.showToast(LogInActivity.this,ConstantUtility.WRONG_EMAIL);

                    } else if (logInBinding.edPassword.getText().toString().matches("")){
                        CommonUtility.showToast(LogInActivity.this, ConstantUtility.FILL_PASSW);

                    }else {
                        signInCall();
                    }

                }else {
                    CommonUtility.showToast(LogInActivity.this,ConstantUtility.CHECK_INTERNET_CONNECTION);
                }

            }
        });

        logInBinding.btnRegisterView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                Toast.makeText(LogInActivity.this, "Please go to "+ ApiConfig.WEB_URL
                        +" for creating new account", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void signInCall() {
        dialog.show();
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<LogInModel> call=apiInterface.postLogInCall(logInBinding.edUserName.getText().toString(),
                                                        logInBinding.edPassword.getText().toString(),uniqueId);
        call.enqueue(new Callback<LogInModel>() {
            @Override
            public void onResponse(Call<LogInModel> call, Response<LogInModel> response) {
                try {
                if (response.isSuccessful() && response.body()!=null){
                    String access_token=response.body().getAccessToken();
                    TokenStorage.savedSharedToken(LogInActivity.this, ConstantUtility.AUTH_TOKEN,access_token);
                    CommonUtility.showToast(LogInActivity.this,response.body().getMessage());
                    startActivity(new Intent(LogInActivity.this, MainActivity.class));
                    finish();

                    logInBinding.edUserName.setText("");
                    logInBinding.edPassword.setText("");

                }else if (response.code()==401){
                    CommonUtility.showToast(LogInActivity.this,response.body().getMessage());
                } else if (response.code()==429){
//                    CommonUtility.showToast(LogInActivity.this,response.body().getMessage());
                    CommonUtility.showToast(LogInActivity.this,ConstantUtility.MAX_USER_LOGIN);
                }
                else if (response.code()==500){
                    CommonUtility.showToast(LogInActivity.this,ConstantUtility.SERVER_ISSUE);
                }

              }catch (Exception e){
                    e.printStackTrace();
                    Log.e(TAG,"Exception: "+e.toString());
                } dialog.dismiss();
                Log.e(TAG,"ResponCode "+response.code()+"");
            }
            @Override
            public void onFailure(Call<LogInModel> call, Throwable t) {
                Log.e(TAG,"failure: "+call+" "+t.getLocalizedMessage());
                CommonUtility.showToast(LogInActivity.this,ConstantUtility.SERVER_ISSUE);
                dialog.dismiss();
            }
        });
    }
}
