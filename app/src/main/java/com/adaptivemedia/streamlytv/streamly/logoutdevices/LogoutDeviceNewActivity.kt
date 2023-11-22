package com.adaptivemedia.streamlytv.streamly.logoutdevices

import android.app.Activity
import android.app.AlertDialog
import android.content.ContentValues
import android.content.Intent
import android.os.Bundle
import android.text.Editable
import android.text.TextWatcher
import android.util.Log
import android.view.View
import android.widget.EditText
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import androidx.fragment.app.FragmentActivity
import androidx.recyclerview.widget.GridLayoutManager
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import com.adaptivemedia.streamlytv.R
import com.adaptivemedia.streamlytv.streamly.dialogs.SessionExpiredDialog
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface
import com.adaptivemedia.streamlytv.streamly.settings.device_signout.DeviceSignOutResponse
import com.adaptivemedia.streamlytv.streamly.terms.RegisteredDataa
import com.adaptivemedia.streamlytv.streamly.terms.RegisteredDevicesModel
import com.adaptivemedia.streamlytv.streamly.utilities.*
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class LogoutDeviceNewActivity : FragmentActivity() {
    private var ivBack: ImageView? = null
    private var tvLogout: TextView? = null
    private var tvNoRecord: TextView? = null
    private var progressBar: LinearLayout? = null
    private var tvConnectedDevices: TextView? = null
    private var devicesList = ArrayList<RegisteredDataa>()
//    private var devicesBean: RegisteredDevicesBean? = null
    private var rdevicesAdapter : RegisteredDevicesAdapter?= null
    private var rvRDevices : RecyclerView?= null
    private var logoutId: String? = null
    private var edtSearch: EditText? = null
    private var ivVoiceSearch: ImageView? = null
    private val REQUEST_CODE: Int = 100
    private val TAG = "RgstrdDvcsActvty"

    private var activity: Activity? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_logout_devices)
        activity = this@LogoutDeviceNewActivity

        findview()

        edtSearch!!.addTextChangedListener(object : TextWatcher {
            override fun beforeTextChanged(charSequence: CharSequence, i: Int, i1: Int, i2: Int) {}

            override fun onTextChanged(charSequence: CharSequence, i: Int, i1: Int, i2: Int) {}

            override fun afterTextChanged(editable: Editable) {
                filter(editable.toString())
//                Log.e(TAG,"testttttttttt" + listFromActivity1!!.size)
                edtSearch!!.text.toString().toUpperCase()
//                val lowerCase = uppercase.toLowerCase()
            }
        })
    }

    fun findview(){
        progressBar = findViewById(R.id.progressBar)
        tvNoRecord = findViewById(R.id.tvNoRecord)
        edtSearch = findViewById(R.id.ed_search_room) as EditText
        tvConnectedDevices = findViewById(R.id.tvTotalConnectedDevices)
        rvRDevices = findViewById(R.id.rvLogoutDevices)
        rvRDevices!!.setHasFixedSize(true)
        rvRDevices!!.layoutManager = LinearLayoutManager(activity)

        connectedDevice()
    }

    private fun filter(text: String) {
//        if (devicesList.size > 0) {
//            edtSearch!!.inputType = InputType.TYPE_TEXT_FLAG_CAP_CHARACTERS
        rdevicesAdapter!!.getFilter(activity).filter(text)
//        } else {
//            //            Toast.makeText(activity, "No Match found", Toast.LENGTH_LONG).show();
//        }
    }

    fun logotMehodcall() {

//
//
//
//        ftvLogout!!.visibility = View.VISIBLE
        rdevicesAdapter!!.notifyDataSetChanged()

//        tvLogout!!.setOnClickListener {
        Log.e(TAG, "logotMehodcall click::>> ")


        val alertDialog = AlertDialog.Builder(activity)
        alertDialog.setTitle(activity!!.getString(R.string.app_name))
        alertDialog.setMessage("Are you sure you want to logout this device?")
        alertDialog.setPositiveButton("Yes") { dialog, which ->

                deviceLogout(logoutId!!)
        }

        alertDialog.setNegativeButton("No") { dialog, which ->
            dialog.cancel()
        }
        alertDialog.show()
//        }
    }
    private fun deviceLogout(deviceId: String) {
        progressBar!!.show()
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        val call = apiInterface.postDeviceLogOutCall("Bearer ",deviceId)
        call.enqueue(object : retrofit2.Callback<DeviceSignOutResponse> {
            override fun onResponse(
                call: Call<DeviceSignOutResponse>,
                response: Response<DeviceSignOutResponse>
            ) {
                progressBar!!.gone()
                try {
                    if (response.isSuccessful && response.body() != null && response.code() == 200) {
                        if (sessionId.isNotEmpty() && sessionId == deviceId) {
                            val intent = Intent(activity, LogInActivity::class.java)
                            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP or Intent.FLAG_ACTIVITY_NEW_TASK or Intent.FLAG_ACTIVITY_CLEAR_TASK)
                            startActivity(intent)
                        } else {
                            connectedDevice()
                        }
                    } else {
                        toast(ConstantUtility.SERVER_ISSUE)
                    }
                } catch (ex: Exception) {
                    ex.printStackTrace()
                    Log.e(ContentValues.TAG, "Exception $ex")
                }
            }

            override fun onFailure(call: Call<DeviceSignOutResponse>, t: Throwable) {
                progressBar!!.gone()

                toast(ConstantUtility.SERVER_ISSUE)
            }
        })
    }

    fun getClickedData(logout_id: String?) {
        logoutId = logout_id
    }

    var sessionId = ""
    private fun connectedDevice() {
        val auth_token = TokenStorage.readSharedToken(activity, ConstantUtility.AUTH_TOKEN, null)
        sessionId = TokenStorage.readSessionId(activity, ConstantUtility.SESSION_ID, "")

       progressBar!!.visibility = View.VISIBLE
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        val call = apiInterface.getConnectedDevicesnew(NetworkKeys.KEY_BEARER + auth_token)
        call.enqueue(object : Callback<RegisteredDevicesModel?> {
            override fun onResponse(
                call: Call<RegisteredDevicesModel?>,
                response: Response<RegisteredDevicesModel?>
            ) {
                try {
                    if (response.isSuccessful && response.body() != null && response.code() != 401) {
                        val dataList = response.body()!!
//                        dataList.data?.let {
//                            list.addAll(dataList.data)
//                            searchList.addAll(dataList.data)
//                            logout1 = dataList.numofdevice!!
//                            binding.tvTotalConnectedDevices.text =
//                                "Total Connected Devices $logout1"
//                            recyclerViewInit()
                           var bean: RegisteredDevicesModel? = null
                            tvConnectedDevices!!.setText("Total Connected Devices :-" + response.body()!!.getnumofdevice())
                            devicesList = response.body()!!.getData()!!
                            rdevicesAdapter = RegisteredDevicesAdapter(activity!!, devicesList, this@LogoutDeviceNewActivity)
                            rvRDevices!!.adapter = rdevicesAdapter
                        rvRDevices!!.setLayoutManager(GridLayoutManager(activity, 4))

                        rdevicesAdapter!!.notifyDataSetChanged()
//                        }
                    } else if (response.code() == 401) {
                        if (response.errorBody() != null) {
                            val sessionExpiredDialog = SessionExpiredDialog(activity!!)
                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                            sessionExpiredDialog.setCancelable(false)
                            sessionExpiredDialog.show()
                        }
                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(activity)
                        CommonUtility.showToast(activity, ConstantUtility.SESSION_EXPIRED)
                        startActivity(Intent(activity, LogInActivity::class.java))
                        finish()
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(activity, ConstantUtility.SERVER_ISSUE)
                    }
                } catch (ex: java.lang.Exception) {
                    ex.printStackTrace()
                }
                progressBar!!.visibility = View.GONE
            }

            override fun onFailure(call: Call<RegisteredDevicesModel?>, t: Throwable) {
                Log.e("df", "failure: " + call + " " + t.localizedMessage)
                CommonUtility.showToast(activity, ConstantUtility.SERVER_ISSUE)
                progressBar!!.visibility = View.GONE
            }
        })
    }

//    private fun getDataToRDevices() {
//        progressBar!!.visibility = View.VISIBLE
//        val rDevicesViewModel = ViewModelProvider(this).get(RDevicesViewModel::class.java)
//        rDevicesViewModel.getDatadevicelist(activity,email,password,type,passcode)!!.observe(
//            this,
//            androidx.lifecycle.Observer { apiResponse: ApiResponse<*> ->
//                val registeredDevicesModel = apiResponse.apiResponse as RegisteredDevicesModel?
//                when (apiResponse.responseCode) {
//                    200 -> {
//                        progressBar!!.visibility = View.GONE
//                        if (registeredDevicesModel!!.getSuccess() == false) {
//                            ApplicationHelper.showToast(activity, "Ip not valid")
//                            Log.e(TAG, "emulator IP is not valid for getting coordinate")
//                            activity!!.finish()
//                        } else {
//                            Log.e("success", "true>>><<<<<")
//
//                            tvConnectedDevices!!.setText(getString(R.string.no_of_connected_devices) + registeredDevicesModel.getnumofdevice())
//                            devicesList = registeredDevicesModel.getData()!!
//                            rdevicesAdapter =
//                                RegisteredDevicesNewAdapter(activity!!, devicesList, this)
//                            rvRDevices!!.adapter = rdevicesAdapter
//                            rdevicesAdapter!!.notifyDataSetChanged()
//                        }
//                    }
//
//                    401 -> {
//                        progressBar!!.visibility = View.GONE
////                        ApplicationHelper.showToast(activity, registeredDevicesModel!!.getMessage())
//                        val sessionExpiredDialog = SessionExpiredDialog(activity)
//                        sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
//                        sessionExpiredDialog.setCancelable(false)
//                        sessionExpiredDialog.show()
//                    }
//
//                    402 -> {
//                        progressBar!!.visibility = View.GONE
//                        Utils.showAlertToLogin(
//                            activity!!,
//                            getString(R.string.app_name),
//                            registeredDevicesModel!!.getMessage().toString()
//                        )
//                    }
//
//                    410 -> {
//                        progressBar!!.visibility = View.GONE
//                        Utils.showAlertToLogin(
//                            activity!!,
//                            getString(R.string.app_name),
//                            registeredDevicesModel!!.getMessage().toString()
//                        )
//                    }
//                    403 -> {
//                        progressBar!!.visibility = View.GONE
//                        ApplicationHelper.showToast(activity, Constants.SERVER_ISSUE)
//                    }
//
//                    404 -> {
//                        TokenStorage.clearSharedToken(activity)
//                        CommonUtility.showToast(activity, ConstantUtility.SESSION_EXPIRED)
//                        startActivity(Intent(activity, LogInActivity::class.java))
//                        finish()
//                    }
//
//                    500 -> {
//                        progressBar!!.visibility = View.GONE
//                        CommonUtility.showToast(activity, ConstantUtility.SERVER_ISSUE)
//
//                    }
//                }
//            })
//    }

}