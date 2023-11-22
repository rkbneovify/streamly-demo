package com.adaptivemedia.streamlytv.streamly.dialogs

import android.os.Bundle
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.fragment.app.DialogFragment
import com.adaptivemedia.streamlytv.databinding.DialogSignoutBinding
import com.adaptivemedia.streamlytv.databinding.DialogZipcodeBinding
import kotlinx.android.synthetic.main.dialog_zipcode.btn_ok

class CommonDialogZipCode(val message: String, private val dialogClickListeners: DialogZipCodeClickListeners) :
    DialogFragment() {


    lateinit var binding: DialogZipcodeBinding

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = DialogZipcodeBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        CommonUtility.checkInternetConnection(context)

        binding.apply {
            tvMessage.text = message
//            btn_ok.requestFocus()

            btn_ok.setOnClickListener {
                dialogClickListeners.ok()
                dismiss()
            }
        }
    }


}

interface DialogZipCodeClickListeners {

    fun ok()
}