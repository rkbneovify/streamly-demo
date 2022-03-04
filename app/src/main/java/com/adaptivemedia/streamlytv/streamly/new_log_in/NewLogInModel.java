
package com.adaptivemedia.streamlytv.streamly.new_log_in;

import java.util.List;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;



public class NewLogInModel {

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
    private String data;
    @SerializedName("connected_device")
    @Expose
    private String connectedDevice;
    @SerializedName("access_token")
    @Expose
    private String accessToken;

    @SerializedName("sessionid")
    @Expose
    private String sessionid;

    @SerializedName("personaldeviceid")
    @Expose
    private String personalDeviceId;

    @SerializedName("device_type")
    @Expose
    private String deviceType;

    @SerializedName("personaldevicename_popup")
    @Expose
    private boolean personaldevicename_popup;

    @SerializedName("token_type")
    @Expose
    private String tokenType;
    @SerializedName("expires_in")
    @Expose
    private Integer expiresIn;
    @SerializedName("device_info")
    @Expose
    private String deviceInfo;
    @SerializedName("user")
    @Expose
    private User user;
    @SerializedName("userrole")
    @Expose
    private String userRole;

    @SerializedName("termandcondition")
    @Expose
    private Boolean termandcondition;
    @SerializedName("rooms")
    @Expose
    private List<Room> rooms = null;

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

    public String getData() {
        return data;
    }

    public void setData(String data) {
        this.data = data;
    }

    public String getConnectedDevice() {
        return connectedDevice;
    }

    public void setConnectedDevice(String connectedDevice) {
        this.connectedDevice = connectedDevice;
    }

    public String getAccessToken() {
        return accessToken;
    }

    public void setAccessToken(String accessToken) {
        this.accessToken = accessToken;
    }

    public String getTokenType() {
        return tokenType;
    }

    public void setTokenType(String tokenType) {
        this.tokenType = tokenType;
    }

    public Integer getExpiresIn() {
        return expiresIn;
    }

    public void setExpiresIn(Integer expiresIn) {
        this.expiresIn = expiresIn;
    }

    public String getDeviceInfo() {
        return deviceInfo;
    }

    public void setDeviceInfo(String deviceInfo) {
        this.deviceInfo = deviceInfo;
    }

    public User getUser() {
        return user;
    }

    public void setUser(User user) {
        this.user = user;
    }

    public Boolean getTermandcondition() {
        return termandcondition;
    }

    public void setTermandcondition(Boolean termandcondition) {
        this.termandcondition = termandcondition;
    }

    public String getUserRole() {
        return userRole;
    }

    public void setUserRole(String userRole) {
        this.userRole = userRole;
    }

    public List<Room> getRooms() {
        return rooms;
    }

    public void setRooms(List<Room> rooms) {
        this.rooms = rooms;
    }

    public String getSessionid() {
        return sessionid;
    }

    public void setSessionid(String sessionid) {
        this.sessionid = sessionid;
    }

    public String getPersonalDeviceId() {
        return personalDeviceId;
    }

    public void setPersonalDeviceId(String personalDeviceId) {
        this.personalDeviceId = personalDeviceId;
    }

    public String getDeviceType() {
        return deviceType;
    }

    public void setDeviceType(String deviceType) {
        this.deviceType = deviceType;
    }

    public boolean getPersonaldevicename_popup() {
        return personaldevicename_popup;
    }

    public void setPersonaldevicename_popup(boolean personaldevicename_popup) {
        this.personaldevicename_popup = personaldevicename_popup;
    }
}
