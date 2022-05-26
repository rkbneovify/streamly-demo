package com.adaptivemedia.streamlytv.streamly.retrofit_clients;

import com.adaptivemedia.streamlytv.streamly.QRCode.HotelQRModel;
import com.adaptivemedia.streamlytv.streamly.QRCode.QRScanStatusModel;
import com.adaptivemedia.streamlytv.streamly.QRCode.QrModel;
import com.adaptivemedia.streamlytv.streamly.channelschedule.ChannelScheduleModel;
import com.adaptivemedia.streamlytv.streamly.channelsschedulepaginate.ChannelSchedulePaginateModel;
import com.adaptivemedia.streamlytv.streamly.epg.ChannelGenre;
import com.adaptivemedia.streamlytv.streamly.epg.ChannellistModel;
import com.adaptivemedia.streamlytv.streamly.log_in.PersonalDeviceNameDataModel;
import com.adaptivemedia.streamlytv.streamly.log_in.QrVisibility;
import com.adaptivemedia.streamlytv.streamly.model.CheckDeviceType;
import com.adaptivemedia.streamlytv.streamly.model.GetIp;
import com.adaptivemedia.streamlytv.streamly.new_log_in.NewLogInModel;
import com.adaptivemedia.streamlytv.streamly.new_log_in.new_hotel_response.NewHotelRoomResponse;
import com.adaptivemedia.streamlytv.streamly.playervideo.drm.DrmKeyModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.geofence.GeofenceModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpDataModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.ip.IpModel;
import com.adaptivemedia.streamlytv.streamly.playervideo.zipcode.OutstationLocationModel;
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.DeviceSignOutResponse;
import com.adaptivemedia.streamlytv.streamly.settings.privacy.PrivacyPolicyModel;
import com.adaptivemedia.streamlytv.streamly.settings.profile.profilemodel.ProfileModel;
import com.adaptivemedia.streamlytv.streamly.settings.registerdevice.ConnectedDeviceModel;
import com.adaptivemedia.streamlytv.streamly.settings.support.SupportModel;
import com.adaptivemedia.streamlytv.streamly.termsofuses.TermsModel;
import com.adaptivemedia.streamlytv.streamly.user_room.select_room_response.SelectedHotelRoomResponse;
import com.adaptivemedia.streamlytv.streamly.utilities.NetworkKeys;
import com.adaptivemedia.streamlytv.streamly.log_in.LogInModel;

import java.util.Map;

import io.reactivex.Observable;
import retrofit2.Call;
import retrofit2.http.FieldMap;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.Headers;
import retrofit2.http.POST;
import retrofit2.http.PUT;
import retrofit2.http.Path;
import retrofit2.http.Query;

public interface ApiInterface {

    @Headers("Accept: application/json")
    @POST("/api/auth/login")
    Call<NewLogInModel> postLogInCall(@Header(NetworkKeys.KEY_USER_AGENT) String userAgent,@Query(NetworkKeys.KEY_EMAIL) String user_email, @Query(NetworkKeys.KEY_PASS) String user_password, @Query(NetworkKeys.KEY_DEVICE_ID) String deviceid);


    @Headers("Accept: application/json")
    @POST("/api/auth/login-with-passcode")
    Call<NewLogInModel> postLoginUsingPasscode(@Header(NetworkKeys.KEY_USER_AGENT) String userAgeny,@Query(NetworkKeys.KEY_PASSCODE) String passcode, @Query(NetworkKeys.KEY_DEVICE_ID) String deviceid);


    @Headers("Accept: application/json")
    @POST("/api/auth/sync-session-with-room")
    Call<SelectedHotelRoomResponse> postHotelRoom(@Query(NetworkKeys.KEY_AUTH) String authorization_token, @Query(NetworkKeys.ROOM_ID) Integer roomId);


    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @POST("/api/auth/get-hotelrooms")
    Call<NewHotelRoomResponse> postNewHotelRoom(@Query(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @POST("/api/auth/logout")
    Call<LogInModel> postLogOutCall(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @POST("/api/auth/same-device-id-logout")
    Call<DeviceSignOutResponse> postDeviceLogOutCall(@Query(NetworkKeys.KEY_ID) String deviceid);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET("/api/auth/profile")
    Call<ProfileModel> getUserProfile(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET("/api/auth/connecteddevice")
    Call<ConnectedDeviceModel> getConnectedDevice(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET("/api/auth/check")
    Call<CheckDeviceType> getDeviceType(@Header(NetworkKeys.KEY_USER_AGENT) String userAgent);


    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET(NetworkKeys.KEY_SUPPORT)
    Call<SupportModel> getSupportNumbers();

    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
    @GET("/api/auth/privacypol")
    Call<PrivacyPolicyModel> getPoliciesText(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @GET("/api/auth/channellist")
    Call<ChannellistModel> getChannelList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @GET("/api/auth/channelgenre")
    Call<ChannelGenre> getChannelGenre(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @GET("/api/auth/my_recents")
    Call<ChannellistModel> getRecentList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @POST("/api/auth/addtorecent")
    Call<ChannellistModel> postRecentList(@Header(NetworkKeys.KEY_AUTH) String authorization_token,@Query(NetworkKeys.KEY_CHANNELIDS) String channel_ids);

//    @Headers("User-Agent: iphone")
    @GET("/api/auth/channellist")
    Observable<ChannellistModel> getChannelList2(@Header(NetworkKeys.KEY_AUTH) String authorization_token);


    /*@Headers("Accept: application/json")
    @GET("/api/auth/channeldayschdeule")//api/auth/channeldayschdeule
    Call<ChannelScheduleModel> gotScheduleList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);
*/

//    @Headers("User-Agent: iphone")
    @GET("/api/auth/channeldayschdeule")//api/auth/channeldayschdeule
    Observable<com.adaptivemedia.streamlytv.streamly.channelschedule.ChannelScheduleModel> getScheduleList(@Header(NetworkKeys.KEY_ACCEPT) String applicationJson, @Header(NetworkKeys.KEY_AUTH) String authorization_token);

    /**
     * this function will run for genre channels that's why we call channeldayschdeule api with genre
     * */
    @Headers("Accept: application/json")
    @GET("/api/auth/channeldayschdeuletzwithoffset")
    Observable<com.adaptivemedia.streamlytv.streamly.channelschedule.ChannelScheduleModel> getScheduleListWithTimeZone(@Query("hoursoffset")String timeZone, @Query("genre")String genre, @Header(NetworkKeys.KEY_ACCEPT) String applicationJson, @Header(NetworkKeys.KEY_AUTH) String authorization_token);

    /**
     * this function will run for all channels that's why we call channeldayschdeule api without genre
     * */
    @Headers("Accept: application/json")
    @GET("/api/auth/channeldayschdeuletzwithoffset")
    Observable<ChannelScheduleModel> getScheduleListWithoutGenre(@Query("hoursoffset")String timeZone, @Header(NetworkKeys.KEY_ACCEPT) String applicationJson, @Header(NetworkKeys.KEY_AUTH) String authorization_token);

    //    @Headers("User-Agent: iphone")
    @GET("/api/auth/channeldayschdeulepaginate")//api/auth/channeldayschdeule
    Observable<ChannelSchedulePaginateModel> getScheduleListPaginate(@Query(NetworkKeys.PAGE)int page_number, @Header(NetworkKeys.KEY_ACCEPT) String applicationJson, @Header(NetworkKeys.KEY_AUTH) String authorization_token);

    /*@Headers("Accept: application/json")ai call
    @GET("channeldayschdeule")//api/auth/channeldayschdeule
    Observable<com.streamly.tv.channelschedule.ChannelScheduleModel> getScheduleList(@Header(NetworkKeys.KEY_AUTH) String authorization_token);*/


    @Headers("Accept: application/json")
    @GET("/api/auth/channelgenre")//api/auth/channeldayschdeule
    Observable<com.adaptivemedia.streamlytv.streamly.epg.channelgenre.ChannelGenreModel> getChannelGenre2(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

//    @Headers("Accept: application/json")
//    @GET("/api/auth/channelgenre")
//    Call<ChannelGenreModel> getChannelGenre(@Header(NetworkKeys.KEY_AUTH) String authorization_token);


    @Headers("Accept: application/json")
    @GET("/api/auth/termofuse")
    Call<TermsModel> getTermofuse();


    @Headers("Accept: application/json")
    @GET("/api/auth/getdrmkey")
    Call<DrmKeyModel> getDrmKey(@Header(NetworkKeys.KEY_AUTH) String authorization_token);

    @Headers("Accept: application/json")
    @GET("/api/qrcode")
    Call<QrModel> getQrCode();

    @Headers("Accept: application/json")
    @POST("/api/login/create/qrcode")
    Call<HotelQRModel> getHotelQrCode();

    @Headers("Accept: application/json")
    @POST("/api/login/scan/qrcode")
    Call<QRScanStatusModel> getQrCodeScanStatus(@Query(NetworkKeys.KEY) String key);

    @Headers("Accept: application/json")
    @POST("/api/login/entry/login")
    Call<NewLogInModel> getHotelLogin(@Header(NetworkKeys.KEY_USER_AGENT) String userAgent,@Query(NetworkKeys.KEY) String key, @Query(NetworkKeys.KEY_DEVICE_ID) String deviceID);

//    http://ip-api.com/json/23.119.88.145
//    http://ip-api.com/json/['your ip']

    @Headers("Accept: application/json")
    @GET("/{your_ip}/json/")
    Call<IpDataModel> getLatLong(@Path("your_ip") String your_ip);

//    https://streamlytv.com/api/auth/geofence"

    @Headers("Accept: application/json")
    @POST("/api/auth/geofence")
    @FormUrlEncoded
    Call<GeofenceModel> postGeofenceCall(@Header(NetworkKeys.KEY_AUTH) String authorization_token,@FieldMap Map<String,Double> params);


    @Headers("Accept: application/json")
    @POST("/api/auth/outsidelocationplay")
    @FormUrlEncoded
    Call<OutstationLocationModel> postOutstationlocationplay(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @FieldMap Map<String,String> params);

    @Headers("Accept: application/json")
    @PUT("/api/auth/personaldevicename/{session_id}")
    @FormUrlEncoded
    Call<PersonalDeviceNameDataModel> setPersonalDeviceName(@Header(NetworkKeys.KEY_AUTH) String authorization_token, @Path(value = "session_id", encoded = false) String session_id ,@FieldMap(encoded = false) Map<String,String> params);

    @GET("/api/auth/qrvisibility")
    Call<QrVisibility> getVisibility();


    @GET("/get_json.php")
    Call<GetIp> getDeviceIpAddress();

}
