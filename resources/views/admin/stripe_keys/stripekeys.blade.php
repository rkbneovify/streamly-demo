@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <style>
      .field-icon {
  margin-left: -30px;
  margin-top: -25px;
  position: relative;
  z-index: 2;
}
.form-control {
    display: inline!important;
}
.switch {
  float: right;
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
    </style>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stripe Keys</h1> {{ session()->get( 'info' ) }}
          </div>
         
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div class="card">
    <div style="" class="card-header">
    <div>
    <h3 class="card-title">TEST KEYS</h3>
    <label class="switch">
    <input id="togBtn" type="checkbox" value>
    <span class="slider round"></span>
    </label>
    <br><br>
    <label for="pwd">STRIPE KEY:</label><br>
    <input style="width: 600px;" id="password-field" type="password" class="form-control" name="password" value="secret">
    <span toggle="#password-field" class="fa fa-fw fa-eye field-icon toggle-password1"></span><br><br>
    <label for="pwd">STRIPE SECRET:</label><br>
    <input style="width: 600px;" id="password-field2" type="password" class="form-control" name="password" value="secret">
    <span toggle="#password-field2" class="fa fa-fw fa-eye field-icon toggle-password2"></span>
    </div>
    </div>
    </div>


 
    <!-- Main content -->
    <section class="content">

    

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script>

    $(".toggle-password1").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});

$(".toggle-password2").click(function() {

$(this).toggleClass("fa-eye fa-eye-slash");
var input = $($(this).attr("toggle"));
if (input.attr("type") == "password") {
  input.attr("type", "text");
} else {
  input.attr("type", "password");
}
});

$("#togBtn").on('change', function() {
if ($(this).is(':checked')) {
  alert('live');// To verify

  var myHeaders = new Headers();
                var requestOptions = {
                  method: 'PUT',
                  headers: myHeaders,
                  redirect: 'follow'
                };

                fetch(window.location.origin+"/keysupdate/live", requestOptions)
                .then(response => response.json())
                .then(result => {
			        		console.log(result);
			        	})
                .catch(error => console.log('error', error));
}
else {
  alert('testing');// To verify
  var myHeaders = new Headers();
                var requestOptions = {
                  method: 'PUT',
                  headers: myHeaders,
                  redirect: 'follow'
                };

                fetch(window.location.origin+"/keysupdate/testing", requestOptions)
                .then(response => response.json())
                .then(result => {
			        		console.log(result);
			        	})
                .catch(error => console.log('error', error));
}
});
  </script>
@endsection