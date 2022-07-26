package com.adaptivemedia.streamlytv.streamly.new_log_in.devices

import androidx.annotation.Keep
import java.io.Serializable

@Keep
data class Data(
    val connected_devices: Int? = 0,
    val device_id: String? = "",
    val device_img: String? = "",
    val device_name: String? = "",
    val device_type: String? = "",
    val hotel_room: String? = "",
    var isLogout: Boolean = false,
    val personaldeviceid: String? = "",
    val personaldevicename: String? = "",
    val session_id: String? = ""
):Serializable