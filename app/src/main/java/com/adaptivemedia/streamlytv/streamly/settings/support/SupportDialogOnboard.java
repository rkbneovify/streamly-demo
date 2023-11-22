package com.adaptivemedia.streamlytv.streamly.settings.support;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class SupportDialogOnboard extends Dialog {
    private static final String TAG=SupportDialog.class.getSimpleName();
    private Context context;
    private Button btnOk;
    private RecyclerView rvSupport;
    private List<SupportTextType> list=new ArrayList<>();
    private TextView messageText, emailText, mobileText,mobileText2;
    ProgressBar progressBar;
    LinearLayout support_layout;
    ImageView iv_support_img,ivclose;
    public SupportDialogOnboard(@NonNull Context context) {
        super(context);
        this.context=context;
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_support);
        CommonUtility.checkInternetConnection(context);
        initViews();

//        ivclose.requestFocus();

        if (ConstantUtility.isNetworkConnected) {
            getSupportDetails();
        }else {
            CommonUtility.showToast(context,ConstantUtility.CHECK_INTERNET_CONNECTION);
        }


        ivclose.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });
    }

    private void initViews() {
        ivclose=findViewById(R.id.ivclose);
//        rvSupport=findViewById(R.id.rv_support);
//        progressBar=findViewById(R.id.progress_bar);
//        messageText=findViewById(R.id.message_text);

        messageText = findViewById(R.id.support_msg);
        emailText = findViewById(R.id.support_email);
        mobileText = findViewById(R.id.support_mobile);
        mobileText2 = findViewById(R.id.support_mobile2);
        progressBar = findViewById(R.id.support_progr);
        support_layout = findViewById(R.id.support_lay);
        iv_support_img = findViewById(R.id.iv_support_img);
    }

    private void getSupportDetails() {
        progressBar.setVisibility(View.VISIBLE);
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<SupportModel> call=apiInterface.getSupportNumbers();
        call.enqueue(new Callback<SupportModel>() {
            @Override
            public void onResponse(Call<SupportModel> call, Response<SupportModel> response) {
                try {
                    if (response.isSuccessful() && response.body() != null) {

                        Data datalist= response.body().getData();

                        emailText.setText(datalist.getEmail());
                        mobileText.setText(datalist.getNum1());
//                        mobileText2.setText(datalist.getNum2());
//                        messageText.setText(response.body().getMessage());
                        progressBar.setVisibility(View.GONE);
                        support_layout.setVisibility(View.VISIBLE);
                        iv_support_img.setVisibility(View.VISIBLE);
                        messageText.setVisibility(View.VISIBLE);
                        ivclose.setVisibility(View.VISIBLE);
//                     list.add(new SupportTextType(datalist.getEmail()));
//
//                        messageText.setText(response.body().getMessage());
//
//                        SupportAdpater supportAdpater=new SupportAdpater(context,list);
//                        rvSupport.setLayoutManager(new LinearLayoutManager(context, RecyclerView.VERTICAL,false));
//                        rvSupport.setAdapter(supportAdpater);
//                        btnOk.setVisibility(View.VISIBLE);

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
                Log.e(TAG,"support_numbers "+response.code()+"");
                progressBar.setVisibility(View.GONE);
//                btnOk.setVisibility(View.VISIBLE);
            }

            @Override
            public void onFailure(Call<SupportModel> call, Throwable t) {
                Log.e(TAG, "Throwables  "+t);
                progressBar.setVisibility(View.GONE);
                CommonUtility.showToast(context, t.getMessage());

//                btnOk.setVisibility(View.VISIBLE);
            }
        });
    }
}
