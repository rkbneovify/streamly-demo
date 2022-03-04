package com.adaptivemedia.streamlytv.streamly.QRCode;

import com.google.gson.annotations.SerializedName;


public class QRScanStatusModel {

    @SerializedName("status")
    private Integer status;
    @SerializedName("msg")
    private String msg;

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

}
