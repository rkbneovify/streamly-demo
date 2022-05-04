package com.adaptivemedia.streamlytv.streamly.user_room;

import static com.adaptivemedia.streamlytv.streamly.utilities.ApiConfig.BASE_URL_TESTING;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.IntentFilter;
import android.media.Image;
import android.os.Bundle;
import android.speech.RecognizerIntent;
import android.text.Editable;
import android.text.TextWatcher;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.fragment.app.FragmentActivity;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interfaces.JSONObjectRequestListener;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog;
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInModel;
import com.adaptivemedia.streamlytv.streamly.new_log_in.Room;
import com.adaptivemedia.streamlytv.streamly.receivers.NetworkStateReceiver;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient;
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface;
import com.adaptivemedia.streamlytv.streamly.roomModel.RoomErrorResponse;
import com.adaptivemedia.streamlytv.streamly.roomModel.roomResponse.RoomRefreshResponse;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UserRoomActivity extends FragmentActivity implements NetworkStateReceiver.NetworkStateReceiverListener {
    private static final String TAG = UserRoomActivity.class.getSimpleName();

    private ProgressBar progressBar;
    private RecyclerView rvRoom;
    private TextView logoutTextView;
    private EditText searchRoomEd;
    ImageView mickIconSearch;
    String authToken;
    private static final int REQUEST_CODE = 1234;
    private Context context;
    private UserRoomAdapter userRoomAdapter;
    Dialog alertDialog;
    AlertDialog progressDialog;

    ArrayList<Room> roomList = new ArrayList<>();
    private LinearLayout llNoDataFound, layoutInternet, linearLayoutNoRoom;
    NetworkStateReceiver networkStateReceiver;

    DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which){
                case DialogInterface.BUTTON_POSITIVE:

                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    break;
            }
        }
    };

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_room);
        context = this;
        alertDialog = new Dialog(context);
        alertDialog.setContentView(R.layout.logout_dialog_view);
        alertDialog.findViewById(R.id.yes_btn_logout_dialog).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Dialog loadingDialog = initProgressDialog(UserRoomActivity.this);
                loadingDialog.show();
                String auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
                ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
                Call<LogInModel> call = apiInterface.postLogOutCall("Bearer " + auth_token);
                call.enqueue(new Callback<LogInModel>() {
                    @Override
                    public void onResponse(Call<LogInModel> call, Response<LogInModel> response) {
                        loadingDialog.dismiss();
                        try {
                            progressBar.setVisibility(View.GONE);
                            TokenStorage.clearSharedToken(context);
                            context.startActivity(new Intent(context, LogInActivity.class));
                            ((Activity) context).finish();
                        } catch (Exception ex) {
                            ex.printStackTrace();

                        }
                    }

                    @Override
                    public void onFailure(Call<LogInModel> call, Throwable t) {
                        loadingDialog.dismiss();
                        progressBar.setVisibility(View.GONE);
                        Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
                        CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE);
                    }
                });
                alertDialog.dismiss();
            }
        });

        alertDialog.findViewById(R.id.no_btn_logout_dialog).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                alertDialog.dismiss();
            }
        });

        networkStateReceiver = new NetworkStateReceiver();
        networkStateReceiver.addListener(this);
        searchRoomEd = findViewById(R.id.ed_search_room);
        this.registerReceiver(networkStateReceiver, new IntentFilter(android.net.ConnectivityManager.CONNECTIVITY_ACTION));

        authToken = TokenStorage.readSharedToken(UserRoomActivity.this, ConstantUtility.AUTH_TOKEN, null);

        Log.i(TAG, "onCreate: UserRoomActivity " + authToken);
        initViews();
        searchRoomEd.addTextChangedListener(new TextWatcher() {
            @Override
            public void beforeTextChanged(CharSequence s, int start, int count, int after) {

            }

            @Override
            public void onTextChanged(CharSequence s, int start, int before, int count) {

            }

            @Override
            public void afterTextChanged(Editable s) {
                filter(s.toString());
            }
        });

        mickIconSearch = findViewById(R.id.img_voice_search);
        mickIconSearch.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                startVoiceRecognitionActivity();
            }
        });


    }

    /**
     * Fire an intent to start the voice recognition activity.
     */
    private void startVoiceRecognitionActivity() {
        Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
        intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL, RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
        intent.putExtra(RecognizerIntent.EXTRA_PROMPT, "Voice searching...");
        if(intent.resolveActivity(getPackageManager())!=null)
        {
            startActivityForResult(intent, REQUEST_CODE);
        }
        else{
            Toast.makeText(this, "Your Device Don't Support Speech Input", Toast.LENGTH_SHORT).show();
        }

    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        if (requestCode == REQUEST_CODE && resultCode == RESULT_OK) {
            // Populate the wordsList with the String values the recognition engine thought it heard
            final ArrayList<String> matches = data.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS);
            if (!matches.isEmpty()) {
                String Query = matches.get(0);
                searchRoomEd.setText(Query.replace(" ",""));
            }
        }
        super.onActivityResult(requestCode, resultCode, data);
    }

    void filter(String text) {
        text = text.toLowerCase();
        List<Room> temp = new ArrayList();
        for (Room d : roomList) {
            //or use .equal(text) with you want equal match
            //use .toLowerCase() for better matches
            if (d.getHotelRoom().toLowerCase().contains(text)) {
                temp.add(d);
            }
        }
        //update recyclerview
        userRoomAdapter.updateList(temp);
    }

    private void initViews() {
        llNoDataFound = findViewById(R.id.llNoDataFound);
        linearLayoutNoRoom = findViewById(R.id.layout_no_room_available);
        logoutTextView = findViewById(R.id.room_logout_btn);

        progressBar = findViewById(R.id.progress_bar);

        rvRoom = findViewById(R.id.rv_room);
        layoutInternet = findViewById(R.id.layout_internet);
//        Gson gson = new Gson();
//        String json = TokenStorage.readRoomList(UserRoomActivity.this, ConstantUtility.ROOM_LIST);
//
//        // below line is to get the type of our array list.
//        Type type = new TypeToken<ArrayList<Room>>() {
//        }.getType();
//        roomList = gson.fromJson(json, type);

        latestRoomList();

        logoutTextView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
//                show dialog to ask conformation
                ((TextView) alertDialog.findViewById(R.id.simpleTextView_1)).setText(getString(R.string.logout_message));
                alertDialog.show();
            }
        });

    }

    private void latestRoomList() {
        progressBar.setVisibility(View.VISIBLE);
//

        AndroidNetworking.post(BASE_URL_TESTING + "/api/auth/get-hotelrooms")
                .addHeaders("Authorization", "Bearer " + authToken)
                .setPriority(Priority.HIGH)
                .build()
                .getAsJSONObject(new JSONObjectRequestListener() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {

                            if (response != null) {
                                RoomRefreshResponse roomRefreshResponse = new Gson().fromJson(response.toString(), RoomRefreshResponse.class);
                                if (roomRefreshResponse.getResponseCode() == 200) {
                                    linearLayoutNoRoom.setVisibility(View.GONE);

                                    Gson gson = new Gson();
                                    if (!roomRefreshResponse.getData().isEmpty() && roomRefreshResponse.getData() != null) {
                                        String json = gson.toJson(roomRefreshResponse.getData());

                                        TokenStorage.savedRoomList(UserRoomActivity.this, ConstantUtility.ROOM_LIST, json);

                                        // below line is to get the type of our array list.
                                        Type type = new TypeToken<ArrayList<Room>>() {
                                        }.getType();
                                        roomList = gson.fromJson(json, type);
                                        roomListData();
                                    } else {
                                        linearLayoutNoRoom.setVisibility(View.VISIBLE);
                                    }


                                } else if (roomRefreshResponse.getResponseCode() == 401) {
                                    SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(UserRoomActivity.this);
                                    sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                    sessionExpiredDialog.setCancelable(false);
                                    sessionExpiredDialog.show();
                                } else {
                                    Log.i(TAG, "onResponse: response_code 409 ");
//                                    refreshHotelRooms();
                                    linearLayoutNoRoom.setVisibility(View.VISIBLE);
                                }
                            }

                            progressBar.setVisibility(View.INVISIBLE);

                        } catch (Exception e) {
                            Log.e(TAG, "failure: catch " + " " + e.getLocalizedMessage());
                            Log.e(TAG, "failure: catch " + " " + e.getMessage());

                            progressBar.setVisibility(View.INVISIBLE);
                        }
                        // do anything with response
                    }

                    @Override
                    public void onError(ANError error) {
                        Log.e(TAG, "failure: onError 1 " + " " + error.getLocalizedMessage());
                        Log.e(TAG, "failure: onError 2 " + " " + error.getErrorBody());
                        if (error.getErrorBody() != null) {
                            RoomErrorResponse roomErrorResponse = new Gson().fromJson(error.getErrorBody().toString(), RoomErrorResponse.class);
                            if (roomErrorResponse.getResponseCode() == 401) {
                                SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(UserRoomActivity.this);
                                sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                sessionExpiredDialog.setCancelable(false);
                                sessionExpiredDialog.show();
                            }
                        }
                        // handle error
                        progressBar.setVisibility(View.INVISIBLE);

                    }
                });

    }


    //#neovifyissueresolved28sep
    private void roomListData() {

        progressBar.setVisibility(View.VISIBLE);


        rvRoom.setLayoutManager(new GridLayoutManager(UserRoomActivity.this, 4));
//                        rvRoom.setAdapter(userRoomAdapter);
        if (!roomList.isEmpty() && roomList != null) {
//            rvRoom.post(new Runnable() {
//                @Override
//                public void run() {
            int rvWidth = getScreenWidth() - dpToPx(20);
                    userRoomAdapter = new UserRoomAdapter(UserRoomActivity.this, rvWidth,roomList, item -> {
                        Log.i(TAG, "roomListData: 3 " + item);
                        Log.i(TAG, "roomListData: 4 " + authToken);
                        progressBar.setVisibility(View.VISIBLE);

                        AndroidNetworking.post(BASE_URL_TESTING + "/api/auth/sync-session-with-room")
                                .addHeaders("Authorization", "Bearer " + authToken)
                                .addBodyParameter("roomid", item.toString())
                                .setPriority(Priority.HIGH)
                                .build()
                                .getAsJSONObject(new JSONObjectRequestListener() {
                                    @Override
                                    public void onResponse(JSONObject response) {
                                        try {
                                            if (response.getInt("response_code") == 200) {
                                                Log.i(TAG, "onResponse: roomListData 6 ");
                                                CommonUtility.showToast(UserRoomActivity.this, response.getString("message").toString());
                                                TokenStorage.savedSharedToken(UserRoomActivity.this, ConstantUtility.AUTH_TOKEN, authToken);
                                                startActivity(new Intent(UserRoomActivity.this, MainActivity.class));
                                                finish();
                                            } else if (response.getInt("response_code") == 409) {
                                                Log.i(TAG, "onResponse: response_code 409 ");
//                                    refreshHotelRooms();
                                            } else if (response.getInt("response_code") == 401) {
                                                SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(UserRoomActivity.this);
                                                sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                                sessionExpiredDialog.setCancelable(false);
                                                sessionExpiredDialog.show();
                                            } else {


                                                Log.i(TAG, "onResponse: roomListData 7 ");
                                                CommonUtility.showToast(UserRoomActivity.this, response.getString("message").toString());
                                            }
                                            progressBar.setVisibility(View.INVISIBLE);

                                        } catch (Exception e) {
                                            Log.e(TAG, "failure: catch " + " " + e.getLocalizedMessage());
                                            Log.e(TAG, "failure: catch " + " " + e.getMessage());

                                            progressBar.setVisibility(View.INVISIBLE);
                                        }
                                        // do anything with response
                                    }

                                    @Override
                                    public void onError(ANError error) {
                                        Log.e(TAG, "failure: onError 1 " + " " + error.getLocalizedMessage());
                                        Log.e(TAG, "failure: onError 2 " + " " + error.getErrorBody());
                                        if (error.getErrorBody() != null) {
                                            RoomErrorResponse roomErrorResponse = new Gson().fromJson(error.getErrorBody().toString(), RoomErrorResponse.class);
                                            if (roomErrorResponse.getResponseCode() == 409) {
                                                Log.e(TAG, "failure: onError 3 " + " " + roomErrorResponse.getResponseCode());
                                                Log.e(TAG, "failure: onError 4 " + " " + roomErrorResponse.getMessage());
                                                refreshHotelRooms();
                                            } else if (roomErrorResponse.getResponseCode() == 401) {
                                                SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(UserRoomActivity.this);
                                                sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                                sessionExpiredDialog.setCancelable(false);
                                                sessionExpiredDialog.show();
                                            }
                                        }
                                        // handle error
                                        progressBar.setVisibility(View.INVISIBLE);
                                    }
                                });
                    });
                    rvRoom.setAdapter(userRoomAdapter);
//                }
//            });

        } else {
            linearLayoutNoRoom.setVisibility(View.VISIBLE);
//            CommonUtility.showToast(UserRoomActivity.this, "No Room Available. Please remove from web ");
        }

        progressBar.setVisibility(View.INVISIBLE);
    }

    private void refreshHotelRooms() {
        progressBar.setVisibility(View.VISIBLE);
//

        AndroidNetworking.post(BASE_URL_TESTING + "/api/auth/get-hotelrooms")
                .addHeaders("Authorization", "Bearer " + authToken)
                .setPriority(Priority.HIGH)
                .build()
                .getAsJSONObject(new JSONObjectRequestListener() {
                    @Override
                    public void onResponse(JSONObject response) {
                        try {

                            if (response != null) {
                                RoomRefreshResponse roomRefreshResponse = new Gson().fromJson(response.toString(), RoomRefreshResponse.class);
                                if (roomRefreshResponse.getResponseCode() == 200) {


                                    Gson gson = new Gson();
                                    String json = gson.toJson(roomRefreshResponse.getData());

                                    TokenStorage.savedRoomList(UserRoomActivity.this, ConstantUtility.ROOM_LIST, json);
                                    CommonUtility.showToast(UserRoomActivity.this, roomRefreshResponse.getMessage());
//                        startActivity(new Intent(LoginUsingPasscodeActivity.this, MainActivity.class));
//                        finish();
                                    startActivity(new Intent(UserRoomActivity.this, UserRoomActivity.class));
                                    finish();

                                } else if (roomRefreshResponse.getResponseCode() == 409) {
                                    Log.i(TAG, "onResponse: response_code 409 ");
//                                    refreshHotelRooms();
                                } else {
                                    Log.i(TAG, "onResponse: roomListData 7 ");
                                    CommonUtility.showToast(UserRoomActivity.this, roomRefreshResponse.getMessage().toString());
                                }
                            }

                            progressBar.setVisibility(View.INVISIBLE);

                        } catch (Exception e) {
                            Log.e(TAG, "failure: catch " + " " + e.getLocalizedMessage());
                            Log.e(TAG, "failure: catch " + " " + e.getMessage());

                            progressBar.setVisibility(View.INVISIBLE);
                        }
                        // do anything with response
                    }

                    @Override
                    public void onError(ANError error) {
                        Log.e(TAG, "failure: onError 1 " + " " + error.getLocalizedMessage());
                        Log.e(TAG, "failure: onError 2 " + " " + error.getErrorBody());

                        // handle error
                        progressBar.setVisibility(View.INVISIBLE);

                    }
                });
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        networkStateReceiver.removeListener(this);
        this.unregisterReceiver(networkStateReceiver);
    }

    @Override
    public void networkAvailable() {
        layoutInternet.setVisibility(View.INVISIBLE);


    }

    @Override
    public void networkUnavailable() {

        layoutInternet.setVisibility(View.VISIBLE);

    }

    public Dialog initProgressDialog(Context context){
        Dialog dialog = new Dialog(context);
        dialog.setContentView(R.layout.dialog_loading_progress_bar);
        dialog.getWindow().setBackgroundDrawableResource(R.color.transparent);
        dialog.setCanceledOnTouchOutside(false);
        return dialog;
    }

    public int getScreenWidth() {
        DisplayMetrics displayMetrics = new DisplayMetrics();
        getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        int height = displayMetrics.heightPixels;
        int width = displayMetrics.widthPixels;
        return width;
    }

    public int dpToPx(int dp) {
        DisplayMetrics displayMetrics = context.getResources().getDisplayMetrics();
        return Math.round(dp * (displayMetrics.xdpi / DisplayMetrics.DENSITY_DEFAULT));
    }
}