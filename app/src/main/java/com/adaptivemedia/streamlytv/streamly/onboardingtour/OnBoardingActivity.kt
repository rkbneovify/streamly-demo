package com.adaptivemedia.streamlytv.streamly.onboardingtour

import android.annotation.SuppressLint
import android.app.Activity
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.*
import androidx.annotation.Nullable
import androidx.fragment.app.FragmentActivity
import androidx.viewpager.widget.ViewPager
import com.adaptivemedia.streamlytv.R
import com.adaptivemedia.streamlytv.streamly.MainActivity
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity
import com.adaptivemedia.streamlytv.streamly.settings.privacy.PrivacyDialog
import com.adaptivemedia.streamlytv.streamly.settings.privacy.PrivacyDialogOnboard
import com.adaptivemedia.streamlytv.streamly.settings.support.SupportDialogOnboard
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility
import com.android.volley.AuthFailureError
import com.android.volley.DefaultRetryPolicy
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.google.android.material.tabs.TabLayout
import com.smarteist.autoimageslider.SliderView
import org.json.JSONArray
import org.json.JSONObject


class OnBoardingActivity : FragmentActivity(), View.OnClickListener {
    private var currentPage = 0
    private var scrollpage = 0
    private var mAdapter: OnboardingAdapter? = null
    private val onboardlist: ArrayList<OnBoardingItem?> = ArrayList<OnBoardingItem?>()
    private var viewpager: ViewPager? = null
    private var tabDots: TabLayout? = null
    private var tvSkip: TextView? = null
    private var btnLogin: Button? = null
    private var btnprivacy: Button? = null
    private var btnhelp: Button? = null
    private var tvNext: TextView? = null
    private var sliderView: SliderView? = null
    private var bean: OnBoardingItem? = null
    private var chkDontshowagain: CheckBox? = null
    private var isRemember = false
    private lateinit var loadingProgressBar: ProgressBar
    var auth_token: String? = null
    var auth_token_passcode: String? = null
    var personDeviceName: String? = null
    var room:kotlin.String? = null

    override fun onCreate(@Nullable savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_on_boarding)
        activity = this@OnBoardingActivity
        findViews()
        ConstantUtility.isOnboardingtour = false
        TokenStorage.savedSharedToken(
            activity,
            ConstantUtility.BOARDINGTOUR,
            null
        )
        onboadinglist()
//        bean = OnBoardingItem()
//        bean!!.setHeading("You design !")
//        bean!!.setDetails("You can choose from our templates to customize your card, letter, etc.")
//        onboardlist.add(bean)
//        bean!!.setHeading("We post !")
//        bean!!.setDetails("We will print your customized letter or card and post it for you")
//        onboardlist.add(bean)
//        bean!!.setHeading("We deliver !")
//        bean!!.setDetails("We will deliver your letter or card to your loved ones")
//        onboardlist.add(bean)

//        Log.e(TAG, "::>>> " + onboardlist.size)
//        mAdapter = OnboardingAdapter(activity as OnBoardingActivity, onboardlist)
//        viewpager!!.setAdapter(mAdapter)
//        tabDots!!.setupWithViewPager(viewpager, true)
        viewpager!!.addOnPageChangeListener(object : ViewPager.OnPageChangeListener {
            override fun onPageScrollStateChanged(state: Int) {}
            override fun onPageScrolled(position: Int, positionOffset: Float, positionOffsetPixels: Int) {
                if (position < 2) {
                    tvSkip!!.visibility = View.GONE
                    tvNext!!.text = "NEXT"
                    scrollpage = 1
                } else if (position == 2) {
                    tvSkip!!.visibility = View.GONE
                    tvNext!!.text = "GOT IT"
                }
            }

            override fun onPageSelected(position: Int) {
                // Check if this is the page you want.
                if (position == 2) {
                    tvSkip!!.visibility = View.GONE
                    tvNext!!.text = "GOT IT"
                }
            }
        })
    }

    private fun findViews() {
        viewpager = findViewById(R.id.viewpager)
        btnLogin = findViewById(R.id.btnLogin)
        btnprivacy = findViewById(R.id.btnprivacy)
        btnhelp = findViewById(R.id.btnhelp)
//        tabDots = findViewById(R.id.tabDots)
        tvSkip = findViewById(R.id.tvSkip)
        tvNext = findViewById(R.id.tvNext)
        loadingProgressBar = findViewById(R.id.personal_device_name_progress)

        auth_token =
            TokenStorage.readSharedToken(this@OnBoardingActivity, ConstantUtility.AUTH_TOKEN, null)
        auth_token_passcode = TokenStorage.readSharedTokenPasscode(
            this@OnBoardingActivity,
            ConstantUtility.AUTH_TOKEN_PASSCODE,
            null
        )
         sliderView = findViewById<SliderView>(R.id.slider)


        // below method is use to set
        // scroll time in seconds.
        sliderView!!.setScrollTimeInSec(7);

        // to set it scrollable automatically
        // we use below method.
        sliderView!!.setAutoCycle(true);

        // to start autocycle below method is used.
        sliderView!!.startAutoCycle();
        chkDontshowagain = findViewById(R.id.chkDontshowagain)
        chkDontshowagain!!.setOnCheckedChangeListener(CompoundButton.OnCheckedChangeListener { buttonView, isChecked ->
            if (isChecked) {
                isRemember = true
                TokenStorage.savedSharedToken(
                  activity,
                    ConstantUtility.BOARDINGTOUR,
                   "1"
                )

//                val intent = Intent(activity, LogInActivity::class.java)
//                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
                ConstantUtility.isOnboardingtour = true
//                 startActivity(intent)
//                 finish()
                Log.e("chkDontshowagain", "::::>> " + chkDontshowagain!!.isChecked())
            } else {
                ConstantUtility.isOnboardingtour = false
            }
        })

        if (auth_token_passcode != null && auth_token != null) {
            btnLogin!!.text = "Skip"
        } else {
            btnLogin!!.text = "Sign In"

        }

        tvSkip!!.setOnClickListener(this)
        tvNext!!.setOnClickListener(this)
        btnLogin!!.setOnClickListener(this)
        btnprivacy!!.setOnClickListener(this)
        btnhelp!!.setOnClickListener(this)
    }

    override fun onClick(v: View) {
        val intent: Intent
        when (v.id) {
            R.id.tvSkip ->{
//                if (Preferences.getRememberMe() != null) {
//                intent = Intent(activity, HomeActivity::class.java)
//                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
////                intent.putExtra(Constants.FROM_SCREEN, "way_to_home")
//                startActivity(intent)
//                finish()
//            } else {
                intent = Intent(activity, LogInActivity::class.java)
                intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
                //   Preferences.setOnboardingtour("1");
                startActivity(intent)
                finish()

            }

            R.id.btnprivacy ->{
                val privacyDialog = PrivacyDialogOnboard(this)
                privacyDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                privacyDialog.show()
            }
             R.id.btnhelp ->{
                 val supportDialog = SupportDialogOnboard(this)
                 supportDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                 supportDialog.show()
            }

            R.id.tvNext -> if (tvNext!!.text.toString() == "GOT IT") {
                //      Preferences.setOnboardingtour("1");
//                if (Preferences.getRememberMe() != null) {
//                    intent = Intent(activity, HomeActivity::class.java)
//                    intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
//                    intent.putExtra(Constants.FROM_SCREEN, "way_to_home")
//                    startActivity(intent)
//                    finish()
//                } else {
                    intent = Intent(activity, LogInActivity::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
                    startActivity(intent)
                    finish()
//                }
            } else if (scrollpage == 1) {
                viewpager!!.setCurrentItem(currentPage + 1)
                currentPage = currentPage + 1
            }
            R.id.btnLogin ->{

                //      Preferences.setOnboardingtour("1");
                if (auth_token_passcode != null && auth_token != null) {
                    intent = Intent(activity, MainActivity::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
                    startActivity(intent)
                    finish()
                } else {
                    intent = Intent(activity, LogInActivity::class.java)
                    intent.flags = Intent.FLAG_ACTIVITY_CLEAR_TOP
                    startActivity(intent)
                    finish()
                }
            }
        }
    }
     fun onboadinglist() {
        loadingProgressBar.visibility = View.VISIBLE

//        ShowProgressDialog(activity, getString(R.string.please_wait));
        val postRequest: StringRequest = object : StringRequest(
            Method.POST, ConstantUtility.LOGINGUIDE,
            object : Response.Listener<String> {
                var error_msg: String? = null
                @SuppressLint("LongLogTag")
                override fun onResponse(response: String) {
                    Log.e("!_@@ onboadinglist Resp:>", "onboadinglist===>>>>$response")
                    //                        hideProgressDialog();
                    loadingProgressBar.visibility = View.GONE

                    try {
                        val `object` = JSONObject(response)
                        val message: String
                        val success: String
                        success = `object`.getString("success")
//                        message = `object`.getString("message")
                        val response_code = `object`.getInt("response_code")
                        //                            if (response_code == 403) {
//                                ApplicationHelper.INSTANCE.showToast(activity, Constants.SERVER_ISSUE);
//                            } else if (response_code == 500) {
//                                ApplicationHelper.INSTANCE.showToast(activity, Constants.SERVER_ISSUE);
//                            } else {
//
//                            }
                            if (success.equals("true")) {
                             var jarray: JSONArray = `object`.getJSONArray("data")

                                for (j in 0 until jarray.length()) {
                                    val jobj: JSONObject = jarray.getJSONObject(j)
                                    var image = jobj.getString("image");
                                    var title = jobj.getString("title");
                                    var description = jobj.getString("description");

                                    bean = OnBoardingItem()
                                    bean!!.setPic(image)
                                    bean!!.setHeading(title)
                                    bean!!.setDetails(description)
                                    onboardlist.add(bean)

                                    Log.e(TAG, "::>>> " + onboardlist.size)
                                    mAdapter = OnboardingAdapter(activity as OnBoardingActivity, onboardlist)
                                    sliderView!!.setAutoCycleDirection(SliderView.LAYOUT_DIRECTION_LTR);

                                    // below method is used to
                                    // setadapter to sliderview.
                                    sliderView!!.setSliderAdapter(mAdapter!!);
                                }
                            } else{

                            }
//                                Utils.showAlert(activity, getString(R.string.app_name), message);
                    } catch (e: Exception) {
                        e.printStackTrace()
                        Log.e("error..", ": $e")
                    }
                }
            },
            Response.ErrorListener { error ->
                Log.e("Errorlist", error.toString())
                loadingProgressBar.visibility = View.GONE

                //                hideProgressDialog();
                //                Toast.makeText(getApplicationContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
                Log.e(TAG, "error fail>>: " + error.message)
            }) {
            override fun getParams(): Map<String, String>? {
                val params: MutableMap<String, String> = HashMap()

                Log.e("!_@@ Params :", "" + params)
                return params
            }

            @Throws(AuthFailureError::class)
            override fun getHeaders(): Map<String, String> {
                val headers: MutableMap<String, String> = HashMap()
                headers["User-Agent"] = System.getProperty("http.agent")
                headers["Accept"] = "application/json"
                headers["Authorization"] = "Bearer"
                Log.e("!_@@ headers :", "" + headers)
                return headers
            }
        }
        val queue = Volley.newRequestQueue(this)
        queue.cache.clear()
        postRequest.retryPolicy =
            DefaultRetryPolicy(
                60000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT
            )
        queue.add(postRequest)
    }

    companion object {
        private const val TAG = "OnBoardingTourActivity"
        private var activity: Activity? = null
    }
}
