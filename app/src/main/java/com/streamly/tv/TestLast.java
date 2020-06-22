package com.streamly.tv;

import android.content.Intent;
import android.util.Log;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.egeniq.androidtvprogramguide.ProgramGuideFragment;
import com.egeniq.androidtvprogramguide.entity.ProgramGuideSchedule;
import com.streamly.tv.epg.SimpleProgram;
import com.streamly.tv.playervideo.PlayerActivity;

import org.jetbrains.annotations.NotNull;
import org.jetbrains.annotations.Nullable;
import org.threeten.bp.LocalDate;

import static com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions.withCrossFade;

public class TestLast extends ProgramGuideFragment {

    private static final String TAG=EpgFragmen.class.getSimpleName();

    @Override
    public boolean isTopMenuVisible() {
        return false;
    }





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
    public void requestRefresh() {
        // You can refresh other data here as well.
        this.requestingProgramGuideFor(this.getCurrentDate());
    }

    @Override
    public void requestingProgramGuideFor(@NotNull LocalDate localDate) {

    }
}
