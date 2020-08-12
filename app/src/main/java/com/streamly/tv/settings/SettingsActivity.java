package com.streamly.tv.settings;

import android.app.ProgressDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.graphics.drawable.BitmapDrawable;
import android.os.Bundle;
import android.renderscript.Allocation;
import android.renderscript.RenderScript;
import android.renderscript.ScriptIntrinsicBlur;
import android.util.Log;
import android.view.View;
import android.view.ViewTreeObserver;

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

//        settingsBinding.account.clearFocus();
        settingsBinding.account.requestFocus();
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
                profileDialog.getWindow().setBackgroundDrawableResource(android.R.color.transparent);
                profileDialog.show();
            break;

            case R.id.register_device :
                RegisterDeviceDialog registerDeviceDialog=new RegisterDeviceDialog(this);
                registerDeviceDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                registerDeviceDialog.show();
                break;

            case R.id.support :
                SupportDialog supportDialog=new SupportDialog(this);
                supportDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                supportDialog.show();
                break;

            case R.id.privacy :
                PrivacyDialog privacyDialog=new PrivacyDialog(this);
                privacyDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                privacyDialog.show();
                break;

            case R.id.sign_out :
                SignOutDialog signOutDialog=new SignOutDialog(this);
                signOutDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                signOutDialog.show();
                break;

            case R.id.text_channels :
                startActivity(new Intent(SettingsActivity.this, MainActivity.class));
                finish();
                break;
            //handle multiple view click events
        }
    }


    private void applyBlur(View view) {
        view.getViewTreeObserver().addOnPreDrawListener(new ViewTreeObserver.OnPreDrawListener() {
            @Override
            public boolean onPreDraw() {
                view.getViewTreeObserver().removeOnPreDrawListener(this);
                view.buildDrawingCache();

                Bitmap bmp = view.getDrawingCache();
                blur(bmp, view);
                return true;
            }
        });
    }

    private void blur(Bitmap bkg, View view) {
        long startMs = System.currentTimeMillis();

        float radius = 20;

        Bitmap overlay = Bitmap.createBitmap((int) (view.getMeasuredWidth()),
                (int) (view.getMeasuredHeight()), Bitmap.Config.ARGB_8888);

        Canvas canvas = new Canvas(overlay);

        canvas.translate(-view.getLeft(), -view.getTop());
        canvas.drawBitmap(bkg, 0, 0, null);

        RenderScript rs = RenderScript.create(SettingsActivity.this);

        Allocation overlayAlloc = Allocation.createFromBitmap(
                rs, overlay);

        ScriptIntrinsicBlur blur = ScriptIntrinsicBlur.create(
                rs, overlayAlloc.getElement());

        blur.setInput(overlayAlloc);

        blur.setRadius(radius);

        blur.forEach(overlayAlloc);

        overlayAlloc.copyTo(overlay);

        view.setBackground(new BitmapDrawable(
                getResources(), overlay));

        rs.destroy();
    }


}
