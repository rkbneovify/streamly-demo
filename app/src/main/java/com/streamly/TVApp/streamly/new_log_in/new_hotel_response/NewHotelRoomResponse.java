
package com.streamly.TVApp.streamly.new_log_in.new_hotel_response;

import java.util.List;
import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class NewHotelRoomResponse {

    @SerializedName("success")
    @Expose
    private Boolean success;
    @SerializedName("message")
    @Expose
    private String message;
    @SerializedName("response_code")
    @Expose
    private Integer responseCode;
    @SerializedName("data")
    @Expose
    private List<Room> data = null;

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

    public Integer getResponseCode() {
        return responseCode;
    }

    public void setResponseCode(Integer responseCode) {
        this.responseCode = responseCode;
    }

    public List<Room> getData() {
        return data;
    }

    public void setData(List<Room> data) {
        this.data = data;
    }

}
