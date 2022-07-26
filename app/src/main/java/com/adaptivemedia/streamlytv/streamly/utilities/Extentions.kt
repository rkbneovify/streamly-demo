package com.adaptivemedia.streamlytv.streamly.utilities

import android.content.Context
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import com.adaptivemedia.streamlytv.R


/**
 * this is for message dialog box
 */
fun Context.showDialogs(message: String, onClick: () -> Unit) {
    val builder = AlertDialog.Builder(this,R.style.Theme_AppCompat_Dialog)
    builder.setMessage(message)
    builder.setPositiveButton(getString(R.string.yes)) { dialog, _ ->
        onClick()
        dialog.cancel()
    }
    builder.setNegativeButton(getString(R.string.no)) { dialog, _ ->
        dialog.cancel()
    }
    val alert = builder.create()
    alert.show()
}

fun Context.toast(message: String) {
    Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
}