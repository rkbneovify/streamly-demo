package com.streamly.tv;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.text.Spanned;
import android.text.SpannedString;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import com.bumptech.glide.Glide;
import com.bumptech.glide.RequestBuilder;
import com.bumptech.glide.RequestManager;
import com.bumptech.glide.TransitionOptions;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions;
import com.egeniq.androidtvprogramguide.ProgramGuideFragment;
import com.egeniq.androidtvprogramguide.ProgramGuideFragment.State;
import com.egeniq.androidtvprogramguide.entity.ProgramGuideChannel;
import com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule;
import com.streamly.tv.epg.SimpleChannel;
import com.streamly.tv.playervideo.PlayerActivity;
import io.reactivex.Single;
import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.functions.Consumer;
import io.reactivex.schedulers.Schedulers;
import java.util.ArrayList;
import java.util.Collection;
import java.util.HashMap;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.Callable;
import java.util.concurrent.TimeUnit;
import kotlin.Metadata;
import kotlin.Pair;
import kotlin.collections.CollectionsKt;
import kotlin.jvm.internal.Intrinsics;
import kotlin.random.Random;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.threeten.bp.Instant;
import org.threeten.bp.LocalDate;
import org.threeten.bp.ZoneId;
import org.threeten.bp.ZoneOffset;
import org.threeten.bp.ZonedDateTime;
import org.threeten.bp.chrono.ChronoZonedDateTime;
import org.threeten.bp.format.DateTimeFormatter;
import org.threeten.bp.temporal.ChronoUnit;
import org.threeten.bp.temporal.TemporalAccessor;
import org.threeten.bp.temporal.TemporalUnit;

public final class Test extends ProgramGuideFragment {
    private static final String TAG;

    private HashMap _$_findViewCache;

    public void onScheduleClicked(@NotNull ProgramGuideSchedule schedule) {
        Intrinsics.checkParameterIsNotNull(schedule, "schedule");
        Test.SimpleProgram innerSchedule = (Test.SimpleProgram)schedule.getProgram();
        if (innerSchedule == null) {
            Log.w(TAG, "Unable to open schedule: " + innerSchedule);
        } else {
            if (schedule.isCurrentProgram()) {
                Intent intent = new Intent(this.getContext(), PlayerActivity.class);
                this.startActivity(intent);
            } else {
                Toast.makeText(this.getContext(), (CharSequence)"Open detail page", Toast.LENGTH_SHORT).show();
            }

        }
    }

    public void onScheduleSelected(@Nullable ProgramGuideSchedule programGuideSchedule) {
        View var10000 = this.getView();
        TextView titleView = var10000 != null ? (TextView)var10000.findViewById(R.id.programguide_detail_title) : null;
        if (titleView != null) {
            titleView.setText((CharSequence)(programGuideSchedule != null ? programGuideSchedule.getDisplayTitle() : null));
        }

        var10000 = this.getView();
        TextView metadataView = var10000 != null ? (TextView)var10000.findViewById(R.id.programguide_detail_metadata) : null;
        String var11;
        Test.SimpleProgram var10001;
        if (metadataView != null) {
            label70: {
                if (programGuideSchedule != null) {
                    var10001 = (Test.SimpleProgram)programGuideSchedule.getProgram();
                    if (var10001 != null) {
                        var11 = var10001.getMetadata();
                        break label70;
                    }
                }

                var11 = null;
            }

            metadataView.setText((CharSequence)var11);
        }

        var10000 = this.getView();
        TextView descriptionView = var10000 != null ? (TextView)var10000.findViewById(R.id.programguide_detail_description) : null;
        if (descriptionView != null) {
            label62: {
                if (programGuideSchedule != null) {
                    var10001 = (Test.SimpleProgram)programGuideSchedule.getProgram();
                    if (var10001 != null) {
                        var11 = var10001.getDescription();
                        break label62;
                    }
                }

                var11 = null;
            }

            descriptionView.setText((CharSequence)var11);
        }

        var10000 = this.getView();
        if (var10000 != null) {
            ImageView var12 = (ImageView)var10000.findViewById(R.id.programguide_detail_image);
            if (var12 != null) {
                ImageView imageView = var12;
                if (programGuideSchedule != null) {
                    RequestManager var13 = Glide.with((View)imageView);
                    StringBuilder var14 = (new StringBuilder()).append("https://picsum.photos/462/240?random=");
                    String var6 = programGuideSchedule.getDisplayTitle();
                    StringBuilder var9 = var14;
                    RequestManager var8 = var13;
                    boolean var7 = false;
                    int var10 = var6 != null ? var6.hashCode() : 0;
                } else {
                    Glide.with((View)imageView).clear((View)imageView);
                }

                return;
            }
        }

    }

    public boolean isTopMenuVisible() {
        return false;
    }

    @SuppressLint({"CheckResult"})
    public void requestingProgramGuideFor(@NotNull final LocalDate localDate) {
        Intrinsics.checkParameterIsNotNull(localDate, "localDate");
        this.setState(State.Loading);
        final ZonedDateTime MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo((TemporalUnit)ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final ZonedDateTime MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo((TemporalUnit)ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final ZonedDateTime MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(21).truncatedTo((TemporalUnit)ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final ZonedDateTime MAX_CHANNEL_END_TIME = localDate.plusDays(1L).atStartOfDay().withHour(4).truncatedTo((TemporalUnit)ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final long MIN_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(5L);
        final long MAX_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(120L);
        Single.fromCallable(new Callable() {
//            // $FF: synthetic method
//            // $FF: bridge method
//            public Object call() {
//                return this.call();
//            }

            public final Pair call() {
                List channels = CollectionsKt.listOf(new com.streamly.tv.epg.SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                List showNames = CollectionsKt.listOf(new String[]{"News", "Sherlock Holmes", "It's Always Sunny In Philadelphia", "Second World War Documentary", "World Cup Final Replay", "Game of Thrones", "NFL Sunday Night Football", "NCIS", "Seinfeld", "ER", "Who Wants To Be A Millionaire", "Our Planet", "Friends", "House of Cards"});
                boolean var4 = false;
                Map channelMap = (Map)(new LinkedHashMap());
                Iterable $this$forEach$iv = (Iterable)channels;
                Iterator var6 = $this$forEach$iv.iterator();

                while(var6.hasNext()) {
                    Object element$iv = var6.next();
                    SimpleChannel channel = (SimpleChannel)element$iv;
                    boolean var10 = false;
                    List scheduleList = (List)(new ArrayList());
                    Test var10000 = Test.this;
                    ZonedDateTime var10001 = MIN_CHANNEL_START_TIME;
                    Intrinsics.checkExpressionValueIsNotNull(var10001, "MIN_CHANNEL_START_TIME");
                    ZonedDateTime var10002 = MAX_CHANNEL_START_TIME;
                    Intrinsics.checkExpressionValueIsNotNull(var10002, "MAX_CHANNEL_START_TIME");

                    ZonedDateTime endTime;
                    Collection var13;
                    Test var15;
                    boolean var16;
                    Object var17;
                    ProgramGuideSchedule finalSchedule;
                    ZonedDateTime nextTime;
                    String var22;
                    for(nextTime = var10000.randomTimeBetween(var10001, var10002); nextTime.isBefore((ChronoZonedDateTime)MIN_CHANNEL_END_TIME); nextTime = endTime) {
                        endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(nextTime.toEpochSecond() + Random.Default.nextLong(MIN_SHOW_LENGTH_SECONDS, MAX_SHOW_LENGTH_SECONDS)), (ZoneId)ZoneOffset.UTC);
                        var13 = (Collection)showNames;
                        var15 = Test.this;
                        var16 = false;
                        var17 = CollectionsKt.random(var13, (Random)Random.Default);
                        var22 = (String)var17;
                        Intrinsics.checkExpressionValueIsNotNull(endTime, "endTime");
                        finalSchedule = var15.createSchedule(channel, var22, nextTime, endTime);
                        scheduleList.add(finalSchedule);
                    }

                    ZonedDateTime var21;
                    if (nextTime.isBefore((ChronoZonedDateTime)MAX_CHANNEL_END_TIME)) {
                        var10000 = Test.this;
                        var10002 = MAX_CHANNEL_END_TIME;
                        Intrinsics.checkExpressionValueIsNotNull(var10002, "MAX_CHANNEL_END_TIME");
                        var21 = var10000.randomTimeBetween(nextTime, var10002);
                    } else {
                        var21 = MAX_CHANNEL_END_TIME;
                    }

                    endTime = var21;
                    var13 = (Collection)showNames;
                    var15 = Test.this;
                    var16 = false;
                    var17 = CollectionsKt.random(var13, (Random)Random.Default);
                    var22 = (String)var17;
                    Intrinsics.checkExpressionValueIsNotNull(endTime, "endTime");
                    finalSchedule = var15.createSchedule(channel, var22, nextTime, endTime);
                    scheduleList.add(finalSchedule);
                    channelMap.put(channel.getId(), scheduleList);
                }

                return new Pair(channels, channelMap);
            }
        }).delay(1, TimeUnit.SECONDS);


    }

    private final ProgramGuideSchedule createSchedule(Test.SimpleChannel channel, String scheduleName, ZonedDateTime startTime, ZonedDateTime endTime) {
        long id = Random.Default.nextLong(100000L);
        String metadata = DateTimeFormatter.ofPattern("'Starts at' HH:mm").format((TemporalAccessor)startTime);
        com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule.Companion var10000 = ProgramGuideSchedule.Companion;
        Instant var10002 = startTime.toInstant();
        Intrinsics.checkExpressionValueIsNotNull(var10002, "startTime.toInstant()");
        Instant var10003 = endTime.toInstant();
        Intrinsics.checkExpressionValueIsNotNull(var10003, "endTime.toInstant()");
        String var10008 = String.valueOf(id);
        Intrinsics.checkExpressionValueIsNotNull(metadata, "metadata");
        return var10000.createScheduleWithProgram(id, var10002, var10003, true, scheduleName, new SimpleProgram(var10008, "This is an example description for the programme. This description is taken from the SimpleProgram class, so by using a different class, you could easily modify the demo to use your own class", metadata));
    }

    private final ZonedDateTime randomTimeBetween(ZonedDateTime min, ZonedDateTime max) {
        long randomEpoch = Random.Default.nextLong(min.toEpochSecond(), max.toEpochSecond());
        ZonedDateTime var10000 = ZonedDateTime.ofInstant(Instant.ofEpochSecond(randomEpoch), (ZoneId)ZoneOffset.UTC);
        Intrinsics.checkExpressionValueIsNotNull(var10000, "ZonedDateTime.ofInstant(…omEpoch), ZoneOffset.UTC)");
        return var10000;
    }

    public void requestRefresh() {
        this.requestingProgramGuideFor(this.getCurrentDate());
    }

    static {
        String var10000 = Test.class.getName();
        Intrinsics.checkExpressionValueIsNotNull(var10000, "Test::class.java.name");
        TAG = var10000;
    }

    public View _$_findCachedViewById(int var1) {
        if (this._$_findViewCache == null) {
            this._$_findViewCache = new HashMap();
        }

        View var2 = (View)this._$_findViewCache.get(var1);
        if (var2 == null) {
            View var10000 = this.getView();
            if (var10000 == null) {
                return null;
            }

            var2 = var10000.findViewById(var1);
            this._$_findViewCache.put(var1, var2);
        }

        return var2;
    }

    public void _$_clearFindViewByIdCache() {
        if (this._$_findViewCache != null) {
            this._$_findViewCache.clear();
        }

    }

    // $FF: synthetic method
    public void onDestroyView() {
        super.onDestroyView();
        this._$_clearFindViewByIdCache();
    }


    public static final class SimpleChannel implements ProgramGuideChannel {
        @NotNull
        private final String id;
        @Nullable
        private final Spanned name;
        @Nullable
        private final String imageUrl;

        @NotNull
        public String getId() {
            return this.id;
        }

        @Nullable
        public Spanned getName() {
            return this.name;
        }

        @Nullable
        public String getImageUrl() {
            return this.imageUrl;
        }

        public SimpleChannel(@NotNull String id, @Nullable Spanned name, @Nullable String imageUrl) {
            super();
            this.id = id;
            this.name = name;
            this.imageUrl = imageUrl;
        }

        @NotNull
        public final String component1() {
            return this.getId();
        }

        @Nullable
        public final Spanned component2() {
            return this.getName();
        }

        @Nullable
        public final String component3() {
            return this.getImageUrl();
        }

        @NotNull
        public final Test.SimpleChannel copy(@NotNull String id, @Nullable Spanned name, @Nullable String imageUrl) {
            Intrinsics.checkParameterIsNotNull(id, "id");
            return new SimpleChannel(id, name, imageUrl);
        }

        // $FF: synthetic method
        public static Test.SimpleChannel copy$default(Test.SimpleChannel var0, String var1, Spanned var2, String var3, int var4, Object var5) {
            if ((var4 & 1) != 0) {
                var1 = var0.getId();
            }

            if ((var4 & 2) != 0) {
                var2 = var0.getName();
            }

            if ((var4 & 4) != 0) {
                var3 = var0.getImageUrl();
            }

            return var0.copy(var1, var2, var3);
        }

        @NotNull
        public String toString() {
            return "SimpleChannel(id=" + this.getId() + ", name=" + this.getName() + ", imageUrl=" + this.getImageUrl() + ")";
        }

        public int hashCode() {
            String var10000 = this.getId();
            int var1 = (var10000 != null ? var10000.hashCode() : 0) * 31;
            Spanned var10001 = this.getName();
            var1 = (var1 + (var10001 != null ? var10001.hashCode() : 0)) * 31;
            String var2 = this.getImageUrl();
            return var1 + (var2 != null ? var2.hashCode() : 0);
        }

        public boolean equals(@Nullable Object var1) {
            if (this != var1) {
                if (var1 instanceof Test.SimpleChannel) {
                    Test.SimpleChannel var2 = (Test.SimpleChannel)var1;
                    if (Intrinsics.areEqual(this.getId(), var2.getId()) && Intrinsics.areEqual(this.getName(), var2.getName()) && Intrinsics.areEqual(this.getImageUrl(), var2.getImageUrl())) {
                        return true;
                    }
                }

                return false;
            } else {
                return true;
            }
        }
    }


    public static final class SimpleProgram {
        @NotNull
        private final String id;
        @NotNull
        private final String description;
        @NotNull
        private final String metadata;

        @NotNull
        public final String getId() {
            return this.id;
        }

        @NotNull
        public final String getDescription() {
            return this.description;
        }

        @NotNull
        public final String getMetadata() {
            return this.metadata;
        }

        public SimpleProgram(@NotNull String id, @NotNull String description, @NotNull String metadata) {
            super();
            this.id = id;
            this.description = description;
            this.metadata = metadata;
        }

        @NotNull
        public final String component1() {
            return this.id;
        }

        @NotNull
        public final String component2() {
            return this.description;
        }

        @NotNull
        public final String component3() {
            return this.metadata;
        }

        @NotNull
        public final Test.SimpleProgram copy(@NotNull String id, @NotNull String description, @NotNull String metadata) {
            return new SimpleProgram(id, description, metadata);
        }

        // $FF: synthetic method
        public static Test.SimpleProgram copy$default(Test.SimpleProgram var0, String var1, String var2, String var3, int var4, Object var5) {
            if ((var4 & 1) != 0) {
                var1 = var0.id;
            }

            if ((var4 & 2) != 0) {
                var2 = var0.description;
            }

            if ((var4 & 4) != 0) {
                var3 = var0.metadata;
            }

            return var0.copy(var1, var2, var3);
        }

        @NotNull
        public String toString() {
            return "SimpleProgram(id=" + this.id + ", description=" + this.description + ", metadata=" + this.metadata + ")";
        }

        public int hashCode() {
            String var10000 = this.id;
            int var1 = (var10000 != null ? var10000.hashCode() : 0) * 31;
            String var10001 = this.description;
            var1 = (var1 + (var10001 != null ? var10001.hashCode() : 0)) * 31;
            var10001 = this.metadata;
            return var1 + (var10001 != null ? var10001.hashCode() : 0);
        }

        public boolean equals(@Nullable Object var1) {
            if (this != var1) {
                if (var1 instanceof Test.SimpleProgram) {
                    Test.SimpleProgram var2 = (Test.SimpleProgram)var1;
                    if (Intrinsics.areEqual(this.id, var2.id) && Intrinsics.areEqual(this.description, var2.description) && Intrinsics.areEqual(this.metadata, var2.metadata)) {
                        return true;
                    }
                }

                return false;
            } else {
                return true;
            }
        }
    }
}
