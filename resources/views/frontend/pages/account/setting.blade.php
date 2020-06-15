@extends('frontend/partials/master')

@section('stylesheets-bottom')
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
		}	

		.col-centered {
		    margin: 0 auto;
		    position: relative;
		}

		ul.setting-card li:not(:first-child) {
		    padding: 12px 34px;
		    letter-spacing: 0.24px;
		}	
	</style>
@endsection

@section('content')
	<section class="page-section">
		<div class="container">
				<h2 class="section-heading text-center">Account Settings</h2>
				<div class="row setting-cards">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>Device Management:</li>
							<li>1. Samsung smart TV - 43 inch <span><i class="fa fa-trash"></i></span></li>
						</ul>
					</div>
				</div>
		</div>
		
	</section>
	
@endsection