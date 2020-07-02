@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Create Subscription</h1>
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
                <option value="{{ $pack->id }}">
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

              <div class="form-group">
                <label for="card-element" class="block text-gray-700 text-sm font-bold mb-2">
                    Name on Card
                </label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        <div class="form-group">
            <label for="card-element" class="block text-gray-700 text-sm font-bold mb-2">
                Credit Card Info
            </label>
            <!-- Stripe Elements Placeholder -->
            <div id="card-element" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></div>
            <div id="card-errors" class="text-red-400 text-bold mt-2 text-sm font-medium"></div>
        </div>
              
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
          
          <button type="submit" id="card-button" class="btn btn-success">
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
            const stripe = Stripe('{{ env("STRIPE_KEY") }}');
            console.log(stripe);

            const elements = stripe.elements();
            const cardElement = elements.create('card');

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

            var form = document.getElementById('signup-form');

            form.addEventListener('submit', async (e) => {
                event.preventDefault();

                const { paymentMethod, error } = await stripe.createPaymentMethod(
                    'card', cardElement, {
                        billing_details: { name: cardHolderName.value }
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