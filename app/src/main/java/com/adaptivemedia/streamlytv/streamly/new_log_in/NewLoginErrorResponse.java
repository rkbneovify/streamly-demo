
package com.adaptivemedia.streamlytv.streamly.new_log_in;


import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.Loggedindeviceinfolist;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class NewLoginErrorResponse {

    @SerializedName("success")
    @Expose
    private Boolean success;
    @SerializedName("response_code")
    @Expose
    private Integer responseCode;
    @SerializedName("message")
    @Expose
    private String message;
    @SerializedName("data")
    @Expose
    private String data;
    @SerializedName("loggedindeviceinfolist")
    @Expose
    private Loggedindeviceinfolist loggedindeviceinfolist;

    public Boolean getSuccess() {
        return success;
    }

    public void setSuccess(Boolean success) {
        this.success = success;
    }

    public Integer getResponseCode() {
        return responseCode;
    }

    public void setResponseCode(Integer responseCode) {
        this.responseCode = responseCode;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getData() {
        return data;
    }

    public void setData(String data) {
        this.data = data;
    }

    public Loggedindeviceinfolist getLoggedindeviceinfolist() {
        return loggedindeviceinfolist;
    }

    public void setLoggedindeviceinfolist(Loggedindeviceinfolist loggedindeviceinfolist) {
        this.loggedindeviceinfolist = loggedindeviceinfolist;
    }
}
