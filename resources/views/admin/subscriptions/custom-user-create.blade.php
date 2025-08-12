@extends('master')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Create Subscription</h1> {{ session()->get('info') }}
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
                    <div class="card ">
                        <div class="card-header">
                            {{-- <h3 class="card-title">General</h3> --}}

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="panel-body">


                            <form id="subs-form" method="POST" enctype="multipart/form-data"
                                action="{{ route('admin.subscriptions.custom.store') }}">


                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                <div class="card-body">

                                    <div class="form-group{{ $errors->has('product') ? ' has-error' : '' }}">
                                        <label for="product">Plans coming from stripe dashboard</label>

                                        <select name="product" id="product" class="form-control">
                                            <option value="">Select Plan</option>
                                            @foreach ($plans as $id => $pack)
                                                <option
                                                    {{ old('product') == $pack->id . '|' . $pack->trial_period_days ? 'selected' : '' }}
                                                    value="{{ $pack->id . '|' . $pack->trial_period_days }}">
                                                    {{ $pack->nickname }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('product')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>




                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class='form-group'>
                                                <label class='control-label'>Name</label>
                                                <input name="name" class="form-control" value="{{ $user->name }}" />
                                                @error('name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class='form-group  required'>
                                                <label class='control-label'>Email</label>
                                                <input name="email" class="form-control" value="{{ $user->email }}"
                                                    readonly />
                                                @error('email')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class='form-group  required'>
                                                <label class='control-label'>Address</label>
                                                <input id="address-line1" name="address_line1" class="form-control"
                                                    placeholder="77 Winchester Lane"
                                                    value="{{ old('address_line1') ?? $user->address }}" />
                                                @error('address_line1')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class='form-group  required'>
                                                <label class='control-label'>Address (cont.)</label>
                                                <input id="address-line2" name="address_line2" class="form-control"
                                                    value="{{ old('address_line2') }}" />

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class='form-group  required'>
                                                <label class='control-label'>City</label>
                                                <input id="address-city" name="address_city" class="form-control" readonly
                                                    value="{{ old('address-city') ?? $user->city }}" />
                                                @error('address_city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class='form-group  required'>
                                                <label class='control-label'>State</label>
                                                <input id="address-state" name="address_state" class="form-control" readonly
                                                    value="{{ old('address_state') ?? $user->state }}" />
                                                @error('address_state')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class='form-group  required'>
                                        <label class='control-label'>Country</label>

                                        <input id="address-country" name="address_country" class="form-control"
                                            value="{{ old('address_country') ?? $user->country }}" />
                                        @error('address_country')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class='form-group cvc required'>
                                                <label class='control-label'>Coupon Code</label>
                                                <input autocomplete='off' class='form-control' placeholder='Coupon code'
                                                    name="coupon" type='text'>
                                                <small class="help-block" style="display:none;"></small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class='form-group zip required'>
                                                <label class='control-label'>Credit card zip</label>
                                                <input autocomplete='off' class='form-control' name="zip"
                                                    type='number' pattern="/^-?\d+\.?\d*$/"
                                                    onKeyPress="if(this.value.length==5) return false;" required>
                                                <small id="ziphelp"></small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="card-element" class="control-label">
                                                    Card Holder Name
                                                </label>
                                                <input type="text" name="card_holder_name" id="txtCardHolderName"
                                                    class="form-control">
                                                @error('card_holder_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">

                                            <div class="form-group">
                                                <label for="card-element"
                                                    class="block text-gray-700 text-sm font-bold mb-2">
                                                    Credit Card Info
                                                </label>
                                                <!-- Stripe Elements Placeholder -->
                                                <div id="card-element"
                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                </div>
                                                <div id="card-errors"
                                                    class="text-red-400 text-bold mt-2 text-sm font-medium"></div>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- /.card-body -->
                                </div>
                                <!-- /.card -->
                        </div>
                    </div>
                </div>
                <div class="row px-3 mb-5">
                    <div class="col-md-6">
                        <button type="submit" id="card-button" class=" btn btn-success" disabled>
                            Save
                        </button>
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.users.custom.list') }}" class="btn btn-secondary ms-3">Cancel</a>
                    </div>
                </div>

                </form>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection


@section('scripts')
    <style>
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
    </style>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {



            var bt = document.getElementById('card-button');
            $('body').on('blur', '[name="coupon"]', function() {
                $coupon = $(this);
                if ($coupon.val().length > 0) { // check if user typed anything
                    // $coupon.val($coupon.val().toUpperCase()); // I make all my coupons uppercase

                    $.ajax({

                        url: "{{ route('ajax-check') }}",

                        type: "GET",
                        data: {
                            coupon: $coupon.val()
                        },
                        dataType: 'json',
                        success: function(data) {
                            $coupon.closest('.form-group')
                                .removeClass('has-error') // just in case
                                .addClass('has-success')
                                .find('.help-block').text(data.message).show();
                        },
                        error: function() {
                            $coupon.closest('.form-group')
                                .removeClass('has-success') // just in case
                                .addClass('has-error')
                                .find('.help-block').text(data.responseJSON.message).show();
                            $coupon.val(
                                ''
                            ); // coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
                        }
                    });
                }
            });

            $('body').on('focusout', '[name="zip"]', function() {
                $zip = $(this);
                if ($zip.val().length > 0) { // check if user typed anything
                    $.ajax({

                        url: "{{ route('ajax-check') }}",

                        type: "GET",
                        data: {
                            zip: $zip.val()
                        },
                        dataType: 'json',
                        success: function(data) {
                            bt.disabled = false;
                            $('#card-button').prop('disabled', false);
                            $('#ziphelp').html('<p>' + data.message + '</p>');
                            $('#address-city').val(data.city);
                            $('#address-state').val(data.state);
                            $('#address-country').val("US");
                        },
                        error: function(xhr, status, error) {
                            if (xhr.status == 404) {
                                bt.disabled = true;
                                // alert('status:' + xhr.status + ', status text: ' + xhr.responseText);
                                $('#card-button').prop('disabled', true);
                                $('#ziphelp').html('<p>' + xhr.responseJSON.message + '</p>');


                            }
                            // coupon was invalid, clear input so it doesn't get posted if user doesn't input a new valid coupon
                        }
                    });
                }
            });

            // disable spaces for specific inputs
            $('body').on('keydown', '[name="username"],[name="email"],[name="coupon"]', function(e) {
                return e.which !== 32;
            });

        });
        // const stripe = Stripe('{{ env('STRIPE_KEY') }}');
        // console.log(stripe);
        const stripe = Stripe('{{ $stripekey }}');
        const elements = stripe.elements();
        const cardElement = elements.create('card', {
            hidePostalCode: true
        });

        cardElement.mount('#card-element');

        const cardHolderName = document.getElementById('txtCardHolderName');
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
            cardElement.update({
                value: {
                    postalCode: event.target.value
                }
            });
        });
        var form = document.getElementById('subs-form');

        form.addEventListener('submit', async (e) => {
            event.preventDefault();
            var options = {
                line1: document.getElementById('address-line1').value,
                line2: document.getElementById('address-line2').value,
                city: document.getElementById('address-city').value,
                state: document.getElementById('address-state').value,
                country: document.getElementById('address-country').value,
            };
            const {
                paymentMethod,
                error
            } = await stripe.createPaymentMethod({
                type: 'card',
                card: cardElement,
                billing_details: {
                    name: '{{ $user->name }}',
                    email: '{{ $user->email }}',
                    address: options
                },

            });

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
