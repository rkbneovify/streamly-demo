<!DOCTYPE HTML>
<html>

<head>

	
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ asset('frontend/qr/jquery.qrcode-0.11.0.min.js') }}" ></script>
    <script type="text/javascript">
        $(document).ready(function() {
            initiate();

        });

        function initiate() {
            if ("WebSocket" in window) {
                var base = window.location.hostname;
                //alert(base);
                // var ws = new WebSocket('ws://localhost:9990/chat');
                var ws = new WebSocket('ws://'+base+':8090');
                ws.onopen = function() {
                   ws.send(JSON.stringify({ type: "server", code: 0, step: 0 }));

                };
                ws.onmessage = function(evt) {
                   const data = JSON.parse(event.data);
                   //console.log("datafromservver",data);
                   const step = data.data && data.data.step;
                           if (step === 0) {
          /**  const anchor = document.createElement("a");
            anchor.href = data.data.url;
            anchor.setAttribute("target", "_blank");
            anchor.setAttribute("rel", "noopener noreferrer");
            anchor.textContent = "link";
            anchor.style = "margin-left: 8px;";
            messageEl.appendChild(anchor);*/
 //Generate QR Code and show to user.
                        $("#qrcode").qrcode({
                            "width": 100,
                            "height": 100,
                            "text": data.data.url
                        });
                          console.log("QR code generated successfully");
          
        }  else if (step === 2) {
            const { username, token } = data.data;
            //localStorage.setItem(TOKEN_KEY, token);

           $("#qrcode").html("");
            ws.close();
//alert(username);
is_loginfun(username);
           // fetchUser();
        }
					//$("#qrcode").html("");                
					


                };


                ws.onclose = function() {
                    console.log("Connection is closed...");
                };
            } else {
                alert("WebSocket NOT supported by your Browser!");
            }
        }

	                
// Check whether the login has been confirmed 
function is_loginfun(param){
      var key = param;
      console.log("is_login called");
    $.ajax({
        type: "POST" ,
        dataType: "json" ,
        url: "web/loginws" ,
        data:{
            key:key ,
              "_token":"{{ csrf_token() }}"
        },
          
            headers: {'x-csrf-token': '{{ csrf_token() }}'}, 
        success:function(data) {
              if (data.status==1 ){
                  var uid = data.jwt;
                  var user = data.user;
                  console.log("user",user);
                //  var sign = data.sign;
                  // Cancel timed tasks and clear cookies  
               // clearInterval($('#is_login' ).val ());
                //$( '#is_login').val('' );
                  // Hide the successful scanning code  
               // $('.barcode-container.scanned .status.scanned, .barcode-container.scanned .mask' ).hide();
                  //  
               // Confirmed pop-up $('.confirmed,.mask' ).show();
                  // Confirm login at a time of 1 second  
               
                console.log("login successfull",uid);

               // $('.qrcode-img').attr('src','');
                alert("login successfull",uid);
                 // $('#thelogindata').text(uid)
                  window.location.href = '/';

            } else  if (data.status==2 ){
                  // Cancel timed tasks and clear cookies  
                //clearInterval($('#is_login').val());
                //$('#is_login').val('' );
                alert(data.msg);
            }
        }
    });
}
    </script>

    <body>
        <br>
        <br>
        <div align="center">
            <div id="qrcode">
              <img src='iconLoading.gif' />
            </div>
            <div id="profile"></div>
        </div>

    </body>

</html>