package com.streamly.TVApp.streamly.log_in

import android.app.Activity
import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.provider.Settings
import android.util.Log
import android.view.View
import android.widget.*
import androidx.appcompat.widget.AppCompatButton
import androidx.core.widget.ContentLoadingProgressBar
import androidx.fragment.app.FragmentActivity
import com.androidnetworking.model.Progress
import com.streamly.TVApp.streamly.MainActivity
import com.streamly.TVApp.streamly.R
import com.streamly.TVApp.streamly.dialogs.SessionExpiredDialog
import com.streamly.TVApp.streamly.local_storage.TokenStorage
import com.streamly.TVApp.streamly.local_storage.TokenStorage.readUserRole
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface
import com.streamly.TVApp.streamly.user_room.UserRoomActivity
import com.streamly.TVApp.streamly.utilities.CommonUtility
import com.streamly.TVApp.streamly.utilities.ConstantUtility
import com.streamly.TVApp.streamly.utilities.NetworkKeys
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response

class PersonalDeviceName : FragmentActivity() {
    private lateinit var personalDeviceName: EditText
    private lateinit var nextImageButton: AppCompatButton
    private lateinit var logoutButton: TextView
    private lateinit var loadingProgressBar: ProgressBar
    private lateinit var context:Context;
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_personal_device_name)
        personalDeviceName = findViewById(R.id.editTextTextPersonName)
        nextImageButton = findViewById(R.id.next_image_button)
        logoutButton = findViewById(R.id.personal_device_name_logout_btn);
        loadingProgressBar = findViewById(R.id.personal_device_name_progress)
//        setting the focus on the edit text
        personalDeviceName.requestFocus()
        loadingProgressBar.visibility = View.GONE
        context = this
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
            loadingProgressBar.visibility = View.VISIBLE
            val authToken = NetworkKeys.KEY_BEARER + TokenStorage.readSharedTokenPasscode(
                this@PersonalDeviceName,
                ConstantUtility.AUTH_TOKEN_PASSCODE,
                null
            )
            val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
            val call = apiInterface.postLogOutCall("Bearer $authToken")
            call.enqueue(object : Callback<LogInModel?> {
                override fun onResponse(call: Call<LogInModel?>, response: Response<LogInModel?>) {
                    try {
                        loadingProgressBar.visibility = View.GONE

                        TokenStorage.clearSharedToken(context)
                        context.startActivity(Intent(context, LogInActivity::class.java))
                        (context as Activity).finish()
                    } catch (ex: Exception) {
                        ex.printStackTrace()
                    }
                }

                override fun onFailure(call: Call<LogInModel?>, t: Throwable) {
                    loadingProgressBar.visibility = View.GONE

                    CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE)
                }
            })
        }
    }
}