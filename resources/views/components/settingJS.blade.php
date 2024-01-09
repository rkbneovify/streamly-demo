//hoursoffset start
	String.prototype.toHHMMSS = function () {

var sec_num = parseInt(this, 10); // don't forget the second param
var hours   = Math.floor(sec_num / 3600);
var minutes = Math.floor((sec_num - (hours * 3600)) / 60);


if (hours   < 10) {hours   = "0"+hours;}
if (minutes < 10) {minutes = "0"+minutes;}

return hours + ':' + minutes ;
}
const d = new Date();
let diff = d.getTimezoneOffset();
var newdiff = String(diff * 60);
if (newdiff < 0) {
  var resultHours = newdiff.substr(1);
  var signvalue = true;
}else{
	var resultHours = newdiff;
	var signvalue = false;
  }  
var newtz = resultHours.toHHMMSS();
if(signvalue == true){
var resultoffset = "+".concat(newtz)
}else{ 
	var resultoffset = "-".concat(newtz);
	}
	//hoursoffset end
	if(resultoffset == "+05:30"){
		var timezone = "Eastern Standard Time";
	}else if(resultoffset == "-05:00"){
		var timezone = "Eastern Standard Time";
	}else if(resultoffset == "-08:00"){
		var timezone = "Pacific Standard Time";
	}else if(resultoffset == "-06:00"){
		var timezone = "Central Standard Time";
	}else if(resultoffset == "-07:00"){
		var timezone = "Mountain Standard Time";
	}else if(resultoffset == "-09:00"){
		var timezone = "Alaska Standard Time";
	}else if(resultoffset == "-10:00"){
		var timezone = "Hawaii Standard Time";
	}else if(resultoffset == "-04:00"){
		var timezone = "Canada Atlantic Time";
	}else if(resultoffset == "-03:30"){
		var timezone = "Newfoundland Time";
	}else{
		var timezone = "GMT".concat(resultoffset)
	}
	document.getElementById("timezone").innerHTML = timezone;
	</script>
	
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
<script>
    var firebaseConfig = {
        apiKey: "AIzaSyDZUPtq5LXm8bbcX5P-z0r-B08V65xq2aw",
    authDomain: "streamly-tv-android.firebaseapp.com",
    databaseURL: "https://streamly-tv-android.firebaseio.com",
    projectId: "streamly-tv-android",
    storageBucket: "streamly-tv-android.appspot.com",
    messagingSenderId: "187207008307",
    appId: "1:187207008307:web:40473f0b50d96ab0d76346",
    measurementId: "G-5DTH6FMY71"
    };
	firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();
	messaging.onMessage(function (payload) {
        const title = payload.notification.title;
        const options = {
            body: payload.notification.body,
            icon: payload.notification.icon,
        };
        new Notification(title, options);
    });

//slides card
let slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
  showSlides(slideIndex += n);
}

function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");
  if (n > slides.length) {slideIndex = 1}    
  if (n < 1) {slideIndex = slides.length}
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";  
  }
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}

function removeCard(intValue){
   //alert(intValue);
   let text = "Are you sure want to delete?";

  if (confirm(text) == true) { 
	window.location.href = "/user/removeCard/"+intValue+"";
  } else { 
  }
}

$('#defaultValue').on('change', function(){
   this.value = this.checked ? 1 : 0;
}).change();