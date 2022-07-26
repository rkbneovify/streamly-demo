package com.adaptivemedia.streamlytv.streamly.new_log_in.devices

import androidx.annotation.Keep
import java.io.Serializable

@Keep
data class Loggedindeviceinfolist(
    val `data`: List<Data?>? = null,
    val message: String? = "",
    val numofdevice: Int? = 0
) : Serializable