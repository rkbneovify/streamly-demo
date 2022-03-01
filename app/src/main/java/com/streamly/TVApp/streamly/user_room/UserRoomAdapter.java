package com.streamly.TVApp.streamly.user_room;

import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
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
    int selectedPosition = -1;
    AlertDialog.Builder builder;
    AlertDialog alertDialog;

    private  OnItemClickListener listener;

    DialogInterface.OnClickListener dialogClickListener = new DialogInterface.OnClickListener() {
        @Override
        public void onClick(DialogInterface dialog, int which) {
            switch (which){
                case DialogInterface.BUTTON_POSITIVE:
                    listener.onItemClick(list.get(selectedPosition).getId().toString());
                    alertDialog.dismiss();
                    break;
                case DialogInterface.BUTTON_NEGATIVE:
                    alertDialog.dismiss();
                    break;
            }
        }
    };

    public UserRoomAdapter(Context context, List<Room> list,OnItemClickListener listener) {
        this.context = context;
        this.list = list;
        this.listener=listener;
        builder = new AlertDialog.Builder(context);
        builder.setMessage(R.string.room_selection_msg).setPositiveButton("Yes", dialogClickListener)
                .setNegativeButton("No", dialogClickListener);
        alertDialog = builder.create();
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
                selectedPosition = position;
//                Todo Show a dialog for Are you sure to select this room? and on yes click send call back else do nothing.
                alertDialog.show();
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

    public void updateList(List<Room> list){
        this.list = list;
        notifyDataSetChanged();
    }
}
