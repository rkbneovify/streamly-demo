package com.adaptivemedia.streamlytv.streamly

import com.adaptivemedia.streamlytv.streamly.entity.ProgramGuideSchedule

interface ProgramGuideHolder<T> {
    val programGuideGrid : ProgramGuideGridView<T>
    val programGuideManager : ProgramGuideManager<T>

    fun getTimelineRowScrollOffset(): Int
    fun onScheduleClickedInternal(schedule: ProgramGuideSchedule<T>, channelId:String)

    val DISPLAY_SHOW_PROGRESS : Boolean
}