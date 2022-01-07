package com.streamly.TVApp.streamly.settings.registerdevice;

public class RegisterDeviceType {

    private String deviceModel;
    private String deviceId;
    private String personalDeviceName;
    private String deviceImageUrl;


    public RegisterDeviceType() {
    }

    public RegisterDeviceType(String deviceModel, String deviceId,String deviceImageUrl, String personalDeviceName) {
        this.deviceModel = deviceModel;
        this.deviceId = deviceId;
        this.deviceImageUrl = deviceImageUrl;
        this.personalDeviceName = personalDeviceName;
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

    public String getPersonalDeviceName() {
        return personalDeviceName;
    }

    public void setPersonalDeviceName(String personalDeviceName) {
        this.personalDeviceName = personalDeviceName;
    }
}
