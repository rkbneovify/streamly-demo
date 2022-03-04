
package com.adaptivemedia.streamlytv.streamly.new_log_in.new_hotel_response;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class Room {

    @SerializedName("id")
    @Expose
    private Integer id;
    @SerializedName("user_id")
    @Expose
    private Integer userId;
    @SerializedName("hotel_room")
    @Expose
    private String hotelRoom;
    @SerializedName("session_id")
    @Expose
    private Object sessionId;
    @SerializedName("created_on")
    @Expose
    private String createdOn;
    @SerializedName("modified_on")
    @Expose
    private String modifiedOn;

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public Integer getUserId() {
        return userId;
    }

    public void setUserId(Integer userId) {
        this.userId = userId;
    }

    public String getHotelRoom() {
        return hotelRoom;
    }

    public void setHotelRoom(String hotelRoom) {
        this.hotelRoom = hotelRoom;
    }

    public Object getSessionId() {
        return sessionId;
    }

    public void setSessionId(Object sessionId) {
        this.sessionId = sessionId;
    }

    public String getCreatedOn() {
        return createdOn;
    }

    public void setCreatedOn(String createdOn) {
        this.createdOn = createdOn;
    }

    public String getModifiedOn() {
        return modifiedOn;
    }

    public void setModifiedOn(String modifiedOn) {
        this.modifiedOn = modifiedOn;
    }

}
