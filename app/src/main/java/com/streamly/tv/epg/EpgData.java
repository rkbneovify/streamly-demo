package com.streamly.tv.epg;

import android.content.Context;

import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.settings.support.SupportModel;
import com.streamly.tv.utilities.ConstantUtility;
import com.streamly.tv.utilities.NetworkKeys;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class EpgData {
    Context context;

    public EpgData(Context context) {
        this.context = context;
    }

    public static void getChannellist(Context context){
        String auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<SupportModel> call=apiInterface.getSupportNumbers(NetworkKeys.KEY_BEARER +auth_token,NetworkKeys.KEY_APP_JSON);
        call.enqueue(new Callback<SupportModel>() {
            @Override
            public void onResponse(Call<SupportModel> call, Response<SupportModel> response) {


            }

            @Override
            public void onFailure(Call<SupportModel> call, Throwable t) {

            }
        });

    }

}
