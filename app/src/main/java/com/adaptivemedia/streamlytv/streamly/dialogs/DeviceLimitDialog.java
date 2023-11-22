package com.adaptivemedia.streamlytv.streamly.dialogs;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

public class DeviceLimitDialog extends Dialog {
    private final String TAG= DeviceLimitDialog.class.getSimpleName();
    private Context context;
    private Button btnOk;
    private TextView textView;
    private ProgressDialog dialog;
    private String auth_token;
    private  String maxDeviceText;

    public DeviceLimitDialog(@NonNull Context context, String maxDeviceText) {
        super(context);
        this.context=context;
        this.maxDeviceText=maxDeviceText;
    }



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_device_limit);
        CommonUtility.checkInternetConnection(context);
        dialog= CommonUtility.custom_loader(context);
        auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
        initViews();


//        btnNo.requestFocus();
        btnOk.requestFocus();



        btnOk.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                    cancel();
            }
        });
    }






    private void initViews() {

        btnOk=findViewById(R.id.btn_ok);
        textView=findViewById(R.id.tv_device_limit);
        textView.setText(maxDeviceText);

    }
}
