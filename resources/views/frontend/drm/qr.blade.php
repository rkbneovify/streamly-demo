<!DOCTYPE HTML>
<html>

<head>

	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ asset('frontend/qr/jquery.qrcode-0.11.0.min.js') }}" ></script>
    <script type="text/javascript">
        $(document).ready(function() {
            initiate();

        });

        function initiate() {
           // Click the scan code pop-up box   
    $('.scanqrcode').click( function (){
        $( '.login-section').hide(); // Hide the login box   
        $('.qrcode').show(); // pop-up window   
        $('.timeout' ).hide();
        $( '.barcode-container.scanned .status.scanned, .barcode-container.scanned .mask' ).hide();
        $( '.login_op').show(); // Show mask layer 
        // Request QR code 
        $.ajax({
            type: "POST" ,
            dataType: "json" ,
            url: "/login/create/qrcode" ,
            Success: function (data) {
                   if(data.status ==. 1 ) {
                       var qrcodeimg =data.msg;
                       // of Put in hidden The Key Domain   
                    $('#key').val(data.key);
                       // Replace the QR code   
                    $('.qrcode-img').attr('src',qrcodeimg);
                       //Trigger timed task, check whether it has been Scan code 
                    var inter = setInterval("is_sacn_qrcode();",3000 );
                    $( '#timing' ).val(inter);
                }
            }
        });
    });

    // Check whether the code has been scanned 
function is_sacn_qrcode (){
    var key = $('#key').val();
    $.ajax({
        type: "POST" ,
        dataType: "json" ,
        url: "/login/scan/qrcode" ,
        data:{
            key:key
        },
        success: function (data) {
              if (data.status==1 ){
                  // Scan code successfully  
                $('.barcode-container.scanned .status.scanned, .barcode-container.scanned .mask' ).show() ;
                  // Cancel timing tasks and clear cookies  
                clearInterval($('#timing' ).val());
                $( '#timing').val('' );
                  // // close the pop-up window at a timing of 2 seconds 
                // setTimeout(function(){ 
                //     $('.qrcode').hide( ); 
                // }, 2000);

                // Check whether the login has been confirmed 
                var is_login = setInterval("is_login();",3000 );
                $('#is_login').val(is_login);
                  // $.cookie('is_login', is_login); 

            } else  if (data.status==2 ){
                $( '.timeout,.mask' ).show();
                  // Cancel timing tasks and clear cookies  
                clearInterval($('#timing' ).val());
                $( '#timing').val('' );
            }
        }
    });
}

// Check whether the login has been confirmed 
function is_login(){
      var key = $('#key').val();
    $.ajax({
        type: "POST" ,
        dataType: "json" ,
        url: "/login/entry/login" ,
        data:{
            key:key
        },
        success: function (data) {
              if (data.status==1 ){
                  var uid = data.uid;
                  var sign = data.sign;
                  // Cancel timed tasks and clear cookies  
                clearInterval($('#is_login' ).val ());
                $( '#is_login').val('' );
                  // Hide the successful scanning code  
                $('.barcode-container.scanned .status.scanned, .barcode-container.scanned .mask' ).hide();
                  //  
                Confirmed pop-up $('.confirmed,.mask' ).show();
                  // Confirm login at a time of 1 second  
                setTimeout( function (){
                      // Confirm login  
                    $('#login_username' ).val (data.user);
                    $( '#login_password' ).val(data.sign);
                    $( '#login-form' ).submit();
                }, 1000 );

            } else  if (data.status==2 ){
                  // Cancel timed tasks and clear cookies  
                clearInterval($('#is_login' ).val());
                $( '#is_login').val('' );
                alert(data.msg);
            }
        }
    });
}
        
        
        
        }

	
        function getProfileData(token) {
            if(token=='1234567'){
                var str = "";
                str += "<b>Your Name:</b>sahil<br>";
                str += "<b>Gender:</b>male<br>";
                str += "<b>Photo:</b>Some pic<br>";
                str += "Profile Link:  sdkjs";
                $("#profile").html(str);
            }else{
                var str = "";
                str += "<b>Your Name:</b>Unknown successfull<br>";
                str += "<b>Gender:</b>Unkonow<br>";
                str += "<b>Photo:</b>Some pic<br>";
                str += "Profile Link:  Unkonow";
                $("#profile").html(str);
            }
            // var url = "https://graph.facebook.com/me?access_token=" + token;
            // $.get(url, function(obj) {
            //     var str = "";
            //     str += "<b>Your Name:</b>" + obj.first_name + "  " + obj.last_name + "<br>";
            //     str += "<b>Gender:</b>" + obj.gender + "<br>";
            //     str += "<b>Photo:</b><img src='https://graph.facebook.com/me/picture?access_token=" + token + "'/>" + "<br>";
            //     str += "Profile Link:  <a href='" + obj.link + "'>" + obj.link + "</a>";
            //     $("#profile").html(str);

            // }, "json").error(function(err) {


            // });;

        }
    </script>

    <body>
        <br>
        <br>
        <button class="scanqrcode">Login with QR</button>
        <div align="center">
            <div id="qrcode">
              <img src='iconLoading.gif' />
            </div>
            <div id="profile"></div>
        </div>

    </body>

</html>