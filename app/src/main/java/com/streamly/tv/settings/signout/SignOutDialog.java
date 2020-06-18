package com.streamly.tv.settings.signout;

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
import com.streamly.tv.R;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.log_in.LogInModel;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SignOutDialog extends Dialog {
    private final String TAG=SignOutDialog.class.getSimpleName();
    private Context context;
    private Button btnNo,btnYes;
    private ProgressDialog dialog;
    private String auth_token;

    public SignOutDialog(@NonNull Context context) {
        super(context);
        this.context=context;
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_signout);
        CommonUtility.checkInternetConnection(context);
        dialog= CommonUtility.custom_loader(context);
        auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
        initViews();



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
                    sigOutCall();
                    cancel();
                }else {
                    CommonUtility.showToast(context,ConstantUtility.CHECK_INTERNET_CONNECTION);
                }

            }
        });



    }



    private void sigOutCall() {
        dialog.show();
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<LogInModel> call=apiInterface.postLogOutCall("Bearer "+auth_token,"application/json");
        call.enqueue(new Callback<LogInModel>() {
            @Override
            public void onResponse(Call<LogInModel> call, Response<LogInModel> response) {
                try{

                    if (response.isSuccessful() && response.body()!=null){
                        CommonUtility.showToast(context,response.body().getMessage());
                        TokenStorage.clearSharedToken(context);
                        context.startActivity(new Intent(context, LogInActivity.class));
                        ((Activity)context).finish();
                    }else if (response.code()==401){
                        CommonUtility.showToast(context,response.body().getMessage());
                    }
                    else if (response.code()==404){
                        TokenStorage.clearSharedToken(context);
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
            public void onFailure(Call<LogInModel> call, Throwable t) {
                Log.e(TAG,"failure: "+call+" "+t.getLocalizedMessage());
                CommonUtility.showToast(context,ConstantUtility.SERVER_ISSUE);
                dialog.dismiss();
            }
        });
    }


    private void initViews() {
        btnNo=findViewById(R.id.btn_no);
        btnYes=findViewById(R.id.btn_yes);
    }
}
