<!DOCTYPE HTML>
<html>

<head>

	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ asset('frontend/qr/jquery.qrcode-0.11.0.min.js') }}" ></script>
    <script type="text/javascript">
                
// Check whether the login has been confirmed 
function is_loginfun(){
      var key = $('#key').val();
      console.log("is_login called");
    $.ajax({
        type: "POST" ,
        dataType: "json" ,
        url: "api/login/entry/login" ,
        data:{
            key:key
        },
        success:function(data) {
              if (data.status==1 ){
                  var uid = data.jwt;
                //  var sign = data.sign;
                  // Cancel timed tasks and clear cookies  
                clearInterval($('#is_login' ).val ());
                $( '#is_login').val('' );
                  // Hide the successful scanning code  
               // $('.barcode-container.scanned .status.scanned, .barcode-container.scanned .mask' ).hide();
                  //  
               // Confirmed pop-up $('.confirmed,.mask' ).show();
                  // Confirm login at a time of 1 second  
                setTimeout( function (){
                      // Confirm login  
                    $('#login_username' ).val (data.user);
                    $( '#login_password' ).val(data.sign);
                    $( '#login-form' ).submit();
                }, 1000 );
                console.log("login successfull",uid);

                $('.qrcode-img').attr('src','');
                alert("login successfull",uid);

            } else  if (data.status==2 ){
                  // Cancel timed tasks and clear cookies  
                clearInterval($('#is_login').val());
                $('#is_login').val('' );
                alert(data.msg);
            }
        }
    });
}

    // Check whether the code has been scanned 
function is_sacn_qrcode(){
    var key = $('#key').val();
    console.log("is_sacn_qrcode called");
    //return;
    $.ajax({
        type: "POST" ,
        dataType: "json" ,
        url: "api/login/scan/qrcode" ,
        data:{
            key:key
        },
        success: function(data){
              if(data.status==1){
                  // Scan code successfully  
                // $('.barcode-container.scanned .status.scanned, .barcode-container.scanned .mask' ).show() ; 
                  // Cancel timing tasks and clear cookies  
                clearInterval($('#timing').val());
                $( '#timing').val('');
                  // // close the pop-up window at a timing of 2 seconds 
                // setTimeout(function(){ 
                //     $('.qrcode').hide( ); 
                // }, 2000);

                // Check whether the login has been confirmed 
            
            var is_login = setInterval(function() {
      is_loginfun();
   },3000);
                $('#is_login').val(is_login);
                  // $.cookie('is_login', is_login); 

            } else if(data.status==2 ){
                $('.timeout,.mask').show();
                  // Cancel timing tasks and clear cookies  
                clearInterval($('#timing').val());
                $('#timing').val('');
            }
        }
    });
}
        $(document).ready(function() {


        
           // Click the scan code pop-up box   
    $('.scanqrcode').click(function(){
       // $( '.login-section').hide(); // Hide the login box   
        //$('.qrcode').show(); // pop-up window   
        //$('.timeout' ).hide();
        //$( '.barcode-container.scanned .status.scanned, .barcode-container.scanned .mask' ).hide();
        //$( '.login_op').show(); // Show mask layer 
        // Request QR code 
        $.ajax({
            type: "POST" ,
            dataType: "json" ,
            url: "api/login/create/qrcode" , 
            headers: {'x-csrf-token': '{{ csrf_token() }}'},
            success:function(data){
                   if(data.status == 1) {
                       var qrcodeimg=data.msg;
                       // of Put in hidden The Key Domain   
                    $('#key').val(data.key);
                    console.log("key",data.key);
                       // Replace the QR code   
                    $('.qrcode-img').attr('src',qrcodeimg);
                       //Trigger timed task, check whether it has been Scan code 
                    var inter = setInterval(function() {
      is_sacn_qrcode();
   },3000);
                    $('#timing').val(inter);
                }
            }
        });
    });


        
    
        });    
        
    </script>

    <body>
        <br>
        <br>
        <button class="scanqrcode">Login with QR</button>
        <div align="center">
            <div id="qrcode">
              <img class="qrcode-img" src="" />
              <input type="hidden" id="key">
             <input type="text" id="timing" value="timing">
             <input type="text" id="is_login" value="is_login ">
            </div>
            <div id="profile"></div>
        </div>

    </body>

</html>