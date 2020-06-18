
package com.streamly.tv.settings.support;

import java.util.List;
import com.google.gson.annotations.SerializedName;


@SuppressWarnings("unused")
public class SupportModel {

    @SerializedName("data")
    private List<String> mData;
    @SerializedName("message")
    private String mMessage;
    @SerializedName("response_code")
    private Long mResponseCode;
    @SerializedName("success")
    private Boolean mSuccess;

    public List<String> getData() {
        return mData;
    }

    public void setData(List<String> data) {
        mData = data;
    }

    public String getMessage() {
        return mMessage;
    }

    public void setMessage(String message) {
        mMessage = message;
    }

    public Long getResponseCode() {
        return mResponseCode;
    }

    public void setResponseCode(Long responseCode) {
        mResponseCode = responseCode;
    }

    public Boolean getSuccess() {
        return mSuccess;
    }

    public void setSuccess(Boolean success) {
        mSuccess = success;
    }

}
