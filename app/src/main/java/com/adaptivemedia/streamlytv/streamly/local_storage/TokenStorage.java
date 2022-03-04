package com.adaptivemedia.streamlytv.streamly.local_storage;

import android.content.Context;
import android.content.SharedPreferences;

import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;


public class TokenStorage {

    private TokenStorage() {
        throw new IllegalStateException("Utility class");
    }

    private final static String FILE_NAME = ConstantUtility.PREF_DATA;

    public static String readSharedToken(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(access_token, access_token_Value);
    }

    public static void savedSharedToken(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(access_token, access_token_Value);
        editor.apply();
    }

    public static String readSharedTokenPasscode(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(access_token, access_token_Value);
    }

    public static void savePersonalDeviceName(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(access_token, access_token_Value);
        editor.apply();
    }

    public static String readPersonalDeviceName(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(access_token, access_token_Value);
    }

    public static void savedSharedTokenPassCode(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(access_token, access_token_Value);
        editor.apply();
    }

    public static String readSessionId(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(access_token, access_token_Value);
    }

    public static void saveSessionId(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(access_token, access_token_Value);
        editor.apply();
    }

    //    personal device id ------------------
    public static String readPersonalDeviceID(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(access_token, access_token_Value);
    }

    public static void savePersonalDeviceID(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(access_token, access_token_Value);
        editor.apply();
    }

    //    device Type----------------------
    public static String readDeviceType(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(access_token, access_token_Value);
    }

    public static void saveDevicetype(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(access_token, access_token_Value);
        editor.apply();
    }

    //    personal Device Popup ----------------
    public static boolean readPersonalDeviceNamePopup(Context context, String access_token, boolean access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getBoolean(access_token, access_token_Value);
    }

    public static void savePersonalDeviceNamePopup(Context context, String access_token, boolean access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean(access_token, access_token_Value);
        editor.apply();
    }

    //    user role ----------------
    public static String readUserRole(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(access_token, access_token_Value);
    }

    public static void saveUserRole(Context context, String access_token, String access_token_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(access_token, access_token_Value);
        editor.apply();
    }


    public static void savedRoomList(Context context, String roomListKey, String roomListValue) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(roomListKey, String.valueOf(roomListValue));
        editor.apply();
    }

    public static String readRoomList(Context context, String roomListKey) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(roomListKey, roomListKey);
    }

    public static void clearSharedToken(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.clear();
        editor.apply();

    }

    public static void savedHotelKey(Context context, String key, String keyVale) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(key, keyVale);
        editor.apply();
    }

    public static String readHotelKey(Context context, String key, String keyVale) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(key, keyVale);
    }
}
