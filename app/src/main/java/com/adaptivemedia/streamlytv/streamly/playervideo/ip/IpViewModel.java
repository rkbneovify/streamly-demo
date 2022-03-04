package com.adaptivemedia.streamlytv.streamly.playervideo.ip;

import android.content.Context;

import androidx.lifecycle.LiveData;
import androidx.lifecycle.MutableLiveData;
import androidx.lifecycle.ViewModel;

import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiResponse;

public class IpViewModel extends ViewModel {

    private IpRepo ipRepo;
    private MutableLiveData<ApiResponse> mutableLiveData;


    public IpViewModel() {
        ipRepo=new IpRepo();
    }


    public LiveData<ApiResponse> getLatLong(String IP_Address, Context context) {
        if(mutableLiveData==null){
            mutableLiveData = ipRepo.requestIp(IP_Address, context);
        }
        return mutableLiveData;
    }

}
