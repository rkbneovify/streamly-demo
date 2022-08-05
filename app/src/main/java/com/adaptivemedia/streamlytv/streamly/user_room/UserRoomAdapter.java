package com.adaptivemedia.streamlytv.streamly.user_room;

import android.app.Dialog;
import android.content.Context;
import android.util.DisplayMetrics;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.adaptivemedia.streamlytv.R;
import com.adaptivemedia.streamlytv.streamly.new_log_in.Room;

import java.util.List;

public class UserRoomAdapter extends RecyclerView.Adapter<UserRoomAdapter.MyViewHolder> {
    private Context context;
    private List<Room> list;
    private View view;
    int selectedPosition = -1;
    Dialog alertDialog;
    int rvWidth;
    private  OnItemClickListener listener;

    public UserRoomAdapter(Context context, List<Room> list,OnItemClickListener listener) {
        this.context = context;
        this.list = list;
        this.listener=listener;
        alertDialog = new Dialog(context);
        alertDialog.setContentView(R.layout.logout_dialog_view);
        alertDialog.findViewById(R.id.yes_btn_logout_dialog).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                listener.onItemClick(list.get(selectedPosition).getId().toString());
                alertDialog.dismiss();
            }
        });

        alertDialog.findViewById(R.id.no_btn_logout_dialog).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                alertDialog.dismiss();
            }
        });
        ((TextView) alertDialog.findViewById(R.id.simpleTextView_1)).setText(context.getString(R.string.room_selection_msg));
    }

    public UserRoomAdapter(Context context, int rvWidth ,List<Room> list,OnItemClickListener listener) {
        this.context = context;
        this.list = list;
        this.listener=listener;
        this.rvWidth = rvWidth;
        alertDialog = new Dialog(context);
        alertDialog.setContentView(R.layout.logout_dialog_view);
        alertDialog.findViewById(R.id.yes_btn_logout_dialog).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                listener.onItemClick(list.get(selectedPosition).getId().toString());
                alertDialog.dismiss();
            }
        });

        alertDialog.findViewById(R.id.no_btn_logout_dialog).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                alertDialog.dismiss();
            }
        });
        ((TextView) alertDialog.findViewById(R.id.simpleTextView_1)).setText(context.getString(R.string.room_selection_msg));
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
        int holderWidth = rvWidth/4 - dpToPx(60);
        LinearLayout.LayoutParams layoutParams = (LinearLayout.LayoutParams) holder.roomLayout.getLayoutParams();
        layoutParams.width = holderWidth;
        layoutParams.leftMargin = dpToPx(120);
        layoutParams.rightMargin = dpToPx(120);
        layoutParams.bottomMargin = dpToPx(20);
        layoutParams.topMargin = dpToPx(20);
        layoutParams.gravity = Gravity.CENTER;
        holder.roomLayout.setLayoutParams(layoutParams);
        holder.roomName.setText(list.get(position).getHotelRoom());
        holder.roomLayout.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                selectedPosition = position;
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

//            itemView.setOnFocusChangeListener(new View.OnFocusChangeListener() {
//                @Override
//                public void onFocusChange(View v, boolean hasFocus) {
//                    if (hasFocus) {
//                        // run scale animation and make it bigger
//                        Animation anim = AnimationUtils.loadAnimation(context, R.anim.scale_in_tv);
//                        itemView.startAnimation(anim);
//                        anim.setFillAfter(true);
//                    } else {
//                        // run scale animation and make it smaller
//                        Animation anim = AnimationUtils.loadAnimation(context, R.anim.scale_out_tv);
//                        itemView.startAnimation(anim);
//                        anim.setFillAfter(true);
//                    }
//                }
//            });
        }
    }

    public void updateList(List<Room> list){
        this.list = list;
        notifyDataSetChanged();
    }

    public int dpToPx(int dp) {
        DisplayMetrics displayMetrics = context.getResources().getDisplayMetrics();
        return Math.round(dp * (displayMetrics.xdpi / DisplayMetrics.DENSITY_DEFAULT));
    }
}
