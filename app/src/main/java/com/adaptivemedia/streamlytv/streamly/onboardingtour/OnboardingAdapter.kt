package com.adaptivemedia.streamlytv.streamly.onboardingtour

import android.content.Context
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.LinearLayout
import android.widget.TextView
import androidx.viewpager.widget.PagerAdapter
import com.adaptivemedia.streamlytv.R
import com.bumptech.glide.Glide
import com.smarteist.autoimageslider.SliderViewAdapter


//class OnboardingAdapter(activity: OnBoardingActivity, var onboardlist: ArrayList<OnBoardingItem?>) : PagerAdapter() {
//    private val TAG = "ViewPagerAdapter.class"
//    private var mContext: Context? = null
//     var onboardlists: ArrayList<OnBoardingItem>? = null
//    private var tvHeading: TextView? = null
//    private  var tvDetails:TextView? = null
//    private var ivPic: ImageView? = null
//    var mLayoutInflater: LayoutInflater? = null
//    var mResources = intArrayOf(
//        R.drawable.onboarding_one,
//        R.drawable.boarding_two,
//        R.drawable.boarding_three,
//
//    )
//
//    init {
//        this.mContext = activity
//    }
//    var heading = arrayOf("Watch on any device", "Download and go", "No pesky contracts")
//    var details = arrayOf(
//        "Stream on your phone, tablet, laptop and TV without paying more",
//        "Save your data, watch offline on a plane, train or submarine.",
//        "Join today, Cancel anytime."
//
//    )
//
////
////    fun OnboardingAdapter(context: Context?, onboardlist: ArrayList<OnBoardingItem>?) {
////        mContext = context
////        this.onboardlist = onboardlist
////    }
////
////    @SuppressLint("NotConstructor")
////    fun OnboardingAdapter(activity: Activity?, mResources: IntArray?) {
////        mContext = activity
////        mLayoutInflater =
////            mContext!!.getSystemService(Context.LAYOUT_INFLATER_SERVICE) as LayoutInflater
////    }
//
//    override fun getCount(): Int {
//        return mResources!!.size
//    }
//
//    override fun isViewFromObject(view: View, `object`: Any): Boolean {
//        return view === `object`
//    }
//
//    override fun instantiateItem(container: ViewGroup, position: Int): Any {
//        val itemView: View =
//            LayoutInflater.from(mContext).inflate(R.layout.row_container_boarding, container, false)
//        ivPic = itemView.findViewById(R.id.ivPic)
//        tvHeading = itemView.findViewById(R.id.tvHeading)
//        tvDetails = itemView.findViewById<TextView>(R.id.tvDetails)
//        tvHeading!!.setText(heading[position])
//        tvDetails!!.setText(details[position])
//        ivPic!!.setImageResource(mResources[position])
//        container.addView(itemView)
//        return itemView
//    }
//
//    override fun destroyItem(container: ViewGroup, position: Int, `object`: Any) {
//        container.removeView(`object` as LinearLayout)
//    }
//
//
//}


class OnboardingAdapter(context: Context?, onboardlist: ArrayList<OnBoardingItem?>) :
    SliderViewAdapter<OnboardingAdapter.SliderAdapterViewHolder>() {
    // list for storing urls of images.
    private val mSliderItems: ArrayList<OnBoardingItem?>
        private var mContext: Context? = null

    var mLayoutInflater: LayoutInflater? = null
    var mResources = intArrayOf(
        R.drawable.onboarding_one,
        R.drawable.boarding_two,
        R.drawable.boarding_three,

    )


    var heading = arrayOf("Watch on any device", "Download and go", "No pesky contracts")
    var details = arrayOf(
        "Stream on your phone, tablet, laptop and TV without paying more",
        "Save your data, watch offline on a plane, train or submarine.",
        "Join today, Cancel anytime."

    )

    // We are inflating the slider_layout
    // inside on Create View Holder method.
    override fun onCreateViewHolder(parent: ViewGroup): SliderAdapterViewHolder {
        val inflate: View =
            LayoutInflater.from(parent.context).inflate(R.layout.row_container_boarding, null)
        return SliderAdapterViewHolder(inflate)
    }

    // Inside on bind view holder we will
    // set data to item of Slider View.
    override fun onBindViewHolder(viewHolder: SliderAdapterViewHolder, position: Int) {
        val sliderItem: OnBoardingItem = mSliderItems[position]!!

        // Glide is use to load image
        // from url in your imageview.
        Glide.with(viewHolder.ivPic!!)
            .load(mSliderItems[position]!!.getPic().toString())
            .fitCenter()
            .into(viewHolder.ivPic!!)

       viewHolder.tvDetails!!.setText(mSliderItems[position]!!.getDetails())
        viewHolder.tvHeading!!.setText(mSliderItems[position]!!.getHeading())
    }

    // this method will return
    // the count of our list.
    override fun getCount(): Int {
        return mSliderItems.size
    }

    class SliderAdapterViewHolder(itemView: View) :
        ViewHolder(itemView) {
        // Adapter class for initializing
        // the views of our slider view.
        lateinit var itemView: View
        var onboardlists: ArrayList<OnBoardingItem>? = null
         var tvHeading: TextView? = null
          var tvDetails:TextView? = null
        var ivPic: ImageView? = null
        init {
            ivPic = itemView.findViewById(R.id.ivPic)
        tvHeading = itemView.findViewById(R.id.tvHeading)
        tvDetails = itemView.findViewById<TextView>(R.id.tvDetails)
        //        this.itemView = itemView
        }
    }

    // Constructor
    init {
        this.mContext = context
        this.mSliderItems = onboardlist
    }
}
