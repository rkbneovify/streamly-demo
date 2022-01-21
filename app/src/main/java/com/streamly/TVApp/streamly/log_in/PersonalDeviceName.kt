package com.streamly.TVApp.streamly.log_in

import android.content.Intent
import android.os.Bundle
import android.provider.Settings
import android.widget.EditText
import android.widget.ImageButton
import android.widget.Toast
import androidx.core.widget.ContentLoadingProgressBar
import androidx.fragment.app.FragmentActivity
import com.streamly.TVApp.streamly.MainActivity
import com.streamly.TVApp.streamly.R
import com.streamly.TVApp.streamly.local_storage.TokenStorage
import com.streamly.TVApp.streamly.local_storage.TokenStorage.readUserRole
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface
import com.streamly.TVApp.streamly.user_room.UserRoomActivity
import com.streamly.TVApp.streamly.utilities.ConstantUtility
import com.streamly.TVApp.streamly.utilities.NetworkKeys
import retrofit2.Call
import retrofit2.Response

class PersonalDeviceName : FragmentActivity() {
    private lateinit var personalDeviceName: EditText
    private lateinit var nextImageButton: ImageButton
    private lateinit var loadingProgressBar: ContentLoadingProgressBar
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_personal_device_name)
        personalDeviceName = findViewById(R.id.editTextTextPersonName)
        nextImageButton = findViewById(R.id.next_image_button)
        loadingProgressBar = findViewById(R.id.personal_device_name_progress)
//        setting the focus on the edit text
        personalDeviceName.requestFocus()
        loadingProgressBar.hide()

        initClickListener()
    }

    private fun initClickListener() {
        nextImageButton.setOnClickListener { view ->
            loadingProgressBar.show()
            if (personalDeviceName.text.toString() == "") {
                Toast.makeText(this, getString(R.string.e_m_enter_device_name), Toast.LENGTH_SHORT)
                    .show()
                loadingProgressBar.hide()
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
                            loadingProgressBar.hide()
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
                            loadingProgressBar.hide()
                        }
                    })
                }
            }
        }
    }
}