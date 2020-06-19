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

	<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<script>
		jQuery(document).ready(function(){
			jQuery('.remove-device').on('click', function(){
				jQuery('#removeDevice').modal('show');
			});

			jQuery('.cancel-subscription').on('click', function(){
				jQuery('#cancelSubscription').modal('show');
			});

			jQuery('.change-password').on('click', function(){
				jQuery('#changePassword').modal('show');
			});
		});
	</script>
@endsection

@section('content')

	<div class="modal fade" id="removeDevice">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					<h4>Remove Device</h4>
					<p>Samsung smart TV - 43 inch will be removed. Would you like to continue?</p>
					<div>
						<a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a>
						<a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">No</a>
					</div>
				</div>

			</div>
	    </div>
	</div>

	<div class="modal fade" id="cancelSubscription">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					<h4>Cancel Subscription</h4>
					<p>Are you sure you want to cancel your Streamly live TV at $69/mo. pack?</p>
					<div>
						<a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Yes</a>
						<a class="btn btn-sm" href="javascript:void(0)" data-dismiss="modal" style="border: 1px solid #707070;border-radius: 6px;padding: 0.5rem 1.5rem;">No</a>
					</div>
				</div>

			</div>
	    </div>
	</div>

	<div class="modal fade" id="changePassword">
	    <div class="modal-dialog">
			<div class="modal-content">

				<div class="modal-body text-center">
					<form class="text-left">
						<div class="form-group">
							<label for="oldpassword">Old Password</label>
							<input type="password" class="form-control" name="oldpassword" id="oldpassword">
						</div>

						<div class="form-group">
							<label for="newpassword">New Password</label>
							<input type="text" class="form-control" name="newpassword" id="newpassword">
						</div>

						<div class="form-group">
							<label for="cnewpassword">New Password</label>
							<input type="password" class="form-control" name="cnewpassword" id="cnewpassword">
						</div>

						<div class="text-center">
							<a class="btn btn-sm" href="javascript:void(0)" style="background: #C4C4C4 0% 0% no-repeat padding-box;padding: 0.5rem 1.5rem;">Change Password</a>
						</div>
					</form>
				</div>

			</div>
	    </div>
	</div>

	<section class="page-section">
		<div class="container">
				<h2 class="section-heading text-center">Account Settings</h2>
				<div class="row setting-cards">
					<div class="col-centered col-md-8">
						<ul class="setting-card">
							<li>Device Management:</li>
							<li>1. Samsung smart TV - 43 inch <i class="fa fa-trash pull-right light-brown remove-device" aria-hidden="true"></i></li>
							<li>2. Sony bravia smart TV - 56 inch <i class="fa fa-trash pull-right light-brown remove-device" aria-hidden="true"></i></li>
							<li>3. Apple iPhone - XR <i class="fa fa-trash pull-right light-brown remove-device" aria-hidden="true"></i></li>
							<li>4. Samsung Note 10 <i class="fa fa-trash pull-right remove-device light-brown" aria-hidden="true"></i></li>
							<li>5. Apple iPhone - XS max <i class="fa fa-trash remove-device pull-right light-brown" aria-hidden="true"></i></li>
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
									<a href="javascript:void(0)" class="change-password">Change password</a>
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