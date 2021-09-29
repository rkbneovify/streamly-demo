@extends('frontend/partials/master')

@section('top-stylesheets')
@include('frontend.partials.sidenavRegisterAndLoginCSS')
@endsection
@section('stylesheets')

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="{{ asset('frontend/qr/jquery.qrcode-0.11.0.min.js') }}" ></script>
    <style>


        .alert.alert-position {
            position: absolute;
            width: 100%;
            z-index: 1040;
        }
    </style>
@endsection
@section('stylesheets-bottom')

@include('frontend.partials.sidenavRegisterAndLoginJS')
<script type="text/javascript">

</script>
	<style>
		nav#mainNav {
			background-color: #004E92;
		}

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}
		
		.page-section {
		    padding: 8rem 0;
		}

		ul.setting-card {
		    margin: 0 auto;
		    list-style: none;
		    box-shadow: 0px 3px 6px #00000029;
    		padding: 0;
		}

		ul.setting-card li:first-child {
			font-weight: bold;
			background: #004E92 0% 0% no-repeat padding-box;
			padding: 12px 34px;
			letter-spacing: 0.24px;
			color: #FFFFFF;
			border-top-right-radius: 6px;
    		border-top-left-radius: 6px;
		}

		ul.setting-card {
		    margin: 0 auto;
		    color: #5E5E5E;
		}	

		.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		ul.setting-card li:not(:first-child) {
		    padding: 12px 34px;
		    letter-spacing: 0.24px;
		}	

		.light-brown{
			color: #989898;
		}

		.account-save-btn {
			background: #C4C4C4 0% 0% no-repeat padding-box;
			border-radius: 6px;
		}

		.account-cancel-btn {
			border: 1px solid #707070;
			border-radius: 6px;
		}

		.cancel-subscription {
			display: block;
    		text-align: right;
    		color: #CF0000;
		}

		i.fa.fa-trash {
		    cursor: pointer;
		}

		i.fa.fa-edit {
			cursor: pointer;
		}

		.modal-dialog {
			min-height: calc(100vh - 60px);
		    display: flex;
		    flex-direction: column;
		    justify-content: center;
		    overflow: auto;
		}

		.modal-body {
		    position: relative;
		    flex: 1 1 auto;
		    padding: 3rem 5rem;
		}

		.modal-body p{
			letter-spacing: 0.24px;
			color: #5E5E5E;
		}
	</style>


@endsection

@section('content')
@if(session()->has('message'))
 <div class="alert alert-success alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		{{ session()->get('message') }}
</div>
@endif

    @include('frontend.partials.sidenavRegisterAndLogin')
    <section class="page-section">
		<div class="container">
				<h2 class="section-heading text-center">QR code scanner</h2>
				<div class="row setting-cards">
					<div class="col-centered col-md-8">
						<ul class="setting-card"> 
                        <li class="text-center">
                        
<?php $hashedid= HashUserID(Auth::user()->id); ?>
<p>passcode: {{$hashedid}}</p>
<p>Name: {{Auth::user()->name}}</p>
<p>Email: {{Auth::user()->email}}</p>
</li>
                        <li class="text-center">
    <div id="qr-reader" ></div>
    <div id="qr-reader-results"></div>
     <p id="login_mobile_scan_qrcode"></p>
     <p id="qrcodedoLogin"></p></li>
                        </ul>
							
					</div>
				</div>
				

				@if ($errors->any())
				<div class="alert alert-danger">
						<ul>
								@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
								@endforeach
						</ul>
				</div>
		@endif

		</div>
		
	</section><section class="page-section">
	
		
	</section>
	
@endsection
	
	
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
}
function login_mobile_scan_qrcode(param){
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