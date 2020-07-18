/*
package com.streamly.tv;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Build;
import android.text.SpannableString;
import android.util.Log;
import android.util.Pair;
import android.widget.ImageView;
import android.widget.TextView;
import androidx.annotation.RequiresApi;
import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.egeniq.androidtvprogramguide.ProgramGuideFragment;
import com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule;
import com.streamly.tv.channelschedule.ChannelScheduleModel;
import com.streamly.tv.channelschedule.Program;
import com.streamly.tv.epg.ChannellistModel;
import com.streamly.tv.epg.Datum;
import com.streamly.tv.epg.SimpleChannel;
import com.streamly.tv.epg.SimpleProgram;
import com.streamly.tv.local_storage.TokenStorage;
import com.streamly.tv.log_in.LogInActivity;
import com.streamly.tv.retrofit_clients.ApiClient;
import com.streamly.tv.retrofit_clients.ApiInterface;
import com.streamly.tv.utilities.CommonUtility;
import com.streamly.tv.utilities.ConstantUtility;
import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.threeten.bp.Instant;
import org.threeten.bp.LocalDate;
import org.threeten.bp.ZoneOffset;
import org.threeten.bp.ZonedDateTime;
import org.threeten.bp.format.DateTimeFormatter;
import org.threeten.bp.temporal.ChronoUnit;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.concurrent.TimeUnit;
import kotlin.random.Random;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import static com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions.withCrossFade;

public class EpgFragmen extends ProgramGuideFragment {

    private static final String TAG = EpgFragmen.class.getSimpleName();
    List<SimpleChannel> channelsList = new ArrayList<>();
    LocalDate localDate;
    Context context;
    ZonedDateTime MIN_CHANNEL_START_TIME;
    ZonedDateTime MAX_CHANNEL_START_TIME;
    ZonedDateTime MIN_CHANNEL_END_TIME;
    ZonedDateTime MAX_CHANNEL_END_TIME;
    long MIN_SHOW_LENGTH_SECONDS;
    long MAX_SHOW_LENGTH_SECONDS;

    public EpgFragmen(Context context) {
        this.context = context;
    }

    @Override
    public void onScheduleClicked(@NotNull ProgramGuideSchedule schedule) {
//        ProgramGuideSchedule innerSchedule = (ProgramGuideSchedule) schedule.getProgram();
        if (schedule.getProgram() == null) {
            // If this happens, then our data source gives partial info
            Log.w(TAG, "Unable to open schedule: $innerSchedule");
            return;
        }

        */
/*if (schedule.isCurrentProgram()) {
            Intent intent = new Intent(getContext(), PlayerActivity.class);
            startActivity(intent);
        } else {
            Toast.makeText(getContext(), "Open detail page", Toast.LENGTH_LONG).show();
        }*//*



    }

    @Override
    public void onScheduleSelected(@Nullable ProgramGuideSchedule programGuideSchedule) {
        */
/*TextView titleView = getView().findViewById(R.id.programguide_detail_title);
        if (titleView != null) {
            titleView.setText((CharSequence) (programGuideSchedule != null ? programGuideSchedule.getDisplayTitle() : null));

        }
        TextView metadataView = getView().findViewById(R.id.programguide_detail_metadata);
//        if (programGuideSchedule != null && metadataView != null) {
        if (metadataView != null && programGuideSchedule != null) {
//            EpgFragment.SimpleProgram simpleProgram=(EpgFragment.SimpleProgram)programGuideSchedule.getProgram();

            SimpleProgram simpleProgram = (SimpleProgram) programGuideSchedule.getProgram();
//            metadataView.setText((CharSequence) simpleProgram.getMetadata());
        }


        TextView descriptionView = getView().findViewById(R.id.programguide_detail_description);
        if (descriptionView != null && programGuideSchedule != null) {
            SimpleProgram simpleProgram = (SimpleProgram) programGuideSchedule.getProgram();
//            EpgFragment.SimpleProgram simpleProgram= (EpgFragment.SimpleProgram)programGuideSchedule.getProgram();
            descriptionView.setText((CharSequence) simpleProgram.getDescription());

        }*//*

        */
/*ImageView imageView = getView().findViewById(R.id.programguide_detail_image);

        if (programGuideSchedule != null) {
            Glide.with(imageView)
                    .load("https://picsum.photos/462/240?random=" + programGuideSchedule.getDisplayTitle().hashCode())
                    .centerCrop()
                    .error(R.drawable.programguide_icon_placeholder)
                    .diskCacheStrategy(DiskCacheStrategy.ALL)
                    .transition(withCrossFade())
                    .into(imageView);
        } else {
            Glide.with(imageView).clear(imageView);
        }*//*


    }


    @Override
    public void requestingProgramGuideFor(@NotNull final LocalDate localDate) {
        this.localDate = localDate;
        // Faking an asynchronous loading here
        this.setState(State.Loading);
        final String auth_token = TokenStorage.readSharedToken(context, ConstantUtility.AUTH_TOKEN, null);
        MIN_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(2).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        MAX_CHANNEL_START_TIME = localDate.atStartOfDay().withHour(8).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        MIN_CHANNEL_END_TIME = localDate.atStartOfDay().withHour(21).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        MAX_CHANNEL_END_TIME = localDate.plusDays(1L).atStartOfDay().withHour(4).truncatedTo(ChronoUnit.HOURS).atZone(this.getDISPLAY_TIMEZONE());
        MIN_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(5L);
        MAX_SHOW_LENGTH_SECONDS = TimeUnit.MINUTES.toSeconds(120L);

        final ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ChannellistModel> call = apiInterface.getChannelList("Bearer" + auth_token);
        call.enqueue(new Callback<ChannellistModel>() {
            @Override
            public void onResponse(Call<ChannellistModel> call, Response<ChannellistModel> response) {
                try {
                    Context context = getContext();
                    if (response.isSuccessful() && response.body() != null) {


                        List<Datum> dataList = response.body().getData();
                        for (int i = 0; i < dataList.size(); i++) {
                            //       channelsList.add(new SimpleChannel(dataList.get(i).getId(), dataList.get(i).getCallSign(),dataList.get(i).getLogo()));
                            channelsList.add(new SimpleChannel(String.valueOf(dataList.get(i).getId()), dataList.get(i).getCallSign(),new SpannableString(dataList.get(i).getCallSign()), dataList.get(i).getLogo()));

                        }

                        getSecondApi(auth_token, channelsList);


                    } else if (response.code() == 401) {
                        CommonUtility.showToast(context, response.body().getMessage());
                        TokenStorage.clearSharedToken(context);
                        context.startActivity(new Intent(context, LogInActivity.class));
                        ((Activity) context).finish();
                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(context);
                        CommonUtility.showToast(context, ConstantUtility.SESSION_EXPIRED);
                        context.startActivity(new Intent(context, LogInActivity.class));
                        ((Activity) context).finish();
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE);
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                }

                Log.e(TAG, "EPG " + response.code() + "");
            }

            @Override
            public void onFailure(Call<ChannellistModel> call, Throwable t) {
                Log.e(TAG, "EPG_failure " + t.getLocalizedMessage());
            }
        });


        */
/*List<SimpleChannel> channels = new ArrayList<>();
        channels.add(new SimpleChannel("npo-1", "",new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
        channels.add(new SimpleChannel("npo-1","", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
        channels.add(new SimpleChannel("npo-1", "",new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
        channels.add(new SimpleChannel("npo-1", "",new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
        channels.add(new SimpleChannel("npo-1","", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
        channels.add(new SimpleChannel("npo-1","", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
        channels.add(new SimpleChannel("npo-1","", new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));
        channels.add(new SimpleChannel("npo-1", "",new SpannedString("MTV- USA"), "https://upload.wikimedia.org/wikipedia/commons/thumb/0/02/NPO_1_logo_2014.svg/320px-NPO_1_logo_2014.svg.png"));


        final List<String> showNames =  Arrays.asList("News", "Sherlock Holmes", "It's Always Sunny In Philadelphia", "Second World War Documentary", "World Cup Final Replay", "Game of Thrones",
                "NFL Sunday Night Football", "NCIS", "Seinfeld", "ER", "Who Wants To Be A Millionaire", "Our Planet", "Friends", "House of Cards");

        Map<String,List<ProgramGuideSchedule<SimpleProgram>>> channelMap=new HashMap<>();

        List<ProgramGuideSchedule<SimpleProgram>> scheduleList = new ArrayList<>();
        for (int i=0;i<3;i++){
            ZonedDateTime nextTime = randomTimeBetween(MIN_CHANNEL_START_TIME, MAX_CHANNEL_START_TIME);
            while (nextTime.isBefore(MIN_CHANNEL_END_TIME)) {
                ZonedDateTime endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(nextTime.toEpochSecond() + Random.Default.nextLong(MIN_SHOW_LENGTH_SECONDS, MAX_SHOW_LENGTH_SECONDS)), ZoneOffset.UTC);
                ProgramGuideSchedule<SimpleProgram> schedule = createSchedule(channels.get(i), showNames.get(i), nextTime, endTime);
                Log.e(TAG,"#schedule "+schedule);
                Log.e(TAG,"#schList "+scheduleList);
                scheduleList.add(schedule);
                nextTime = endTime;
            }

            ZonedDateTime endTime = nextTime.isBefore(MAX_CHANNEL_END_TIME) ? randomTimeBetween(nextTime, MAX_CHANNEL_END_TIME) : MAX_CHANNEL_END_TIME;

            ProgramGuideSchedule<SimpleProgram> finalSchedule = createSchedule(channels.get(i), showNames.get(2), nextTime, endTime);
            Log.e(TAG,"#finalSchedule "+finalSchedule);

            scheduleList.add(finalSchedule);
            channelMap.put(channels.get(i).getId(), scheduleList);
        }
        Pair<List<SimpleChannel>, Map<String,List<ProgramGuideSchedule<SimpleProgram>>>> pair=new Pair<>(channels,channelMap);
        Log.e(TAG,"#pair "+pair);
        Log.e(TAG,"#pairFirst "+pair.first);
        Log.e(TAG,"#pairSec "+pair.second);
        setData(pair.first,pair.second,localDate);
        setState(State.Content);*//*



    }

    private void getSecondApi(String auth_token, final List<SimpleChannel> channelsList) {
        ApiInterface apiInterface = ApiClient.getClient().create(ApiInterface.class);
        Call<ChannelScheduleModel> call = apiInterface.gotScheduleList("Bearer" + auth_token);
        call.enqueue(new Callback<ChannelScheduleModel>() {
            @RequiresApi(api = Build.VERSION_CODES.N)
            @Override
            public void onResponse(Call<ChannelScheduleModel> call, Response<ChannelScheduleModel> response) {
                try {
                    Context context = getContext();
                    if (response.isSuccessful() && response.body() != null) {

                        List<com.streamly.tv.channelschedule.Datum> dataList = response.body().getData();

                        Map<String, List<ProgramGuideSchedule<SimpleProgram>>> channelMap = new HashMap<>();

                        channelsList.forEach ( channel ->{

                            Log.e("###$TAG", "outSideIT "+channel.getName().toString());

                            List<String> showNames = new ArrayList<String>();
                            dataList.forEach(i->{
                                if (channel.getCallSign().equals(i.getCallSign())) {
                                    List<Program> programList = i.getPrograms();

                                    programList.forEach(j->{
                                            showNames.add(j.getTitle());
                                    });

                                }

                            });

//                            for (int i = 0; i < dataList.size(); i++) {
//
//                            }

                            List<ProgramGuideSchedule<SimpleProgram>> scheduleList = new ArrayList<>();
                            showNames.forEach(j->{


                            ZonedDateTime nextTime = randomTimeBetween(MIN_CHANNEL_START_TIME, MAX_CHANNEL_START_TIME);
                        while (nextTime.isBefore(MIN_CHANNEL_END_TIME)) {
//                            ZonedDateTime endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(programList.get(j).getEnd()), ZoneOffset.UTC);
                            ZonedDateTime endTime = ZonedDateTime.ofInstant(Instant.ofEpochSecond(nextTime.toEpochSecond()+ Random.Default.nextLong(MIN_SHOW_LENGTH_SECONDS, MAX_SHOW_LENGTH_SECONDS)), ZoneOffset.UTC);
                            ProgramGuideSchedule<SimpleProgram> schedule = createSchedule(channel,j,nextTime, endTime);
                            scheduleList.add(schedule);
                            nextTime = endTime;
                        }
                        ZonedDateTime endTime = nextTime.isBefore(MAX_CHANNEL_END_TIME) ? randomTimeBetween(nextTime, MAX_CHANNEL_END_TIME) : MAX_CHANNEL_END_TIME;

//                                    ProgramGuideSchedule<SimpleProgram> finalSchedule = createSchedule(channelsList.get(i), programList.get(j).getTitle(), nextTime, endTime);
                            ProgramGuideSchedule<SimpleProgram> finalSchedule = createSchedule(channel, j, nextTime, endTime);

                            scheduleList.add(finalSchedule);
                            channelMap.put(channel.getId(), scheduleList);
//                                List<String> showNames = new ArrayList<String>();
//                        for (int i = 0; i < dataList.size(); i++) {
//                            if (channelsList.get(i).getCallSign().equals(dataList.get(i).getCallSign())) {
//                                Log.e("###$TAG", "insideIT " + dataList.get(i).getCallSign());
//
//                                List<Program> programList = dataList.get(i).getPrograms();
//                                for (int j = 0; j < programList.size(); j++) {
//                                    showNames.add(programList.get(j).getTitle());
//
////                                    if (j==20)
////                                        break;
//
//
//                                }
//                            }
//                        }
                            });

                        });
                        Pair<List<SimpleChannel>, Map<String, List<ProgramGuideSchedule<SimpleProgram>>>> pair = new Pair<>(channelsList, channelMap);
                        setData(pair.first, pair.second, localDate);
                        setState(State.Content);


                    } else if (response.code() == 401) {
                        CommonUtility.showToast(context, response.body().getMessage());
                        TokenStorage.clearSharedToken(context);
                        context.startActivity(new Intent(context, LogInActivity.class));
                        ((Activity) context).finish();
                    } else if (response.code() == 404) {
                        TokenStorage.clearSharedToken(context);
                        CommonUtility.showToast(context, ConstantUtility.SESSION_EXPIRED);
                        context.startActivity(new Intent(context, LogInActivity.class));
                        ((Activity) context).finish();
                    } else if (response.code() == 500) {
                        CommonUtility.showToast(context, ConstantUtility.SERVER_ISSUE);
                    }
                } catch (Exception ex) {
                    ex.printStackTrace();
                    Log.e(TAG, "Exception " + ex);
                }
            }

            @Override
            public void onFailure(Call<ChannelScheduleModel> call, Throwable t) {
                Log.e(TAG, "hrowable " + t);
            }
        });


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


    private ProgramGuideSchedule<SimpleProgram> createSchedule(SimpleChannel channel, String scheduleName, ZonedDateTime startTime, ZonedDateTime endTime) {
        long id = Random.Default.nextLong(100000L);
        String metadata = DateTimeFormatter.ofPattern("'Starts at' HH:mm").format(startTime);
//        String metadata = DateTimeFormatter.ofPattern("'Starts at' HH:mm").format(startTime);
//        String metadata = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format((TemporalAccessor)startTime);
//        com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule.Companion var10000 = ProgramGuideSchedule.Companion;
//        ProgramGuideSchedule.OriginalTimes ot = new ProgramGuideSchedule.OriginalTimes(startTime.toInstant(), endTime.toInstant());
//        ProgramGuideSchedule p = new ProgramGuideSchedule(id, startTime.toInstant(), endTime.toInstant(), ot, true, scheduleName, new SimpleProgram(id + "",
//                "This is an example description for the programme. This description is taken from the SimpleProgram class, so by using a different class, " +
//                        "you could easily modify the demo to use your own class", metadata));

        return ProgramGuideSchedule.Companion.createScheduleWithProgram(
                id,
                startTime.toInstant(),
                endTime.toInstant(),
                true,
                scheduleName,
                new SimpleProgram(id+"",
                        "This is an example description for the programme. This description is taken from the SimpleProgram class, so by using a different class, " +
                                "you could easily modify the demo to use your own class",
                        metadata)
        );
//        return p;

    }


    private ZonedDateTime randomTimeBetween(ZonedDateTime min, ZonedDateTime max) {
//        long randomEpoch = new Random().nextLong(min.toEpochSecond(), max.toEpochSecond());
        long randomEpoch = Random.Default.nextLong(min.toEpochSecond(), max.toEpochSecond());
        return ZonedDateTime.ofInstant(Instant.ofEpochSecond(randomEpoch), ZoneOffset.UTC);
    }
}
*/
