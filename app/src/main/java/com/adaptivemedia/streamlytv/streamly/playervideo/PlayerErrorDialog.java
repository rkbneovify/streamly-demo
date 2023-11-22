package com.adaptivemedia.streamlytv.streamly.playervideo;

import android.app.Dialog;
import android.content.Context;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import androidx.annotation.NonNull;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;

public class PlayerErrorDialog extends Dialog {
    private final String TAG= PlayerErrorDialog.class.getSimpleName();
    private Context context;
    private Button btnBack, btnTryAgain;
    PlayerDialogInterface playerDialogInterface;
    PlayerActivity playerActivity;

    public PlayerErrorDialog(@NonNull Context context, PlayerDialogInterface playerDialogInterface) {
        super(context);
        this.context=context;
        this.playerDialogInterface=playerDialogInterface;
    }

    public interface PlayerDialogInterface {
         void okButton();
         void tryAgainButton();
    }



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.dialog_player_error);
        playerActivity = (PlayerActivity) getOwnerActivity();
        CommonUtility.checkInternetConnection(context);
        initViews();


//        btnNo.requestFocus();
        btnTryAgain.requestFocus();

        btnBack.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                playerDialogInterface.okButton();
                dismiss();
            }
        });
        btnTryAgain.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                playerDialogInterface.tryAgainButton();
                dismiss();
            }
        });

    }



    private void initViews() {
        btnBack=findViewById(R.id.player_btn_back);
        btnTryAgain=findViewById(R.id.player_btn_try_again);
    }

}
