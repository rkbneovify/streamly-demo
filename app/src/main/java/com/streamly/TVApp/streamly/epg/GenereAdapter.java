package com.streamly.TVApp.streamly.epg;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.streamly.TVApp.streamly.MainActivity;
import com.streamly.TVApp.streamly.R;

import java.util.ArrayList;
import java.util.List;


public class GenereAdapter extends RecyclerView.Adapter<GenereAdapter.MyViewHolder> {

    private Context context;
    private List<String> genreList;


    public GenereAdapter(Context context, List<String> genreList){
        this.context = context;
        this.genreList = genreList;
    }


    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.genere_item, null);
        MyViewHolder mh = new MyViewHolder(v);
        return mh;
    }

    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, int position) {

        holder.textView.setText(genreList.get(position));

        holder.textView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                if (context instanceof MainActivity) {
                    ((MainActivity) context).fragment(genreList.get(position));
                }
            }
        });

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
