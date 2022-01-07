package com.streamly.TVApp.streamly.settings.registerdevice;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

@SuppressWarnings("unused")
public class Datum {
    @SerializedName("connected_devices")
    @Expose
    private Integer connectedDevices;
    @SerializedName("device_id")
    @Expose
    private String deviceId;
    @SerializedName("device_name")
    @Expose
    private String deviceName;
    @SerializedName("session_id")
    @Expose
    private String sessionId;
    @SerializedName("hotel_room")
    @Expose
    private String hotelRoom;
    @SerializedName("device_img")
    @Expose
    private String deviceImg;
    @SerializedName("personaldevicename")
    @Expose
    private String personaldevicename;

    public Integer getConnectedDevices() {
        return connectedDevices;
    }

    public void setConnectedDevices(Integer connectedDevices) {
        this.connectedDevices = connectedDevices;
    }

    public String getDeviceId() {
        return deviceId;
    }

    public void setDeviceId(String deviceId) {
        this.deviceId = deviceId;
    }

    public String getDeviceName() {
        return deviceName;
    }

    public void setDeviceName(String deviceName) {
        this.deviceName = deviceName;
    }

    public String getSessionId() {
        return sessionId;
    }

    public void setSessionId(String sessionId) {
        this.sessionId = sessionId;
    }

    public String getHotelRoom() {
        return hotelRoom;
    }

    public void setHotelRoom(String hotelRoom) {
        this.hotelRoom = hotelRoom;
    }

    public String getDeviceImg() {
        return deviceImg;
    }

    public void setDeviceImg(String deviceImg) {
        this.deviceImg = deviceImg;
    }

    public String getPersonaldevicename() {
        return personaldevicename;
    }

    public void setPersonaldevicename(String personaldevicename) {
        this.personaldevicename = personaldevicename;
    }
}
