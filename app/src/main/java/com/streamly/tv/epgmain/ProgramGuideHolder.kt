package com.streamly.tv

import com.streamly.tv.entity.ProgramGuideSchedule

interface ProgramGuideHolder<T> {
    val programGuideGrid : ProgramGuideGridView<T>
    val programGuideManager : ProgramGuideManager<T>

    fun getTimelineRowScrollOffset(): Int
    fun onScheduleClickedInternal(schedule: ProgramGuideSchedule<T>)

    val DISPLAY_SHOW_PROGRESS : Boolean
}