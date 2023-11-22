package com.adaptivemedia.streamlytv.streamly.logoutdevices

import android.app.Activity
import android.content.ContentValues.TAG
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import androidx.core.widget.doAfterTextChanged
import androidx.fragment.app.FragmentActivity
import com.adaptivemedia.streamlytv.R
import com.adaptivemedia.streamlytv.databinding.ActivityLogoutDevicesBinding
import com.adaptivemedia.streamlytv.streamly.dialogs.CommonDialog
import com.adaptivemedia.streamlytv.streamly.dialogs.DialogClickListeners
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.Data
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.Loggedindeviceinfolist
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.DeviceSignOutResponse
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility
import com.adaptivemedia.streamlytv.streamly.utilities.NetworkKeys
import com.adaptivemedia.streamlytv.streamly.utilities.gone
import com.adaptivemedia.streamlytv.streamly.utilities.show
import com.adaptivemedia.streamlytv.streamly.utilities.toast
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response


class   LogoutDevicesActivity : FragmentActivity() {
    private lateinit var binding: ActivityLogoutDevicesBinding
    private var model = Data()
    private var logout = 0
    private var logout1 = 0
    private var  deviceID: String? = null
    private var isLogin = false
    private var loginAdapter: LogoutDevicesAdapter? = null
    val list = mutableListOf<Data?>()
    val searchList = mutableListOf<Data?>()
    var auth_token:String? = null
    private val context = this
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        binding = ActivityLogoutDevicesBinding.inflate(layoutInflater)
        setContentView(binding.root)
        isLogin = intent.getBooleanExtra("isLogin", false)
         auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null)
        if (isLogin) {
            val data = intent.getSerializableExtra("list") as Loggedindeviceinfolist
            logout1 = data.numofdevice!!
            binding.tvTotalConnectedDevices.text = "Total Connected Devices $logout1"
            data.data?.let {
                list.addAll(it)
                searchList.addAll(it)
            }

        } else {
            connectedDevice()
        }
        onClickListeners()
        recyclerViewInit()

         deviceID = TokenStorage.readPersonalDeviceID(this, ConstantUtility.PERSONAL_DEVICE_ID, "")

    }


    /**
     * update a data into the list
     */
    private fun recyclerViewInit() {
        loginAdapter!!.submitList(searchList.toMutableList())
    }

    /**
     * onCLick listnets
     */
    private fun onClickListeners() {
        binding.apply {
            loginAdapter = LogoutDevicesAdapter() { data, pos ->
                model = data
                if(model.personaldeviceid.equals(deviceID)){
                    CommonUtility.showToast(context, "This device")

                }else{
                    showDialog()
                }
                Log.e(TAG, "device_id:----" + model.personaldeviceid)
                Log.e(TAG, "personal device_id:-" + deviceID)
            }


            rvLogoutDevices.adapter = loginAdapter


            edSearchRoom.doAfterTextChanged {
                searchList.clear()
                val data = it.toString().lowercase()
                for (l in 0 until list.size) {
                    if (nullCheck(list[l]!!.hotel_room).lowercase()
                            .contains(data) || nullCheck(list[l]!!.device_name).lowercase()
                            .contains(data) || nullCheck(list[l]!!.device_type).lowercase()
                            .contains(
                                data
                            )
                    ) {
                        searchList.add(list[l])
                    }
                }

                recyclerViewInit()
            }


        }
    }


    /**
     * device logout api call
     */
    private fun deviceLogout(deviceId: String) {
        binding.progressBar.show()
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        Log.d(TAG, "deviceLogout: authtoken "+auth_token)
        if(auth_token!=null && auth_token!=""){
            val call = apiInterface.postDeviceLogOutCall("Bearer " + auth_token,deviceId)
            call.enqueue(object : retrofit2.Callback<DeviceSignOutResponse> {
                override fun onResponse(
                    call: Call<DeviceSignOutResponse>,
                    response: Response<DeviceSignOutResponse>
                ) {
                    binding.progressBar.gone()
                    try {
                        if (response.isSuccessful && response.body() != null && response.code() == 200) {
                            if (sessionId.isNotEmpty() && sessionId == deviceId) {
                                val intent = Intent(context, LogInActivity::class.java)
                                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK)
                                startActivity(intent)
                            } else if (isLogin) {
                                binding.tvTotalConnectedDevices.text =
                                    "Total Connected Devices ${--logout1}"
                                toast(response.body()!!.message)
                                setResult(Activity.RESULT_OK)
                                finish()
                            } else {
                                list.remove(model)
                                searchList.remove(model)
                                recyclerViewInit()
                                noRecord()
                            }
                        } else if (response.code() == 401) {
                            TokenStorage.clearSharedToken(context)
                            CommonUtility.showToast(context, ConstantUtility.SESSION_EXPIRED)
                            context.startActivity(Intent(context, LogInActivity::class.java))
                            (context as Activity).finish()
                        }else {
                            toast(ConstantUtility.SERVER_ISSUE)
                        }
                    } catch (ex: Exception) {
                        ex.printStackTrace()
                        Log.e(TAG, "Exception $ex")
                    }
                }

                override fun onFailure(call: Call<DeviceSignOutResponse>, t: Throwable) {
                    binding.progressBar.gone()

                    toast(ConstantUtility.SERVER_ISSUE)
                }
            })
        }else{
            val call = apiInterface.postDeviceLogOutCall("Bearer ",deviceId)
            call.enqueue(object : retrofit2.Callback<DeviceSignOutResponse> {
                override fun onResponse(
                    call: Call<DeviceSignOutResponse>,
                    response: Response<DeviceSignOutResponse>
                ) {
                    binding.progressBar.gone()
                    try {
                        if (response.isSuccessful && response.body() != null && response.code() == 200) {
                            if (sessionId.isNotEmpty() && sessionId == deviceId) {
                                Log.d(TAG, "onResponse: 115 ")
                                val intent = Intent(context, LogInActivity::class.java)
                                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK)
                                startActivity(intent)
                            }
//                            else if (isLogin) {
//                                Log.d(TAG, "onResponse: 116 ")
//                                binding.tvTotalConnectedDevices.text =
//                                    "Total Connected Devices ${--logout1}"
//                                toast(response.body()!!.message)
//                                setResult(Activity.RESULT_OK)
//                                finish()
//                            }
                            else {
                                Log.d(TAG, "onResponse: 117 ")
                                list.remove(model)
                                searchList.remove(model)
                                recyclerViewInit()
                                noRecord()
                            }
                        } else {
                            Log.d(TAG, "onResponse: 118 ")
                            toast(ConstantUtility.SERVER_ISSUE)
                        }
                    } catch (ex: Exception) {
                        Log.d(TAG, "onResponse: 119 ")
                        ex.printStackTrace()
                        Log.e(TAG, "Exception $ex")
                    }
                }

                override fun onFailure(call: Call<DeviceSignOutResponse>, t: Throwable) {
                    Log.d(TAG, "onResponse: 120 ")
                    binding.progressBar.gone()

                    toast(ConstantUtility.SERVER_ISSUE)
                }
            })
        }


    }


    private fun showDialog() {

        val c = CommonDialog(
            "Are you sure you want to logout this device.",
            object : DialogClickListeners {
                override fun negative() {}
                override fun positive() {
                    deviceLogout(model.session_id!!)
                }
            })

        c.show(supportFragmentManager, "ffnadjg")
        /*    val dialog = AlertDialog.Builder(this, R.style.AlertDialogCustom)
            *//*
        dialog.setTitle("Register Now");*//*dialog.setMessage("Are you sure you want to logout this device.")

        // Specifying a listener allows you to take an action before dismissing the dialog.
        // The dialog is automatically dismissed when a dialog button is clicked.
        dialog.setPositiveButton(
            getString(R.string.yes)
        ) { dialog, which ->
            deviceLogout(model.session_id!!)
            dialog.dismiss()
        }
        dialog.setNegativeButton(
            getString(R.string.no)
        ) { dialog, which -> // Continue with delete operation
            dialog.dismiss()
        }
        dialog.show()
        dialog.setCancelable(true)*/
    }


    private fun nullCheck(value: String?): String {
        return if (value == null || value == "null") "" else value
    }

    var sessionId = ""
    private fun connectedDevice() {

        sessionId = TokenStorage.readSessionId(context, ConstantUtility.SESSION_ID, "")

        binding.progressBar.visibility = View.VISIBLE
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        val call = apiInterface.getConnectedDevice(NetworkKeys.KEY_BEARER + auth_token)
        call.enqueue(object : Callback<Loggedindeviceinfolist?> {
            override fun onResponse(
                call: Call<Loggedindeviceinfolist?>,
                response: Response<Loggedindeviceinfolist?>
            ) {
                try {
                    if (response.isSuccessful && response.body() != null && response.code() != 401) {
                        val dataList = response.body()!!
                        dataList.data?.let {
                            list.addAll(dataList.data)
                            searchList.addAll(dataList.data)
                            logout1 = dataList.numofdevice!!
                            binding.tvTotalConnectedDevices.text =
                                "Total Connected Devices $logout1"
                            recyclerViewInit()
                        }
                    } else if (response.code() == 401) {
                        if (response.errorBody() != null) {
                            val sessionExpiredDialog = SessionExpiredDialog(context)
                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                            sessionExpiredDialog.setCancelable(false)
                            sessionExpiredDialog.show()
                        }
                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(context)
                        CommonUtility.showToast(context, ConstantUtility.SESSION_EXPIRED)
                        context.startActivity(Intent(context, LogInActivity::class.java))
                        (context as Activity).finish()
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE)
                    }
                } catch (ex: java.lang.Exception) {
                    ex.printStackTrace()
                }
                binding.progressBar.visibility = View.GONE
                noRecord()
            }

            override fun onFailure(call: Call<Loggedindeviceinfolist?>, t: Throwable) {
                Log.e("df", "failure: " + call + " " + t.localizedMessage)
                CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE)
                binding.progressBar.visibility = View.GONE
                noRecord()
            }
        })
    }


    fun noRecord() {
        binding.apply {
            if (list.isEmpty()) {
                tvNoRecord.show()
            } else {
                tvNoRecord.gone()
            }

        }
    }
}
