package com.streamly.TVApp.streamly.QRCode;

import com.google.gson.annotations.SerializedName;

public class HotelQRModel {
    @SerializedName("status")
    private Integer status;

    @SerializedName("msg")
    private String msg;

    @SerializedName("key")
    private String key;

    public Integer getStatus() {
        return status;
    }

    public void setStatus(Integer status) {
        this.status = status;
    }

    public String getMsg() {
        return msg;
    }

    public void setMsg(String msg) {
        this.msg = msg;
    }

    public String getKey() {
        return key;
    }

    public void setKey(String key) {
        this.key = key;
    }
}
