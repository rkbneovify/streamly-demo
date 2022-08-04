package com.adaptivemedia.streamlytv.streamly.dialogs

import com.adaptivemedia.streamlytv.streamly.dialogs.ToastDialog
import android.app.ProgressDialog
import android.os.Bundle
import com.adaptivemedia.streamlytv.R
import com.adaptivemedia.streamlytv.streamly.utilities.CommonUtility
import com.adaptivemedia.streamlytv.streamly.local_storage.TokenStorage
import com.adaptivemedia.streamlytv.streamly.utilities.ConstantUtility
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiInterface
import com.adaptivemedia.streamlytv.streamly.retrofit_clients.ApiClient
import com.adaptivemedia.streamlytv.streamly.log_in.LogInModel
import android.content.Intent
import com.adaptivemedia.streamlytv.streamly.log_in.LogInActivity
import android.app.Activity
import android.app.Dialog
import android.content.Context
import android.util.Log
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Button
import androidx.fragment.app.DialogFragment
import com.adaptivemedia.streamlytv.databinding.SessionExpiredDialogBinding
import retrofit2.Call
import retrofit2.Callback
import retrofit2.Response
import java.lang.Exception

class ToastDialog(private val message: String) : DialogFragment() {

    lateinit var binding: SessionExpiredDialogBinding
    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View {
        binding = SessionExpiredDialogBinding.inflate(inflater, container, false)
        return binding.root
    }

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        binding.apply {
            tvMessage.text = message
            btnOkSessionExpired.requestFocus()
            btnOkSessionExpired.setOnClickListener {
                dismiss()
            }
        }
    }


}