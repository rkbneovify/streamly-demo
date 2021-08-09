
package com.streamly.TVApp.streamly.settings.device_signout;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class DeviceSignOutResponse {

    @SerializedName("response_code")
    @Expose
    private Integer responseCode;
    @SerializedName("success")
    @Expose
    private Boolean success;
    @SerializedName("message")
    @Expose
    private String message;

    public Integer getResponseCode() {
        return responseCode;
    }

    public void setResponseCode(Integer responseCode) {
        this.responseCode = responseCode;
    }

    public Boolean getSuccess() {
        return success;
    }

    public void setSuccess(Boolean success) {
        this.success = success;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

}
