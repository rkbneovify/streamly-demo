package com.adaptivemedia.streamlytv.streamly.services.calls

import com.adaptivemedia.streamlytv.streamly.services.CommonEnqueueValidator
import com.adaptivemedia.streamlytv.streamly.services.NetworkCallResponse
import com.adaptivemedia.streamlytv.streamly.services.ServiceBuilder
import com.adaptivemedia.streamlytv.streamly.services.WebServiceInterface

class GetChannelList {
    private val apiTag = GetChannelList::class.java.simpleName
    fun callGetChannelListApi(
            authToken: String, mCallback: NetworkCallResponse) {
        val webServiceInterface = ServiceBuilder().buildClient(
                WebServiceInterface::class.java,
                "http://100.26.190.92")
        val getChannelList = webServiceInterface.getChannelList(authToken = authToken)
        val commonEnqueueValidator = CommonEnqueueValidator(getChannelList,
                mCallback, apiTag)
        commonEnqueueValidator.enqueue()
    }
}