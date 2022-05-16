package com.adaptivemedia.streamlytv.streamly.settings.support;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

public class SupportActivity extends AppCompatActivity {
    private ImageView supportImg;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_support);
        initViews();
        if (ConstantUtility.isNetworkConnected) {
           // getSupportDetails();
        }else {
            CommonUtility.showToast(this,ConstantUtility.CHECK_INTERNET_CONNECTION);
        }
    }

    private void initViews() {
        /*supportImg = findViewById(R.id.support_img);*/
    }
}