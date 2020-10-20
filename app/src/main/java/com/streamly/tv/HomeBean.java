package com.streamly.tv;

import com.google.gson.annotations.SerializedName;
import com.streamly.tv.channelschedule.Program;

import org.threeten.bp.Instant;

import java.util.List;

public class HomeBean {

    @SerializedName("CallSign")
    public String headerTitle;
    @SerializedName("end")
    public Instant end_time;
    @SerializedName("start")
    public Instant start_time;
    @SerializedName("title")
    public String title;

    public HomeBean() {
    }

    public HomeBean(String headerTitle, Instant end_time, Instant start_time, String title) {
        this.headerTitle = headerTitle;
        this.end_time = end_time;
        this.start_time = start_time;
        this.title = title;
    }

    public String getHeaderTitle() {
        return headerTitle;
    }

    public void setHeaderTitle(String headerTitle) {
        this.headerTitle = headerTitle;
    }

    public Instant getEnd_time() {
        return end_time;
    }

    public void setEnd_time(Instant end_time) {
        this.end_time = end_time;
    }

    public Instant getStart_time() {
        return start_time;
    }

    public void setStart_time(Instant start_time) {
        this.start_time = start_time;
    }

/*  public Long getEnd_time() {
        return end_time;
    }

    public void setEnd_time(Long end_time) {
        this.end_time = end_time;
    }

    public Long getStart_time() {
        return start_time;
    }

    public void setStart_time(Long start_time) {
        this.start_time = start_time;
    }*/

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }
}
