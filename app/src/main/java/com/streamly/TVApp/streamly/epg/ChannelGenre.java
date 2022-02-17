package com.streamly.TVApp.streamly.epg;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

import java.util.List;


@SuppressWarnings("unused")
public class ChannelGenre {

    @SerializedName("success")
    @Expose
    private Boolean mSuccess;
    @SerializedName("allGenre")
    @Expose
    public List<String> genre = null;
    @SerializedName("message")
    @Expose
    private String mMessage;
    @SerializedName("response_code")
    @Expose
    private Long mResponseCode;

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

    public List<String> getGenre() {
        return genre;
    }

    public void setGenre(List genre) {
        this.genre = genre;
    }
}
