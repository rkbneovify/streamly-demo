package com.streamly.TVApp.streamly.user_room;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.streamly.TVApp.streamly.R;
import com.streamly.TVApp.streamly.new_log_in.Room;

import java.util.List;

public class UserRoomAdapter extends RecyclerView.Adapter<UserRoomAdapter.MyViewHolder> {
    private Context context;
    private List<Room> list;
    private View view;

    private  OnItemClickListener listener;

    public UserRoomAdapter(Context context, List<Room> list,OnItemClickListener listener) {
        this.context = context;
        this.list = list;
        this.listener=listener;
    }

    @NonNull
    @Override
    public MyViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        view = LayoutInflater.from(context).inflate(R.layout.view_room, parent, false);
        UserRoomAdapter.MyViewHolder viewHolder = new UserRoomAdapter.MyViewHolder(view);
        return viewHolder;
    }

    @Override
    public void onBindViewHolder(@NonNull MyViewHolder holder, int position) {


        holder.roomName.setText(list.get(position).getHotelRoom());
        holder.roomLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                listener.onItemClick(list.get(position).getId().toString());

            }
        });


    }

    @Override
    public int getItemCount() {
//        if (list != null) {
//            return list.size();
//        }
        return list.size();

    }
    public interface OnItemClickListener {
        void onItemClick(String item);
    }




    public class MyViewHolder extends RecyclerView.ViewHolder {

        ImageView roomLogo;
        TextView roomName;
        LinearLayout roomLayout;

        public MyViewHolder(@NonNull View itemView) {
            super(itemView);

            roomLayout = itemView.findViewById(R.id.room_layout);
            roomLogo = itemView.findViewById(R.id.room_logo);
            roomName = itemView.findViewById(R.id.room_name);
        }
    }
}
