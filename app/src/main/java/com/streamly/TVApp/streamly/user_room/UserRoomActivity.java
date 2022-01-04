package com.streamly.TVApp.streamly.user_room;

import androidx.fragment.app.FragmentActivity;
import androidx.recyclerview.widget.GridLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import android.content.Intent;
import android.content.IntentFilter;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.ProgressBar;

import com.androidnetworking.AndroidNetworking;
import com.androidnetworking.common.Priority;
import com.androidnetworking.error.ANError;
import com.androidnetworking.interfaces.JSONObjectRequestListener;
import com.google.gson.Gson;
import com.google.gson.reflect.TypeToken;
import com.streamly.TVApp.streamly.MainActivity;
import com.streamly.TVApp.streamly.R;
import com.streamly.TVApp.streamly.dialogs.SessionExpiredDialog;
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.loginusingpasscode.LoginUsingPasscodeActivity;
import com.streamly.TVApp.streamly.new_log_in.NewLoginErrorResponse;
import com.streamly.TVApp.streamly.new_log_in.Room;
import com.streamly.TVApp.streamly.new_log_in.new_hotel_response.NewHotelRoomResponse;
import com.streamly.TVApp.streamly.receivers.NetworkStateReceiver;
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient;
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface;
import com.streamly.TVApp.streamly.roomModel.RoomErrorResponse;
import com.streamly.TVApp.streamly.roomModel.roomResponse.RoomRefreshResponse;
import com.streamly.TVApp.streamly.utilities.CommonUtility;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;

import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.ArrayList;

import static com.streamly.TVApp.streamly.utilities.ApiConfig.BASE_URL_TESTING;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UserRoomActivity extends FragmentActivity implements NetworkStateReceiver.NetworkStateReceiverListener {
    private static final String TAG = UserRoomActivity.class.getSimpleName();

    private ProgressBar progressBar;
    private RecyclerView rvRoom;
    String authToken;
    private static final int REQUEST_CODE = 1234;

    ArrayList<Room> roomList = new ArrayList<>();
    UserRoomAdapter userRoomAdapter;
    private LinearLayout llNoDataFound, layoutInternet , linearLayoutNoRoom;
    NetworkStateReceiver networkStateReceiver;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_room);


        networkStateReceiver = new NetworkStateReceiver();
        networkStateReceiver.addListener(this);
        this.registerReceiver(networkStateReceiver, new IntentFilter(android.net.ConnectivityManager.CONNECTIVITY_ACTION));

        authToken = TokenStorage.readSharedTokenPasscode(UserRoomActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, null);

        Log.i(TAG, "onCreate: UserRoomActivity " + authToken);
        initViews();


    }

    private void initViews() {
        llNoDataFound = findViewById(R.id.llNoDataFound);
        linearLayoutNoRoom = findViewById(R.id.layout_no_room_available);

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
                                    if(!roomRefreshResponse.getData().isEmpty() && roomRefreshResponse.getData()!=null){
                                        String json = gson.toJson(roomRefreshResponse.getData());

                                        TokenStorage.savedRoomList(UserRoomActivity.this, ConstantUtility.ROOM_LIST, json);

                                        // below line is to get the type of our array list.
                                        Type type = new TypeToken<ArrayList<Room>>() {
                                        }.getType();
                                        roomList = gson.fromJson(json, type);
                                        roomListData();
                                    }

                                    else{
                                        linearLayoutNoRoom.setVisibility(View.VISIBLE);
                                    }



                                }
                                else if(roomRefreshResponse.getResponseCode() == 401){
                                    SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(UserRoomActivity.this);
                                    sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                    sessionExpiredDialog.setCancelable(false);
                                    sessionExpiredDialog.show();
                                }
                                else {
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
            rvRoom.setAdapter(new UserRoomAdapter(UserRoomActivity.this, roomList, item -> {
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
                                    } else if(response.getInt("response_code") == 401){
                                        SessionExpiredDialog sessionExpiredDialog = new SessionExpiredDialog(UserRoomActivity.this);
                                        sessionExpiredDialog.getWindow().setBackgroundDrawableResource(R.drawable.white_transparent);
                                        sessionExpiredDialog.setCancelable(false);
                                        sessionExpiredDialog.show();
                                    }else {


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
                                    }
                                   else if (roomErrorResponse.getResponseCode() == 401) {
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
            }));
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
}