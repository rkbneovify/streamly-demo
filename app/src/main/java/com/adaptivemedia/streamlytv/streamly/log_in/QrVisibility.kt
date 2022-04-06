package com.adaptivemedia.streamlytv.streamly.log_in

import com.google.gson.annotations.SerializedName

data class QrVisibility(
    @SerializedName("visible")
    var visible:Boolean = false
)
