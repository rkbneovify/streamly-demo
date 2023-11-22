package com.adaptivemedia.streamlytv.streamly.dialogs;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;


public class CustomDialog extends Dialog {
    private final String TAG= CustomDialog.class.getSimpleName();
    private Context context;
    private Button btnOk;
    private TextView msg, title;
    private ProgressDialog dialog;
    private String auth_token;
    private  String msgText;
    private String titleText;

    public CustomDialog(@NonNull Context context, String msgText, String titleText) {
        super(context);
        this.context=context;
        this.msgText=msgText;
        this.titleText=titleText;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.custom_dialog);
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

        btnOk=findViewById(R.id.custom_btn_ok);
        msg=findViewById(R.id.custom_dialog_msg);
        title=findViewById(R.id.custom_dialog_title);
        msg.setText(msgText);
        title.setText(titleText);

    }
}
