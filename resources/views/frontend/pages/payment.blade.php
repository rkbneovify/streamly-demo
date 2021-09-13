@extends('frontend/partials/master')

@section('top-stylesheets')
<script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
  .ui-widget.ui-widget-content {
    border: 1px solid #c5c5c5;
}
.ui-menu {
    list-style: none;
    padding: 0;
    margin: 0;
    display: block;
    outline: 0;
}
.ui-widget-content {
    border: 1px solid #dddddd;
    background: #ffffff;
    color: #333333;
}

  				.ui-menu .ui-menu-item-wrapper {
    position: relative;
    padding: 3px 1em 3px .4em;
}

.ui-menu .ui-menu-item {
    margin: 0;
    cursor: pointer;
    list-style-image: url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7);
}

.ui-menu-item .ui-menu-item-wrapper.ui-state-active {
    background:#004E92 !important;
    font-weight: bold !important;
    color: #ffffff !important;
} 

/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
				button[disabled] {
  cursor: not-allowed;
	pointer-events: all !important;
}
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
			/* opacity: 0.51; */
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
											<td>{{$plandetails->nickname}}</td>
											<th class="text-right" style="padding-right: 1rem;">$ {{$plandetails->amount/100}}/ {{$plandetails->interval_count}} {{$plandetails->interval}}</th>
										</tr><tr>
											<td>Trial</td>
											<th class="text-right" style="padding-right: 1rem;">{{$plandetails->trial_period_days??'no trials'}} days</th>
										</tr>
										<tr>
											<td>Tax</td>
											<th class="text-right" style="padding-right: 1rem;">$ 0</th>
										</tr>
										<tr  id= "couponrow ">
											<td>Coupon</td>
											<th class="text-right" style="padding-right: 1rem;" id="couponoff">$ 0</th>
										</tr>
									</tbody>
								</table>
								<hr/>
								<div class="container padding-L-rem">
									<div class="row text-left">
										<div class="col-md-6 padding-L-rem">
											Total
										</div>
										<div class="col-md-6 padding-L-rem text-right" id="totalprice" style="font-weight: bold;">
											$ {{$plandetails->amount/100}}
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
									<div class="col-md-8" style="padding: 2rem;">
										{{-- <img src="{{ asset('frontend/img/noun_credit_card.svg') }}"> --}}
										<span><strong>Final amount</strong> </span>
										<span style="display: block;" id="totalprice2">$ {{$plandetails->amount/100}}</span>
									</div>
									{{-- <div class="col-md-4" style="padding: 2rem;">
										<img src="{{ asset('frontend/img/paypal.svg') }}">
										<span style="display: block;">Paypal</span>
									</div> --}}
								</div>
								{{-- {{dd(auth()->user())}} --}}

                    <!-- If user is not subscribed to a plan, show them the payment form -->
										@if(!auth()->user()->subscribed('main'))
								@if(session('error_message'))
 <div class="alert alert-danger alert-dismissible ">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
                                <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                                    Payment Failed
                                </div>
                                <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                                    <p>{{ session('error_message') }}</p>
                                </div>
                            </div>
                        @endif
								<form id="signup-form" action="{{ url('insertpay', [$stripeplanID]) }}" method="post" class="mt-3 text-left" style="padding: 2rem;">
				                    {{-- <div class="form-group">
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
													 --}}
													 <div class="form-group">
														<label for="card-element" class="control-label">Name on Card</label>
														<input type="text" id="name" name="name" class="form-control" required>
														@if ($errors->has('name'))
														<span class="help-block">
														<div class="alert alert-danger alert-dismissible ">
																<button type="button" class="close" data-dismiss="alert">&times;</button>
																<p style="color:red">{{ $errors->first('name') }}</p></div>
														</span>
														@endif
												</div>
											
												<div class="form-row">
													<div class="col-md-6">
															<div class='form-group cvc required'>
																	<label class='control-label'>Coupon Code</label>
																	<input autocomplete='off' class='form-control' placeholder='Coupon code' name="coupon" type='text'>
																	<small class="help-block" style="display:none;"></small>
															</div>
													</div><div class="col-md-6">
															<div class='form-group zip required'>
																	<label class='control-label'>Credit card zip</label>
																	<input id="zipcode"  type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;"  autocomplete='off' class='form-control'  name="zip" type='number' required>
																	<small id="ziphelp" ></small>
															</div>
															@if ($errors->has('zip'))
                      <span class="help-block">
											<div class="alert alert-danger alert-dismissible ">
													<button type="button" class="close" data-dismiss="alert">&times;</button>
													<p style="color:red">{{ $errors->first('zip') }}</p></div>
                      </span>
                      @endif
													</div>
											</div>
											<div class="form-row">
												<div class="col-md-6">
														<div class='form-group  required'>
																<label class='control-label'>Address</label>
																<input id="address-line1" name="address_line1" class="form-control" placeholder="77 Winchester Lane" />
														
														</div>
												</div><div class="col-md-6">
														<div class='form-group  required'>
																<label class='control-label'>Address 2</label>
																<input id="address-line2" name="address_line2" class="form-control" placeholder="" />
																
														</div>
												</div>
										</div>
										<div class="form-row">
												<div class="col-md-6">
														<div class='form-group  required'>
																<label class='control-label'>City</label>
																<input id="address-city" name="address_city" class="form-control" placeholder="" disabled/>
														
														</div>
												</div><div class="col-md-6">
														<div class='form-group  required'>
																<label class='control-label'>State</label>
																<input id="address-state" name="address_state" class="form-control" placeholder="" disabled/>
																
														</div>
												</div>
										</div>
										<div class='form-group  required'>
											<label class='control-label'>Country</label>
											
			<input id="address-country" name="address_country" class="form-control" placeholder="" disabled/>
									</div>
				                    <!-- Stripe Elements Placeholder -->
														<div id="card-element" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></div>
														<div id="card-errors" class="text-red-400 text-bold mt-2 text-sm font-medium"></div>
												</div>
											@if	(empty($frontendjson['billingdetailspage']))
				                    <span class="remove-p-a-r">
				                    	you will be charged ${{$plandetails->amount/100}}/ {{$plandetails->interval_count}} {{$plandetails->interval}} plus applicable taxes on a recurring basis. If you do not cancel a service during its free trial period, you will be charged. Your subscription will continue until you cancel. To cancel, login to your streamly account and follow the instructions. By selecting 'SUBMIT,' you agree to the above terms.
														</span>
														@else
                    
														<span class="remove-p-a-r">
				                  {{$frontendjson['billingdetailspage']}}	</span>
		
												@endif
													<input name="trial_days" type="hidden" value="{{$plandetails->trial_period_days}}">	
													<input name="previousurl" type="hidden" value="{{url()->current()}}">	
													<div class="form-group mt-3">
				                        <button type="submit" id="card-button" class="btn btn-lg submit-btn" style="margin-left: auto;margin-right: auto;display: flex;text-align: center;">
				                        	<span style="width: 100%;">SUBMIT</span>
				                        </button>
				                    </div>

				                    {!! csrf_field() !!}
												</form>
												<!-- Otherwise if the user is subscribed, show them a success message -->
										

                    	@else
                    
                        <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mt-8" role="alert">
                            <p class="font-bold mb-2">Thanks for Subscribing!</p>
                            <p>Your subscription is now active.</p>
														<p>Start watching StreamlyTV right here by visiting the link to the Channel Guide above, 

or by downloading the StreamlyTV App from Apple, AppleTV, Android, Amazon Fire, Roku, or some SmartTV stores!</p>
														
														 
                        </div>

                    @endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</section>
	
@endsection
@section('stylesheets-bottom') 
@if(!auth()->user()->subscribed('main'))
        <script src="https://js.stripe.com/v3/"></script>

        <script>
					       $(function() {
									var bt = document.getElementById('card-button');

$('body').on('blur','[name="coupon"]',function(){
    $coupon = $(this);
    if($coupon.val().length > 0) { // check if user typed anything
  // $coupon.val($coupon.val().toUpperCase()); // I make all my coupons uppercase
  
	var text = document.getElementById("totalprice").textContent;		
  $.ajax({		 
    url:"{{ route('ajax-check') }}",

    type:"GET",
    data:{coupon: $coupon.val()},
    dataType: 'json',
    success: function (data) {
      $coupon.closest('.form-group')
      .removeClass('has-error') // just in case
      .addClass('has-success')
			
      .find('.help-block').css('color', 'green').text(data.message).show();
			if (data.amountoff != null){
			var totprice= {{$plandetails->amount/100}}-data.amountoff;
			document.getElementById("totalprice").textContent='$ '+totprice.toFixed(2);
			document.getElementById("totalprice2").textContent='$ '+totprice.toFixed(2);
			document.getElementById("couponoff").textContent='$ '+data.amountoff+' off';
			
			};
			if (data.percentoff != null){

				console.log('percenttoff',data.percentoff);
				//Calculate the percent.

 var totalValue = {{$plandetails->amount/100}} * ( (100-data.percentoff) / 100 )
			document.getElementById("totalprice").textContent='$ '+totalValue.toFixed(2);
			document.getElementById("totalprice2").textContent='$ '+totalValue.toFixed(2);
			document.getElementById("couponoff").textContent=data.percentoff+'% off';
			
};
    },
    error:function(){ 
      $coupon.closest('.form-group')
      .removeClass('has-success') // just in case
      .addClass('has-error')
			
      .find('.help-block').css('color', 'red').text(data.responseJSON.message).show();
  $coupon.val(''); // coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
    }
});
}
});

$('body').on('focusout','[name="zip"]',function(){
    $zip = $(this);
    if($zip.val().length > 0) { // check if user typed anything
  $.ajax({
							 
    url:"{{ route('ajax-check') }}",

    type:"GET",
    data:{zip: $zip.val()},
    dataType: 'json',
    success: function (data) {
			bt.disabled = false;
      $('#ziphelp').html('<p style="color:green">'+data.message+'</p>');
			$('#address-city').val(data.city); 
			$('#address-state').val(data.state); 
			$('#address-country').val("US"); 
    },
    error:function(xhr, status, error){ 
      if(xhr.status==404) {
        // alert('status:' + xhr.status + ', status text: ' + xhr.responseText);
				bt.disabled = true;
      $('#ziphelp').html('<p style="color:red">'+xhr.responseJSON.message+'</p>');
			$('#address-city').val(''); 
			$('#address-state').val(''); 
			$('#address-country').val("US");  
  
    }
       // coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
    }
});
}
});

// disable spaces for specific inputs
$('body').on('keydown','[name="username"],[name="email"],[name="coupon"]',function(e){
    return e.which !== 32;
});

$( "#zipcode" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
					$.ajaxSetup({
                  headers: {
                      
		'X-CSRF-TOKEN': $("input[name=_token]").val()
                  }
              });
          $.ajax({
                  url: "{{ url('/getzipcodes') }}",
            type: 'post',
            dataType: "json",
            data: {
               search: request.term
            },
            success: function( data ) {
               response( data );
							//  console.log(data);
            }
          });
        },
        select: function (event, ui) {
           // Set selection
           $('#zipcode').val(ui.item.label); // display the selected text
          //  $('#employeeid').val(ui.item.value); // save selected id to input
           return false;
        },
				open: function() {
        $("ul.ui-menu").width( $(this).innerWidth() );
        
    }
      });

});
            const stripe = Stripe('{{ env("STRIPE_KEY") }}');
           // console.log(stripe);

            const elements = stripe.elements();
            const cardElement = elements.create('card',{hidePostalCode: true});

            cardElement.mount('#card-element');

            const cardHolderName = document.getElementById('name');
            const cardButton = document.getElementById('card-button');
            const clientSecret = cardButton.dataset.secret;
            let validCard = false;
            const cardError = document.getElementById('card-errors');

            cardElement.addEventListener('change', function(event) {
                
                if (event.error) {
                    validCard = false;
                    cardError.textContent = event.error.message;
                } else {
                    validCard = true;
                    cardError.textContent = '';
                }
            });
 // Update an element with details collected elsewhere on your page
var myPostalCodeField = document.querySelector('input[name="zip"]');
myPostalCodeField.addEventListener('change', function(event) {
  cardElement.update({value: {postalCode: event.target.value}});
});
            var form = document.getElementById('signup-form');

            form.addEventListener('submit', async (e) => {
                event.preventDefault();
								var options = {
									line1: document.getElementById('address-line1').value,
									line2: document.getElementById('address-line2').value,
    city: document.getElementById('address-city').value,
    state: document.getElementById('address-state').value,
    country: document.getElementById('address-country').value,
  };
                const { paymentMethod, error } = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: { name: cardHolderName.value ,"address": options}
                    }
                );

                if (error) {
                    // Display "error.message" to the user...
                    console.log(error);
                } else {
                    // The card has been verified successfully...
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method');
                    hiddenInput.setAttribute('value', paymentMethod.id);
                    form.appendChild(hiddenInput);
                    // Submit the form
                    form.submit();
                }

            });
        
        </script>
@endif
@endsection