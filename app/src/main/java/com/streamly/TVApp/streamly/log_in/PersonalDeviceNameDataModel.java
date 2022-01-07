package com.streamly.TVApp.streamly.log_in;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

/*
* {
  "success": true,
  "data": {
    "devicename": "My device",
    "sessionid": "1KzJgOVJOe"
  },
  "message": "Device name Updated Successfully.",
  "response_code": 200
}
* */
public class PersonalDeviceNameDataModel {

    @SerializedName("success")
    @Expose
    private Boolean success;

    @SerializedName("data")
    @Expose
    private Data data;

    @SerializedName("message")
    @Expose
    private String message;

    @SerializedName("response_code")
    @Expose
    private int response_code;

    class Data {
        @SerializedName("devicename")
        @Expose
        private String devicename;

        @SerializedName("sessionid")
        @Expose
        private String sessionid;
    }
}
