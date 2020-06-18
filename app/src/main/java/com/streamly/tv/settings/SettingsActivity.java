package com.streamly.tv.settings;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import androidx.databinding.DataBindingUtil;
import androidx.fragment.app.FragmentActivity;
import com.streamly.tv.MainActivity;
import com.streamly.tv.settings.privacy.PrivacyDialog;
import com.streamly.tv.settings.profile.ProfileDialog;
import com.streamly.tv.settings.registerdevice.RegisterDeviceDialog;
import com.streamly.tv.settings.signout.SignOutDialog;
import com.streamly.tv.settings.support.SupportDialog;
import com.streamly.tv.R;
import com.streamly.tv.databinding.ActivitySettingsBinding;
import com.streamly.tv.utilities.CommonUtility;


public class SettingsActivity extends FragmentActivity implements View.OnClickListener {
    private static final String TAG=SettingsActivity.class.getSimpleName();
    private ActivitySettingsBinding settingsBinding;
    private ProgressDialog dialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        CommonUtility.checkInternetConnection(this);
        settingsBinding= DataBindingUtil.setContentView(this, R.layout.activity_settings);
//        setContentView(R.layout.activity_settings);
        dialog= CommonUtility.custom_loader(this);
        initListeners();
    }

    private void initListeners() {
        settingsBinding.account.setOnClickListener(this);
        settingsBinding.registerDevice.setOnClickListener(this);
        settingsBinding.support.setOnClickListener(this);
        settingsBinding.privacy.setOnClickListener(this);
        settingsBinding.signOut.setOnClickListener(this);
        settingsBinding.textChannels.setOnClickListener(this);
    }

    @Override
    public void onClick(View view) {
        Log.e(TAG, "OnClick Method");
        switch (view.getId())
        {
            case R.id.account :
                ProfileDialog profileDialog=new ProfileDialog(this);
                profileDialog.show();
            break;

            case R.id.register_device :
                RegisterDeviceDialog registerDeviceDialog=new RegisterDeviceDialog(this);
                registerDeviceDialog.show();
                break;

            case R.id.support :
                SupportDialog supportDialog=new SupportDialog(this);
                supportDialog.show();
                break;

            case R.id.privacy :
                PrivacyDialog privacyDialog=new PrivacyDialog(this);
                privacyDialog.show();
                break;

            case R.id.sign_out :
                SignOutDialog signOutDialog=new SignOutDialog(this);
                signOutDialog.show();
                break;

            case R.id.text_channels :
                startActivity(new Intent(SettingsActivity.this, MainActivity.class));
                finish();
                break;
            //handle multiple view click events
        }
    }
}
