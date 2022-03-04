package com.adaptivemedia.streamlytv.streamly.log_in

import android.app.Activity
import android.app.AlertDialog
import android.app.Dialog
import android.content.Context
import android.content.DialogInterface
import android.content.Intent
import android.os.Bundle
import android.provider.Settings
import android.view.View
import android.view.Window
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.widget.AppCompatButton
import androidx.fragment.app.FragmentActivity
import com.adaptivemedia.streamlytv.streamly.MainActivity
import com.adaptivemedia.streamlytv.R
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage.readUserRole
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface
import com.adaptivemedia.streamlytv.streamly.user_room.UserRoomActivity
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility
import com.adaptivemedia.streamlytv.streamly.utilities.NetworkKeys
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response


class PersonalDeviceName : FragmentActivity() {
    private lateinit var personalDeviceName: EditText
    private lateinit var nextImageButton: AppCompatButton
    private lateinit var logoutButton: TextView
    private lateinit var loadingProgressBar: ProgressBar
    private lateinit var context:Context;
    var builder: AlertDialog.Builder? = null
    var alertDialog: Dialog? = null
    private var dialogClickListener =
        DialogInterface.OnClickListener { dialog, which ->
            when (which) {
                DialogInterface.BUTTON_POSITIVE -> {
                    val auth_token =
                        TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null)
                    val apiInterface = ApiClient.getClient().create(
                        ApiInterface::class.java
                    )

                    val loadingDialog = initProgressDialog(this)
                    loadingDialog?.show()
                    val call = apiInterface.postLogOutCall("Bearer $auth_token")
                    call.enqueue(object : Callback<LogInModel?> {
                        override fun onResponse(
                            call: Call<LogInModel?>,
                            response: Response<LogInModel?>
                        ) {
                            loadingDialog?.dismiss()
                            try {
                                TokenStorage.clearSharedToken(context)
                                context.startActivity(Intent(context, LogInActivity::class.java))
                                (context as Activity).finish()
                            } catch (ex: java.lang.Exception) {
                                ex.printStackTrace()
                            }
                        }

                        override fun onFailure(call: Call<LogInModel?>, t: Throwable) {
                            loadingDialog?.dismiss()
                            CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE)
                        }
                    })
                    alertDialog!!.dismiss()
                }
                DialogInterface.BUTTON_NEGATIVE -> alertDialog!!.dismiss()
            }
        }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_personal_device_name)
        context = this

        alertDialog = Dialog(context)
        alertDialog!!.requestWindowFeature(Window.FEATURE_NO_TITLE)
        alertDialog!!.setCancelable(false)
        alertDialog!!.setContentView(R.layout.logout_dialog_view)
        alertDialog!!.findViewById<View>(R.id.yes_btn_logout_dialog).setOnClickListener {
            val loadingDialog = initProgressDialog(context)
            loadingDialog!!.show()
            val auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null)
            val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
            val call = apiInterface.postLogOutCall("Bearer $auth_token")
            call.enqueue(object : Callback<LogInModel?> {
                override fun onResponse(
                    call: Call<LogInModel?>,
                    response: Response<LogInModel?>
                ) {
                    loadingDialog.dismiss()
                    try {
                        TokenStorage.clearSharedToken(context)
                        context.startActivity(Intent(context, LogInActivity::class.java))
                        (context as Activity).finish()
                    } catch (ex: Exception) {
                        ex.printStackTrace()
                    }
                }

                override fun onFailure(call: Call<LogInModel?>, t: Throwable) {
                    loadingDialog.dismiss()
                    CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE)
                }
            })
            alertDialog!!.dismiss()
        }

        alertDialog!!.findViewById<View>(R.id.no_btn_logout_dialog).setOnClickListener { alertDialog!!.dismiss() }
        personalDeviceName = findViewById(R.id.editTextTextPersonName)
        nextImageButton = findViewById(R.id.next_image_button)
        logoutButton = findViewById(R.id.personal_device_name_logout_btn);
        loadingProgressBar = findViewById(R.id.personal_device_name_progress)
//        setting the focus on the edit text
        personalDeviceName.requestFocus()
        loadingProgressBar.visibility = View.GONE
        initClickListener()
    }

    private fun initClickListener() {
        nextImageButton.setOnClickListener { view ->
            loadingProgressBar.visibility = View.VISIBLE
            if (personalDeviceName.text.toString() == "") {
                Toast.makeText(this, getString(R.string.e_m_enter_device_name), Toast.LENGTH_SHORT)
                    .show()
                loadingProgressBar.visibility = View.GONE
            } else {

                val deviceName = personalDeviceName.text.toString()
                val session_id = TokenStorage.readSessionId(
                    this@PersonalDeviceName,
                    ConstantUtility.SESSION_ID,
                    null
                );
                val authToken = NetworkKeys.KEY_BEARER + TokenStorage.readSharedTokenPasscode(
                    this@PersonalDeviceName,
                    ConstantUtility.AUTH_TOKEN_PASSCODE,
                    null
                )
                var deviceID = Settings.Secure.getString(contentResolver, Settings.Secure.ANDROID_ID)

                val personalDeviceId = TokenStorage.readPersonalDeviceID(this, ConstantUtility.PERSONAL_DEVICE_ID, "")
                val deviceType = TokenStorage.readDeviceType(this, ConstantUtility.DEVICE_TYPE, "")
                val apiInterface = ApiClient.getClient().create(
                    ApiInterface::class.java
                )
                var data = HashMap<String, String>()
                data.set("devicename", deviceName);
                data.set("device_type", deviceID);
                if (session_id != null) {
                    val call = apiInterface.setPersonalDeviceName(authToken, personalDeviceId, data)
                    call.enqueue(object : retrofit2.Callback<PersonalDeviceNameDataModel> {
                        /**
                         * Invoked for a received HTTP response.
                         *
                         * Note: An HTTP response may still indicate an application-level failure such as a 404 or 500.
                         * Call [Response.isSuccessful] to determine if the response indicates success.
                         */
                        override fun onResponse(
                            call: Call<PersonalDeviceNameDataModel>,
                            response: Response<PersonalDeviceNameDataModel>
                        ) {
                            loadingProgressBar.visibility = View.GONE
                            if (response.code() == 200) {
                                TokenStorage.savePersonalDeviceName(
                                    this@PersonalDeviceName,
                                    ConstantUtility.PERSONAL_DEVICE_NAME,
                                    deviceName
                                )
                                Toast.makeText(
                                    this@PersonalDeviceName,
                                    response.message(),
                                    Toast.LENGTH_SHORT
                                ).show()
                                val userRole = readUserRole(
                                    this@PersonalDeviceName,
                                    ConstantUtility.USER_ROLE,
                                    ""
                                )
                                if (userRole != "" && userRole == "user") {
                                    startActivity(
                                        Intent(
                                            this@PersonalDeviceName,
                                            MainActivity::class.java
                                        )
                                    )
                                } else {
                                    startActivity(
                                        Intent(
                                            this@PersonalDeviceName,
                                            UserRoomActivity::class.java
                                        )
                                    )
                                }
                                finish()
                            } else {
                                Toast.makeText(
                                    this@PersonalDeviceName,
                                    response.message(),
                                    Toast.LENGTH_SHORT
                                ).show()
                            }
                        }

                        /**
                         * Invoked when a network exception occurred talking to the server or when an unexpected
                         * exception occurred creating the request or processing the response.
                         */
                        override fun onFailure(
                            call: Call<PersonalDeviceNameDataModel>,
                            t: Throwable
                        ) {

                            Toast.makeText(this@PersonalDeviceName, t.message, Toast.LENGTH_SHORT)
                                .show()
                            loadingProgressBar.visibility = View.GONE

                        }
                    })
                }
            }
        }
        logoutButton.setOnClickListener {
            alertDialog?.show();
        }
    }

    fun initProgressDialog(context: Context?): Dialog? {
        val dialog = Dialog(context!!)
        dialog.setContentView(R.layout.dialog_loading_progress_bar)
        dialog.window!!.setBackgroundDrawableResource(R.color.transparent)
        dialog.setCanceledOnTouchOutside(false)
        return dialog
    }
}