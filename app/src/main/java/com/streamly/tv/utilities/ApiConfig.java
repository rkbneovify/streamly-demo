package com.streamly.tv.utilities;

public class ApiConfig {

    private ApiConfig() {
        throw new IllegalStateException("Utility class");
    }

//    public static final String WEB_URL="https://ac3a8022.ngrok.io";
//    public static final String WEB_URL="http://100.26.190.92";
    public static final String WEB_URL="http://streamlytv.com";
    public static final String BASE_URL=WEB_URL+"/api/auth/";

}
