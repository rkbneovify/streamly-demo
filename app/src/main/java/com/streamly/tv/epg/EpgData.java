package com.streamly.tv.epg;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.nfc.Tag;
import android.util.Log;

import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.settings.support.SupportModel;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import com.streamly.tv.utilities.NetworkKeys;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class EpgData {
    Context context;
    private static final String TAG=EpgData.class.getSimpleName();

    public EpgData(Context context) {
        this.context = context;
    }

    public static void getChannellist(final Context context){
        String auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
        Call<ChannellistModel> call=apiInterface.getChannelList(NetworkKeys.KEY_BEARER +auth_token,NetworkKeys.KEY_APP_JSON);
        call.enqueue(new Callback<ChannellistModel>() {
            @Override
            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {

                try {
                    if (response.isSuccessful() && response.body() != null) {

                        List<Datum> list= response.body().getData();
                        Map<String,String> hashMap=new HashMap<>();

                        for (int i=0; i<list.size();i++){
                            hashMap.put("call_sign",list.get(i).getCallSign());
                        }


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

            }

            @Override
            public void onFailure(Call<ChannellistModel> call, Throwable t) {

            }
        });
    }

}

// Feel free to change configuration values like this:
// override val DISPLAY_CURRENT_TIME_INDICATOR = false
// override val DISPLAY_SHOW_PROGRESS = false


    /*SimpleChannel("npo-2", SpannedString("CN"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
        SimpleChannel("bbc-news", SpannedString("ESPN"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
        SimpleChannel("zdf", SpannedString("CNN-intl"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
        SimpleChannel("jednotka", SpannedString("BBC earth"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
        SimpleChannel("tv-nova", SpannedString("Discovery"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
        SimpleChannel("tv-5-monde", SpannedString("MTV"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
        SimpleChannel("orf-2", SpannedString("Sports TV"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
        SimpleChannel("tvp-1", SpannedString("BBC"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png")*/

