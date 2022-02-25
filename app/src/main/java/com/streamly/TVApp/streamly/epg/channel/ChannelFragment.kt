package com.streamly.TVApp.streamly.epg.channel

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.text.Spanned
import android.text.SpannedString
import android.util.Log
import android.widget.ImageView
import android.widget.TextView
import com.bumptech.glide.Glide
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions
import com.streamly.TVApp.streamly.HomeBean
import com.streamly.TVApp.streamly.ProgramGuideFragment
import com.streamly.TVApp.streamly.R
import com.streamly.TVApp.streamly.VerticalHomeBean
import com.streamly.TVApp.streamly.dialogs.SessionExpiredDialog
import com.streamly.TVApp.streamly.entity.ProgramGuideChannel
import com.streamly.TVApp.streamly.entity.ProgramGuideSchedule
import com.streamly.TVApp.streamly.epg.ChannellistModel
import com.streamly.TVApp.streamly.epg.Datum
import com.streamly.TVApp.streamly.local_storage.TokenStorage
import com.streamly.TVApp.streamly.playervideo.PlayerActivity
import com.streamly.TVApp.streamly.retrofit_clients.ApiClient
import com.streamly.TVApp.streamly.retrofit_clients.ApiInterface
import com.streamly.TVApp.streamly.utilities.CommonUtility
import com.streamly.TVApp.streamly.utilities.ConstantUtility
import com.streamly.TVApp.streamly.utilities.NetworkKeys
import io.reactivex.android.schedulers.AndroidSchedulers
import io.reactivex.schedulers.Schedulers
import org.threeten.bp.Instant
import org.threeten.bp.LocalDate
import org.threeten.bp.ZoneOffset
import org.threeten.bp.ZonedDateTime
import org.threeten.bp.temporal.ChronoUnit
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.text.SimpleDateFormat
import java.util.*
import java.util.concurrent.TimeUnit
import java.util.regex.Matcher
import java.util.regex.Pattern
import kotlin.random.Random

class ChannelFragment(context: Context, val genre: String) :
    ProgramGuideFragment<ChannelFragment.SimpleProgram>() {
    //class EpgFragment (context: Context): ProgramGuideFragment<EpgFragment.SimpleProgram>() {
    val eContext = context
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
    var recentList: List<Datum>? = null


    companion object {
        private val TAG = ChannelFragment::class.java.name
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
        val endTimeMetadata: String,
        val playUrl: String,
        val imageUrl: String
    )


    //#neovifyissueresolved28sep
    override fun onScheduleClicked(
        programGuideSchedule: ProgramGuideSchedule<SimpleProgram>,
        channelId: String
    ) {
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

            /*for (i in 0 until channel_showName_list.size) {
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
           }*/

            val intent1 = Intent(activity, PlayerActivity::class.java)
            intent1.putExtra("from", innerSchedule.playUrl)
            intent1.putExtra("id", channelId)
            startActivity(intent1)

            Log.e(
                TAG, "Open live player " + innerSchedule.metadata + "<<>>" + innerSchedule.playUrl
                        + "<<>>" + innerSchedule.id
            )
        } else {
            Log.e(
                TAG, "Open detail page " + innerSchedule.metadata + "<<>>" + innerSchedule.playUrl
                        + "<<>>" + innerSchedule.id
            )


        }
    }

    override fun onResume() {
        super.onResume()
        id_matched = 0
    }

    override fun onScheduleSelected(programGuideSchedule: ProgramGuideSchedule<SimpleProgram>?) {
        val titleView = view?.findViewById<TextView>(R.id.programguide_detail_title)
        titleView?.text = programGuideSchedule?.displayTitle
        val metadataView = view?.findViewById<TextView>(R.id.programguide_detail_metadata)
        metadataView?.text = programGuideSchedule?.program?.metadata
//        val endTimeMetaDataView = view?.findViewById<TextView>(R.id.programguide_detail_end_time_metadata)
//        endTimeMetaDataView?.text = programGuideSchedule?.program?.endTimeMetadata
        val descriptionView = view?.findViewById<TextView>(R.id.programguide_detail_description)
        descriptionView?.text = programGuideSchedule?.program?.description
        val imageView = view?.findViewById<ImageView>(R.id.programguide_detail_image) ?: return
        if (programGuideSchedule != null) {
            Glide.with(imageView)
                .load(programGuideSchedule.program?.imageUrl.toString())
                .error(R.drawable.programguide_icon_placeholder)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .transition(DrawableTransitionOptions.withCrossFade())
                .into(imageView)
        } else {
            Glide.with(imageView).clear(imageView)
        }

    }

    override fun isTopMenuVisible(): Boolean {
        return false
    }

    @SuppressLint("CheckResult")
    override fun requestingProgramGuideFor(localDate: LocalDate) {
        this.localDate = localDate
        // Faking an asynchronous loading here

        this.localDate = localDate
        // Faking an asynchronous loading here
        setState(State.Loading)

        MIN_CHANNEL_START_TIME =
            localDate.atStartOfDay().minusDays(1).withHour(2).truncatedTo(ChronoUnit.HOURS)
                .atZone(DISPLAY_TIMEZONE)
        MAX_CHANNEL_START_TIME =
            localDate.atStartOfDay().minusDays(1).withHour(8).truncatedTo(ChronoUnit.HOURS)
                .atZone(DISPLAY_TIMEZONE)
        MIN_CHANNEL_END_TIME =
            localDate.atStartOfDay().plusDays(1).withHour(21).truncatedTo(ChronoUnit.HOURS)
                .atZone(DISPLAY_TIMEZONE)
        MAX_CHANNEL_END_TIME =
            localDate.atStartOfDay().plusDays(2).withHour(4).truncatedTo(ChronoUnit.HOURS)
                .atZone(DISPLAY_TIMEZONE)

        /* MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
         MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
         MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(23).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
         MAX_CHANNEL_END_TIME = localDate.plusDays(1).atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)*/

        val authToken = TokenStorage.readSharedToken(eContext, ConstantUtility.AUTH_TOKEN, null)
        if (genre.lowercase().equals("recent")) {
            getRecentList(authToken)
        } else {
            getSecondApi(authToken)
        }
    }

    private fun getRecentList(authToken: String) {
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        val call = apiInterface.getRecentList("Bearer $authToken")
        call.enqueue(object : Callback<ChannellistModel?> {
            override fun onResponse(
                call: Call<ChannellistModel?>,
                response: Response<ChannellistModel?>
            ) {
                try {
                    if (response.isSuccessful && response.body() != null && response.code() == 200) {
                        recentList = response.body()!!.data
                        getSecondApi(authToken)
                    } else if (response.code() == 401) {
                        if (response.errorBody() != null) {
                            val sessionExpiredDialog = SessionExpiredDialog(requireContext())
                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                            sessionExpiredDialog.setCancelable(false)
                            sessionExpiredDialog.show()
                        }
                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(requireContext())
                        CommonUtility.showToast(requireContext(), ConstantUtility.SESSION_EXPIRED)
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(requireContext(), ConstantUtility.SERVER_ISSUE)
                    } else if (response.code() == 402) {
//                        (requireActivity() as MainActivity).settingText.setVisibility(View.GONE)
//                        imageSearch.setVisibility(View.GONE)
                        CommonUtility.showToast(
                            requireContext(),
                            "Payment Required. Kindly buy subscription"
                        )
                    }
                } catch (ex: Exception) {
                    ex.printStackTrace()
                    Log.e(TAG, "Exception $ex")
                }
            }

            override fun onFailure(call: Call<ChannellistModel?>, t: Throwable) {
                Log.e(TAG, "failure: " + call + " " + t.localizedMessage)
                CommonUtility.showToast(requireContext(), ConstantUtility.SERVER_ISSUE)
            }
        })
    }

    //#neovifyissueresolved28sep
    @SuppressLint("CheckResult")
    private fun getSecondApi(authToken: String) {
        var dayss: Int? = 1
        val timeZoneString = SimpleDateFormat("ZZZZZ").format(System.currentTimeMillis())
//        val timeZoneString = "-08:00"
        val apiInterface = ApiClient.getClient3().create(ApiInterface::class.java)
        Log.d("authTocken", "Bearer $authToken")
        if (genre.lowercase().equals("all") or genre.lowercase().equals("recent")) {
            apiInterface.getScheduleListWithoutGenre(
                timeZoneString,
                NetworkKeys.KEY_APP_JSON,
                "Bearer $authToken"
            )
                .subscribeOn(Schedulers.io()).observeOn(AndroidSchedulers.mainThread())
                .subscribe(
                    {
                        //success
                        Log.e(TAG, "GET SECHEDULELIST " + it.message);
                        val showNames = ArrayList<String>()
//                           for(i in 0..it.data.size-1){
                        Log.e("### episodeData", it.data.size.toString())

                        //#neovifyissueresolved28sep
                        val p: Pattern = Pattern.compile("\\d+")
                        val m: Matcher = p.matcher(it.message)
                        while (m.find()) {
                            Log.e(TAG, "::>>>digit: " + m.group())
                            dayss = (m.group()).toInt()
                        }
//                        checking for gener type is all or recent if recent the add only those channel who are in recent list
                        if (genre.lowercase().equals("recent")) {
                            for (d in it.data) {
                                recentList?.let { recentList ->
                                    for (recent in recentList) {
                                        if (recent.id.equals(d.getmId())) {
                                            channels.add(
                                                SimpleChannel(
                                                    d.getmId().toString(),
                                                    SpannedString(d.callSign.toString()),
                                                    d.logo,
                                                    d.channelUrl
                                                )
                                            )
                                        }
                                    }
                                }
                            }
                        } else {
                            for (d in it.data) {
                                channels.add(
                                    SimpleChannel(
                                        d.getmId().toString(),
                                        SpannedString(d.callSign.toString()),
                                        d.logo,
                                        d.channelUrl
                                    )
                                )
                            }
                        }

                        //#neovifyissueresolved28sep
                        for (i in 0 until it.data.size) {
//                            here i am saperate the data by all and recent genre
                            if (genre.lowercase().equals("recent")) {
                                for (recent in recentList!!){
                                    if(recent.id.equals(it.data[i].getmId())){
                                        verticalBean = VerticalHomeBean()
                                        verticalBean.headerTitle = it.data[i].callSign
                                        verticalBean.channelId = it.data[i].getmId();
                                        verticalBean.logoUrl = it.data[i].logo
                                        verticalBean.baseUrl = it.data[i].channelUrl
                                        Log.e(TAG, "call sign 1 " + it.data[i].callSign)

                                        homelist = ArrayList()

                                        it.data[i].programs.forEach {
                                            homeBean = HomeBean()

                                            showNames.add(it.title)
                                            showName_list.add(it.title)

                                            val st =
                                                Instant.ofEpochMilli((it.startTz.toString() + "000").toLong())
                                            val et =
                                                Instant.ofEpochMilli((it.endTz.toString() + "000").toLong())

                                            homeBean.title = it.title
                                            homeBean.description = it.description
                                            homeBean.start_time = st
                                            homeBean.end_time = et
                                            homelist.add(homeBean)
                                        }

                                        verticalBean.allItemsInSection = homelist
                                        channel_showName_list.add(it.data[i].getmId().toString())
                                        verticallist.add(verticalBean)
                                    }
                                }
                            } else {
                                verticalBean = VerticalHomeBean()
                                verticalBean.headerTitle = it.data[i].callSign
                                verticalBean.channelId = it.data[i].getmId();
                                verticalBean.logoUrl = it.data[i].logo
                                verticalBean.baseUrl = it.data[i].channelUrl
                                Log.e(TAG, "call sign 1 " + it.data[i].callSign)

                                homelist = ArrayList()

                                it.data[i].programs.forEach {
                                    homeBean = HomeBean()

                                    showNames.add(it.title)
                                    showName_list.add(it.title)

                                    val st =
                                        Instant.ofEpochMilli((it.startTz.toString() + "000").toLong())
                                    val et =
                                        Instant.ofEpochMilli((it.endTz.toString() + "000").toLong())

                                    homeBean.title = it.title
                                    homeBean.description = it.description
                                    homeBean.start_time = st
                                    homeBean.end_time = et
                                    homelist.add(homeBean)
                                }

                                verticalBean.allItemsInSection = homelist
                                channel_showName_list.add(it.data[i].getmId().toString())
                                verticallist.add(verticalBean)
                            }
                        }
                        val channelMap =
                            mutableMapOf<String, List<ProgramGuideSchedule<SimpleProgram>>>()

                        var k = 0
                        Log.e("### verticalList ", "$k <ss>" + channels.size)

                        verticallist.forEach first@{ channel ->
                            if (k < channels.size) {
                                val scheduleList =
                                    mutableListOf<ProgramGuideSchedule<SimpleProgram>>()
                                var nextTime = randomTimeBetween(
                                    MIN_CHANNEL_START_TIME,
                                    MAX_CHANNEL_START_TIME
                                )
                                for (p in 0 until verticallist[k].allItemsInSection!!.size) {
                                    val endTime = ZonedDateTime.ofInstant(
                                        Instant.ofEpochSecond(
                                            nextTime.toEpochSecond() + Random.nextLong(
                                                MIN_SHOW_LENGTH_SECONDS,
                                                MAX_SHOW_LENGTH_SECONDS
                                            )
                                        ), ZoneOffset.systemDefault()
                                    )


                                    val schedule = createSchedule(
                                        channels.get(k),
                                        verticallist[k].allItemsInSection!![p].title!!,
                                        verticallist[k].allItemsInSection!![p]!!.start_time!!,
                                        verticallist[k].allItemsInSection!![p]!!.end_time!!,
                                        verticallist[k].allItemsInSection!![p].description!!,
                                        channels.get(k).videoUrl.toString(),
                                        channels.get(k).imageUrl.toString()
                                    )
                                    Log.e(TAG, "getSecondApi: " + channels.get(k).id)
                                    Log.e(TAG, "getSecondApi 2 : " + channels.get(k).name)

                                    scheduleList.add(schedule)
                                    nextTime = endTime
                                }
                                channelMap.put(channel.channelId.toString(), scheduleList)
                            } else {
                                Log.e("### $TAG else ", "$k <ss>" + channels.size)
                                return@first
                            }

                            k += 1
                        }
                        val pair = Pair(channels, channelMap)
                        setData(pair.first, pair.second, localDate, dayss!!)
                        setState(State.Content)

                        if (it.responseCode.toInt() == 401) {
                            val sessionExpiredDialog = SessionExpiredDialog(eContext)
                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                            sessionExpiredDialog.setCancelable(false)
                            sessionExpiredDialog.show()
                        }

                    },
                    {
                        //error
                        Log.e("###$TAG", "error " + it.message)
                        Log.e("###$TAG", "error " + it.localizedMessage)
                        if (it.message.toString().contains("401")) {
                            val sessionExpiredDialog =
                                SessionExpiredDialog(eContext)
                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                            sessionExpiredDialog.setCancelable(false)
                            sessionExpiredDialog.show()
                        } else if (it.message.toString().contains("402")) {
                            CommonUtility.showToast(
                                eContext,
                                "Payment Required. Kindly buy subscription"
                            )

                        }

                    })
        } else {
            apiInterface.getScheduleListWithTimeZone(
                timeZoneString,
                genre,
                NetworkKeys.KEY_APP_JSON,
                "Bearer $authToken"
            )
                .subscribeOn(Schedulers.io()).observeOn(AndroidSchedulers.mainThread())
                .subscribe(
                    {
                        //success
                        Log.e(TAG, "GET SECHEDULELIST " + it.message);
                        val showNames = ArrayList<String>()
//                           for(i in 0..it.data.size-1){
                        Log.e("### episodeData", it.data.size.toString())

                        //#neovifyissueresolved28sep
                        val p: Pattern = Pattern.compile("\\d+")
                        val m: Matcher = p.matcher(it.message)
                        while (m.find()) {
                            Log.e(TAG, "::>>>digit: " + m.group())
                            dayss = (m.group()).toInt()
                        }
                        for (d in it.data) {
                            channels.add(
                                SimpleChannel(
                                    d.getmId().toString(),
                                    SpannedString(d.callSign.toString()),
                                    d.logo,
                                    d.channelUrl
                                )
                            )
                        }
                        //#neovifyissueresolved28sep
                        for (i in 0 until it.data.size) {
                            verticalBean = VerticalHomeBean()
                            verticalBean.headerTitle = it.data[i].callSign
                            verticalBean.channelId = it.data[i].getmId();
                            verticalBean.logoUrl = it.data[i].logo
                            verticalBean.baseUrl = it.data[i].channelUrl
                            Log.e(TAG, "call sign 1 " + it.data[i].callSign)

                            homelist = ArrayList()

                            it.data[i].programs.forEach {
                                homeBean = HomeBean()

                                showNames.add(it.title)
                                showName_list.add(it.title)

                                val st =
                                    Instant.ofEpochMilli((it.startTz.toString() + "000").toLong())
                                val et =
                                    Instant.ofEpochMilli((it.endTz.toString() + "000").toLong())

                                homeBean.title = it.title
                                homeBean.description = it.description
                                homeBean.start_time = st
                                homeBean.end_time = et
                                homelist.add(homeBean)
                            }

                            verticalBean.allItemsInSection = homelist
                            channel_showName_list.add(it.data[i].getmId().toString())
                            verticallist.add(verticalBean)
                        }
                        val channelMap =
                            mutableMapOf<String, List<ProgramGuideSchedule<SimpleProgram>>>()

                        var k = 0
                        Log.e("### verticalList ", "$k <ss>" + channels.size)

                        verticallist.forEach first@{ channel ->
                            if (k < channels.size) {
                                val scheduleList =
                                    mutableListOf<ProgramGuideSchedule<SimpleProgram>>()
                                var nextTime = randomTimeBetween(
                                    MIN_CHANNEL_START_TIME,
                                    MAX_CHANNEL_START_TIME
                                )
                                for (p in 0 until verticallist[k].allItemsInSection!!.size) {
                                    val endTime = ZonedDateTime.ofInstant(
                                        Instant.ofEpochSecond(
                                            nextTime.toEpochSecond() + Random.nextLong(
                                                MIN_SHOW_LENGTH_SECONDS,
                                                MAX_SHOW_LENGTH_SECONDS
                                            )
                                        ), ZoneOffset.systemDefault()
                                    )


                                    val schedule = createSchedule(
                                        channels.get(k),
                                        verticallist[k].allItemsInSection!![p].title!!,
                                        verticallist[k].allItemsInSection!![p]!!.start_time!!,
                                        verticallist[k].allItemsInSection!![p]!!.end_time!!,
                                        verticallist[k].allItemsInSection!![p].description!!,
                                        channels.get(k).videoUrl.toString(),
                                        channels.get(k).imageUrl.toString()
                                    )
                                    Log.e(TAG, "getSecondApi: " + channels.get(k).id)
                                    Log.e(TAG, "getSecondApi 2 : " + channels.get(k).name)

                                    scheduleList.add(schedule)
                                    nextTime = endTime
                                }
                                channelMap.put(channel.channelId.toString(), scheduleList)
                            } else {
                                Log.e("### $TAG else ", "$k <ss>" + channels.size)
                                return@first
                            }

                            k += 1
                        }
                        val pair = Pair(channels, channelMap)
                        setData(pair.first, pair.second, localDate, dayss!!)
                        setState(State.Content)

                        if (it.responseCode.toInt() == 401) {
                            val sessionExpiredDialog = SessionExpiredDialog(eContext)
                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                            sessionExpiredDialog.setCancelable(false)
                            sessionExpiredDialog.show()
                        }

                    },
                    {
                        //error
                        Log.e("###$TAG", "error " + it.message)
                        Log.e("###$TAG", "error " + it.localizedMessage)
                        if (it.message.toString().contains("401")) {
                            val sessionExpiredDialog =
                                SessionExpiredDialog(eContext)
                            sessionExpiredDialog.window!!.setBackgroundDrawableResource(R.drawable.white_transparent)
                            sessionExpiredDialog.setCancelable(false)
                            sessionExpiredDialog.show()
                        } else if (it.message.toString().contains("402")) {
                            CommonUtility.showToast(
                                eContext,
                                "Payment Required. Kindly buy subscription"
                            )

                        }

                    })
        }

    }


    //#neovifyissueresolved28sep
    private fun createSchedule(
        channel: SimpleChannel,
        scheduleName: String,
        startTime: Instant,
        endTime: Instant,
//            startTime: ZonedDateTime,
//            endTime: ZonedDateTime,
        description: String,
        playUrl: String,
        imageUrl: String
    ): ProgramGuideSchedule<SimpleProgram> {
        val id = Random.nextLong(100_000L)
//        val metadata = DateTimeFormatter.ofPattern("'Starts at' HH:mm").format(startTime)
        var metadata = getDate(startTime.toEpochMilli(), "HH:mm")
        val endTimeMetadata = getDate(endTime.toEpochMilli(), "HH:mm")
        metadata = "$metadata - $endTimeMetadata"
        return ProgramGuideSchedule.createScheduleWithProgram(
            id, startTime, endTime, true, scheduleName,
//        return ProgramGuideSchedule.createScheduleWithProgram(id, startTime.toInstant(), endTime.toInstant(), true, scheduleName,
            SimpleProgram(
                id.toString(), description,
                metadata + "",
                endTimeMetadata + "",
                playUrl, imageUrl
            )
        )

    }

    /**
     * Return date in specified format.
     * @param milliSeconds Date in milliseconds
     * @param dateFormat Date format
     * @return String representing date in specified format
     */
    fun getDate(milliSeconds: Long, dateFormat: String?): String? {
        // Create a DateFormatter object for displaying date in specified format.
        val formatter = SimpleDateFormat(dateFormat)
        // Create a calendar object that will convert the date and time value in milliseconds to date.
        val calendar: Calendar = Calendar.getInstance()
        calendar.setTimeInMillis(milliSeconds)
        return formatter.format(calendar.getTime())
    }

    private fun randomTimeBetween(min: ZonedDateTime, max: ZonedDateTime): ZonedDateTime {
        val randomEpoch = Random.nextLong(min.toEpochSecond(), max.toEpochSecond())
        return ZonedDateTime.ofInstant(
            Instant.ofEpochSecond(randomEpoch),
            ZoneOffset.systemDefault()
        )
    }

    override fun requestRefresh() {
        // You can refresh other data here as well.
        requestingProgramGuideFor(currentDate)
    }
}