package com.adaptivemedia.streamlytv.streamly.onboardingtour

import java.io.Serializable

class OnBoardingItem : Serializable {
    var heading: String? = null
    var details: String? = null
    var pic: String? = null
    var id: String? = null
    var template_id: String? = null
    var type_name: String? = null
    var enabled: String? = null


    @JvmName("getHeading1")
    fun getHeading(): String? {
        return heading
    }

    @JvmName("setHeading1")
    fun setHeading(heading: String?) {
        this.heading = heading
    }

    @JvmName("getDetails1")
    fun getDetails(): String? {
        return details
    }

    @JvmName("setDetails1")
    fun setDetails(details: String?) {
        this.details = details
    }

    @JvmName("getPic1")
    fun getPic(): String? {
        return pic
    }

    @JvmName("setPic1")
    fun setPic(pic: String) {
        this.pic = pic
    }

    @JvmName("getId1")
    fun getId(): String? {
        return id
    }

    @JvmName("setId1")
    fun setId(id: String?) {
        this.id = id
    }

    @JvmName("getTemplate_id1")
    fun getTemplate_id(): String? {
        return template_id
    }

    @JvmName("setTemplate_id1")
    fun setTemplate_id(template_id: String?) {
        this.template_id = template_id
    }

    @JvmName("getType_name1")
    fun getType_name(): String? {
        return type_name
    }

    @JvmName("setType_name1")
    fun setType_name(type_name: String?) {
        this.type_name = type_name
    }

    @JvmName("getEnabled1")
    fun getEnabled(): String? {
        return enabled
    }

    @JvmName("setEnabled1")
    fun setEnabled(enabled: String?) {
        this.enabled = enabled
    }
}
