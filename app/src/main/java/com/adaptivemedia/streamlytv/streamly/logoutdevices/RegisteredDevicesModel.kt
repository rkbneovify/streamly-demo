package com.adaptivemedia.streamlytv.streamly.terms

import com.google.gson.annotations.SerializedName

class RegisteredDevicesModel {

    @SerializedName("success")
    private var success: Boolean? = null

    @SerializedName("response_code")
    private var response_code: String? = null

    @SerializedName("data")
    private var data: ArrayList<RegisteredDataa>? = null

    @SerializedName("message")
    private var message: String? = null

    @SerializedName("numofdevice")
    private var numofdevice: String? = null

    fun getSuccess(): Boolean? {
        return success
    }

    fun setSuccess(successs: Boolean) {
        success = successs
    }

    fun setData(dataa: ArrayList<RegisteredDataa>?) {
        data = dataa
    }

    fun getData(): ArrayList<RegisteredDataa>? {
        return data
    }

    /* fun getData(): RegisteredDataa? {
         return data
     }

     fun setData(dataa: RegisteredDataa) {
         data = dataa
     }*/

    fun getMessage(): String? {
        return message
    }

    fun setMessage(message: String?) {
        this.message = message
    }

    fun getnumofdevice(): String? {
        return numofdevice
    }

    fun setnumofdevice(numofdevice: String?) {
        this.numofdevice = numofdevice
    }

    fun getResponseCode(): String? {
        return response_code
    }

    fun setResponseCode(response_code: String?) {
        this.response_code = response_code
    }

}

class RegisteredDataa {

    @SerializedName("device_id")
    var device_id: String? = null


    @SerializedName("connected_devices")
    var connected_devices: String? = null

    @SerializedName("device_name")
    var device_name: String? = null

    @SerializedName("num2")
    private var num2: String? = null

    @SerializedName("session_id")
    var session_id: String? = null
 @SerializedName("personaldeviceid")
 var personaldeviceid: String? = null

    @SerializedName("hotel_room")
    private var hotel_room: String? = null

    @SerializedName("device_img")
    var device_img: String? = null
 @SerializedName("device_type")
    var device_type: String? = null

    @SerializedName("personaldevicename")
    var personaldevicename: String? = null

    var isSelected: Boolean = false

    fun gethotel_room(): String? {
        return hotel_room
    }

    fun sethotel_room(hotel_room: String?) {
        this.hotel_room = hotel_room
    }
  fun getsession_id(): String? {
        return session_id
    }
fun getpersonaldeviceid(): String? {
        return personaldeviceid
    }

    fun setsession_id(session_id: String?) {
        this.session_id = hotel_room
    }


    fun getdevice_img(): String? {
        return device_img
    }

    fun setdevice_img(device_img: String?) {
        this.device_img = device_img
    }
 fun getdevice_type(): String? {
        return device_type
    }

    fun setdevice_type(device_type: String?) {
        this.device_type = device_type
    }

    fun getpersonaldevicename(): String? {
        return personaldevicename
    }

    fun setpersonaldevicename(personaldevicename: String?) {
        this.personaldevicename = personaldevicename
    }

    fun getconnected_devices(): String? {
        return connected_devices
    }

    fun setconnected_devices(connected_devices: String?) {
        this.connected_devices = connected_devices
    }

    fun getDeviceid(): String? {
        return device_id
    }

    fun setDeviceid(device_id: String?) {
        this.device_id = device_id
    }

    fun getDeviceName(): String? {
        return device_name
    }

    fun setDeviceName(device_name: String?) {
        this.device_name = device_name
    }

    fun getNum2(): String? {
        return num2
    }

    fun setNum2(num2: String?) {
        this.num2 = num2
    }

}
