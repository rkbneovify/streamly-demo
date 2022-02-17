package com.streamly.TVApp.streamly.epg;

import java.util.ArrayList;
import java.util.List;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


@SuppressWarnings("unused")
public class ChannellistModel {

    @SerializedName("success")
    @Expose
    private Boolean mSuccess;
//    @SerializedName("allGenre")
//    @Expose
//    public List<String> genre = null;

    @SerializedName("data")
    @Expose
    private List<Datum> mData;
    @SerializedName("message")
    @Expose
    private String mMessage;
    @SerializedName("response_code")
    @Expose
    private Long mResponseCode;

    public List<Datum> getData() {
        return mData;
    }

    public void setData(List<Datum> data) {
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

//    public List<String> getGenre() {
//        return genre;
//    }
//
//    public void setGenre(List genre) {
//        this.genre = genre;
//    }
}
