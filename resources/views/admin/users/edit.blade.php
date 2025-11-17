@extends('master')
@section('stylesheets')
    <script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
            background: #004E92 !important;
            font-weight: bold !important;
            color: #ffffff !important;
        }
    </style>
@stop
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Edit</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">User Edit</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">General</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
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
                                <form action="{{ url('admin/userupdate', [$user->id]) }}" method="POST"
                                    enctype="multipart/form-data" id ="useradd">
                                    @csrf
                                    @method('PUT')


                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name" class="col-md-4 control-label">Name</label>


                                <input id="display_name" type="text" class="form-control" name="name"
                                    value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif

                            </div>
                            <!-- <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }}">
                                                                                                                                                                                                                                                                                                                              <label for="contact" class="col-md-4 control-label">contact</label>


                                                                                                                                                                                                                                                                                                                              <input id="contact" type="text" class="form-control" name="contact" value="{{ $user->contact }}"
                                                                                                                                                                                                                                                                                                                                autofocus>

                                                                                                                                                                                                                                                                                                                              @if ($errors->has('contact'))
    <span class="help-block">
                                                                                                                                                                                                                                                                                                                                <strong>{{ $errors->first('contact') }}</strong>
                                                                                                                                                                                                                                                                                                                              </span>
    @endif

                                                                                                                                                                                                                                                                                                                            </div> -->
                            <!-- <div class="form-group{{ $errors->has('gender') ? ' has-error' : '' }}">
                                                                                                                                                                                                                                                                                                                              <label for="gender" class="col-md-4 control-label">gender</label>


                                                                                                                                                                                                                                                                                                                              <input id="gender" type="text" class="form-control" name="gender" value="{{ $user->gender }}"
                                                                                                                                                                                                                                                                                                                                autofocus>

                                                                                                                                                                                                                                                                                                                              @if ($errors->has('gender'))
    <span class="help-block">
                                                                                                                                                                                                                                                                                                                                <strong>{{ $errors->first('gender') }}</strong>
                                                                                                                                                                                                                                                                                                                              </span>
    @endif

                                                                                                                                                                                                                                                                                                                            </div> -->

                            <!-- <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                                                                                                                                                                                                                                                                                                                              <label for="country" class="col-md-4 control-label">country</label>


                                                                                                                                                                                                                                                                                                                              <input id="country" type="text" class="form-control" name="country" value="{{ $user->country }}"
                                                                                                                                                                                                                                                                                                                                autofocus>

                                                                                                                                                                                                                                                                                                                              @if ($errors->has('country'))
    <span class="help-block">
                                                                                                                                                                                                                                                                                                                                <strong>{{ $errors->first('country') }}</strong>
                                                                                                                                                                                                                                                                                                                              </span>
    @endif

                                                                                                                                                                                                                                                                                                                            </div> -->

                            <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" class="form-control"
                                    value="{{ $user->city }}">
                                @if ($errors->has('city'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('city') }}</strong>
                                    </span>
                                @endif
                            </div>



                            <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" class="form-control"
                                    value="{{ $user->state }}">
                                @if ($errors->has('state'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('state') }}</strong>
                                    </span>
                                @endif
                            </div>



                            <!-- <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                                                                                                                                                                                                                                                                                                                              <label for="address" class="col-md-6 control-label">address</label>


                                                                                                                                                                                                                                                                                                                              <input id="address" type="text" class="form-control" name="address" value="{{ $user->address }}"
                                                                                                                                                                                                                                                                                                                                autofocus>

                                                                                                                                                                                                                                                                                                                              @if ($errors->has('address'))
    <span class="help-block">
                                                                                                                                                                                                                                                                                                                                <strong>{{ $errors->first('address') }}</strong>
                                                                                                                                                                                                                                                                                                                              </span>
    @endif

                                                                                                                                                                                                                                                                                                                            </div> -->
                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail</label>


                                <input id="email" type="text" class="form-control" name="email"
                                    value="{{ $user->email }}" required readonly>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif

                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>


                                <input id="password" type="password" class="form-control" name="password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif

                            </div>

                            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>


                                <input id="password_confirmation" type="password" class="form-control"
                                    name="password_confirmation" autofocus>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif

                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Info</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                                <label for="dob">Date of birth</label>

                                <input type="date" name="dob" placehoder="dob" class="form-control" />
                                <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
                                @if ($errors->has('dob'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('roles') ? ' has-error' : '' }}">
                                <label for="roles" class="col-md-4 control-label">Roles</label>

                                <select id="role" class="form-control" name="roles[]"
                                    onchange="handleRoleChange()">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ in_array($role->id, $userRoles) ? 'selected' : null }}>
                                            {{ $role->display_name }}
                                        </option>
                                    @endforeach
                                </select>

                                @if ($errors->has('roles'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('roles') }}</strong>
                                    </span>
                                @endif
                            </div>

                            <div class="" id="deviceSection" style="display: none;">
                                <div class="form-group{{ $errors->has('number_of_devices') ? ' has-error' : '' }}">
                                    <label for="number_of_devices">Number of Devices</label>
                                    <div class="device-counter">
                                        <button type="button" onclick="decreaseDevices()">−</button>

                                        <input type="text" id="number_of_devices" name="number_of_devices"
                                            value="{{ $user->num_of_devices }}">

                                        <button type="button" onclick="increaseDevices()">+</button>
                                    </div>
                                    @if ($errors->has('number_of_devices'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('number_of_devices') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>



                            {{-- houses calc --}}
                            <div class="" id="housesDiv" style="display: none;">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group {{ $errors->has('num_of_houses') ? ' has-error' : '' }}">
                                            <label for="number_of_devices">Number of Houses</label>
                                            <div class="input-group">
                                                <button class="btn btn-light" type="button"
                                                    onclick="decreaseHouses()">−</button>
                                                <input class="form-control" type="text" id="num_of_houses"
                                                    name="num_of_houses" value="{{ $user->num_of_houses }}">
                                                <button class="btn btn-light" type="button"
                                                    onclick="increaseHouses()">+</button>
                                            </div>
                                            @if ($errors->has('num_of_houses'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('num_of_houses') }}</strong>
                                                </span>
                                            @endif
                                        </div>


                                    </div>
                                    <div class="col">
                                        <div
                                            class="form-group {{ $errors->has('device_per_house') ? ' has-error' : '' }}">
                                            <label for="number_of_devices">Device Per House</label>
                                            @if ($currentDeviceCount <= 0)
                                                <div class="input-group">
                                                    <button class="btn btn-light" type="button"
                                                        onclick="decreaseDevicePerUnit()">−</button>
                                                    <input class="form-control" min="1" type="number"
                                                        id="device_per_house" name="device_per_house"
                                                        value="{{ $user->device_per_house }}">
                                                    <button class="btn btn-light" type="button"
                                                        onclick="increaseDevicePerUnit()">+</button>
                                                </div>
                                            @else
                                                <div class="input-group">
                                                    {{-- <button class="btn btn-light" type="button"
                                                    onclick="decreaseDevicePerUnit()">−</button> --}}
                                                    <input class="form-control" min="1" type="number"
                                                        id="device_per_house" name="device_per_house" readonly
                                                        value="{{ $user->device_per_house }}">
                                                    {{-- <button class="btn btn-light" type="button"
                                                    onclick="increaseDevicePerUnit()">+</button> --}}
                                                </div>
                                            @endif

                                            @if ($errors->has('device_per_house'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('device_per_house') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <p>Total Devices : <span id="totalDevices"></span> </p>

                            </div>
                            {{--  --}}
                            <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                                <label for="zipcode" class="col-md-4 control-label">zipcode</label>

                                <div id="zipErrorMsg" style="color: red; font-size: 14px; display: none;"></div>

                                <input id="zipcode" type="number" list="zipcodeList" pattern="/^-?\d+\.?\d*$/"
                                    class="form-control" name="zipcode" value="{{ $user->zipcode }}">
                                <datalist id="zipcodeList"></datalist>
                                @if ($errors->has('zipcode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('zipcode') }}</strong>
                                    </span>
                                @endif

                            </div>


                            <div class="form-group{{ $errors->has('plan_duration') ? ' has-error' : '' }}">
                                <label for="plan_duration">Plan Duration</label>
                                <!-- <p>DEBUG: Selected Plan = {{ $planDuration }}</p> -->
                                <select name="plan_duration" class="form-control">


                                    <option value="">Select Duration</option>
                                    <option value="1 Month" {{ $planDuration == '1 Month' ? 'selected' : '' }}>1 Month
                                    </option>
                                    <option value="3 Months" {{ $planDuration == '3 Months' ? 'selected' : '' }}>3 Months
                                    </option>
                                    <option value="6 Months" {{ $planDuration == '6 Months' ? 'selected' : '' }}>6 Months
                                    </option>
                                    <option value="12 Months" {{ $planDuration == '12 Months' ? 'selected' : '' }}>12
                                        Months</option>
                                    <option value="Never" {{ $planDuration == 'Never' ? 'selected' : '' }}>Never</option>
                                </select>



                                @if ($errors->has('plan_duration'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('plan_duration') }}</strong>
                                    </span>
                                @endif
                            </div>


                            <div class="form-group">
                                <label>Channel Availability
                                    <span style="font-weight: normal; color: #999;">(Select only one)</span>
                                </label>

                                <div style="display: flex; gap: 10px; margin-top: 8px;">
                                    <!-- Show All Channels -->
                                    <label
                                        style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                        <input type="checkbox" name="channel_availability" value=1 class="channel-check"
                                            {{ isset($channelRule) && $channelRule->validate == 1 ? 'checked' : '' }}>
                                        Show All Channels
                                    </label>

                                    <!-- Show Local Channels -->
                                    <label
                                        style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                        <input type="checkbox" name="channel_availability" value=2 class="channel-check"
                                            {{ isset($channelRule) && $channelRule->validate == 2 ? 'checked' : '' }}>
                                        Show Local Channels
                                    </label>
                                </div>
                            </div>


                            <!-- new code  -->
                            <div class="form-group">
                                <label>Connected Device
                                    <span style="font-weight: normal; color: #999;">(Select only one)</span>
                                </label>

                                <!-- Hidden input to ensure value is submitted -->
                                <input type="hidden" name="connected_device" id="connected_device_input"
                                    value="{{ $user->connected_device ?? '' }}">

                                <div style="display: flex; gap: 10px; margin-top: 8px;">
                                    <!-- Hide -->
                                    <label
                                        style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                        <input type="checkbox" value="0" class="channel-checkk"
                                            {{ isset($user) && $user->connected_device == 0 ? 'checked' : '' }}>
                                        &nbsp; Hide
                                    </label>

                                    <!-- Show -->
                                    <label
                                        style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                        <input type="checkbox" value="1" class="channel-checkk"
                                            {{ isset($user) && $user->connected_device == 1 ? 'checked' : '' }}>
                                        &nbsp; Show
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class=" custom-control-input" name="toggleswitch"
                                        id="customSwitch3">
                                    <label class="custom-control-label" for="customSwitch3">Block user</label>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-8 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>

                    <a class="btn btn-link" href="{{ url('admin/users') }}">
                        Cancel
                    </a>
                </div>
            </div>
            </form>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#zipcode").autocomplete({
                source: function(request, response) {
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
                        success: function(data) {
                            response(data);
                            //  console.log(data);
                        }
                    });
                },
                select: function(event, ui) {
                    // Set selection
                    $('#zipcode').val(ui.item.label); // display the selected text
                    //  $('#employeeid').val(ui.item.value); // save selected id to input
                    return false;
                },
                open: function() {
                    $("ul.ui-menu").width($(this).innerWidth());

                }
            });
        });


        document.querySelectorAll('.channel-check').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    document.querySelectorAll('.channel-check').forEach(function(other) {
                        if (other !== checkbox) {
                            other.checked = false;
                        }
                    });
                }
            });
        });

        let deviceChanged = false;

        function handleRoleChange() {
            const roleSelect = document.getElementById('role');
            const selectedRoles = Array.from(roleSelect.selectedOptions).map(opt => opt.value);
            const deviceSection = document.getElementById('deviceSection');
            const deviceInput = document.getElementById('number_of_devices');
            const housesDiv = document.getElementById('housesDiv');
            if (selectedRoles.includes('5')) {
                deviceSection.style.display = 'block';

                // ✅ Always set name so it submits
                deviceInput.setAttribute('name', 'number_of_devices');
            } else {
                deviceSection.style.display = 'none';
                deviceInput.removeAttribute('name');
                deviceInput.value = 1;
                deviceChanged = false;
            }

            if (selectedRoles.includes('7')) {

                housesDiv.style.display = 'block';


            } else {
                housesDiv.style.display = 'none';

            }

        }

        function increaseDevices() {
            const input = document.getElementById('number_of_devices');
            input.value = parseInt(input.value || 1) + 1;
            deviceChanged = true;
            input.setAttribute('name', 'number_of_devices');
        }

        function decreaseDevices() {
            const input = document.getElementById('number_of_devices');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                deviceChanged = true;
                input.setAttribute('name', 'number_of_devices');
            }
        }


        function increaseHouses() {
            const input = document.getElementById('num_of_houses');
            input.value = parseInt(input.value || 1) + 1;
            totalDeviceCalc();
        }

        function decreaseHouses() {
            const input = document.getElementById('num_of_houses');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                totalDeviceCalc();
            }
        }

        function increaseDevicePerUnit() {
            let input = document.getElementById('device_per_house');
            input.value = parseInt(input.value) + 1;
            totalDeviceCalc();

        }

        function decreaseDevicePerUnit() {
            let input = document.getElementById('device_per_house');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
            totalDeviceCalc();
        }

        function totalDeviceCalc() {
            let devicePerHouse = parseInt(document.getElementById('device_per_house').value);
            let numOfHouses = parseInt(document.getElementById('num_of_houses').value);
            if (numOfHouses > 0) {
                let totalDevices = devicePerHouse * numOfHouses;
                document.getElementById('totalDevices').innerText = totalDevices;
            } else {
                document.getElementById('totalDevices').innerText = 0;
            }


        }



        let numOfHouses = document.getElementById('num_of_houses');
        numOfHouses.addEventListener('input', () => {
            totalDeviceCalc();
        });
        document.addEventListener('DOMContentLoaded', function() {
            handleRoleChange();
            totalDeviceCalc();
        });


        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.channel-checkk');
            const hiddenInput = document.getElementById('connected_device_input');

            checkboxes.forEach((box) => {
                box.addEventListener('change', function() {
                    if (this.checked) {
                        checkboxes.forEach((cb) => {
                            if (cb !== this) cb.checked = false;
                        });
                        hiddenInput.value = this.value;
                    } else {
                        if (![...checkboxes].some(cb => cb.checked)) {
                            hiddenInput.value = '';
                        }
                    }
                });
            });
        });


        let zipData = {}; // Store city/state for each zip
        let lastInvalidZip = ''; // To avoid repeating alert

        $(document).ready(function() {
            $('#zipcode').on('input', function() {
                const val = $(this).val().trim();

                if (val.length >= 2) {
                    $.ajax({
                        url: "{{ url('admin/users/fetchzipcode') }}",
                        method: "GET",
                        data: {
                            search: val
                        },
                        success: function(data) {
                            const datalist = $('#zipcodeList');
                            datalist.empty();
                            zipData = {};

                            data.forEach(item => {
                                zipData[item.value] = {
                                    city: item.city,
                                    state: item.state
                                };
                                datalist.append(`<option value="${item.value}">`);
                            });

                            // When ZIP is 5 digits, check validity
                            if (val.length === 5) {
                                if (zipData.hasOwnProperty(val)) {
                                    fillCityState(val);
                                    lastInvalidZip = ''; // reset invalid zip tracker
                                } else {
                                    showInvalidZip(val);
                                }
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching zip:', xhr.responseText);
                        }
                    });
                } else {
                    clearCityState();
                }
            });
        });

        // Fill city/state and enable
        function fillCityState(zip) {
            const location = zipData[zip];
            $('#cityStateDisplay').text(`${location.city}, ${location.state}`);
            $('#city').val(location.city).prop('disabled', false);
            $('#state').val(location.state).prop('disabled', false);
        }

        function showInvalidZip(zip) {
            clearCityState();

            // Disable inputs
            $('#city').prop('disabled', true);
            $('#state').prop('disabled', true);

            // Show error message above city field
            $('#zipErrorMsg').text('Enter valid ZIP code').show();

            if (lastInvalidZip !== zip) {
                alert('This ZIP code does not exist in our records.');
                lastInvalidZip = zip; // track last invalid zip to avoid duplicate alerts
            }
        }

        function clearCityState() {
            $('#cityStateDisplay').text('');
            $('#city').val('').prop('disabled', false);
            $('#state').val('').prop('disabled', false);

            // Hide error message
            $('#zipErrorMsg').hide();
        }




        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('useradd'); // Replace with your form ID

            const predefinedLocalZipcodes = [
                "10005"
                // "85120", "85215", "85205", "75662", "08872", "08904", "18344", "85022",
                // "18438", "18302", "18326"
            ];

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const channelAvailability = formData.get('channel_availability');
                const zipcode = formData.get('zipcode');

                if (channelAvailability === "2" && !predefinedLocalZipcodes.includes(zipcode)) {
                    console.log("Running AJAX for local channel with new ZIP");

                    const url = form.action;

                    fetch(url, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(async response => {
                            const contentType = response.headers.get("content-type") || "";

                            if (response.ok) {
                                alert("User created successfully!");
                                console.log("Backend Response:", await response.json());
                                form.reset();
                                return;
                            }

                            if (response.status === 422 && contentType.includes(
                                    "application/json")) {
                                const resData = await response.json();
                                console.warn(" 422 Validation Response:", resData);

                                if (resData.status === 'zipcode_not_in_local') {
                                    console.log(" Showing confirm dialog:", resData.message);

                                    if (confirm(resData.message)) {
                                        console.log(
                                            " User confirmed ZIP addition. Resubmitting with force_add..."
                                        );

                                        const forceInput = document.createElement('input');
                                        forceInput.type = 'hidden';
                                        forceInput.name = 'force_add';
                                        forceInput.value = '1';
                                        form.appendChild(forceInput);

                                        form.submit();
                                    } else {
                                        console.log(" User canceled ZIP addition.");
                                    }
                                } else {
                                    console.warn("⚠️ Other 422 error:", resData.errors);
                                    alert("Form submission error. Check required fields.");
                                }

                                return;
                            }

                            const rawText = await response.text();
                            console.error("Unexpected response from server:", rawText);
                            alert("Unexpected error occurred. See console for details.");
                        })
                        .catch(error => {
                            console.error(" Network/JS error:", error);
                            alert("Something went wrong. Check the console.");
                        });

                } else {
                    console.log("Submitting normally (non-local or known ZIP)");
                    form.submit();
                }
            });
        });
    </script>

@stop
