package com.adaptivemedia.streamlytv.streamly.playervideo.drm;

import android.content.Context;
import android.util.Log;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;

import com.google.gson.Gson;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceRepo;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiResponse;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.NetworkKeys;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DrmKeyRepo {
    private final String TAG= GeofenceRepo.class.getSimpleName();
    private Integer defaultImage = -1;

        public ApiInterface endpoints;

        public DrmKeyRepo() {
            endpoints = ApiClient.getClient().create(ApiInterface.class);
        }



        public LiveData<ApiResponse> getDrmKey(Context context) {
            final MutableLiveData<ApiResponse> apiResponse = new MutableLiveData<>();
            String  authToken= TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
            Call<DrmKeyModel> call=endpoints.getDrmKey(NetworkKeys.KEY_BEARER+authToken);
            call.enqueue(new Callback<DrmKeyModel>() {
                @Override
                public void onResponse(Call<DrmKeyModel> call, Response<DrmKeyModel> response) {
                    try{
                        if (response.isSuccessful()) {
                            apiResponse.postValue(new ApiResponse(response.body(),response.code()));
                            Log.e(TAG, "success "+response);
                        }else {
                            Log.e(TAG, "elseResponse "+response);
                            DrmKeyModel drmKeyModel=new Gson().fromJson(response.errorBody().string(),DrmKeyModel.class);
                            apiResponse.postValue(new ApiResponse(drmKeyModel,response.code()));

                        }
                    }catch (Exception ex){
                        ex.printStackTrace();
                        Log.e(TAG, "Exception "+ex);
                    }
                }

                @Override
                public void onFailure(Call<DrmKeyModel> call, Throwable t) {
                    apiResponse.postValue(new ApiResponse(t));
                    Log.e(TAG, "failure "+t);
                }
            });
            return apiResponse;
        }

}