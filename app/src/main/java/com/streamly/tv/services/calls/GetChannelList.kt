package com.streamly.tv.services.calls

import com.streamly.tv.services.CommonEnqueueValidator
import com.streamly.tv.services.NetworkCallResponse
import com.streamly.tv.services.ServiceBuilder
import com.streamly.tv.services.WebServiceInterface

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