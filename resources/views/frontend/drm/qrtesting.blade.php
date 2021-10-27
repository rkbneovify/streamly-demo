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
               // var ws = new WebSocket('wss://'+base+':8090');
                var ws = new WebSocket('wss://'+base+':8090');
                console.log(ws);
                ws.onopen = function() {
                   ws.send(JSON.stringify({ type: "server", code: 0, step: 0 }));

                };
                ws.onmessage = function(evt) {
                   const data = JSON.parse(event.data);
                   //console.log("datafromservver",data);
                   const step = data.data && data.data.step;
                           if (step === 0) {
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
        }            
					


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
               
                console.log("login successfull",uid);

                alert("login successfull",uid);
                  window.location.href = '/';

            } else  if (data.status==2 ){
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