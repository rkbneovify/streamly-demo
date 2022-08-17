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

    public static String readRoom(Context context, String room, String room_value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(room, room_value);
    }

    public static void savedRoom(Context context, String room, String room_value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(room, room_value);
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

    //    user home timezone ----------------
    public static String readHomeTimeZoneOffset(Context context, String home_time_zone, String home_time_zone_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(home_time_zone, home_time_zone_Value);
    }

    public static void saveHomeTimeZoneOffset(Context context, String home_time_zone, String home_time_zone_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(home_time_zone, home_time_zone_Value);
        editor.apply();
    }

    //    user home timezone ----------------
    public static String readCurrentTimeZoneOffset(Context context, String current_time_zone, String current_time_zone_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getString(current_time_zone, current_time_zone_Value);
    }

    public static void saveCurrentTimeZoneOffset(Context context, String current_time_zone, String current_time_zone_Value) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString(current_time_zone, current_time_zone_Value);
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

    public static void savedVisible(Context context, String access_token, boolean savedVisibleKey) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putBoolean( access_token,savedVisibleKey);
        editor.apply();
    }

    public static Boolean readBarcodeVisible(Context context, String access_token) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        return sharedPreferences.getBoolean(access_token, false);
    }

    public static void saveHomeZipCodeName(Context context, Double db_Lat, Double db_Lon) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        SharedPreferences.Editor editor = sharedPreferences.edit();
        editor.putString("Lat",db_Lat.toString());
        editor.putString("Lon", db_Lon.toString());
        editor.apply();
    }

    public static String[] readHomeZipCodeName(Context context) {
        SharedPreferences sharedPreferences = context.getSharedPreferences(FILE_NAME, Context.MODE_PRIVATE);
        String la = sharedPreferences.getString("Lat","");
        String lo = sharedPreferences.getString("Lon","");

        String[] LatLong = new String[]{la,lo};
        return LatLong;
    }
}
