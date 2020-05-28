package com.streamly.tv.settings.registerdevice;

import android.app.Dialog;
import android.content.Context;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import com.streamly.tv.R;
import java.util.ArrayList;
import java.util.List;

public class RegisterDeviceDialog extends Dialog {
    private Button btnOK;
    private RecyclerView rvDeviceModel;
    private Context context;
    List<RegisterDeviceType> list=new ArrayList<RegisterDeviceType>();


    public RegisterDeviceDialog(@NonNull Context context) {
        super(context);
        this.context=context;
    }


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_register_device);

        initViews();

        btnOK.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                cancel();
            }
        });


        RegisterDeviceAdapter deviceAdapter=new RegisterDeviceAdapter(context,getList());
        rvDeviceModel.setLayoutManager(new LinearLayoutManager(context,RecyclerView.VERTICAL,false));
        rvDeviceModel.setAdapter(deviceAdapter);

    }


    private List<RegisterDeviceType> getList(){
            list.add(new RegisterDeviceType("1."+"Samsung smart tv -43 inch","548782-5555-895"));
            list.add(new RegisterDeviceType("2."+"Sony Bravia smart tv -56 inch","548782-5555-895"));
            list.add(new RegisterDeviceType("3."+"Apple iphone- XR","548782-5555-895"));
            list.add(new RegisterDeviceType("4."+"Samsung Note 10","548782-5555-895"));
            list.add(new RegisterDeviceType("5."+"Apple iPhone -XS max","548782-5555-895"));
        return list;
    }


    private void initViews() {
        btnOK=findViewById(R.id.btn_ok);
        rvDeviceModel=findViewById(R.id.rv_device_model);
    }


}
