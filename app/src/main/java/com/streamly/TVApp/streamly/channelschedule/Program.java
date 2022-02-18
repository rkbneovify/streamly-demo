package com.streamly.TVApp.streamly.channelschedule;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


@SuppressWarnings("unused")
public class Program {

    @SerializedName("end")
    @Expose
    private long endTz;

    @SerializedName("start")
    @Expose
    private long startTz;

    @SerializedName("title")
    @Expose
    private String mTitle;

    @SerializedName("description")
    @Expose
    private String mDescription;

    public String getTitle() {
        return mTitle;
    }

    public void setTitle(String title) {
        mTitle = title;
    }

    public String getDescription() {
        return mDescription;
    }

    public void setDescription(String description) {
        this.mDescription = description;
    }

    public Long getEndTz() {
        return endTz;
    }

    public void setEndTz(Long endTz) {
        this.endTz = endTz;
    }

    public Long getStartTz() {
        return startTz;
    }

    public void setStartTz(Long startTz) {
        this.startTz = startTz;
    }
}
