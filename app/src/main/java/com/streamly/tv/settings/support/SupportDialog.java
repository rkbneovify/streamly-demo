package com.streamly.tv.settings.support;

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
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.streamly.tv.R;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import com.streamly.tv.utilities.NetworkKeys;
import java.util.ArrayList;
import java.util.List;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;


public class SupportDialog extends Dialog {
    private static final String TAG=SupportDialog.class.getSimpleName();
    private Context context;
    private Button btnOk;
    private String auth_token;
    private ProgressDialog dialog;
    private RecyclerView rvSupport;
    private List<SupportTextType> list=new ArrayList<>();

    public SupportDialog(@NonNull Context context) {
        super(context);
        this.context=context;
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_support);
        CommonUtility.checkInternetConnection(context);
        initViews();

        auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
        if (ConstantUtility.isNetworkConnected) {
            getSupportDetails();
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

    private void initViews() {
        btnOk=findViewById(R.id.btn_ok);
        rvSupport=findViewById(R.id.rv_support);
        dialog= CommonUtility.custom_loader(context);
    }

    private void getSupportDetails() {

        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<SupportModel> call=apiInterface.getSupportNumbers(NetworkKeys.KEY_BEARER +auth_token,NetworkKeys.KEY_APP_JSON);
        call.enqueue(new Callback<SupportModel>() {
            @Override
            public void onResponse(Call<SupportModel> call, Response<SupportModel> response) {
                try {
                    if (response.isSuccessful() && response.body() != null) {

                        List<String> datalist= response.body().getData();
                        for (int i=0;i<datalist.size();i++){
                            list.add(new SupportTextType(datalist.get(i)));
                        }
                        SupportAdpater supportAdpater=new SupportAdpater(context,list);
                        rvSupport.setLayoutManager(new LinearLayoutManager(context, RecyclerView.VERTICAL,false));
                        rvSupport.setAdapter(supportAdpater);

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
                Log.e(TAG,"support_numbers "+response.code()+"");
                dialog.dismiss();
            }

            @Override
            public void onFailure(Call<SupportModel> call, Throwable t) {

            }
        });
    }
}
