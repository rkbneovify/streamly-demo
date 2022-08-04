package com.adaptivemedia.streamlytv.streamly.utilities

import android.content.Context
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AlertDialog
import com.adaptivemedia.streamlytv.R

val data = "{\n" +
        "    \"success\": false,\n" +
        "    \"response_code\": 479,\n" +
        "    \"message\": \"Your account is in use on 20 devices. please stop playing on other devices to continue\",\n" +
        "    \"loggedindeviceinfolist\": {\n" +
        "        \"numofdevice\": 20,\n" +
        "        \"message\": \"Connected device found successfully\",\n" +
        "        \"data\": [\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"12345\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"8PvjbmlvQM\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"X6x79D6Rq9\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"ecded168-a2b4-5590-ac94-01764c70fdb5\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"rkJK6Wwvn1\",\n" +
        "                \"hotel_room\": \"Room19\",\n" +
        "                \"personaldevicename\": \"ab\",\n" +
        "                \"personaldeviceid\": \"kadvqqVv0y\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"ecded168-a2b4-5590-ac94-01764c70fdb5\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"bLRV81LvPj\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": \"ab\",\n" +
        "                \"personaldeviceid\": \"kadvqqVv0y\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"5f7f5b2114d87bc2\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"KQRZ6lARDn\",\n" +
        "                \"hotel_room\": \"Room3\",\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"glr7pgZRqz\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"123455\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"po7YVXAvgr\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"VAG7Gb9R9x\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"1234564\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"olJDMo2Red\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"yolJDYPRed\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"b3130434d93929a2\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"eLJQN3jR13\",\n" +
        "                \"hotel_room\": \"Room13\",\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"Qqo7EddRma\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"12345646\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"X3RxpZ3RLQ\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"NrkJKrxvn1\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"077fc361-47e4-5170-b77f-9c1545a6093d\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"YnvXraxR6N\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": \"avabear\",\n" +
        "                \"personaldeviceid\": \"NrkJKVbvn1\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"43778\",\n" +
        "                \"device_name\": \"Safari in iOS\",\n" +
        "                \"session_id\": \"ydRmAQ9Rp2\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"wP5JzowR4M\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"123456\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"zp7wm1EJoW\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"NKp78rYJBo\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"123456e\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"XLJ0Golvx5\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"jKQRZg67Dn\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"6f05b658-3098-578c-b196-fc4785796b1e\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"QWvyZzdJoG\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": \"Javi\",\n" +
        "                \"personaldeviceid\": \"9X3RxBevLQ\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"17b7a7bb-6c00-5cb1-85cb-1fd71ecbf6ac\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"AG7Glr8v9x\",\n" +
        "                \"hotel_room\": \"Room16\",\n" +
        "                \"personaldevicename\": \"Office\",\n" +
        "                \"personaldeviceid\": \"qDLJPkjv4n\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"123456dd\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"40vL6AWRPr\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"GydRmYkRp2\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"123456dddh\",\n" +
        "                \"device_name\": \"PostmanRuntime\",\n" +
        "                \"session_id\": \"po7YVnAvgr\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"83NRdVWvE2\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"fbe644ae50cd509d\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"advq8Vr70y\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"MAdvnEe7kP\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"53f7e0a36fb87b7f\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"nx7o8g6vjb\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": null,\n" +
        "                \"personaldeviceid\": \"5lE71PBRNQ\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"a90b98dd-d4e1-5d8b-bb36-9bbe3e4d8fc6\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"lr7pwlWRqz\",\n" +
        "                \"hotel_room\": \"Room15\",\n" +
        "                \"personaldevicename\": \"jlowc\",\n" +
        "                \"personaldeviceid\": \"83NRdDXRE2\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            },\n" +
        "            {\n" +
        "                \"connected_devices\": 1,\n" +
        "                \"device_id\": \"2745ae5d-e50b-54a5-9a36-4f7c721edc1d\",\n" +
        "                \"device_name\": \" in AndroidOS\",\n" +
        "                \"session_id\": \"olJDM94Red\",\n" +
        "                \"hotel_room\": null,\n" +
        "                \"personaldevicename\": \"abcdef\",\n" +
        "                \"personaldeviceid\": \"Nz0RepMJBK\",\n" +
        "                \"device_img\": \"https://streamlytv.com/uploads/devicelogo/tv/normaltv.png\",\n" +
        "                \"device_type\": \"non-web\"\n" +
        "            }\n" +
        "        ]\n" +
        "    }\n" +
        "}"

/**
 * this is for message dialog box
 */
fun Context.showDialogs(message: String, onClick: () -> Unit) {
    val builder = AlertDialog.Builder(this, R.style.CustomDialogTheme)
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

fun View.gone() {
    visibility = View.GONE
}

fun View.inVisible() {
    visibility = View.INVISIBLE
}

fun View.show() {
    visibility = View.VISIBLE
}
internal fun data(){

}
val pair = Pair<Boolean, Int>(false, 20)
fun main() {
    val numbers = listOf("one", "two", "three", "four")
    val longerThan3 = numbers.filter { it =="four" }.joinToString { it }
    println(longerThan3)


    println(numbers.lastIndexOf("four"))
}