package com.adaptivemedia.streamlytv.streamly.playervideo.ip

import com.google.gson.annotations.SerializedName


/**
 * {
    "ip": "103.69.247.212",
    "version": "IPv4",
    "city": "Delhi",
    "region": "National Capital Territory of Delhi",
    "region_code": "DL",
    "country": "IN",
    "country_name": "India",
    "country_code": "IN",
    "country_code_iso3": "IND",
    "country_capital": "New Delhi",
    "country_tld": ".in",
    "continent_code": "AS",
    "in_eu": false,
    "postal": "110054",
    "latitude": 28.6542,
    "longitude": 77.2373,
    "timezone": "Asia/Kolkata",
    "utc_offset": "+0530",
    "country_calling_code": "+91",
    "currency": "INR",
    "currency_name": "Rupee",
    "languages": "en-IN,hi,bn,te,mr,ta,ur,gu,kn,ml,or,pa,as,bh,sat,ks,ne,sd,kok,doi,mni,sit,sa,fr,lus,inc",
    "country_area": 3287590.0,
    "country_population": 1352617328,
    "asn": "AS132116",
    "org": "Ani Network Pvt Ltd"
}

//reserve ip pass
{
"ip": "10.0.2.16",
"error": true,
"reason": "Reserved IP Address",
"reserved": true,
"version": "IPv4"
}

// invalid ip pass
{
"ip": "10.0.782.716",
"error": true,
"reason": "Invalid IP Address"
}
this is the response of the IP api add corresponding field according to need
*/
class IpDataModel(
    @SerializedName("ip")
    val ip:String,
    @SerializedName("country")
    val country:String,
    @SerializedName("latitude")
    val latitude:Double,
    @SerializedName("longitude")
    val longitude:Double,
    @SerializedName("error")
    var error:Boolean = false,
    @SerializedName("reason")
    var reason: String = ""
)