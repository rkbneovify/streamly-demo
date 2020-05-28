package com.streamly.tv.channel_page;

import android.app.Activity;
import android.app.ProgressDialog;
import android.os.Bundle;
import androidx.databinding.DataBindingUtil;
import com.streamly.tv.R;
import com.streamly.tv.databinding.ActivityChannelBinding;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;

public class ChannelActivity extends Activity {
    private static final String TAG = ChannelActivity.class.getSimpleName();
    ActivityChannelBinding channelBinding;
    String auth_token;
    private ProgressDialog dialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        channelBinding= DataBindingUtil.setContentView(this,R.layout.activity_channel);
        dialog=CommonUtility.custom_loader(ChannelActivity.this);

        auth_token = TokenStorage.readSharedToken(ChannelActivity.this, ConstantUtility.AUTH_TOKEN, null);


    }

    /*private void logOutCall() {
        dialog.show();
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<LogInModel> call=apiInterface.postLogOutCall("Bearer "+auth_token,"application/json");
        call.enqueue(new Callback<LogInModel>() {
            @Override
            public void onResponse(Call<LogInModel> call, Response<LogInModel> response) {
                try{

                if (response.isSuccessful() && response.body()!=null){
                    CommonUtility.showToast(ChannelActivity.this,response.body().getMessage());
                    TokenStorage.clearSharedToken(ChannelActivity.this);
                    startActivity(new Intent(ChannelActivity.this, LogInActivity.class));
                    finish();
                }else if (response.code()==401){
                    CommonUtility.showToast(ChannelActivity.this,response.body().getMessage());
                }
                else if (response.code()==500){
                    CommonUtility.showToast(ChannelActivity.this,ConstantUtility.SERVER_ISSUE);
                }

              }catch (Exception ex){
                    ex.printStackTrace();
                    Log.e(TAG, "Exception "+ex);
                }
                dialog.dismiss();
            }

            @Override
            public void onFailure(Call<LogInModel> call, Throwable t) {
                Log.e(TAG,"failure: "+call+" "+t.getLocalizedMessage());
                CommonUtility.showToast(ChannelActivity.this,ConstantUtility.SERVER_ISSUE);
                dialog.dismiss();
            }
        });
    }*/
}
