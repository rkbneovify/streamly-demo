package com.streamly.tv

import android.annotation.SuppressLint
import android.content.Intent
import android.text.Spanned
import android.text.SpannedString
import android.util.Log
import android.widget.ImageView
import android.widget.TextView
import android.widget.Toast
import com.bumptech.glide.Glide
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions.withCrossFade
import com.egeniq.androidtvprogramguide.ProgramGuideFragment
import com.egeniq.androidtvprogramguide.entity.ProgramGuideChannel
import com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule
import com.google.gson.Gson
import com.streamly.tv.channelschedule.ChannelScheduleModel
import com.streamly.tv.epg.ChannellistModel
import com.streamly.tv.playervideo.PlayerActivity
import com.streamly.tv.retrofit_clients.ApiClient
import com.streamly.tv.retrofit_clients.ApiInterface
import com.streamly.tv.services.NetworkCallResponse
import com.streamly.tv.services.calls.GetChannelList
import com.streamly.tv.services.calls.GetScheduleList
import io.reactivex.android.schedulers.AndroidSchedulers
import io.reactivex.schedulers.Schedulers
import okhttp3.ResponseBody
import org.threeten.bp.Instant
import org.threeten.bp.LocalDate
import org.threeten.bp.ZoneOffset
import org.threeten.bp.ZonedDateTime
import org.threeten.bp.format.DateTimeFormatter
import org.threeten.bp.temporal.ChronoUnit
import retrofit2.Response
import java.util.concurrent.TimeUnit
import kotlin.random.Random

class EpgFragment : ProgramGuideFragment<EpgFragment.SimpleProgram>(), NetworkCallResponse {


    val channels = ArrayList<SimpleChannel>()
    lateinit var authToken :String
    lateinit var apiInterface : ApiInterface
    lateinit var MIN_CHANNEL_START_TIME : ZonedDateTime
    lateinit var MAX_CHANNEL_START_TIME : ZonedDateTime
    lateinit var MIN_CHANNEL_END_TIME : ZonedDateTime
    lateinit var MAX_CHANNEL_END_TIME : ZonedDateTime
    val MIN_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(5)
    val MAX_SHOW_LENGTH_SECONDS= TimeUnit.MINUTES.toSeconds(120)
    lateinit var localDate: LocalDate

    companion object {
        private val TAG = EpgFragment::class.java.name

//        var list: List<Datum> = ArrayList()
//        var channels : List<SimpleChannel> = ArrayList()
    }

    data class SimpleChannel(
            override val id: String,
            override val name: Spanned?,
            override val imageUrl: String?) : ProgramGuideChannel

    // You can put your own data in the program class
    data class SimpleProgram(
            val id: String,
            val description: String,
            val metadata: String
    )

    override fun onScheduleClicked(schedule: ProgramGuideSchedule<SimpleProgram>) {
        val innerSchedule = schedule.program
        if (innerSchedule == null) {
            // If this happens, then our data source gives partial info
            Log.w(TAG, "Unable to open schedule: $innerSchedule")
            return
        }
        if (schedule.isCurrentProgram) {
//            Toast.makeText(context, "Open live player", Toast.LENGTH_LONG).show()
            val intent = Intent(context, PlayerActivity::class.java)
            startActivity(intent)
        } else {
            Toast.makeText(context, "Open detail page", Toast.LENGTH_LONG).show()
        }
    }

    override fun onScheduleSelected(programGuideSchedule: ProgramGuideSchedule<SimpleProgram>?) {
        val titleView = view?.findViewById<TextView>(R.id.programguide_detail_title)
        titleView?.text = programGuideSchedule?.displayTitle
        val metadataView = view?.findViewById<TextView>(R.id.programguide_detail_metadata)
        metadataView?.text = programGuideSchedule?.program?.metadata
        val descriptionView = view?.findViewById<TextView>(R.id.programguide_detail_description)
        descriptionView?.text = programGuideSchedule?.program?.description
        val imageView = view?.findViewById<ImageView>(R.id.programguide_detail_image) ?: return
        if (programGuideSchedule != null) {
            Glide.with(imageView)
                    .load("https://picsum.photos/462/240?random=" + programGuideSchedule.displayTitle.hashCode())
                    .centerCrop()
                    .error(R.drawable.programguide_icon_placeholder)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .transition(withCrossFade())
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
        this.localDate=localDate
        // Faking an asynchronous loading here
        setState(State.Loading)
        /*val*/ MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        /*val*/ MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        /*val*/ MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(21).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        /*val*/ MAX_CHANNEL_END_TIME = localDate.plusDays(1).atStartOfDay().withHour(4).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
//        val MIN_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(5)
//        val MAX_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(120)


//        ApiInterface apinterface=

        // Calling Channel List and Schedule List

//        authToken = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null)
        authToken = "dgdsgsffgf"

        /*val getChannelList = GetChannelList()
        getChannelList.callGetChannelListApi("Bearer $authToken",this)

        val getScheduleList = GetScheduleList()
        getScheduleList.callGetScheduleListApi("Bearer $authToken",this)*/

         apiInterface = ApiClient.getClient().create(ApiInterface::class.java)
        apiInterface.getChannelList2("Bearer $authToken")
                .subscribeOn(Schedulers.io())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe({
                    //success

                    it.data.forEach {
                        channels.add(SimpleChannel(it.id.toString(),SpannedString(it.callSign),it.logo))
                    }

                    Log.e("### arrayList",channels.toString())
                    getSecondApi()
                },{
                    //error
                    Toast.makeText(requireContext(),it.message,Toast.LENGTH_SHORT).show()
                })


     /*   Single.fromCallable {

//            for (i in list.indices) {
//                Log.e("value ", i.toString())
//             channels = listOf(SimpleChannel("npo-1", SpannedString(list[i].callSign), list[i].logo))
//                    }
           val channels = listOf(
                    SimpleChannel("npo-1", SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png")
                    )

            val showNames = listOf("News", "Sherlock Holmes", "It's Always Sunny In Philadelphia", "Second World War Documentary", "World Cup Final Replay", "Game of Thrones",
                    "NFL Sunday Night Football", "NCIS", "Seinfeld", "ER", "Who Wants To Be A Millionaire", "Our Planet", "Friends", "House of Cards")

            val channelMap = mutableMapOf<String, List<ProgramGuideSchedule<SimpleProgram>>>()

            channels.forEach { channel ->
                val scheduleList = mutableListOf<ProgramGuideSchedule<SimpleProgram>>()
                var nextTime = randomTimeBetween(MIN_CHANNEL_START_TIME, MAX_CHANNEL_START_TIME)
                while (nextTime.isBefore(MIN_CHANNEL_END_TIME)) {
                    val endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(nextTime.toEpochSecond() + Random.nextLong(MIN_SHOW_LENGTH_SECONDS, MAX_SHOW_LENGTH_SECONDS)), ZoneOffset.UTC)
                    val schedule = createSchedule(channel, showNames.random(), nextTime, endTime)
                    scheduleList.add(schedule)
                    nextTime = endTime
                }
                val endTime = if (nextTime.isBefore(MAX_CHANNEL_END_TIME)) randomTimeBetween(nextTime, MAX_CHANNEL_END_TIME) else MAX_CHANNEL_END_TIME
                val finalSchedule = createSchedule(channel, showNames.random(), nextTime, endTime)
                scheduleList.add(finalSchedule)
                channelMap.put(channel.id, scheduleList)
            }
            return@fromCallable Pair(channels, channelMap)
        }.delay(1, TimeUnit.SECONDS)
                .subscribeOn(Schedulers.io())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe({
                    setData(it.first, it.second, localDate)
                    setState(State.Content)
                }, {
                    Log.e(TAG, "Unable to load example data!", it)
                })*/
    }

    private fun getSecondApi() {
        apiInterface.getScheduleList("Bearer $authToken").subscribeOn(Schedulers.io()).observeOn(AndroidSchedulers.mainThread())
                   .subscribe({
                    //success
                    Log.e("###$TAG", it.data.toString())


//                       val showNames = listOf("News", "Sherlock Holmes", "It's Always Sunny In Philadelphia", "Second World War Documentary", "World Cup Final Replay", "Game of Thrones",
//                               "NFL Sunday Night Football", "NCIS", "Seinfeld", "ER", "Who Wants To Be A Millionaire", "Our Planet", "Friends", "House of Cards")

                       val channelMap = mutableMapOf<String, List<ProgramGuideSchedule<SimpleProgram>>>()

                       channels.forEach { channel ->
                           Log.e("###$TAG", "outSideIT "+channel.name.toString())
                           val showNames = ArrayList<String>()
                           for(i in 0..it.data.size-1){

                               if(channel.name.toString()!!.equals(it.data[i].callSign.toString())){
                                   Log.e("###$TAG", "insideIT "+it.data[i].callSign.toString())
                                   it.data[i].programs.forEach {
                                       showNames.add(it.title)
                                   }
                               }
                           }
//


                           val scheduleList = mutableListOf<ProgramGuideSchedule<SimpleProgram>>()
                           var nextTime = randomTimeBetween(MIN_CHANNEL_START_TIME, MAX_CHANNEL_START_TIME)
                           while (nextTime.isBefore(MIN_CHANNEL_END_TIME)) {
                               val endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(nextTime.toEpochSecond() + Random.nextLong(MIN_SHOW_LENGTH_SECONDS, MAX_SHOW_LENGTH_SECONDS)), ZoneOffset.UTC)
                               val schedule = createSchedule(channel, showNames.random(), nextTime, endTime)
                               scheduleList.add(schedule)
                               nextTime = endTime
                           }
                           val endTime = if (nextTime.isBefore(MAX_CHANNEL_END_TIME)) randomTimeBetween(nextTime, MAX_CHANNEL_END_TIME) else MAX_CHANNEL_END_TIME
                           val finalSchedule = createSchedule(channel, showNames.random(), nextTime, endTime)
                           scheduleList.add(finalSchedule)
                           channelMap.put(channel.id, scheduleList)
                       }
                       val pair = Pair(channels, channelMap)

                       setData(pair.first, pair.second, localDate)
                       setState(State.Content)

                },{
                    //error
                    Log.e("###$TAG",it.message)
                })
    }

    private fun createSchedule(channel: SimpleChannel, scheduleName: String, startTime: ZonedDateTime, endTime: ZonedDateTime): ProgramGuideSchedule<SimpleProgram> {
        val id = Random.nextLong(100_000L)
        val metadata = DateTimeFormatter.ofPattern("'Starts at' HH:mm").format(startTime)
        return ProgramGuideSchedule.createScheduleWithProgram(
                id,
                startTime.toInstant(),
                endTime.toInstant(),
                true,
                scheduleName,
                SimpleProgram(id.toString(),
                        "This is an example description for the programme. This description is taken from the SimpleProgram class, so by using a different class, " +
                                "you could easily modify the demo to use your own class",
                        metadata)
        )
    }

    private fun randomTimeBetween(min: ZonedDateTime, max: ZonedDateTime): ZonedDateTime {
        val randomEpoch = Random.nextLong(min.toEpochSecond(), max.toEpochSecond())
        return ZonedDateTime.ofInstant(Instant.ofEpochSecond(randomEpoch), ZoneOffset.UTC)
    }

    override fun requestRefresh() {
        // You can refresh other data here as well.
        requestingProgramGuideFor(currentDate)
    }


    // Kotlin Methods to receive responses and failures of the APIS
    override fun networkCallResponse(result: Boolean, apiTag: String, responseBody: Response<ResponseBody>) {
        if(apiTag == GetChannelList::class.java.simpleName)
            handleGetChannelListResponse(result, responseBody)
        else if(apiTag == GetScheduleList::class.java.simpleName)
            handleGetScheduleListResponse(result, responseBody)
    }

    override fun networkFailureResponse(result: Boolean, apiTag: String, throwable: Throwable) {

        Log.e("Failure ", throwable.toString())

    }

    private fun handleGetChannelListResponse(result: Boolean,responseBody: Response<ResponseBody>){
        var channellistModel = ChannellistModel()
        if(result){

//            for (i in list.indices) {
//                Log.e("value ", i.toString())
//                }
            channellistModel = Gson().fromJson(responseBody.body()?.string(), ChannellistModel::class.java)


//    List<SimpleChannel> stringList=new ArrayList<>();

//            list = channellistModel.data
            Log.e("###"+TAG, "channelList "+channellistModel.data)



        }
        else {
//            channellistModel.message = retrieveErrorMessage(responseBody = responseBody)
//            profileLiveData.value = myProfileModel
        }
    }

    private fun handleGetScheduleListResponse(result: Boolean,responseBody: Response<ResponseBody>){
        if(result){
            var ScheduleList = Gson().fromJson(responseBody.body()?.string(), ChannelScheduleModel::class.java)


            var stringList: List<com.streamly.tv.channelschedule.Datum> = ArrayList()

            stringList = ScheduleList.data


            Log.e("###"+TAG, "channelList "+stringList)
        }
    }



}