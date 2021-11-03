package com.streamly.TVApp.streamly.settings.registerdevice;

public class RegisterDeviceType {

    private String deviceModel;
    private String deviceId;
    private String deviceImageUrl;


    public RegisterDeviceType() {
    }

    public RegisterDeviceType(String deviceModel, String deviceId,String deviceImageUrl) {
        this.deviceModel = deviceModel;
        this.deviceId = deviceId;
        this.deviceImageUrl = deviceImageUrl;
    }

    public String getDeviceImageUrl() {
        return deviceImageUrl;
    }

    public void setDeviceImageUrl(String deviceImageUrl) {
        this.deviceImageUrl = deviceImageUrl;
    }

    public String getDeviceModel() {
        return deviceModel;
    }

    public void setDeviceModel(String deviceModel) {
        this.deviceModel = deviceModel;
    }

    public String getDeviceId() {
        return deviceId;
    }

    public void setDeviceId(String deviceId) {
        this.deviceId = deviceId;
    }
}
