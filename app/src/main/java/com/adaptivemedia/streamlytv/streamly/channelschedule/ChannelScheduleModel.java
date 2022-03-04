
package com.adaptivemedia.streamlytv.streamly.channelschedule;

import java.util.List;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

/*
* "success": true,
  "date": "2021-12-13",
  "data": {
    "current_page": 2,
    "data": [
      {
        "callSign": "CINHD",
        "id": 44,
        "stationName": "Crime & Investigation Network HD",
        "url": "https://channelstore2.streamlytv.com/Crime_Investigation/index.mpd",
        "logo": "http://127.0.0.1:8000/uploads/channellogo/CIN.png",
        "programs": [
          {
            "title": "Manhunters: Fugitive Task Force",
            "description": "A fugitive hides out in the bustle and chaos of Chinatown.",
            "start": 1639333800,
            "end": 1639335600
          },
          {
            "title": "Manhunters: Fugitive Task Force",
            "description": "When law enforcement need`s to catch the most dangerous fugitives..they call the Manhunters.",
            "start": 1639335600,
            "end": 1639337400
          },
          * */
@SuppressWarnings("unused")
public class ChannelScheduleModel {

    @SerializedName("data")
    @Expose
    private List<Datum> mData;

    @SerializedName("date")
    @Expose
    private String mDate;

    @SerializedName("message")
    @Expose
    private String mMessage;

    @SerializedName("response_code")
    @Expose
    private Long mResponseCode;

    @SerializedName("success")
    @Expose
    private Boolean mSuccess;

    public List<Datum> getData() {
        return mData;
    }

    public void setData(List<Datum> data) {
        mData = data;
    }

    public String getDate() {
        return mDate;
    }

    public void setDate(String date) {
        mDate = date;
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
