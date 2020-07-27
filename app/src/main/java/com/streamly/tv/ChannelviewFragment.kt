package com.streamly.tv

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.text.Spanned
import android.text.SpannedString
import android.util.Log
import android.widget.Toast
import com.android.volley.AuthFailureError
import com.android.volley.DefaultRetryPolicy
import com.android.volley.Response
import com.android.volley.toolbox.StringRequest
import com.android.volley.toolbox.Volley
import com.egeniq.androidtvprogramguide.ProgramGuideFragment
import com.egeniq.androidtvprogramguide.entity.ProgramGuideChannel
import com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule
import com.streamly.tv.local_storage.TokenStorage
import com.streamly.tv.playervideo.PlayerActivity
import com.streamly.tv.utilities.ConstantUtility
import org.json.JSONArray
import org.json.JSONObject
import org.threeten.bp.Instant
import org.threeten.bp.LocalDate
import org.threeten.bp.ZoneOffset
import org.threeten.bp.ZonedDateTime
import org.threeten.bp.format.DateTimeFormatter
import org.threeten.bp.temporal.ChronoUnit
import java.util.*
import java.util.concurrent.TimeUnit
import kotlin.collections.ArrayList
import kotlin.collections.List
import kotlin.collections.Map
import kotlin.collections.MutableMap
import kotlin.collections.forEach
import kotlin.collections.mutableListOf
import kotlin.collections.mutableMapOf
import kotlin.collections.set
import kotlin.random.Random

// TODO: Rename parameter arguments, choose names that match
// the fragment initialization parameters, e.g. ARG_ITEM_NUMBER
private const val ARG_PARAM1 = "param1"
private const val ARG_PARAM2 = "param2"

class ChannelviewFragment(context:Context) : ProgramGuideFragment<ChannelviewFragment.SimpleProgram>() {

    val eContext=context
    val channels = ArrayList<SimpleChannel>()
    lateinit var MIN_CHANNEL_START_TIME: ZonedDateTime
    lateinit var MAX_CHANNEL_START_TIME: ZonedDateTime
    lateinit var MIN_CHANNEL_END_TIME: ZonedDateTime
    lateinit var MAX_CHANNEL_END_TIME: ZonedDateTime
    val MIN_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(5)
    val MAX_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(120)
    lateinit var localDate: LocalDate
    val channel_showName_list = ArrayList<String>()
    val showName_list = ArrayList<String>()
    var id_matched: Int? = 0
    var verticalBean = VerticalHomeBean()
    var homeBean = HomeBean()
    var homelist = ArrayList<HomeBean>()
    var verticallist = ArrayList<VerticalHomeBean>()

//     Feel free to change configuration values like this:
//     override val DISPLAY_CURRENT_TIME_INDICATOR = false
//     override val DISPLAY_SHOW_PROGRESS = false

    companion object {
        private val TAG = ChannelviewFragment::class.java.name
    }

    data class SimpleChannel(
        override val id: String,
        override val name: Spanned?,
        override val imageUrl: String?,
        val videoUrl: String?
    ) : ProgramGuideChannel

    // You can put your own data in the program class
    data class SimpleProgram(
        val id: String,
        val description: String,
        val metadata: String,
        val playUrl: String
    )

    override fun onScheduleClicked(programGuideSchedule: ProgramGuideSchedule<SimpleProgram>) {
        val innerSchedule = programGuideSchedule.program
        if (innerSchedule == null) {
            // If this happens, then our data source gives partial info
            Log.w(TAG, "Unable to open schedule: $innerSchedule")
            return
        }
        if (programGuideSchedule.isCurrentProgram) {
            Log.e(
                TAG, "::::>>>if: \nprogramGuideSchedule.program: " + programGuideSchedule.program
                        + "\nid: " + programGuideSchedule.id
                        + "\ndisplaytitle : " + programGuideSchedule.displayTitle
            )

            for (i in 0 until channel_showName_list.size) {
                for (j in 0 until channels.size) {
                    if (channel_showName_list[i].equals(channels.get(j).id)) {

                        if (id_matched == 0) {
                            Log.e(
                                TAG, "::::>>>if: \nchannelname: " + channels[j].name
                                        + "\nid: " + channels[j].id
                                        + "\nvideourl : " + channels[j].videoUrl
                                        + "\n\n csl: " + channel_showName_list[i]
                            )
                            id_matched = 1

                            val intent1 = Intent(activity, PlayerActivity::class.java)
                            intent1.putExtra("from", channels.get(j).videoUrl)
                            startActivity(intent1)

                        }
                    }
                }
            }

            Toast.makeText(context, "Open live player " + innerSchedule.metadata + "<<>>" + innerSchedule.playUrl
                        + "<<>>" + innerSchedule.id, Toast.LENGTH_LONG).show()
        } else {

            Toast.makeText(context, "Open detail page " + innerSchedule.metadata + "<<>>" + innerSchedule.playUrl
                    + "<<>>" + innerSchedule.id, Toast.LENGTH_LONG).show()
        }
    }

    override fun onResume() {
        super.onResume()
        id_matched = 0
    }


    override fun onScheduleSelected(programGuideSchedule: ProgramGuideSchedule<SimpleProgram>?) {
//        val titleView = view?.findViewById<TextView>(com.egeniq.androidtvprogramguide.R.id.programguide_detail_title)
//        titleView?.text = programGuideSchedule?.displayTitle
//        val metadataView = view?.findViewById<TextView>(com.egeniq.androidtvprogramguide.R.id.programguide_detail_metadata)
//        metadataView?.text = programGuideSchedule?.program?.metadata
//        val descriptionView = view?.findViewById<TextView>(com.egeniq.androidtvprogramguide.R.id.programguide_detail_description)
//        descriptionView?.text = programGuideSchedule?.program?.description
//        val imageView = view?.findViewById<ImageView>(com.egeniq.androidtvprogramguide.R.id.programguide_detail_image) ?: return
//        if (programGuideSchedule != null) {
//            Glide.with(imageView)
//                .load("https://picsum.photos/462/240?random=" + programGuideSchedule.displayTitle.hashCode())
//                .centerCrop()
//                .error(com.egeniq.androidtvprogramguide.R.drawable.programguide_icon_placeholder)
//                .diskCacheStrategy(DiskCacheStrategy.ALL)
//                .transition(DrawableTransitionOptions.withCrossFade())
//                .into(imageView)
//        } else {
//            Glide.with(imageView).clear(imageView)
//        }
    }

    override fun isTopMenuVisible(): Boolean {
        return false
    }

    @SuppressLint("CheckResult")
    override fun requestingProgramGuideFor(localDate: LocalDate) {
        // Faking an asynchronous loading here
        setState(State.Loading)

        this.localDate = localDate
        // Faking an asynchronous loading here
        setState(State.Loading)
        /*val*/ MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        /*val*/ MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        /*val*/ MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(23).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        /*val*/ MAX_CHANNEL_END_TIME = localDate.plusDays(1).atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)

        val authToken = TokenStorage.readSharedToken(eContext, ConstantUtility.AUTH_TOKEN, null)
        getChannelList2(authToken)

        getSecondApiHomeBean(authToken)

    }


    private fun getChannelList2(authToken: String) {
        val postRequest: StringRequest = object : StringRequest(Method.GET, ConstantUtility.CHANNELLIST, object : Response.Listener<String> {
                var error_msg: String? = null
                @SuppressLint("LongLogTag")
                override fun onResponse(response: String) {
                    Log.e("!_@@ getChannelList2 Resp:>", "" + response)
                    // Hide ProgressDialog
//                    BaseActivity.hideProgressDialog()
                    try {

                        var success = ""
                        var message = ""
                        var access_token = ""
                        var expires_in = ""
                        val jobj: JSONObject = JSONObject(response)

                        success = jobj.getString("success")
                        if (success.equals("true")) {
                            val jAry: JSONArray = jobj.getJSONObject("data").getJSONArray("Kids")


                            for (i in 0 until jAry.length()) {
                                val jObj = jAry.getJSONObject(i)
                                val id = jObj.getString("id")
                                val callSign = jObj.getString("callSign")
                                val logo = jObj.getString("logo")
                                val url = jObj.getString("url")

                                channels.add(SimpleChannel(id, SpannedString(callSign), logo, url))
                            }
                        }

                    } catch (e: Exception) {
                        e.printStackTrace()
                        Log.e("error..", ": $e")
                    }
                }
            },
            Response.ErrorListener { error ->
                Log.e("Errorlist", error.toString())
                // Hide ProgressDialog
//                BaseActivity.hideProgressDialog()
            }) {
            public override fun getParams(): Map<String, String> {
                val params: MutableMap<String, String> = HashMap()
                Log.e("!_@@ Parms :", "" + params)
                return params
            }

            @Throws(AuthFailureError::class)
            override fun getHeaders(): Map<String, String> {
                val headers = HashMap<String, String>()
                headers["Accept"] = "application/json"
                headers["Authorization"] = "Bearer $authToken"
                Log.e(TAG, "!_@@ Header :" + headers)
                return headers
            }
        }
        val queue = Volley.newRequestQueue(activity!!)
        //set Time Out to 60 sec
        postRequest.retryPolicy = DefaultRetryPolicy(60000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT)
        queue.add(postRequest)
    }



    private fun getSecondApiHomeBean(authToken: String) {
        val postRequest: StringRequest = object : StringRequest(Method.GET, ConstantUtility.CHANNELDAYSSCHEDULE, object : Response.Listener<String> {
                var error_msg: String? = null
                @SuppressLint("LongLogTag")
                override fun onResponse(response: String) {
                    Log.e("!_@@ getSecondApiHomeBean Resp:>", "" + response)
                    // Hide ProgressDialog
//                    BaseActivity.hideProgressDialog()
                    try {

                        var success = ""
                        var message = ""
                        var access_token = ""
                        var expires_in = ""
                        val jobj = JSONObject(response)

                        val showNames = ArrayList<String>()
                        val jAry: JSONArray = jobj.getJSONArray("data")
                        for (i in 0 until jAry.length()) {
                            verticalBean = VerticalHomeBean()
                            val jObj = jAry.getJSONObject(i)
                            val CallSign = jObj.getString("CallSign")
                            val id = jObj.getString("id")
                            val StationName = jObj.getString("StationName")
                            val url = jObj.getString("url")
                            val logo = jObj.getString("logo")

                            val programs = jObj.getJSONArray("programs")

                            verticalBean.headerTitle = CallSign
                            homelist = ArrayList()

                            for (j in 0 until programs.length()) {
                                homeBean = HomeBean()
                                val titleObj = programs.getJSONObject(j)
                                val title = titleObj.getString("title")
                                val start_time = titleObj.getLong("start")
                                val end_time = titleObj.getLong("end")

                                showNames.add(title)
                                showName_list.add(title)
                                homeBean.title = title
                                homeBean.start_time = start_time
                                homeBean.end_time = end_time
                                homelist.add(homeBean)
                            }

                            verticalBean.allItemsInSection = homelist
                            channel_showName_list.add(id)
                            verticallist.add(verticalBean)
                        }

                        val channelMap = mutableMapOf<String, List<ProgramGuideSchedule<SimpleProgram>>>()

                        var k = 0

                            verticallist.forEach first@{ channel ->
                                Log.e("###$TAG", "k " + k)
                                Log.e("###$TAG", "channels.size " + channels.size)
                                if (k<channels.size) {
                                val scheduleList = mutableListOf<ProgramGuideSchedule<SimpleProgram>>()
                                var nextTime = randomTimeBetween(MIN_CHANNEL_START_TIME, MAX_CHANNEL_START_TIME)

//                            for (p in 0 until verticallist[k].allItemsInSection!!.size) {
                                while (nextTime.isBefore(MIN_CHANNEL_END_TIME)) {
                                    for (p in 0 until verticallist[k].allItemsInSection!!.size) {
                                        Log.e("###$TAG", "k2 " + k)
                                        Log.e("###$TAG", "verticallist[k] " + verticallist[k].allItemsInSection!![p].title!!)

                                        val endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(nextTime.toEpochSecond() + Random.nextLong(MIN_SHOW_LENGTH_SECONDS, MAX_SHOW_LENGTH_SECONDS)), ZoneOffset.UTC)

                                        val schedule = createSchedule(channels.get(k), verticallist[k].allItemsInSection!![p].title!!, nextTime, endTime, channels.get(k).videoUrl.toString())
                                        scheduleList.add(schedule)
                                        nextTime = endTime

//                                }

                                        val endTimee =
                                                if (nextTime.isBefore(MAX_CHANNEL_END_TIME)) randomTimeBetween(nextTime, MAX_CHANNEL_END_TIME)
                                                else MAX_CHANNEL_END_TIME

                                        val finalSchedule = createSchedule(channels.get(k), verticallist[k].allItemsInSection!![p].title!!, nextTime, endTimee, channels.get(k).videoUrl.toString())
                                        channelMap.put(channels.get(k).id, scheduleList)

                                    }

                                }

                            }else {
                                    return@first
                                }
                                k += 1
                        }
                        val pair = Pair(channels, channelMap)
                        setData(pair.first, pair.second, localDate)
                        setState(State.Content)

                    } catch (e: Exception) {
                        e.printStackTrace()
                        Log.e("error..", ": $e")
                    }
                }
            },
            Response.ErrorListener { error ->
                Log.e("Errorlist", error.toString())
                // Hide ProgressDialog
//                BaseActivity.hideProgressDialog()
            }) {
            public override fun getParams(): Map<String, String> {
                val params: MutableMap<String, String> = HashMap()
                Log.e("!_@@ Parms :", "" + params)
                return params
            }

            @Throws(AuthFailureError::class)
            override fun getHeaders(): Map<String, String> {
                val headers = HashMap<String, String>()
                headers["Accept"] = "application/json"
                headers["Authorization"] = "Bearer $authToken"
                Log.e(TAG, "!_@@ Header :" + headers)
                return headers
            }
        }
        val queue = Volley.newRequestQueue(activity!!)
        //set Time Out to 60 sec
        postRequest.retryPolicy = DefaultRetryPolicy(60000, DefaultRetryPolicy.DEFAULT_MAX_RETRIES, DefaultRetryPolicy.DEFAULT_BACKOFF_MULT)
        queue.add(postRequest)

    }

    private fun createSchedule(channel: SimpleChannel, scheduleName: String, startTime: ZonedDateTime, endTime: ZonedDateTime, playUrl: String): ProgramGuideSchedule<SimpleProgram> {
        val id = Random.nextLong(100_000L)
        val metadata = DateTimeFormatter.ofPattern("'Starts at' HH:mm").format(startTime)
        return ProgramGuideSchedule.createScheduleWithProgram(id, startTime.toInstant(), endTime.toInstant(), true, scheduleName,
            SimpleProgram(id.toString(), "This is an example description for the programme. This description is taken from the SimpleProgram class, so by using a different class, " +
                        "you could easily modify the demo to use your own class", metadata, playUrl))
    }


    private fun randomTimeBetween(min: ZonedDateTime, max: ZonedDateTime): ZonedDateTime {
        val randomEpoch = Random.nextLong(min.toEpochSecond(), max.toEpochSecond())
//        Log.e(TAG, ":::>>>randomTimeBetween: " + ZonedDateTime.ofInstant(Instant.ofEpochSecond(randomEpoch), ZoneOffset.UTC))

        return ZonedDateTime.ofInstant(Instant.ofEpochSecond(randomEpoch), ZoneOffset.UTC)
    }

    override fun requestRefresh() {
        // You can refresh other data here as well.
        requestingProgramGuideFor(currentDate)
    }
}