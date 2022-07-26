package com.adaptivemedia.streamlytv.streamly.logoutdevices

import android.text.Html
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import com.adaptivemedia.streamlytv.databinding.LogginDevicesRowLayoutBinding
import com.adaptivemedia.streamlytv.streamly.new_log_in.devices.Data

class LogoutDevicesAdapter : ListAdapter<Data, LogoutDevicesAdapter.MyViewHolder>(DiffUtilLogin()) {

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

    override fun onBindViewHolder(holder: MyViewHolder, position: Int) {
        holder.binding.apply {
            val model = getItem(position)
            tvDeviceName.text = Html.fromHtml("<b>Device Name:</b> ${model.device_name}")
            tvDeviceType.text = Html.fromHtml("<b>Device Type:</b> ${model.device_type}")
            logout.setOnCheckedChangeListener { compoundButton, b ->
                //model.isLogout = b
            }
        }
    }


    class DiffUtilLogin : DiffUtil.ItemCallback<Data>() {
        override fun areItemsTheSame(oldItem: Data, newItem: Data) =
            oldItem.device_id == newItem.device_id

        override fun areContentsTheSame(oldItem: Data, newItem: Data) = oldItem == newItem
    }
}