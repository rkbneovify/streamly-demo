package com.streamly.TVApp.streamly.channelschedule;

import java.util.List;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


@SuppressWarnings("unused")
public class Datum {

//    @SerializedName("CallSign")
    @SerializedName("callSign")
    @Expose
    private String mCallSign;

    @SerializedName("url")
    @Expose
    public String channelUrl;

    @SerializedName("logo")
    @Expose
    public String logo;

    @SerializedName("programs")
    @Expose
    private List<Program> mPrograms;

    @SerializedName("StationName")
    @Expose
    private String mStationName;

    @SerializedName("id")
    @Expose
    private String mId;

    public String getmId() {
        return mId;
    }

    public void setmId(String mId) {
        this.mId = mId;
    }

    public String getCallSign() {
        return mCallSign;
    }

    public void setCallSign(String callSign) {
        mCallSign = callSign;
    }

    public List<Program> getPrograms() {
        return mPrograms;
    }

    public void setPrograms(List<Program> programs) {
        mPrograms = programs;
    }

    public String getStationName() {
        return mStationName;
    }

    public void setStationName(String stationName) {
        mStationName = stationName;
    }

    public String getChannelUrl() {
        return channelUrl;
    }

    public void setChannelUrl(String channelUrl) {
        this.channelUrl = channelUrl;
    }

    public String getLogo() {
        return logo;
    }

    public void setLogo(String logo) {
        this.logo = logo;
    }

    public List<Program> getmPrograms() {
        return mPrograms;
    }

    public void setmPrograms(List<Program> mPrograms) {
        this.mPrograms = mPrograms;
    }

    public String getmStationName() {
        return mStationName;
    }

    public void setmStationName(String mStationName) {
        this.mStationName = mStationName;
    }
}
