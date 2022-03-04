package com.adaptivemedia.streamlytv.streamly.termsofuses;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;
import androidx.annotation.NonNull;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.settings.signout.SignOutDialog;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class TermsOfUses extends Dialog {
    private final String TAG= SignOutDialog.class.getSimpleName();
    private Context context;
    private ProgressBar progressBar;
    private Button btnAccept;
    private TextView tvTerms;
    private String auth_token;


    public TermsOfUses(@NonNull Context context) {
        super(context);
        this.context=context;
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_terms_of_uses);
        CommonUtility.checkInternetConnection(context);
        initViews();

        if (ConstantUtility.isNetworkConnected) {
            getTermsOfUses();
        }else {
            CommonUtility.showToast(context,ConstantUtility.CHECK_INTERNET_CONNECTION);
        }


        btnAccept.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });

    }

    private void getTermsOfUses() {
        progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<TermsModel> call=apiInterface.getTermofuse();

        call.enqueue(new Callback<TermsModel>() {
            @Override
            public void onResponse(Call<TermsModel> call, Response<TermsModel> response) {
                try {
                    if (response.isSuccessful() && response.body() != null) {

                        String termsData= response.body().getData();

                        tvTerms.setText(termsData);

                    } else if (response.code() == 401) {
                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(context);
                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                        sessionExpiredDialog.setCancelable(false);
                        sessionExpiredDialog.show();
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
                progressBar.setVisibility(View.INVISIBLE);
            }

            @Override
            public void onFailure(Call<TermsModel> call, Throwable t) {
                Log.e(TAG,"failure " +t);
                progressBar.setVisibility(View.INVISIBLE);

            }
        });
    }

    private void initViews() {
        btnAccept=findViewById(R.id.btn_accept);
        tvTerms=findViewById(R.id.tv_terms);
        progressBar=findViewById(R.id.progress_bar);
    }
}
