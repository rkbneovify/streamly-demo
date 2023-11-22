package com.adaptivemedia.streamlytv.streamly.logoutdevices

import android.annotation.SuppressLint
import android.app.Activity
import android.content.ContentValues
import android.content.Context
import android.graphics.Color
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Filter
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import androidx.constraintlayout.widget.ConstraintLayout
import androidx.recyclerview.widget.RecyclerView
import com.bumptech.glide.Glide
import com.bumptech.glide.load.engine.DiskCacheStrategy
import com.bumptech.glide.load.resource.drawable.DrawableTransitionOptions
import com.adaptivemedia.streamlytv.R

import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage
import com.adaptivemedia.streamlytv.streamly.terms.RegisteredDataa
import com.adaptivemedia.streamlytv.streamly.terms.RegisteredDevicesModel
import com.adaptivemedia.streamlytv.streamly.utilities.*
import retrofit2.Callback


class RegisteredDevicesAdapter(
    context: Activity,
    var sharedList: ArrayList<RegisteredDataa>,
    activity: Activity?
) : RecyclerView.Adapter<RegisteredDevicesAdapter.ViewHolder>() {

    private val sList: ArrayList<RegisteredDataa>
    var activity1: Activity? = null
    internal var context: Context
    private lateinit var bean: RegisteredDataa
    var lastSelectedPosition: Int = -1
    private var registeredDevicesFragment: LogoutDeviceNewActivity

    init {
        this.context = context
        this.sList = sharedList
        this.registeredDevicesFragment = activity as LogoutDeviceNewActivity
    }

    class ViewHolder(view: View) : RecyclerView.ViewHolder(view) {

        val tvDeviceName: TextView
        val tvpersonaldevicename: TextView
        val tvDeviceType: TextView
        val ivImage: ImageView
        val llDelete: ConstraintLayout
        val llLayout: ConstraintLayout

        init {

            tvDeviceName = view.findViewById(R.id.tvDeviceName)
            tvpersonaldevicename = view.findViewById(R.id.tvDeviceName1)
            tvDeviceType = view.findViewById(R.id.tvDeviceType)
            ivImage = view.findViewById(R.id.ivImage)
            llDelete = view.findViewById(R.id.llDelete)
            llLayout = view.findViewById(R.id.llLayout)


        }
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.loggin_devices_row_layout, parent, false)
        return ViewHolder(view)
    }

    @SuppressLint("LongLogTag", "HardwareIds")
    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
//        holder.ivUnSelected.visibility = View.VISIBLE
        val deviceid = sharedList[position].getDeviceid()

        if (lastSelectedPosition == position) {
            Log.e(ContentValues.TAG, "open if ::>>")
//            holder.ivSelected.visibility = View.VISIBLE
//            holder.ivUnSelected.visibility = View.GONE

        } else {
            Log.e(ContentValues.TAG, "open if ::>>")
//            holder.ivSelected.visibility = View.GONE
//            holder.ivUnSelected.visibility = View.VISIBLE

        }



//        Log.e(TAG, "device pref ::>> " + Preferences.device_id)
//        if (Preferences.device_id.equals(deviceid)) {
//            Log.e(TAG, "::>>" + Preferences.device_id)
//            holder.tvthisDevice.visibility = View.VISIBLE
////            holder.ivSelected.visibility = View.GONE
////            holder.ivUnSelected.visibility = View.GONE
//        }

        holder.tvpersonaldevicename.text = sharedList[position].getpersonaldevicename()
     var  deviceID = TokenStorage.readPersonalDeviceID(registeredDevicesFragment, ConstantUtility.PERSONAL_DEVICE_ID, "")

//        if (sharedList[position].gethotel_room() == null){
//            Log.e(TAG,"gethotel_room if ::>> ")
//            holder.tvRoomNumber.visibility = View.GONE
//            holder.tvRoomNumber1.visibility = View.GONE
//
//            Log.e(TAG, "device pref ::>> " + Preferences.device_id)
            if (deviceID.equals(deviceid)) {
//                Log.e(TAG, "::>>" + Preferences.device_id)
//                holder.tvthisDevice.visibility = View.VISIBLE
//                holder.ivSelected.visibility = View.GONE
//                holder.ivUnSelected.visibility = View.GONE
//                holder.ivlogout.visibility = View.GONE

            }else{
//                holder.ivlogout.visibility = View.VISIBLE
            }

            holder.tvpersonaldevicename.text = sharedList[position].getpersonaldevicename()

//            if (sharedList[position].gethotel_room() == null){
//                Log.e(TAG,"gethotel_room if ::>> ")
//                holder.tvRoomNumber.visibility = View.GONE
//                holder.tvRoomNumber1.visibility = View.GONE
//            } else {
//                Log.e(TAG,"gethotel_room else ::>> ")
//                holder.tvRoomNumber.text = sharedList[position].gethotel_room()
//            }

            holder.tvDeviceType.text = sharedList[position].getdevice_type()
            holder.tvDeviceName.text = sharedList[position].getDeviceName()

            Glide.with(holder.ivImage)
                .load(sharedList[position].getdevice_img())
                .error(R.drawable.programguide_icon_placeholder)
                .diskCacheStrategy(DiskCacheStrategy.ALL)
                .transition(DrawableTransitionOptions.withCrossFade())
                .into(holder.ivImage)

//        } else {
//            Log.e(TAG,"gethotel_room else ::>> ")
//            holder.tvRoomNumber.text = sharedList[position].gethotel_room()
//        }

        holder.tvDeviceName.text = sharedList[position].getdevice_type()

//        Glide.with(holder.imgDevice)
//            .load(sharedList[position].getdevice_img())
//            .error(R.drawable.programguide_icon_placeholder)
//            .diskCacheStrategy(DiskCacheStrategy.ALL)
//            .transition(DrawableTransitionOptions.withCrossFade())
//            .into(holder.imgDevice)

        /*if (sharedList[position].getDeviceName().equals(context.getString(R.string.okhttp))
            || sharedList[position].getDeviceName().equals(context.getString(R.string.android))
            || sharedList[position].getDeviceName().equals(context.getString(R.string.androidd))
        ) {
            holder.imgDevice.setImageResource(R.drawable.android)

        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.inn))
            || sharedList[position].getDeviceName().equals(context.getString(R.string.ios))
            || sharedList[position].getDeviceName().equals(context.getString(R.string.iphone))
        ) {
            holder.imgDevice.setImageResource(R.drawable.ios)

        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.chrome_in_os_x))) {
            holder.imgDevice.setImageResource(R.drawable.ios_mac)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.chrome_in_windows))) {
            holder.imgDevice.setImageResource(R.drawable.windows)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.safari_in_os_x))) {
            holder.imgDevice.setImageResource(R.drawable.ios_mac)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.linux))
            || sharedList[position].getDeviceName().equals(context.getString(R.string.chrome_in_linux))
        ) {
            holder.imgDevice.setImageResource(R.drawable.linux)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.tv))) {
            holder.imgDevice.setImageResource(R.drawable.tv)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.android_tv))) {
            holder.imgDevice.setImageResource(R.drawable.android_tv)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.tab))
            || sharedList[position].getDeviceName().equals(context.getString(R.string.in_androidos))
        ) {
            holder.imgDevice.setImageResource(R.drawable.tab)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.laptop))) {
            holder.imgDevice.setImageResource(R.drawable.laptop)
        } else if (sharedList[position].getDeviceName().equals(context.getString(R.string.in_ios))) {
            holder.imgDevice.setImageResource(R.drawable.ipad)
        } else {
            holder.imgDevice.setImageResource(R.drawable.android)
        }*/
        holder.llLayout.setOnFocusChangeListener { view, b ->
            if (b) {
                if (deviceID.equals(deviceid)) {
                    holder.ivImage.show()
                    holder.llDelete.gone()
                    holder.llLayout.setBackgroundResource(R.drawable.white_border_background)
                }else {
                    holder.ivImage.inVisible()
                    holder.llDelete.show()
                    holder.llLayout.setBackgroundColor(Color.TRANSPARENT)
                }
            } else {
                holder.ivImage.show()
                holder.llDelete.gone()
                holder.llLayout.setBackgroundResource(R.drawable.white_border_background)

            }
        }
        holder.llLayout.setOnClickListener {
            if (deviceID.equals(deviceid)) {
                CommonUtility.showToast(context, "This device")
            }else {
                lastSelectedPosition = position
                sharedList[position].isSelected

                var logout_id = sharedList!![position].getsession_id()
                (registeredDevicesFragment).getClickedData(logout_id)
                registeredDevicesFragment.logotMehodcall()

//                holder.ivSelected.visibility = View.VISIBLE
//                holder.ivUnSelected.visibility = View.GONE
            }
//            Log.e(TAG, ">>>::>>" + Preferences.device_id)
        }
        holder.itemView.setOnClickListener {
//            if (Preferences.device_id.equals(deviceid)) {
//                Log.e(TAG, "::>>" + Preferences.device_id)
//                holder.tvthisDevice.visibility = View.VISIBLE
////                holder.ivSelected.visibility = View.GONE
////                holder.ivUnSelected.visibility = View.GONE
//            }else {
//                lastSelectedPosition = position
//                sharedList[position].isSelected
//
//                var logout_id = sharedList!![position].getsession_id()
//                (registeredDevicesFragment).getClickedData(logout_id)
//                registeredDevicesFragment.logotMehodcall()
//
////                holder.ivSelected.visibility = View.VISIBLE
////                holder.ivUnSelected.visibility = View.GONE
//            }
//            notifyDataSetChanged()
        }
    }

    override fun getItemCount(): Int {
        return sharedList.size
    }
    fun getFilter(activity: Activity?): Filter {
        return object : Filter() {
            override fun performFiltering(charSequence: CharSequence): FilterResults {
                val charString = charSequence.toString()
                if (charString.isEmpty()) {
                    sharedList = sList
                } else {
                    val filteredList = ArrayList<RegisteredDataa>()
                    for (androidVersion in sList!!) {
                        if (androidVersion.personaldevicename!!.toLowerCase().contains(charString) ||
                            androidVersion.personaldevicename!!.toUpperCase().contains(charString) ||
                            androidVersion.device_type!!.toLowerCase().contains(charString) ||
                            androidVersion.device_type!!.toUpperCase().contains(charString)
                        ) {
                            Log.e(ContentValues.TAG, "::>>>if enter...")
                            filteredList.add(androidVersion)
                        } else {
                            Log.e(ContentValues.TAG, "::>>>else enter...")
//                            var searchCallBacklistener = ((SearchCallBack) activity)
                        }
                    }
                    sharedList = filteredList
                }
                val filterResults = FilterResults()
                filterResults.values = sharedList
                return filterResults
            }

            override fun publishResults(charSequence: CharSequence, filterResults: FilterResults) {
//                if (logdatalist.size <= 0) {
////                    searchCallBack!!.getRecyclerGone()
//                } else {
//                    logdatalist = filterResults.values as ArrayList<LogInModel.Rooms?>
//                    searchCallBack!!.showRecyclerView()
//                }
                notifyDataSetChanged()
            }
        }
    }

    companion object {
        private val TAG = "RDevicesAdapter"
    }
}
