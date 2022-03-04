
package com.adaptivemedia.streamlytv.streamly.channelsschedulepaginate;

import java.util.List;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;



public class Datum {

    @SerializedName("callSign")
    @Expose
    private String callSign;
    @SerializedName("id")
    @Expose
    private Integer id;
    @SerializedName("stationName")
    @Expose
    private String stationName;
    @SerializedName("url")
    @Expose
    private String url;
    @SerializedName("logo")
    @Expose
    private String logo;
    @SerializedName("programs")
    @Expose
    private List<Program> programs = null;

    public String getCallSign() {
        return callSign;
    }

    public void setCallSign(String callSign) {
        this.callSign = callSign;
    }

    public Integer getId() {
        return id;
    }

    public void setId(Integer id) {
        this.id = id;
    }

    public String getStationName() {
        return stationName;
    }

    public void setStationName(String stationName) {
        this.stationName = stationName;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }

    public String getLogo() {
        return logo;
    }

    public void setLogo(String logo) {
        this.logo = logo;
    }

    public List<Program> getPrograms() {
        return programs;
    }

    public void setPrograms(List<Program> programs) {
        this.programs = programs;
    }

}
