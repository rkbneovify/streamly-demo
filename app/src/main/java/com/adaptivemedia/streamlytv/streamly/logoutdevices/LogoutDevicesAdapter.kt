package com.adaptivemedia.streamlytv.streamly.logoutdevices

import android.annotation.SuppressLint
import android.text.Html
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.adaptivemedia.streamlytv.R
import com.adaptivemedia.streamlytv.databinding.LogginDevicesRowLayoutBinding
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.Data
import com.adaptivemedia.streamlytv.streamly.utilities.inVisible
import com.adaptivemedia.streamlytv.streamly.utilities.show
import com.bumptech.glide.Glide

class LogoutDevicesAdapter(
    private val onClick: (data: Data, pos: Int) -> Unit
) :
    ListAdapter<Data, LogoutDevicesAdapter.MyViewHolder>(DiffUtilLogin()) {

    var index = -1

    class MyViewHolder(val binding: LogginDevicesRowLayoutBinding) :
        RecyclerView.ViewHolder(binding.root)

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): MyViewHolder {
        return MyViewHolder(
            LogginDevicesRowLayoutBinding.inflate(
                LayoutInflater.from(parent.context),
                parent,
                false
            )
        )
    }

    @SuppressLint("NotifyDataSetChanged")
    override fun onBindViewHolder(holder: MyViewHolder, position: Int) {
        holder.binding.apply {
            val model = getItem(position)
            Glide.with(root.context)
                .load(model.device_img)
                .into(ivImage);
            tvDeviceName.text = model.device_name
            //Html.fromHtml("<b>Device Name: </b> ${model.device_name}")
            // tvDeviceType.text = Html.fromHtml("<b>Device Type: </b> ${model.device_type}")
            // llLayout.requestFocus()


            llLayout.setOnFocusChangeListener { view, b ->
                if (b) {
                    ivImage.inVisible()
                } else {
                    ivImage.show()
                }
            }
            llLayout.setOnClickListener {
                onClick.invoke(model, position)
                /*  index = position
                  notifyDataSetChanged()*/
            }

            /*    if (position == index) {
                    model.isLogout = true
                    logout.isChecked = true
                } else {
                    model.isLogout = false
                    logout.isChecked = false
                }*/

            /*    logout.setOnCheckedChangeListener { compoundButton, b ->
                    model.isLogout = b
                }*/
        }
    }


    class DiffUtilLogin : DiffUtil.ItemCallback<Data>() {
        override fun areItemsTheSame(oldItem: Data, newItem: Data) =
            oldItem.device_id == newItem.device_id

        override fun areContentsTheSame(oldItem: Data, newItem: Data) = oldItem == newItem
    }
}