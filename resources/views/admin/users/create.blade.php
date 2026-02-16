@extends('master')
@section('stylesheets')
    <script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.2/dist/css/bootstrap-multiselect.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect@1.1.2/dist/js/bootstrap-multiselect.min.js"></script>

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

        <style>.device-counter {
            display: inline-flex;
            align-items: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            overflow: hidden;
            width: fit-content;
        }

        .device-counter input {
            width: 50px;
            text-align: center;
            border: none;
            outline: none;
            padding: 6px 10px;
        }

        .device-counter button {
            width: 32px;
            height: 100%;
            border: none;
            background: #f4f4f4;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .device-counter button:hover {
            background: #e0e0e0;
        }

        .device-counter button:focus {
            outline: none;
        }

        .invalid {
            border-color: red;
        }
    </style>


@stop
@section('content')

    @if (session()->has('existuser'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong style= "align-items: center;
    display: flex;
    justify-content: center;">User already exist with
                this email,&nbsp;<a href = "/admin/users/edit/{{ session()->get('existuser') }}">Click here to edit
                    user</a></strong>
        </div>
    @endif



    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User Add</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">User Add</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <form method="POST" enctype="multipart/form-data" action="{{ url('admin/insertuser') }}" id="useradd">
            {{ csrf_field() }}

            <section class="content">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">General</h3>


                            </div>
                            <div class="card-body">
                                <div class="panel-body">








                                </div>
                                <!-- Roles dropdown -->

                                <div class="form-group">
                                    <label for="roles">Roles</label>

                                    <select name="roles" id="roles"
                                        class="form-control @error('roles')
                                        is-invalid
                                    @enderror"
                                        onchange="handleRoleChange()">
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $id => $pack)
                                            <option value="{{ $id }}" {{ old('roles') == $id ? 'selected' : '' }}>
                                                {{ $pack }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                                <!-- Number of Devices section (initially hidden) -->
                                <div class="" id="deviceSection" style="display: none;">
                                    <div class="form-group{{ $errors->has('number_of_devices') ? ' has-error' : '' }}">
                                        <label for="number_of_devices">Number of Devices</label>
                                        <div class="device-counter">
                                            <button type="button" onclick="decreaseDevices()">−</button>
                                            <input type="text" id="number_of_devices" name="number_of_devices"
                                                value="{{ old('number_of_devices', 5) }}"
                                                class="@error('number_of_devices')
                                                    is-invalid
                                                @enderror">
                                            <button type="button" onclick="increaseDevices()">+</button>
                                        </div>
                                        @error('number_of_devices')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- houses calc --}}
                                <div class="" id="housesDiv" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="number_of_devices">Number of Houses</label>
                                                <div class="device-counter">
                                                    <button type="button" onclick="decreaseHouses()">−</button>
                                                    <input type="text" id="num_of_houses" name="num_of_houses"
                                                        value="{{ old('num_of_houses', 1) }}"
                                                        class="@error('num_of_houses')
                                                            is-invalid
                                                        @enderror">
                                                    <button type="button" onclick="increaseHouses()">+</button>
                                                </div>
                                                @error('num_of_houses')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>


                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="number_of_devices">Device Per House</label>
                                                <div class="device-counter">
                                                    <button type="button" onclick="decreaseDevicePerUnit()">−</button>
                                                    <input min="1" type="number" id="device_per_house"
                                                        name="device_per_house" readonly
                                                        class="@error('device_per_house')
                                                            is-invalid
                                                        @enderror"
                                                        value="{{ old('device_per_house', 5) }}">
                                                    <button type="button" onclick="increaseDevicePerUnit()">+</button>
                                                </div>
                                                @error('device_per_house')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <p>Total Devices : <span id="totalDevices"></span> </p>

                                </div>



                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" autofocus>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror


                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-4 control-label">E-Mail</label>
                                    <input id="email" type="text"
                                        class="form-control @error('email')
                                        is-invalid
                                    @enderror"
                                        name="email">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-md-4 control-label">Password</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password')
                                        is-invalid
                                    @enderror"
                                        name="password" autofocus>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation" class="col-md-4 control-label">Confirm
                                        Password</label>


                                    <input id="password_confirmation" type="password"
                                        class="form-control @error('password_confirmation')
                                        is-invalid
                                    @enderror"
                                        name="password_confirmation" autofocus>
                                    @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

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


                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="dob">Date of birth</label>

                                    <input type="date" name="dob" placehoder="dob" value="{{ old('dob') }}"
                                        class="form-control @error('dob')
                                      is-invalid
                                    @enderror" />
                                    @error('dob')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label for="zipcode" class="col-md-4 control-label">zipcode</label>

                                    <div id="zipErrorMsg" style="color: red; font-size: 14px; display: none;"></div>

                                    <!-- <input id="zipcode"  class="form-control" name="zipcode" autofocus type="number" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==5) return false;" > -->

                                    <input id="zipcode" name="zipcode" value="{{ old('zipcode') }}"
                                        class="form-control @error('zipcode')
                                        is-invalid
                                    @enderror"
                                        list="zipcodeList" type="number">
                                    <datalist id="zipcodeList"></datalist>
                                    @error('zipcode')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>





                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" id="city" name="city" value="{{ old('city') }}"
                                        class="form-control @error('city')
                                        is-invalid
                                    @enderror">
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                                <div class="form-group">
                                    <label for="state">State</label>
                                    <input type="text" id="state" name="state" value="{{ old('state') }}"
                                        class="form-control @error('state')
                                        is-invalid
                                    @enderror">
                                    @error('state')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                                <div class="form-group">
                                    <label for="plan_duration">Plan Duration</label>
                                    <select name="plan_duration"
                                        class="form-control @error('plan_duration')
                                        is-invalid
                                    @enderror">
                                        <option value="">Select Duration</option>
                                        <option {{ old('plan_duration') == '1 Month' ? 'selected' : '' }} value="1 Month">
                                            1
                                            Month</option>
                                        <option {{ old('plan_duration') == '3 Months' ? 'selected' : '' }}
                                            value="3 Months">3
                                            Months</option>
                                        <option {{ old('plan_duration') == '6 Months' ? 'selected' : '' }}
                                            value="6 Months">6
                                            Months</option>
                                        <option {{ old('plan_duration') == '12 Months' ? 'selected' : '' }}
                                            value="12 Months">
                                            12 Months
                                        </option>
                                        <option {{ old('plan_duration') == 'Never' ? 'selected' : '' }} value="Never">
                                            Never
                                        </option>
                                    </select>
                                    @error('plan_duration')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="form-group">
                                    <label>Channel Availability

                                    </label>

                                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                                        <!-- Show All Channels -->
                                        <label
                                            style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                            <input type="radio" name="channel_availability" value=1 checked
                                                class="channel-check" id="all_channel" style="margin-right: 8px;">
                                            Show All Channels
                                        </label>

                                        <!-- Show Local Channels -->
                                        <label
                                            style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                            <input type="radio" name="channel_availability" value=2
                                                class="channel-check" id="local_channel" style="margin-right: 8px;">
                                            Show Local Channels
                                        </label>
                                    </div>
                                </div>


                                <!-- Connected Device Section -->
                                <div class="form-group" id="connecteddeviceSection" style="display: none;">
                                    <label>Connected Device
                                        <span style="font-weight: normal; color: #999;">(Select only one)</span>
                                    </label>

                                    <div style="display: flex; gap: 10px; margin-top: 8px;">
                                        <!-- Hide -->
                                        <label
                                            style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                            <input type="checkbox" name="connected_device" value="0"
                                                class="connected-device" id="no_device" style="margin-right: 8px;">
                                            Hide
                                        </label>

                                        <!-- Show -->
                                        <label
                                            style="display: flex; align-items: center; padding: 8px 12px; border: 1px solid #ccc; border-radius: 20px; cursor: pointer;">
                                            <input type="checkbox" name="connected_device" value="1"
                                                class="connected-device" id="show_device" style="margin-right: 8px;"
                                                checked>
                                            Show
                                        </label>
                                    </div>
                                </div>

                                {{-- <div class="form-group">
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class=" custom-control-input"
                                            name="access_premium_channel" id="customSwitch3">
                                        <label class="custom-control-label" for="customSwitch3">Access Premium
                                            Channel</label>
                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <div class="">Allow Premium Channels</div>

                                    <select id="premium_channels" name="premium_channels[]" multiple form="useradd"
                                        class="form-control">
                                        @foreach ($premiumChannels as $channel)
                                            <option value="{{ $channel->id }}">
                                                {{ $channel->name }} </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4 col-md-offset-4">
                            <input class="btn btn-primary" type="submit" value="Save" onclick="formSubmit(event)">

                            <a class="btn btn-link" href="{{ url('admin/users') }}">
                                Cancel
                            </a>
                        </div>
                    </div>



            </section>

        </form>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')

    <script>
        // function loading() {
        //     Swal.fire({
        //         title: 'Please wait...',
        //         allowOutsideClick: false,
        //         allowEscapeKey: false,
        //         didOpen: () => {
        //             Swal.showLoading();
        //         }
        //     });
        // }

        // function formSubmit(event) {
        //     event.preventDefault();
        //     loading();
        //     event.target.closest('form').submit();
        // }
    </script>


    <script type="text/javascript">
        function handleRoleChange() {
            const selectedRole = document.getElementById('roles').value;
            const deviceSection = document.getElementById('deviceSection');
            const housesDiv = document.getElementById('housesDiv');

            if (selectedRole === '5') { // role ID for 'tieruser'
                deviceSection.style.display = 'block';
            } else {
                deviceSection.style.display = 'none';
                document.getElementById('number_of_devices').value = 5;
            }

            if (selectedRole === '7') { // role ID for 'hoa'
                housesDiv.style.display = 'block';
                totalDeviceCalc();
            } else {
                housesDiv.style.display = 'none';
                document.getElementById('num_of_houses').value = 1;
            }
        }

        function increaseDevices() {
            let input = document.getElementById('number_of_devices');
            input.value = parseInt(input.value) + 1;
        }

        function decreaseDevices() {
            let input = document.getElementById('number_of_devices');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }

        function increaseHouses() {
            let input = document.getElementById('num_of_houses');
            input.value = parseInt(input.value) + 1;
            totalDeviceCalc();
        }

        function decreaseHouses() {
            let input = document.getElementById('num_of_houses');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
            totalDeviceCalc();
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
        });



        document.querySelectorAll('.channel-check').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    document.querySelectorAll('.channel-check').forEach(function(other) {
                        show_device
                        if (other !== checkbox) {
                            other.checked = false;
                        }
                    });
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const connecteddeviceSection = document.getElementById('connecteddeviceSection');
            const roleSelect = document.getElementById('roles');
            const checkboxes = document.querySelectorAll('.connected-device');
            const hideCheckbox = document.getElementById('show_device');

            function handleRoleChange() {
                const selectedRole = roleSelect.value;

                if (selectedRole === '5') {
                    connecteddeviceSection.style.display = 'block';
                } else {
                    connecteddeviceSection.style.display = 'none';

                    // Reset checkbox values
                    checkboxes.forEach(cb => cb.checked = false);
                    hideCheckbox.checked = true; // default to Hide
                }
            }

            // Ensure only one checkbox is selected at a time
            checkboxes.forEach((box) => {
                box.addEventListener('change', function() {
                    if (this.checked) {
                        checkboxes.forEach((cb) => {
                            if (cb !== this) cb.checked = false;
                        });
                    }
                });
            });

            handleRoleChange();

            // Attach to change event
            roleSelect.addEventListener('change', handleRoleChange);
        });
    </script>

    <script>
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
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('useradd');

            const predefinedLocalZipcodes = @json($predefinedLocalZipcodes);
            console.log("Predefined Local ZIPs:", predefinedLocalZipcodes);
            // const predefinedLocalZipcodes = [
            //     "85120", "85215", "85205", "75662", "08872", "08904",
            //     "18344", "85022", "18438", "18302", "18326"
            // ];


            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const channelAvailability = formData.get('channel_availability');
                const zipcode = formData.get('zipcode');

                const url = form.action;
                const useAjax = (channelAvailability === "2" && !predefinedLocalZipcodes.includes(zipcode));

                if (useAjax) {
                    console.log("Running AJAX for local channel with new ZIP");

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
                            const isJson = contentType.includes("application/json");
                            const resData = isJson ? await response.json() : null;

                            if (response.ok) {
                                alert("User created successfully!");
                                form.reset();
                                return;
                            }

                            if (response.status === 422 && resData) {
                                // 1️⃣ Check email validation first
                                if (resData.errors && resData.errors.email && resData.errors.email
                                    .length > 0) {
                                    alert(resData.errors.email[0]);
                                    return;
                                }

                                // 2️⃣ If ZIP not in local → confirm
                                if (resData.status === 'zipcode_not_in_local') {
                                    if (confirm(resData.message)) {
                                        const forceInput = document.createElement('input');
                                        forceInput.type = 'hidden';
                                        forceInput.name = 'force_add';
                                        forceInput.value = '1';
                                        form.appendChild(forceInput);
                                        form.submit();
                                    }
                                    return;
                                }

                                alert(resData.message ||
                                    "Form submission error. Check required fields.");
                                return;
                            }

                            console.error("Unexpected response from server:", resData ||
                                await response.text());
                            alert("Something went wrong. Please try again.");
                        })
                        .catch(error => {
                            console.error("Network/JS error:", error);
                            alert("Something went wrong. Check the console.");
                        });

                } else {
                    console.log("Submitting normally (non-local or known ZIP)");
                    form.submit();
                }
            });
        });



        document.getElementById('all_channel').addEventListener('change', function() {
            const zipInput = document.querySelector('[name="zipcode"]');
            if (!zipInput) return;

            const userZip = zipInput.value.trim();
            // if (this.checked && localZipcodes.includes(userZip)) {
            //     document.getElementById('local_channel').checked = true;
            // }
        });



        // automatically select local channels

        let localZipcodes = [
            "85120", "85215", "85205", "75662", "08872", "08904",
            "18344", "85022", "18438", "18302", "18326"
        ]; // example

        document.getElementById("zipcode").addEventListener("input", function() {

            let zip = this.value.trim();

            // if (localZipcodes.includes(zip)) {
            //     // Auto-select Local Channel
            //     document.getElementById("local_channel").checked = true;
            // } else {
            //     // If not a local zip, uncheck Local (optional)
            //     document.getElementById("local_channel").checked = false;
            // }
        });
    </script>
    <script>
        const zip = document.getElementById('zipcode');
        zip.addEventListener('input', () => {
            zip.value = zip.value.toString().slice(0, 5);
        });
    </script>

    {{-- multi select  --}}
    <script>
        $(document).ready(function() {



            $('#premium_channels').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonWidth: '100%'
            });
        });
    </script>




@stop
