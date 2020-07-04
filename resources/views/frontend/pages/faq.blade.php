@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}
	</style>

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	<style>

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}


		.full-width {
			background: #000000 0% 0% no-repeat padding-box;
		}

		.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		.channel-detail {
			position: absolute;
		    top: 20%;
		    right: -10%;
		    color: #ffffff;
		    padding: 20px;
		    background: #333333 0% 0% no-repeat padding-box;
		    box-shadow: 0px 3px 6px #00000029;
		    opacity: 1;
		    backdrop-filter: blur(30px);
		    -webkit-backdrop-filter: blur(30px);
		}

		.channel-detail:before {
		    content: '';
		    width: 0px;
		    height: 0px;
		    background-color: transparent;
		    z-index: 999;
		    display: block;
		    left: -20px;
		    position: absolute;
		    border-left: 10px solid transparent;
		    border-top: 10px solid transparent;
		    border-right: 10px solid #333333;
		    border-bottom: 10px solid transparent;
		}

		.live-news-item {
		    padding: 2rem 0;
		    text-align: center;
		    background: #FFFFFF 0% 0% no-repeat padding-box;
		    box-shadow: 0px 3px 6px #00000029;
		    border-radius: 8px;
		}

		.custom-row .row{
			margin-top: 2rem;
		}

		.live-news-item:after {
		    content: 'live';
		    position: absolute;
		    right: 12px;
		    top: 12px;
		    color: white;
		    background: #E10C26 0% 0% no-repeat padding-box;
		    border-radius: 4px 0px 0px 4px;
		    padding: 0px 5px;
		    line-height: 1;
		}

		.faq-btn{
			background: #004E92 0% 0% no-repeat padding-box;
			border: 0.25px solid #707070;
			border-radius: 18px;
			color: white;
			padding: 0.4rem 1rem;
		}

		.faq-btn.collapsed {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			color: #5E5E5E;
		}

		#accordion-child .card {
		    margin-top: 5px;
		}

		[data-toggle="collapse"] .fa:before {   
		  content: "\f107";
		}

		[data-toggle="collapse"].collapsed .fa:before {
		  content: "\f106";
		}

		.card-header {
		    background: #FFFFFF 0% 0% no-repeat padding-box;
		    box-shadow: 0px 1px 2px #00000029;
		    border-radius: 2px !important;
		}

		#tou {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 0px 1px 2px #00000029;
			border-radius: 2px;
			padding: 1rem;
		    text-align: left;
		    letter-spacing: 0.26px;
		    color: #989898;
		}

		#pp {
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 0px 1px 2px #00000029;
			border-radius: 2px;
			padding: 1rem;
		    text-align: left;
		    letter-spacing: 0.26px;
		    color: #989898;
		}

		#pp h5{
			color: #5E5E5E;
		}

		a.card-link {
		    color: #5E5E5E;
		}

	</style>
@endsection

@section('content')


	<section class="page-section custom-row">

		<div class="container" style="margin-top: 4%;">
			<h4 class="section-heading text-center">Help & Support</h4>
			<div class="row">
				<div class="col-centered">
					<a href="#faq-question" class="btn btn-sm btn-default faq-btn" data-toggle="collapse">FAQ</a>
				
					<a href="#tou" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Terms of uses</a>
				
					<a href="#pp" class="btn btn-sm btn-default collapsed faq-btn" data-toggle="collapse">Privacy Policy</a>
				</div>
			</div>

			<div class="row" id="accordion">
				<div id="faq-question" class="col-sm-12 collapse show" data-parent="#accordion">
					

					<div id="accordion-child">
					    <div class="card">
					      <div class="card-header">
					        <a class="collapsed card-link" data-toggle="collapse" href="#collapseOne">
					          	What is Streamly live TV?
					        	<i class="fa pull-right" aria-hidden="true"></i>
					        </a>

					        

					      </div>
					      <div id="collapseOne" class="collapse" data-parent="#accordion-child">
					        <div class="card-body">
					          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					        </div>
					      </div>
					    </div>
					    <div class="card">
					      <div class="card-header">
					        <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
					        	What can I do Streamly live TV?
					      		<i class="fa pull-right" aria-hidden="true"></i>
					      	</a>

					      

					      </div>
					      <div id="collapseTwo" class="collapse" data-parent="#accordion-child">
					        <div class="card-body">
					          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					        </div>
					      </div>
					    </div>
					    <div class="card">
					      <div class="card-header">
					        <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
					          	Who can use Streamly live TV?
					        	<i class="fa pull-right" aria-hidden="true"></i>
					        </a>

					        

					      </div>
					      <div id="collapseThree" class="collapse" data-parent="#accordion-child">
					        <div class="card-body">
					          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
					        </div>
					      </div>
					    </div>
					</div>


				</div>

				<div id="tou" class="col-sm-12 collapse" data-parent="#accordion">
					<p>{{$Termofuse}}
					</p>
				</div>

				<div id="pp" class="col-sm-12 collapse" data-parent="#accordion">
					<h5>Data Collection</h5>
				<p>{{$privacypol}}</p>

				</div>
			</div>

			</div>
		</div>
	</section>
	
@endsection