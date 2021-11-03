
package com.streamly.TVApp.streamly.model;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;



public class CheckDeviceType {

    @SerializedName("success")
    @Expose
    private Boolean success;
    @SerializedName("extra")
    @Expose
    private String extra;
    @SerializedName("extra2")
    @Expose
    private Extra2 extra2;
    @SerializedName("data")
    @Expose
    private String data;
    @SerializedName("message")
    @Expose
    private String message;
    @SerializedName("response_code")
    @Expose
    private Integer responseCode;

    public Boolean getSuccess() {
        return success;
    }

    public void setSuccess(Boolean success) {
        this.success = success;
    }

    public String getExtra() {
        return extra;
    }

    public void setExtra(String extra) {
        this.extra = extra;
    }

    public Extra2 getExtra2() {
        return extra2;
    }

    public void setExtra2(Extra2 extra2) {
        this.extra2 = extra2;
    }

    public String getData() {
        return data;
    }

    public void setData(String data) {
        this.data = data;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public Integer getResponseCode() {
        return responseCode;
    }

    public void setResponseCode(Integer responseCode) {
        this.responseCode = responseCode;
    }

}
