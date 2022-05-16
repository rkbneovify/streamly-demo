
package com.adaptivemedia.streamlytv.streamly.playervideo.allchannel;

import android.annotation.SuppressLint;
import android.content.Context;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;
import com.bumptech.glide.Glide;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.playervideo.PlayerActivity;

import java.util.List;

public class AllChannelAdapter extends RecyclerView.Adapter<AllChannelAdapter.MyViewHolder> {

    private List<com.adaptivemedia.streamlytv.streamly.epg.Datum> channelList;
    private Context ctx;
    int cPosition;

    public AllChannelAdapter(Context ctx, List<com.adaptivemedia.streamlytv.streamly.epg.Datum> channelList, int cPosition){
        this.ctx = ctx;
        this.channelList = channelList;
        this.cPosition = cPosition;
    }

    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        return new MyViewHolder(LayoutInflater.from(ctx).inflate(R.layout.player_all_channel_row , parent , false));
    }

    @Override
    public void onViewAttachedToWindow(@NonNull MyViewHolder holder) {
        super.onViewAttachedToWindow(holder);
    }

    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, @SuppressLint("RecyclerView") int position) {

        if(position == cPosition){
            holder.card.requestFocus();
            holder.card.setSelected(true);
            holder.card.setFocusable(true);
        }
        holder.textView.setText(channelList.get(position).getId().toString());
        Glide.with(ctx).load(channelList.get(position).getLogo()).into(holder.imageView);

        holder.card.setOnClickListener(view -> {

            if (ctx instanceof PlayerActivity) {
                ((PlayerActivity)ctx).playNext(channelList.get(position).getId().toString() , channelList.get(position).getUrl(),channelList.get(position).getId().toString(),position);
            }
            else{
                Log.e("playerac" , "no");

            }
        });

       /* holder.itemView.setOnClickListener(view -> {

            Log.e("playerac" , "done");
            Log.e("playerac" , "id "+channelList.get(position).getId().toString());
            if (ctx instanceof PlayerActivity) {
                ((PlayerActivity)ctx).playNext(channelList.get(position).getId().toString() , channelList.get(position).getUrl(),channelList.get(position).getId().toString());
            }
            else{
                Log.e("playerac" , "no");

            }

        });*/

    }



    @Override
    public int getItemCount() {
        if(channelList!=null)
        return channelList.size();

        return 0;
    }

    class MyViewHolder extends RecyclerView.ViewHolder{

        TextView textView;
        ImageView imageView;
        LinearLayout card;

        public MyViewHolder(@NonNull View itemView) {
            super(itemView);

            textView = itemView.findViewById(R.id.player_channel_text);
            imageView = itemView.findViewById(R.id.player_channel_image);
            card = itemView.findViewById(R.id.card);

        }
    }

}
