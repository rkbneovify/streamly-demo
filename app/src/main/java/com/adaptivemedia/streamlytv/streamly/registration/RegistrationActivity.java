package com.adaptivemedia.streamlytv.streamly.registration;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;

import androidx.fragment.app.FragmentActivity;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;

public class RegistrationActivity extends FragmentActivity {

    private TextView activatText, openLinkText, streamlyWebText, registrationMsgText;
    private Context context;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_registration);
        CommonUtility.checkInternetConnection(context);
        initviews();

    }

    private void initviews() {
        activatText = findViewById(R.id.activate_msg);
        openLinkText = findViewById(R.id.link_msg);
        streamlyWebText = findViewById(R.id.web_link);
        registrationMsgText = findViewById(R.id.reg_message);
    }



}
