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
                // var ws = new WebSocket('ws://localhost:9990/chat');
                var ws = new WebSocket('ws://localhost:8090');
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
            localStorage.setItem(TOKEN_KEY, token);

           $("#qrcode").html("");
            ws.close();
alert(username);
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
        <div align="center">
            <div id="qrcode">
              <img src='iconLoading.gif' />
            </div>
            <div id="profile"></div>
        </div>

    </body>

</html>