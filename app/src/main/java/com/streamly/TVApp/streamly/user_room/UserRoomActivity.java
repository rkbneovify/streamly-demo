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
import com.streamly.TVApp.streamly.local_storage.TokenStorage;
import com.streamly.TVApp.streamly.new_log_in.Room;
import com.streamly.TVApp.streamly.receivers.NetworkStateReceiver;
import com.streamly.TVApp.streamly.utilities.CommonUtility;
import com.streamly.TVApp.streamly.utilities.ConstantUtility;

import org.json.JSONObject;

import java.lang.reflect.Type;
import java.util.ArrayList;

import static com.streamly.TVApp.streamly.utilities.ApiConfig.BASE_URL_TESTING;

public class UserRoomActivity extends FragmentActivity implements NetworkStateReceiver.NetworkStateReceiverListener {
    private static final String TAG = UserRoomActivity.class.getSimpleName();

    private ProgressBar progressBar;
    private RecyclerView rvRoom;
    String authToken;
    private static final int REQUEST_CODE = 1234;

    ArrayList<Room> roomList = new ArrayList<>();
    UserRoomAdapter userRoomAdapter;
    private LinearLayout llNoDataFound, layoutInternet;
    NetworkStateReceiver networkStateReceiver;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_user_room);
        initViews();


        networkStateReceiver = new NetworkStateReceiver();
        networkStateReceiver.addListener(this);
        this.registerReceiver(networkStateReceiver, new IntentFilter(android.net.ConnectivityManager.CONNECTIVITY_ACTION));

        authToken = TokenStorage.readSharedTokenPasscode(UserRoomActivity.this, ConstantUtility.AUTH_TOKEN_PASSCODE, null);

        Log.i(TAG, "onCreate: UserRoomActivity " + authToken);


    }

    private void initViews() {
        llNoDataFound = findViewById(R.id.llNoDataFound);

        progressBar = findViewById(R.id.progress_bar);

        rvRoom = findViewById(R.id.rv_room);
        layoutInternet = findViewById(R.id.layout_internet);
        Gson gson = new Gson();
        String json = TokenStorage.readRoomList(UserRoomActivity.this, ConstantUtility.ROOM_LIST);

        // below line is to get the type of our array list.
        Type type = new TypeToken<ArrayList<Room>>() {
        }.getType();
        roomList = gson.fromJson(json, type);
    }


    //#neovifyissueresolved28sep
    private void roomListData() {

        progressBar.setVisibility(View.VISIBLE);


        rvRoom.setLayoutManager(new GridLayoutManager(UserRoomActivity.this, 4));
//                        rvRoom.setAdapter(userRoomAdapter);
        rvRoom.setAdapter(new UserRoomAdapter(UserRoomActivity.this, roomList, item -> {
            Log.i(TAG, "roomListData: 3 " + item);
            Log.i(TAG, "roomListData: 4 " + authToken);
            progressBar.setVisibility(View.VISIBLE);

//            ApiInterface apiInterface = ApiClient.getClientTesting2().create(ApiInterface.class);
//            Call<SelectedHotelRoomResponse> call = apiInterface.postHotelRoom("Bearer " + authToken, Integer.valueOf(item));
//    call.enqueue(new Callback<SelectedHotelRoomResponse>() {
//        @Override
//        public void onResponse(Call<SelectedHotelRoomResponse> call, Response<SelectedHotelRoomResponse> response) {
////            {
////                "success": true,
////                    "data": "Room id 75 added",
////                    "message": "Successfully added to room/slot",
////                    "response_code": 200
////            }
//            Log.i(TAG, "onResponse: roomListData 1 "+response.toString());
//            Log.i(TAG, "onResponse: roomListData 2 "+response.body());
//            try {
//                if (response.isSuccessful() && response.code()== 200) {
//                    Log.i(TAG, "onResponse: roomListData 6 ");
//                    CommonUtility.showToast(UserRoomActivity.this, response.body().getData().toString());
//                    TokenStorage.savedSharedToken(UserRoomActivity.this, ConstantUtility.AUTH_TOKEN,authToken);
//                    startActivity(new Intent(UserRoomActivity.this, MainActivity.class));
//                    finish();
//                }
//                else
//                {
//                CommonUtility.showToast(UserRoomActivity.this, response.body().getData().toString());
//                }
//                progressBar.setVisibility(View.INVISIBLE);
//            }
//            catch (Exception e)
//            {
//                Log.e(TAG, "failure: " + call + " " + e.getLocalizedMessage());
//                Log.e(TAG, "failure: " + call + " " + e.getMessage());
//                progressBar.setVisibility(View.INVISIBLE);
//
//            }
//
//        }
//
//        @Override
//        public void onFailure(Call<SelectedHotelRoomResponse> call, Throwable t) {
//            Log.e(TAG, "failure: " + call + " " + t.getLocalizedMessage());
//            progressBar.setVisibility(View.INVISIBLE);
//        }
//    }
//    );

            AndroidNetworking.post(BASE_URL_TESTING+"/api/auth/sync-session-with-room")
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
                            Log.e(TAG, "failure: onError 2 " + " " + error.getMessage());
                            // handle error
                            progressBar.setVisibility(View.INVISIBLE);
                        }
                    });
        }));

        progressBar.setVisibility(View.INVISIBLE);
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
        roomListData();

    }

    @Override
    public void networkUnavailable() {

        layoutInternet.setVisibility(View.VISIBLE);

    }
}