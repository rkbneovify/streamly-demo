package com.adaptivemedia.streamlytv.streamly.services.calls

import com.adaptivemedia.streamlytv.streamly.services.CommonEnqueueValidator
import com.adaptivemedia.streamlytv.streamly.services.NetworkCallResponse
import com.adaptivemedia.streamlytv.streamly.services.ServiceBuilder
import com.adaptivemedia.streamlytv.streamly.services.WebServiceInterface

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