package com.streamly.tv.retrofit_clients;

import com.streamly.tv.log_in.LogInModel;

import retrofit2.Call;
import retrofit2.http.Header;
import retrofit2.http.POST;
import retrofit2.http.Query;

public interface ApiInterface {

    @POST("login")
    Call<LogInModel> postLogInCall(@Query("email") String user_email, @Query("password") String user_password);


    @POST("logout")
    Call<LogInModel> postLogOutCall(@Header("Authorization") String authorization_token, @Header("Accept") String accept);

//    @Headers(NetworkKeys.KEY_CONTENT_TYPE)
//    @POST("login.sf")
//    Call<SignInModel> postLogInCall(@Body JsonObject jsonbody);



}
