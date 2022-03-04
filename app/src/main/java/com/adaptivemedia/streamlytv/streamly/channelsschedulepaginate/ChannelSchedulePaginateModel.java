
package com.adaptivemedia.streamlytv.streamly.channelsschedulepaginate;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class ChannelSchedulePaginateModel {

    @SerializedName("success")
    @Expose
    private Boolean success;
    @SerializedName("date")
    @Expose
    private String date;
    @SerializedName("data")
    @Expose
    private Data data;
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

    public String getDate() {
        return date;
    }

    public void setDate(String date) {
        this.date = date;
    }

    public Data getData() {
        return data;
    }

    public void setData(Data data) {
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
