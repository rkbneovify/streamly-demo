<html>
<head>
    <title>Html-Qrcode Demo</title>
<body>
<?php $hashedid= HashUserID(Auth::user()->id); ?>
<h2>{{$hashedid}}</h2>
<h2>{{Auth::user()->name}}</h2>
<h2>{{Auth::user()->email}}</h2>
    <div id="qr-reader" style="width:500px"></div>
    <div id="qr-reader-results"></div>
     <p id="login_mobile_scan_qrcode"></p>
     <p id="qrcodedoLogin"></p>
</body>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="{{ asset('frontend/qr/html5-qrcode.min.js') }}" ></script>
<script>
function qrcodedoLogin(param){
      var url = param;
      console.log("qrcodedoLogin called",url);
    $.ajax({
        type: "POST" ,
        dataType: "json" ,
        url: url ,
        data:{
            //key:key
        },
        success:function(data) {
              if (data.status==1 ){
                  var qrcodeloginurl = data.msg;
            //scan successfull url recieved

                  $('#qrcodedoLogin').text("QR Loggin successfully");
               // console.log("qrcodeloginurl",qrcodeloginurl);
//qrcodedoLogin(qrcodeloginurl);

            } else  if (data.status==2 ){
//couldn't do login
               // alert(data.msg);

                  $('#qrcodedoLogin').text(data.msg);
            }
        }
    });
}function login_mobile_scan_qrcode(param){
      var url = param;
     // console.log("login_mobile_scan_qrcode called",url);
    $.ajax({
        type: "POST" ,
        dataType: "json" ,
        url: url ,
        headers: { 'userpasscode': '{{$hashedid}}' },
        data:{
            //key:key
        },
        success:function(data) {
              if (data.status==1 ){
                  var qrcodeloginurl = data.msg;
            //scan successfull url recieved
                console.log("qrcodeloginurl",qrcodeloginurl);
                
                  $('#login_mobile_scan_qrcode').text("QR scanned successfully");
qrcodedoLogin(qrcodeloginurl);

            } else  if(data.status==2 ){
                // error found
                
                  $('#login_mobile_scan_qrcode').text(data.msg);
            }
        }
    });
}
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var resultContainer = document.getElementById('qr-reader-results');
        var lastResult, countResults = 0;
        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Handle on success condition with the decoded message.
                console.log(`Scan result ${decodedText}`, decodedResult);
                login_mobile_scan_qrcode(decodedText);
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
</head>
</html>