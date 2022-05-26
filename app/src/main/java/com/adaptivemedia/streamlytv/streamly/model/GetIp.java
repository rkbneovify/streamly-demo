package com.adaptivemedia.streamlytv.streamly.model;

import com.google.gson.annotations.SerializedName;

public class GetIp {

    @SerializedName("country_name")
    private String countryName;
    @SerializedName("country_code")
    private String countryCode;
    @SerializedName("city")
    private String countryCity;
    @SerializedName("ip")
    private String ipAddress;

    public String getCountryName() {
        return countryName;
    }

    public void setCountryName(String countryName) {
        this.countryName = countryName;
    }

    public String getCountryCode() {
        return countryCode;
    }

    public void setCountryCode(String countryCode) {
        this.countryCode = countryCode;
    }

    public String getCountryCity() {
        return countryCity;
    }

    public void setCountryCity(String countryCity) {
        this.countryCity = countryCity;
    }

    public String getIpAddress() {
        return ipAddress;
    }

    public void setIpAddress(String ipAddress) {
        this.ipAddress = ipAddress;
    }
}
