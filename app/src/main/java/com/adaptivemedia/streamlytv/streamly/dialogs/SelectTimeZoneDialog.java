package com.adaptivemedia.streamlytv.streamly.dialogs;

import android.app.Dialog;
import android.content.Context;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ProgressBar;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.playervideo.zipcode.OutstationLocationModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.zipcode.ZipCodeDialog;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.NetworkKeys;

import java.util.HashMap;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SelectTimeZoneDialog extends Dialog {
    private Context context;
    private Button btnContinue, backBtn;
    private ProgressBar progressBar;
    private TextView poptext;
    String popMessage,uniqueId,dbLat,dblon,authToken;
    DialogCallBack dialogCallBack;


    public SelectTimeZoneDialog(@NonNull Context context, String popMessage, String uniqueId,
                                String dbLat, String dblon, String authToken, DialogCallBack dialogCallBack){
        super(context);
        this.context=context;
        this.popMessage=popMessage;
        this.uniqueId=uniqueId;
        this.dbLat=dbLat;
        this.dblon=dblon;
        this.authToken=authToken;
        this.dialogCallBack = dialogCallBack;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_time_zone);
        CommonUtility.checkInternetConnection(context);
        initViews();

        btnContinue.requestFocus();

        poptext.setText(popMessage);
        if (ConstantUtility.isNetworkConnected) {
//            getSupportDetails();
        }else {
            CommonUtility.showToast(context,ConstantUtility.CHECK_INTERNET_CONNECTION);
        }


        btnContinue.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialogCallBack.onCurrentPress();
                /*outStationLocationPlayCall();
                cancel();
                ((PlayerActivity)context).getDrmKey();*/
                //((MainActivity)context).getChannelList();
                String hourOffset = TokenStorage.readCurrentTimeZoneOffset(context,ConstantUtility.HOMETIMEZONE,null);
                Log.e("Hour",hourOffset+"");
                cancel();
                ((MainActivity)context).getChannelList(hourOffset);
            }
        });
        backBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialogCallBack.onHomePress();
                String hourOffset = TokenStorage.readHomeTimeZoneOffset(context,ConstantUtility.HOMETIMEZONE,null);
                Log.e("Hour",hourOffset+":");
                cancel();
                ((MainActivity)context).getChannelList(hourOffset);
//                close the dialog.
                /*cancel();*/
            }
        });
    }

    private void initViews() {
        btnContinue=findViewById(R.id.btn_continue);
        backBtn = findViewById(R.id.btn_back);
        progressBar=findViewById(R.id.progress_bar);
        poptext=findViewById(R.id.poptext);
    }

    public interface DialogCallBack {
        void onCurrentPress();
        void onHomePress();
    }


    @Override
    public void onBackPressed() {
        ((MainActivity)context).onBackPressed();
    }
}
