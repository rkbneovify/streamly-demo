package com.streamly.tv.services.calls

import com.streamly.tv.services.CommonEnqueueValidator
import com.streamly.tv.services.NetworkCallResponse
import com.streamly.tv.services.ServiceBuilder
import com.streamly.tv.services.WebServiceInterface

class GetScheduleList {
    private val apiTag = GetScheduleList::class.java.simpleName
    fun callGetScheduleListApi(
            authToken: String, mCallback: NetworkCallResponse) {
        val webServiceInterface = ServiceBuilder().buildClient(
                WebServiceInterface::class.java,
                "http://100.26.190.92")
        val getScheduleList = webServiceInterface.getScheduleList(authToken = authToken)
        val commonEnqueueValidator = CommonEnqueueValidator(getScheduleList,
                mCallback, apiTag)
        commonEnqueueValidator.enqueue()
    }
}