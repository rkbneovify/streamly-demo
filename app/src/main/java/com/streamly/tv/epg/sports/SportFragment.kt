package com.streamly.tv.epg.sports

import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.text.Spanned
import android.text.SpannedString
import android.util.Log
import android.widget.Toast
import com.streamly.tv.ProgramGuideFragment
import com.streamly.tv.entity.ProgramGuideChannel
import com.streamly.tv.entity.ProgramGuideSchedule
import com.streamly.tv.HomeBean
import com.streamly.tv.VerticalHomeBean
import com.streamly.tv.local_storage.TokenStorage
import com.streamly.tv.playervideo.PlayerActivity
import com.streamly.tv.retrofit_clients.ApiClient
import com.streamly.tv.retrofit_clients.ApiInterface
import com.streamly.tv.utilities.ConstantUtility
import io.reactivex.android.schedulers.AndroidSchedulers
import io.reactivex.schedulers.Schedulers
import org.threeten.bp.Instant
import org.threeten.bp.LocalDate
import org.threeten.bp.ZoneOffset
import org.threeten.bp.ZonedDateTime
import org.threeten.bp.format.DateTimeFormatter
import org.threeten.bp.temporal.ChronoUnit
import java.util.concurrent.TimeUnit
import kotlin.random.Random

class SportFragment (context: Context): ProgramGuideFragment<SportFragment.SimpleProgram>() {
//class EpgFragment (context: Context): ProgramGuideFragment<EpgFragment.SimpleProgram>() {


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


    companion object {
        private val TAG = SportFragment::class.java.name
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
            Log.e(TAG, "::::>>>if: \nprogramGuideSchedule.program: " + programGuideSchedule.program
                    + "\nid: " + programGuideSchedule.id
                    + "\ndisplaytitle : " + programGuideSchedule.displayTitle)

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

            Log.e(TAG, "Open live player " + innerSchedule.metadata + "<<>>" + innerSchedule.playUrl
                    + "<<>>" + innerSchedule.id)
        } else {
            Log.e(TAG, "Open detail page " + innerSchedule.metadata + "<<>>" + innerSchedule.playUrl
                    + "<<>>" + innerSchedule.id)
        }
    }

    override fun onResume() {
        super.onResume()
        id_matched = 0
    }

    override fun onScheduleSelected(programGuideSchedule: ProgramGuideSchedule<SimpleProgram>?) {
        // TODO: 17/7/20 Auto stub overridden function

    }

    override fun isTopMenuVisible(): Boolean {
        return false
    }

    @SuppressLint("CheckResult")
    override fun requestingProgramGuideFor(localDate: LocalDate) {
        this.localDate=localDate
        // Faking an asynchronous loading here

        this.localDate = localDate
        // Faking an asynchronous loading here
        setState(State.Loading)
         MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
         MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
         MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(23).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
         MAX_CHANNEL_END_TIME = localDate.plusDays(1).atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)

       val authToken = TokenStorage.readSharedToken(eContext, ConstantUtility.AUTH_TOKEN, null)
        getFirstApi(authToken)
        getSecondApi(authToken)

    }

    private fun getFirstApi(authToken: String) {
        /*for (i in 0 until data.size) {
            channels.add(SimpleChannel(data[i].id.toString(),SpannedString(data[i].callSign),data[i].logo,data[i].url))
        }*/
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        apiInterface.getChannelGenre2("Bearer $authToken")
                .subscribeOn(Schedulers.io())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe({
                    //success

                    it.data.sports.forEach {
                        channels.add(SimpleChannel(it.id.toString(),SpannedString(it.callSign),it.logo,it.url))
                    }

                },{
                    //error
                    Toast.makeText(requireContext(),it.message, Toast.LENGTH_SHORT).show()
                })
    }

    private fun getSecondApi(authToken: String) {
        val apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        apiInterface.getScheduleList("Bearer $authToken").subscribeOn(Schedulers.io()).observeOn(AndroidSchedulers.mainThread())
                   .subscribe(
                           {
                    //success
                           val showNames = ArrayList<String>()
//                           for(i in 0..it.data.size-1){
                               Log.e("### episodeData",it.data.size.toString())
                               for (i in 0 until it.data.size) {
                                   verticalBean = VerticalHomeBean()

//                                   Log.e("###$TAG", "insideIT "+it.data[i].callSign.toString())
                                   verticalBean.headerTitle = it.data[i].callSign
                                   homelist = ArrayList()

                                   Log.e("### programList $i",""+ it.data[i].programs)
                                   it.data[i].programs.forEach {
                                       homeBean = HomeBean()
                                       showNames.add(it.title)
                                       showName_list.add(it.title)

                                       homeBean.title = it.title
                                       homeBean.start_time = it.start
                                       homeBean.end_time = it.end
                                       homelist.add(homeBean)
                                   }

                                   verticalBean.allItemsInSection = homelist
                                   channel_showName_list.add(it.data[i].getmId())
                                   verticallist.add(verticalBean)
                           }

                       val channelMap = mutableMapOf<String, List<ProgramGuideSchedule<SimpleProgram>>>()

                       var k = 0
                       Log.e("### verticalList ","$k <ss>"+ channels.size)

                             verticallist.forEach  first@ { channel ->
//                           Log.e("###${TAG}", "outSideIT " + channel)

                                       if (k<channels.size) {
                                       val scheduleList = mutableListOf<ProgramGuideSchedule<SimpleProgram>>()
                                       var nextTime = randomTimeBetween(MIN_CHANNEL_START_TIME, MAX_CHANNEL_START_TIME)

//                            for (p in 0 until verticallist[k].allItemsInSection!!.size) {
                                       while (nextTime.isBefore(MIN_CHANNEL_END_TIME)) {
                                           Log.e("### allItemsInSection1 ", "" + verticallist[k].allItemsInSection.size)
                                           for (p in 0 until verticallist[k].allItemsInSection!!.size) {


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

                                   }else{
                                           Log.e("### $TAG else ","$k <ss>"+ channels.size)
                                           return@first
                                       }

                                       k += 1
                               }
                       val pair = Pair(channels, channelMap)
                       setData(pair.first, pair.second, localDate)
                       setState(State.Content)



                   },
                           {
                    //error
                    Log.e("###$TAG","error "+it.message)
                    Log.e("###$TAG","error "+it.localizedMessage)
                })
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
        return ZonedDateTime.ofInstant(Instant.ofEpochSecond(randomEpoch), ZoneOffset.UTC)
    }

    override fun requestRefresh() {
        // You can refresh other data here as well.
        requestingProgramGuideFor(currentDate)
    }
}