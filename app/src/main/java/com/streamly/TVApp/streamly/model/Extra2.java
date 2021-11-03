
package com.streamly.TVApp.streamly.model;


import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;


public class Extra2 {

    @SerializedName("device")
    @Expose
    private String device;
    @SerializedName("classes")
    @Expose
    private String classes;
    @SerializedName("platform")
    @Expose
    private String platform;
    @SerializedName("useragent")
    @Expose
    private String useragent;

    public String getDevice() {
        return device;
    }

    public void setDevice(String device) {
        this.device = device;
    }

    public String getClasses() {
        return classes;
    }

    public void setClasses(String classes) {
        this.classes = classes;
    }

    public String getPlatform() {
        return platform;
    }

    public void setPlatform(String platform) {
        this.platform = platform;
    }

    public String getUseragent() {
        return useragent;
    }

    public void setUseragent(String useragent) {
        this.useragent = useragent;
    }

}
