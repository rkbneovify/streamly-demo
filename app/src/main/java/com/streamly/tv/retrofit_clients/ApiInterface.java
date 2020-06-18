package com.streamly.tv.retrofit_clients;

import com.streamly.tv.epg.ChannellistModel;
import com.streamly.tv.settings.privacy.PrivacyPolicyModel;
import com.streamly.tv.settings.profile.profilemodel.ProfileModel;
import com.streamly.tv.settings.registerdevice.ConnectedDeviceModel;
import com.streamly.tv.settings.support.SupportModel;
import com.streamly.tv.utilities.NetworkKeys;
import com.streamly.tv.log_in.LogInModel;
import retrofit2.Call;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface ApiInterface {

    @POST("login")
    Call<LogInModel> postLogInCall(@Query(NetworkKeys.KEY_EMAIL) String user_email, @Query(NetworkKeys.KEY_PASSWORD) String user_password, @Query(NetworkKeys.KEY_DEVICE_ID) String deviceid);

    @POST("logout")
    Call<LogInModel> postLogOutCall(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @Header(NetworkKeys.KEY_ACCEPT) String accept);

    @GET("profile")
    Call<ProfileModel> getUserProfile(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @Header(NetworkKeys.KEY_ACCEPT) String accept);

    @GET("connecteddevice")
    Call<ConnectedDeviceModel> getConnectedDevice(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @Header(NetworkKeys.KEY_ACCEPT) String accept);

    @GET("support")
    Call<SupportModel> getSupportNumbers(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @Header(NetworkKeys.KEY_ACCEPT) String accept);


    @GET("privacypol")
    Call<PrivacyPolicyModel> getPoliciesText(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @Header(NetworkKeys.KEY_ACCEPT) String accept);

    @GET("channellist")
    Call<ChannellistModel> getChannelList(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @Header(NetworkKeys.KEY_ACCEPT) String accept);


}
