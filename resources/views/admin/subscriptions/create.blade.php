@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Subscription</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Create Subscription</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">General</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
              </div>
            </div>
            <div class="panel-body">
                        <!-- Display Validation Errors -->
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form id="signup-form" method="POST" enctype="multipart/form-data" action="{{ url('admin/insertquotation') }}">
                      
        
        {{ csrf_field() }}
            <div class="card-body">

            <div class="form-group{{ $errors->has('product') ? ' has-error' : '' }}">
              <label for="product">Plans coming from stripe dashboard</label>

              <select name="product" id="product" class="form-control">
                @foreach($plans as $id => $pack)
                <option value="{{ $pack->id."#~".$pack->trial_period_days }}">
                  {{ $pack->nickname }}
                </option>
                
                @endforeach
              </select>
              @if ($errors->has('product'))
              <span class="help-block">
                <strong>{{ $errors->first('product') }}</strong>
              </span>
              @endif
            </div>
              {{-- <div class="form-group{{ $errors->has('subscriptionpack_id') ? ' has-error' : '' }}">
                <label for="subscriptionpack_id">Subscription</label>
                
                <select name="subscriptionpack_id" id="subscriptionpack_id" class="form-control" >
                    @foreach($pack as $id => $pack)
                        <option value="{{ $id }}" >
                            {{ $pack }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('subscriptionpack_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('subscriptionpack_id') }}</strong>
                                    </span>
                                    @endif
              </div> --}}
              
              <div class="form-group{{ $errors->has('users_id') ? ' has-error' : '' }}">
                <label for="users_id">User</label>
                
                <select name="users_id" id="users_id" class="form-control" >
                    @foreach($users as $id => $users)
                        <option value="{{ $users->id }}" >
                            {{ $users->name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('users_id'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('users_id') }}</strong>
                                    </span>
                                    @endif
              </div>
              
              <div class="form-row">
                <div class="col-md-6">
                    <div class='form-group  required'>
                        <label class='control-label'>Address</label>
                        <input id="address-line1" name="address_line1" class="form-control" placeholder="77 Winchester Lane" />
                    
                    </div>
                </div><div class="col-md-6">
                    <div class='form-group  required'>
                        <label class='control-label'>Address (cont.)</label>
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
                  <input autocomplete='off' class='form-control'  name="zip" type='number' required>
                  <small id="ziphelp" ></small>
              </div>
          </div>
      </div> <div class="form-row"><div class="col-md-6">
        <div class="form-group">
          <label for="card-element" class="control-label">
              Name on Card
          </label>
          <input type="text" name="name" id="name" class="form-control">
  </div></div>
        <div class="col-md-6">
       
      <div class="form-group">
        <label for="card-element" class="block text-gray-700 text-sm font-bold mb-2">
            Credit Card Info
        </label>
        <!-- Stripe Elements Placeholder -->
        <div id="card-element" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></div>
        <div id="card-errors" class="text-red-400 text-bold mt-2 text-sm font-medium"></div>
    </div></div></div>
              
              {{-- <div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
              
                <label for="start_date">start date</label>
                 <input type="date" name="start_date" placehoder="Start Date" class="form-control" />
                 <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
                @if ($errors->has('start_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('start_date'), $errors->first('dateeror') }}</strong>
                                    </span>
                                    @endif
              </div> --}}
              {{-- <div id="card-errors" class="text-red-400 text-bold mt-2 text-sm font-medium form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
              
              
             
</div>


              {{-- <div class="form-group">
                <label for="inputProjectLeader">expiry date</label>
                <!-- <input type="text" placehoder="End Date" name="expiry_date" id="end_date" class="form-control"/> -->
                <input type="date" placehoder="End Date" name="expiry_date" class="form-control"/>
              </div> --}}
            
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div></div>
      <div class="row">
        <div class="col-12">
          
          <button type="submit" id="card-button"  class=" btn btn-success" disabled>
                                        Save
                                    </button>
                                    <a href="{{ url('admin/subscriptions') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </div>
      </form>   
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection


@section('scripts')

<script src="https://js.stripe.com/v3/"></script>

        <script>
          $(function() {
            var bt = document.getElementById('card-button');
$('body').on('blur','[name="coupon"]',function(){
    $coupon = $(this);
    if($coupon.val().length > 0) { // check if user typed anything
  // $coupon.val($coupon.val().toUpperCase()); // I make all my coupons uppercase
  
  $.ajax({
							 
    url:"{{ route('ajax-check') }}",

    type:"GET",
    data:{coupon: $coupon.val()},
    dataType: 'json',
    success: function (data) {
      $coupon.closest('.form-group')
      .removeClass('has-error') // just in case
      .addClass('has-success')
      .find('.help-block').text(data.message).show();
    },
    error:function(){ 
      $coupon.closest('.form-group')
      .removeClass('has-success') // just in case
      .addClass('has-error')
      .find('.help-block').text(data.responseJSON.message).show();
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
      $('#card-button').prop('disabled', false);
      $('#ziphelp').html('<p>'+data.message+'</p>');
      $('#address-city').val(data.city); 
			$('#address-state').val(data.state); 
			$('#address-country').val("US"); 
    },
    error:function(xhr, status, error){ 
      if(xhr.status==404) {
        bt.disabled = true;
        // alert('status:' + xhr.status + ', status text: ' + xhr.responseText);
        $('#card-button').prop('disabled', true);
      $('#ziphelp').html('<p>'+xhr.responseJSON.message+'</p>');
              
  
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

@stop