package com.adaptivemedia.streamlytv.streamly.utilities;

import org.jetbrains.annotations.Nullable;

public class ConstantUtility {


    public static final String STREAM_URL="streamUrl";
    @Nullable
//    public static final String CHANNELLIST=ApiConfig.BASE_URL+"channellist";
    public static final String CHANNELLIST=ApiConfig.BASE_URL+"channelgenre";
    @Nullable
    public static final String CHANNELDAYSSCHEDULE=ApiConfig.BASE_URL+"channeldayschdeule";
    public static final String IPNOTVALID ="IP is not valid for getting coordinate" ;

    private ConstantUtility() {
        throw new IllegalStateException("Utility class");
    }
    // Global variable used to store network state
//    public static boolean isNetworkConnected = false;
    public static boolean isNetworkConnected = true;

    public static final String PREF_DATA="pref_data";
    public static final String LOADING ="Loading..." ;
    public static final String AUTH_TOKEN ="token";
    public static final String AUTH_TOKEN_PASSCODE ="token_passcode";
    public static final String SESSION_ID ="sessionid";
    public static final String PERSONAL_DEVICE_ID ="personaldeviceid";
    public static final String DEVICE_TYPE ="device_type";
    public static final String PERSONAL_DEVICE_POPUP ="personaldevicename_popup";
    public static final String USER_ROLE ="user_role";
    public static final String PERSONAL_DEVICE_NAME ="personal_device_name";
    public static final String USER_ID ="user_id";
    public static final String KEY ="key";
    public static final String ROOM_LIST ="room_list";
    public final static String INCORRECT_CREDENTIALS ="Incorrect Username or Password";
    public static final String SERVER_ISSUE ="Server issue, try after sometime";
    public static final String FILL_EMAIL = "Fill Email ID";
    public static final String FILL_PASSCODE = "Fill Passcode";
    public static final String FILL_PASSW ="Fill Password";
    public static final String WRONG_EMAIL = "Wrong email, Please check your email Id";
    public static final String SESSION_EXPIRED ="Session expired, Please re-login";
    public static final String MAX_USER_LOGIN="Your account is in use on 5 devices. Please stop playing on other devices to continue";

    public static final String CHECK_INTERNET_CONNECTION = "NO INTERNET, Please Check Your Internet Connection.";
    public static final String EMAIL_REGEX ="^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$" ;
}


