package com.adaptivemedia.streamlytv.streamly.epg;

import android.annotation.SuppressLint;
import android.content.Context;
import android.graphics.drawable.Drawable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.streamly.MainActivity;
import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.playervideo.PlayerActivity;
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility;
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility;

import java.util.List;


public class GenereAdapter extends RecyclerView.Adapter<GenereAdapter.MyViewHolder> {

    private Context context;
    private List<String> genreList;
    int sPostion;
    int lastPostion = 0;


    public GenereAdapter(Context context, List<String> genreList, int sPostion){
        this.context = context;
        this.genreList = genreList;
        this.sPostion = sPostion;
    }


    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.genere_item, null);
        MyViewHolder mh = new MyViewHolder(v);
        return mh;
    }

    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, @SuppressLint("RecyclerView") int position) {

        if(position == lastPostion){
            holder.textView.requestFocus();
            holder.textView.setSelected(true);
            holder.textView.setFocusable(true);
        }


        holder.textView.setText(genreList.get(position));

        holder.textView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                lastPostion = position;
                notifyDataSetChanged();
                if (context instanceof MainActivity) {
                    ((MainActivity) context).fragment(genreList.get(position));
                }
            }
        });

        if(lastPostion == position ){
            holder.textView.setBackgroundResource(R.drawable.option_states_category);
        }else {
            holder.textView.setBackgroundResource(R.drawable.options_states);
        }

    }

    @Override
    public int getItemCount() {
        return genreList.size();
    }

    class MyViewHolder extends RecyclerView.ViewHolder{

        private TextView textView;

        public MyViewHolder(@NonNull View itemView) {
            super(itemView);

            this.textView = itemView.findViewById(R.id.text_genere);
            textView.setFocusable(true);
            textView.setFocusableInTouchMode(true);
        }
    }

}
