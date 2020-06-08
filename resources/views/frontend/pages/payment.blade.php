@extends('frontend/partials/master')

@section('top-stylesheets')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}

		.page-section {
		    padding: 8rem 0 !important;
		}

		.subscription-box {
			/*top: 241px;*/
			/*left: 139px;*/
			/*width: 368px;*/
			/*height: 249px;*/
			padding-left: 0 !important;
    		padding-right: 0 !important;
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 0px 3px 6px #00000029;
			border-radius: 6px;
			opacity: 1;
		}

		.subscription-box-head{
			/*top: 241px;*/
			/*left: 139px;*/
			/*width: 368px;*/
			/*height: 50px;*/
			width: 100%;
			border-radius: 6px 6px 0px 0px;
			opacity: 0.51;
			padding-bottom: 10px;
		}

		.subscription-box-head h4 {
			text-align: left;
			font: Regular 20px/70px Roboto;
			letter-spacing: 0.24px;
			color: #FFFFFF;
			opacity: 1;
			padding: 10px;
			padding-left: 1rem;
			background: #004E92 0% 0% no-repeat padding-box;
		}

		.subscription-box .table-sm th, .table-sm td {
		    padding-left: 1rem !important;
		    width: 50%;
		}

		.padding-L-rem{
			padding-left: 1rem !important;
		}

		.top-border {
			padding-top: 15px;
    		border-top: 1px solid;
		}

		.subscription-box hr{
			margin-top: 1rem;
			margin-bottom: 1rem;
		}

		.section-heading {
			margin-bottom: 2rem !important;
		}

		.submit-btn{
		    background-color: #03A9F4 !important;
		    border-radius: 6px !important;
		    width: 100%;
		    color: #fff !important;
		}
	</style>

@endsection

@section('content')
	<section class="page-section">
		<div class="container">
			<div class="text-center">
				<h2 class="section-heading">Add Your Billing Details</h2>
				<div class="row">
					<div class="col-md-4 text-left">
						<div class="row subscription-box">
							<div class="subscription-box-head">
								<h4>Your Subscription:</h4>
								<table class="table table-sm table-borderless" style="margin-top: 1rem;">
									<tbody>
										<tr>
											<td>Streamly live TV</td>
											<th class="text-right" style="padding-right: 1rem;">$ 69/ mo.</th>
										</tr>
										<tr>
											<td>Tax</td>
											<th class="text-right" style="padding-right: 1rem;">$ 0</th>
										</tr>
									</tbody>
								</table>
								<hr/>
								<div class="container padding-L-rem">
									<div class="row text-left">
										<div class="col-md-6 padding-L-rem">
											Total
										</div>
										<div class="col-md-6 padding-L-rem text-right" style="font-weight: bold;">
											$ 69
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-8 h-100">
						<div class="my-auto" style="width: 80%;height: 80%;margin: auto;background: #FFFFFF 0% 0% no-repeat padding-box;box-shadow: 0px 3px 6px #00000029;border-radius: 6px;">
							<div class="container">
								<div class="row" style="padding-top: 2rem;margin-left: 2rem;margin-right: 2rem;">
									<div class="col-md-4" style="padding: 2rem;background: #FFFFFF 0% 0% no-repeat padding-box;border: 1px solid #004E92;border-radius: 4px;">
										<img src="{{ asset('frontend/img/noun_credit_card.svg') }}">
										<span style="display: block;">Credit Card</span>
									</div>
									<div class="col-md-4" style="padding: 2rem;">
										<img src="{{ asset('frontend/img/noun_credit_card.svg') }}">
										<span style="display: block;">Debit Card</span>
									</div>
									<div class="col-md-4" style="padding: 2rem;">
										<img src="{{ asset('frontend/img/paypal.svg') }}">
										<span style="display: block;">Paypal</span>
									</div>
								</div>

								<form action="user/payment" method="post" class="mt-3 text-left" style="padding: 2rem;">
				                    <div class="form-group">
				                        <label for="card" class="control-label">Card Number</label>
				                        <input type="text" id="card" name="card" class="form-control">
				                    </div>

				                    <div class="row">
				                    	<div class="col-md-4">
											<div class="form-group">
						                        <label for="card-expiration" class="control-label">Expiration</label>
						                        <input type="text" id="card-expiration" name="card-expiration" class="form-control">
						                    </div>				                    		
				                    	</div>
				                    	<div class="col-md-4">
				                    		<div class="form-group">
						                        <label for="cvv" class="control-label">CVV</label>
						                        <input type="text" id="card-cvv" name="card-cvv" class="form-control">
						                    </div>
				                    	</div>
				                    	<div class="col-md-4">
				                    		<div class="form-group">
						                        <label for="zip-code" class="control-label">Zip Code</label>
						                        <input type="text" id="zip-code" name="zip-code" class="form-control">
						                    </div>
				                    	</div>
				                    </div>

				                    
				                    <span class="remove-p-a-r">
				                    	you will be charged $69/mo plus applicable taxes on a recurring basis. If you do not cancel a service during its free trial period, you will be charged. Your subscription will continue until you cancel. To cancel, login to your streamly account and follow the instructions. By selecting 'SUBMIT,' you agree to the above terms.
				                    </span>

				                    <div class="form-group mt-3">
				                        <button type="submit" class="btn btn-lg submit-btn" style="margin-left: auto;margin-right: auto;display: flex;text-align: center;">
				                        	<span style="width: 100%;">SUBMIT</span>
				                        </button>
				                    </div>

				                    {!! csrf_field() !!}
				                </form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</section>
	
@endsection