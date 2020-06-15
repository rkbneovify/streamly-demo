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
	</style>

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
	<section class="page-section">
		<div class="container">
				<h2 class="section-heading text-center">Account Settings</h2>
				<div class="row setting-cards">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>Device Management:</li>
							<li>1. Samsung smart TV - 43 inch <i class="fa fa-trash pull-right light-brown" aria-hidden="true"></i></li>
							<li>2. Sony bravia smart TV - 56 inch <i class="fa fa-trash pull-right light-brown" aria-hidden="true"></i></li>
							<li>3. Apple iPhone - XR <i class="fa fa-trash pull-right light-brown" aria-hidden="true"></i></li>
							<li>4. Samsung Note 10 <i class="fa fa-trash pull-right light-brown" aria-hidden="true"></i></li>
							<li>5. Apple iPhone - XS max <i class="fa fa-trash pull-right light-brown" aria-hidden="true"></i></li>
						</ul>
					</div>
				</div>

				<div class="row setting-cards mt-5">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>Profile Management</li>
							<li>
								Username:
								<span class="pull-right">Minashau_123 <i class="fa fa-edit light-brown" aria-hidden="true"></i></span>
							</li>
							<li>
								Password:
								<span class="pull-right">
									<a href="javascript:void(0)">Change password</a>
								</span>
							</li>
						</ul>
					</div>
				</div>

				<div class="row setting-cards mt-5">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>
								Current Pack
								<b><span class="pull-right">Streamly live Tv at $ 69/mo.</span></b>
							</li>
							<li>
								Streamly live Tv at $ 69/mo.
								<b><span class="pull-right">24, May 2020</span></b>
							</li>
							<li>
								Payment method
								<b><span class="pull-right">46xx xxx xxxx 5685 <i class="fa fa-edit light-brown" aria-hidden="true"></i></span></b>
							</li>
							<li>
								<a href="javascript:void(0)" class="cancel-subscription">Cancel subscription</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="row setting-cards mt-5">
					<div class="text-center col-centered col-md-8">
						<a class="btn btn-lg account-save-btn" href="javascript:void(0)">SAVE CHANGES</a>
						<a class="btn btn-lg account-cancel-btn ml-4" style="width: 12rem;" href="javascript:void(0)">Cancel</a>
					</div>
				</div>
		</div>
		
	</section>
	
@endsection