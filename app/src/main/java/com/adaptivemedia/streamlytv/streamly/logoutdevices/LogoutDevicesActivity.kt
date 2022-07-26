package com.adaptivemedia.streamlytv.streamly.logoutdevices

import android.content.ContentValues.TAG
import android.os.Bundle
import android.util.Log
import androidx.fragment.app.FragmentActivity
import com.adaptivemedia.streamlytv.databinding.ActivityLogoutDevicesBinding
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.Data
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.Loggedindeviceinfolist
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.DeviceSignOutResponse
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility
import com.adaptivemedia.streamlytv.streamly.utilities.showDialogs
import com.adaptivemedia.streamlytv.streamly.utilities.toast
import retrofit2.Call
import retrofit2.Response

class LogoutDevicesActivity : FragmentActivity() {
    private lateinit var binding: ActivityLogoutDevicesBinding
    private var model = Data()
    private var logout = 0
    private var loginAdapter: LogoutDevicesAdapter? = null
    val list = mutableListOf<Data?>()
    private val context = this
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityLogoutDevicesBinding.inflate(layoutInflater)
        setContentView(binding.root)
           val data = intent.getSerializableExtra("list") as Loggedindeviceinfolist
           binding.tvTotalConnectedDevices.text = "Total Connected Devices ${data.numofdevice}"
           data.data?.let {
               list.addAll(it)
           }
        onClickListeners()
        recyclerViewInit()

    }


    /**
     * update a data into the list
     */
    private fun recyclerViewInit() {
        loginAdapter!!.submitList(list.toMutableList())
    }

    /**
     * onCLick listnets
     */
    private fun onClickListeners() {
        binding.apply {
            loginAdapter = LogoutDevicesAdapter() { data, pos ->
                model = data
                showDialogs("Are you sure you want to logout this device.") {
                    deviceLogout(model.session_id!!)
                }
            }
            rvLogoutDevices.adapter = loginAdapter
            roomLogoutBtn.setOnClickListener {
                logout = 0
                for (i in list) {
                    if (i!!.isLogout) {
                        logout += 1
                        model = i
                    }
                }
                if (logout == 0) {
                    toast("Please select at least one device to logout.")
                } else if (logout > 1) {
                    toast("You can logout at a time only one device")
                } else {
                    showDialogs("Are you sure you want to logout this device.") {
                        deviceLogout(model.session_id!!)
                    }
                }
            }
        }
    }


    /**
     * device logout api call
     */
    private fun deviceLogout(deviceId: String) {
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        val call = apiInterface.postDeviceLogOutCall(deviceId)
        call.enqueue(object : retrofit2.Callback<DeviceSignOutResponse> {
            override fun onResponse(
                call: Call<DeviceSignOutResponse>,
                response: Response<DeviceSignOutResponse>
            ) {
                try {
                    if (response.isSuccessful && response.body() != null && response.code() == 200) {
                        list.remove(model)
                        recyclerViewInit()
                        toast(response.body()!!.message)
                    } else {
                        toast(response.body()!!.message)
                    }
                } catch (ex: Exception) {
                    ex.printStackTrace()
                    Log.e(TAG, "Exception $ex")
                }
            }

            override fun onFailure(call: Call<DeviceSignOutResponse>, t: Throwable) {
                toast(ConstantUtility.SERVER_ISSUE)
            }
        })
    }
}
