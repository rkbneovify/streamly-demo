package com.streamly.tv.settings.privacy;

import android.app.Activity;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import androidx.annotation.NonNull;
import com.streamly.tv.R;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import com.streamly.tv.utilities.NetworkKeys;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class PrivacyDialog extends Dialog {
    private static final String TAG= PrivacyDialog.class.getSimpleName();
    private Context context;
    private Button btnOk;
    private TextView tvPrivacy;
    private ProgressDialog dialog;
    private String auth_token;

    public PrivacyDialog(@NonNull Context context) {
        super(context);
        this.context=context;
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_privacy);
        CommonUtility.checkInternetConnection(context);
        initViews();
        auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);

        if (ConstantUtility.isNetworkConnected) {
            getPrivacyPolicy();
        }else {
            CommonUtility.showToast(context,ConstantUtility.CHECK_INTERNET_CONNECTION);
        }



        btnOk.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });


    }

    private void getPrivacyPolicy() {
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<PrivacyPolicyModel> call=apiInterface.getPoliciesText(NetworkKeys.KEY_BEARER +auth_token,NetworkKeys.KEY_APP_JSON);
        call.enqueue(new Callback<PrivacyPolicyModel>() {
            @Override
            public void onResponse(Call<PrivacyPolicyModel> call, Response<PrivacyPolicyModel> response) {
                try {
                    if (response.isSuccessful() && response.body() != null) {

                        String policyData= response.body().getData();

                        tvPrivacy.setText(policyData);

                    } else if (response.code() == 401) {
                        CommonUtility.showToast(context, response.body().getMessage());
                        TokenStorage.clearSharedToken(context);
                        context.startActivity(new Intent(context, LogInActivity.class));
                        ((Activity) context).finish();
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
                dialog.dismiss();
            }

            @Override
            public void onFailure(Call<PrivacyPolicyModel> call, Throwable t) {
                Log.e(TAG,"failure " +t);

            }
        });

    }

    private void initViews() {
        btnOk=findViewById(R.id.btn_ok);
        tvPrivacy=findViewById(R.id.tv_privacy);
        dialog= CommonUtility.custom_loader(context);
    }
}
