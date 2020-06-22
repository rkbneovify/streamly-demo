package com.streamly.tv;

import android.content.Context;
import android.content.Intent;
import android.text.Spanned;
import android.text.SpannedString;
import android.util.Log;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.egeniq.androidtvprogramguide.ProgramGuideFragment;
import com.egeniq.androidtvprogramguide.entity.ProgramGuideChannel;
import com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule;
import com.streamly.tv.epg.SimpleChannel;
import com.streamly.tv.epg.SimpleProgram;
import com.streamly.tv.playervideo.PlayerActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.threeten.bp.Instant;
import org.threeten.bp.LocalDate;
import org.threeten.bp.ZoneId;
import org.threeten.bp.ZoneOffset;
import org.threeten.bp.ZonedDateTime;
import org.threeten.bp.temporal.ChronoUnit;
import org.threeten.bp.temporal.TemporalAccessor;

import java.text.SimpleDateFormat;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.Callable;
import java.util.concurrent.TimeUnit;

import io.reactivex.Scheduler;
import io.reactivex.Single;
import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.functions.Consumer;
import io.reactivex.internal.operators.single.SingleDoOnSuccess;
import io.reactivex.schedulers.Schedulers;
import kotlin.collections.CollectionsKt;
import kotlin.random.Random;

import static com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions.withCrossFade;

 public class EpgFragmen extends ProgramGuideFragment {

    private static final String TAG=EpgFragmen.class.getSimpleName();



    @Override
    public void onScheduleClicked(@NotNull ProgramGuideSchedule schedule) {
//        ProgramGuideSchedule innerSchedule = (ProgramGuideSchedule) schedule.getProgram();
        if (schedule.getProgram()==null){
            // If this happens, then our data source gives partial info
            Log.w(TAG, "Unable to open schedule: $innerSchedule");
            return;
        }

        if (schedule.isCurrentProgram()) {
            Intent intent = new Intent(getContext(), PlayerActivity.class);
            startActivity(intent);
        } else {
            Toast.makeText(getContext(), "Open detail page", Toast.LENGTH_LONG).show();
        }
        

    }

    @Override
    public void onScheduleSelected(@Nullable ProgramGuideSchedule programGuideSchedule) {
        TextView titleView=getView().findViewById(R.id.programguide_detail_title);
        if (titleView!=null){
            titleView.setText((CharSequence)(programGuideSchedule != null ? programGuideSchedule.getDisplayTitle() : null));

        }
        TextView metadataView=getView().findViewById(R.id.programguide_detail_metadata);
        if (programGuideSchedule != null && metadataView != null){
//            EpgFragment.SimpleProgram simpleProgram=(EpgFragment.SimpleProgram)programGuideSchedule.getProgram();

            SimpleProgram simpleProgram= (SimpleProgram) programGuideSchedule.getProgram();
            metadataView.setText((CharSequence)simpleProgram.getMetadata());
        }


        TextView descriptionView=getView().findViewById(R.id.programguide_detail_description);
        if (descriptionView != null && programGuideSchedule != null){
            SimpleProgram simpleProgram= (SimpleProgram) programGuideSchedule.getProgram();
//            EpgFragment.SimpleProgram simpleProgram= (EpgFragment.SimpleProgram)programGuideSchedule.getProgram();
            descriptionView.setText((CharSequence)simpleProgram.getDescription());

        }
        ImageView imageView=getView().findViewById(R.id.programguide_detail_image);

        if (programGuideSchedule !=null){
            Glide.with(imageView)
                    .load("https://picsum.photos/462/240?random=" + programGuideSchedule.getDisplayTitle().hashCode())
                    .centerCrop()
                    .error(R.drawable.programguide_icon_placeholder)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .transition(withCrossFade())
                    .into(imageView);
        }else {
            Glide.with(imageView).clear(imageView);
        }

    }


    @Override
    public void requestingProgramGuideFor(@NotNull final LocalDate localDate) {
        // Faking an asynchronous loading here
        this.setState(State.Loading);
       final ZonedDateTime MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final  ZonedDateTime MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final ZonedDateTime MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(21).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final ZonedDateTime MAX_CHANNEL_END_TIME = localDate.plusDays(1L).atStartOfDay().withHour(4).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        final  long MIN_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(5L);
        final   long MAX_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(120L);



//        ApiInterface apiInterface= ApiClient.getClient().create(ApiInterface.class);
//        /*apiInterface.getChannelList2().subscribeOn(Schedulers.io()).observeOn(AndroidSchedulers.mainThread())
//                .subscribe({},{});*/


        /*Single.fromCallable(new Callable<Object>() {
            @Override
            public Object call() throws Exception {



                return null;
            }
        }).delay(1, TimeUnit.SECONDS)
                .subscribeOn(Schedulers.io())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe({
                        setData(it.first, it.second, localDate)
                        setState(State.Content)
                }, {
                        Log.e(TAG, "Unable to load example data!", it)
                });*/

        Single.fromCallable(new Callable<Object>() {
            @Override
            public Object call() throws Exception {
                List<SimpleChannel> channels = new ArrayList<>();
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
                channels.add(new SimpleChannel("npo-1", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));


                List<String> showNames =  Arrays.asList("News", "Sherlock Holmes", "It's Always Sunny In Philadelphia", "Second World War Documentary", "World Cup Final Replay", "Game of Thrones",
                        "NFL Sunday Night Football", "NCIS", "Seinfeld", "ER", "Who Wants To Be A Millionaire", "Our Planet", "Friends", "House of Cards");

                Map<String,List<ProgramGuideSchedule<SimpleProgram>>> channelMap=new HashMap<>();

                for (int i=0;i<channels.size();i++){
//                    Map<String, List<ProgramGuideSchedule<SimpleProgram>>> scheduleList = new HashMap<>();

                    List<ProgramGuideSchedule<SimpleProgram>> scheduleList = new ArrayList<>();
                    ZonedDateTime nextTime = randomTimeBetween(MIN_CHANNEL_START_TIME, MAX_CHANNEL_START_TIME);
                    while (nextTime.isBefore(MIN_CHANNEL_END_TIME)) {
                        ZonedDateTime endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(nextTime.toEpochSecond() + Random.Default.nextLong(MIN_SHOW_LENGTH_SECONDS, MAX_SHOW_LENGTH_SECONDS)), ZoneOffset.UTC);
                        ProgramGuideSchedule<SimpleProgram> schedule = createSchedule(channels.get(i), showNames.get(2), nextTime, endTime);
                        scheduleList.add(schedule);
                        nextTime = endTime;
                    }

                    ZonedDateTime endTime = nextTime.isBefore(MAX_CHANNEL_END_TIME) ? randomTimeBetween(nextTime, MAX_CHANNEL_END_TIME) : MAX_CHANNEL_END_TIME;
                    ProgramGuideSchedule<SimpleProgram> finalSchedule = createSchedule(channels.get(i), showNames.get(2), nextTime, endTime);
                    scheduleList.add(finalSchedule);
                    channelMap.put(channels.get(i).getId(), scheduleList);


                }


                Map<List<SimpleChannel>,Map<String,List<ProgramGuideSchedule<SimpleProgram>>> > map= new HashMap();
                return map.put(channels,channelMap);
//                return null;
            }
        });
//        .delay(1, TimeUnit.SECONDS)
//                .subscribeOn(Schedulers.io())
//                    .observeOn(AndroidSchedulers.mainThread())
//                    .subscribe(new Consumer<Object>() {
//
//                                   @Override
//                                   public void accept(Object o) throws Exception {
////      Map<List<SimpleChannel>,Map<String,List<ProgramGuideSchedule<SimpleProgram>>> > map = (Map<List<SimpleChannel>,Map<String,List<ProgramGuideSchedule<SimpleProgram>>> >)o
////                                       setData((List<? extends ProgramGuideChannel>) map.get(1), map.get("second"), localDate);
////                                       setState(State.Content);
//
//                                   }
//                               }, new Consumer<Throwable>() {
//                                   @Override
//                                   public void accept(Throwable throwable) throws Exception {
//                                       Log.e(TAG, "Unable to load example data!", throwable);
//
//                                   }
//                               }
//                    );
    }


    @Override
    public boolean isTopMenuVisible() {
        return false;
    }



    @Override
    public void requestRefresh() {
        // You can refresh other data here as well.
        this.requestingProgramGuideFor(this.getCurrentDate());
    }


    private  ProgramGuideSchedule<SimpleProgram>  createSchedule(SimpleChannel channel,  String scheduleName,  ZonedDateTime startTime,  ZonedDateTime endTime){
        long id = Random.Default.nextLong(100000L);
        String metadata = new SimpleDateFormat("'Starts at' HH:mm").format((TemporalAccessor)startTime);
        com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule.Companion var10000 = ProgramGuideSchedule.Companion;
        return  var10000.createScheduleWithProgram(id, startTime.toInstant(), endTime.toInstant(), true,
                scheduleName,
                new SimpleProgram(id+"",
                        "This is an example description for the programme. This description is taken from the SimpleProgram class, so by using a different class, " +
                                "you could easily modify the demo to use your own class", metadata)
        );
    }


    private ZonedDateTime randomTimeBetween(ZonedDateTime min,  ZonedDateTime max) {
//        long randomEpoch = new Random().nextLong(min.toEpochSecond(), max.toEpochSecond());
        long randomEpoch = Random.Default.nextLong(min.toEpochSecond(), max.toEpochSecond());
        return ZonedDateTime.ofInstant(Instant.ofEpochSecond(randomEpoch), ZoneOffset.UTC);
    }
}
