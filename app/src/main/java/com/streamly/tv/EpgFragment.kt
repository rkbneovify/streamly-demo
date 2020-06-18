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
import com.streamly.tv.local_storage.TokenStorage
import com.streamly.tv.playervideo.PlayerActivity
import com.streamly.tv.retrofit_clients.ApiClient
import com.streamly.tv.retrofit_clients.ApiInterface
import com.streamly.tv.utilities.ConstantUtility
import com.streamly.tv.utilities.NetworkKeys
import io.reactivex.Single
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

class EpgFragment : ProgramGuideFragment<EpgFragment.SimpleProgram>() {

    // Feel free to change configuration values like this:
    // override val DISPLAY_CURRENT_TIME_INDICATOR = false
    // override val DISPLAY_SHOW_PROGRESS = false

    companion object {
        private val TAG = EpgFragment::class.java.name
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
        // Faking an asynchronous loading here
        setState(State.Loading)

        val MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        val MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)

        val MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(21).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)
        val MAX_CHANNEL_END_TIME = localDate.plusDays(1).atStartOfDay().withHour(4).truncatedTo(ChronoUnit.HOURS).atZone(DISPLAY_TIMEZONE)

        val MIN_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(5)
        val MAX_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(120)


//        ApiInterface apinterface=

        Single.fromCallable {
            val channels = listOf(

                SimpleChannel("npo-1", SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("npo-2", SpannedString("CN"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("bbc-news", SpannedString("ESPN"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("zdf", SpannedString("CNN-intl"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("jednotka", SpannedString("BBC earth"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("tv-nova", SpannedString("Discovery"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("tv-5-monde", SpannedString("MTV"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("orf-2", SpannedString("Sports TV"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"),
                SimpleChannel("tvp-1", SpannedString("BBC"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png")

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

}
