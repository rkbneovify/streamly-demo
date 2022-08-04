package com.adaptivemedia.streamlytv.streamly.new_log_in.devices

import androidx.annotation.Keep

@Keep
data class LoginDevicesModel(
    val loggedindeviceinfolist: Loggedindeviceinfolist?,
    val message: String? = "",
    val response_code: Int? = 0,
    val success: Boolean? = false
)