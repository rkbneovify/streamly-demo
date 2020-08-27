package com.streamly.tv.retrofit_clients;

import com.streamly.tv.channelschedule.ChannelScheduleModel;
import com.streamly.tv.epg.ChannellistModel;
import com.streamly.tv.epg.channelgenre.ChannelGenreModel;
import com.streamly.tv.playervideo.DrmKeyModel;
import com.streamly.tv.settings.privacy.PrivacyPolicyModel;
import com.streamly.tv.settings.profile.profilemodel.ProfileModel;
import com.streamly.tv.settings.registerdevice.ConnectedDeviceModel;
import com.streamly.tv.settings.support.SupportModel;
import com.streamly.tv.termsofuses.TermsModel;
import com.streamly.tv.utilities.NetworkKeys;
import com.streamly.tv.log_in.LogInModel;
import io.reactivex.Observable;
import retrofit2.Call;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface ApiInterface {

    @Headers("Accept: application/json")
    @POST("login")
    Call<LogInModel> postLogInCall(@Query(NetworkKeys.KEY_EMAIL) String user_email, @Query(NetworkKeys.KEY_PASSWORD) String user_password, @Query(NetworkKeys.KEY_DEVICE_ID) String deviceid);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @POST("logout")
    Call<LogInModel> postLogOutCall(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET("profile")
    Call<ProfileModel> getUserProfile(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET("connecteddevice")
    Call<ConnectedDeviceModel> getConnectedDevice(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET(NetworkKeys.KEY_SUPPORT)
    Call<SupportModel> getSupportNumbers();

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET("privacypol")
    Call<PrivacyPolicyModel> getPoliciesText(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @GET("channellist")
    Call<ChannellistModel> getChannelList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @GET("channellist")
    Observable<ChannellistModel> getChannelList2(@Header(NetworkKeys.KEY_AUTH) String authorization_token);


    @Headers("Accept: application/json")
    @GET("channeldayschdeule")//api/auth/channeldayschdeule
    Call<ChannelScheduleModel> gotScheduleList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);


    @Headers("Accept: application/json")
//    @GET("oldchanneldayschdeule")//api/auth/channeldayschdeule
    @GET("channeldayschdeule")//api/auth/channeldayschdeule
    Observable<com.streamly.tv.channelschedule.ChannelScheduleModel> getScheduleList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    /*@Headers("Accept: application/json")
    @GET("channeldayschdeule")//api/auth/channeldayschdeule
    Observable<com.streamly.tv.channelschedule.ChannelScheduleModel> getScheduleList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);*/


    @Headers("Accept: application/json")
    @GET("channelgenre")//api/auth/channeldayschdeule
    Observable<com.streamly.tv.epg.channelgenre.ChannelGenreModel> getChannelGenre2(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @GET("channelgenre")
    Call<ChannelGenreModel> getChannelGenre(@Header(NetworkKeys.KEY_AUTH) String authorization_token);


    @Headers("Accept: application/json")
    @GET("termofuse")
    Call<TermsModel> getTermofuse();


    @Headers("Accept: application/json")
    @GET("getdrmkey")
    Call<DrmKeyModel> getDrmKey(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

}
