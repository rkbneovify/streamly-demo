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
    </style>
    <style>
        .radio-btn {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 20px;
            cursor: pointer;
            gap: 10px;
        }

        .invalid {
            border-color: red;
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

                                <form action="{{ url('admin/userupdate', [$user->id]) }}" method="POST"
                                    enctype="multipart/form-data" id ="useradd">
                                    @csrf
                                    @method('PUT')


                            </div>
                            <div class="form-group">
                                <label for="name" class="col-md-4 control-label">Name</label>


                                <input id="display_name" type="text"
                                    class="form-control @error('name')
                                    invalid
                                @enderror"
                                    name="name" value="{{ $user->name }}" autofocus>

                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

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

                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city"
                                    class="form-control @error('city')
                                    invalid
                                @enderror"
                                    value="{{ $user->city }}">
                                @error('city')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state"
                                    class="form-control @error('state')
                                    invalid
                                @enderror"
                                    value="{{ $user->state }}">
                                @error('state')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
                            <div class="form-group">
                                <label for="email" class="col-md-4 control-label">E-Mail</label>


                                <input id="email" type="text"
                                    class="form-control @error('email')
                                    invalid
                                @enderror"
                                    name="email" value="{{ $user->email }}" readonly>

                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-check">
                                <input name="password_field" class="form-check-input" type="checkbox"
                                    id="btnShowPassword">
                                <label class="form-check-label" for="btnShowPassword">
                                    <b> Show
                                        Password Fields</b>
                                </label>
                            </div>
                            <div id="divPassword" class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>


                                <input id="password" type="password"
                                    class="form-control @error('password')
                                    invalid
                                @enderror"
                                    name="password">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div id="divConfirmPassword" class="form-group">
                                <label for="password_confirmation" class="col-md-4 control-label">Confirm Password</label>


                                <input id="password_confirmation" type="password"
                                    class="form-control @error('password')
                                    invalid
                                @enderror"
                                    name="password_confirmation" autofocus>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                            <div class="form-group">
                                <label for="dob">Date of birth</label>

                                <input type="date" name="dob" placehoder="dob"
                                    class="form-control @error('dob')
                                    invalid
                                @enderror" />
                                <!-- <input type="text" name="start_date" placehoder="Start Date" class="form-control" id="start_date"/> -->
                                @error('dob')
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

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                    data-toggle="tooltip" title="Collapse">
                                    <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="roles" class="col-md-4 control-label">Roles</label>

                                <select id="role"
                                    class="form-control @error('roles')
                                    invalid
                                @enderror"
                                    name="roles[]" onchange="handleRoleChange()">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ in_array($role->id, $userRoles) ? 'selected' : null }}>
                                            {{ $role->display_name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('roles')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="" id="deviceSection" style="display: none;">
                                <div class="form-group">
                                    <label for="number_of_devices">Number of Devices</label>
                                    <div class="device-counter">
                                        <button type="button" onclick="decreaseDevices()">−</button>

                                        <input type="text" id="number_of_devices" name="number_of_devices"
                                            value="{{ $user->num_of_devices ?? $currentDeviceCount }}"
                                            class="@error('number_of_devices')
                                                invalid
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
                                        <div class="form-group {{ $errors->has('num_of_houses') ? ' has-error' : '' }}">
                                            <label for="number_of_devices">Number of Houses</label>
                                            <div class="input-group">
                                                <button class="btn btn-light" type="button"
                                                    onclick="decreaseHouses()">−</button>
                                                <input
                                                    class="form-control @error('num_of_houses')
                                                    invalid
                                                @enderror"
                                                    type="text" id="num_of_houses" name="num_of_houses"
                                                    value="{{ $user->num_of_houses }}">
                                                <button class="btn btn-light" type="button"
                                                    onclick="increaseHouses()">+</button>
                                            </div>
                                            @error('num_of_houses')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>


                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="number_of_devices">Device Per House</label>
                                            @if ($currentDeviceCount <= 0)
                                                <div class="input-group">
                                                    <button class="btn btn-light" type="button"
                                                        onclick="decreaseDevicePerUnit()">−</button>
                                                    <input
                                                        class="form-control @error('device_per_house')
                                                        invalid
                                                    @enderror"
                                                        min="1" type="number" id="device_per_house"
                                                        name="device_per_house" value="{{ $user->device_per_house }}">
                                                    <button class="btn btn-light" type="button"
                                                        onclick="increaseDevicePerUnit()">+</button>
                                                </div>
                                            @else
                                                <div class="input-group">
                                                    <button class="btn btn-light" type="button"
                                                        onclick="decreaseDevicePerUnit()">−</button>
                                                    <input
                                                        class="form-control @error('device_per_house')
                                                        invalid
                                                    @enderror"
                                                        min="1" type="number" id="device_per_house"
                                                        name="device_per_house" value="{{ $user->device_per_house }}">
                                                    <button class="btn btn-light" type="button"
                                                        onclick="increaseDevicePerUnit()">+</button>
                                                </div>
                                            @endif

                                            @error('device_per_house')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <p>Total Devices : <span id="totalDevices"></span> </p>

                            </div>
                            {{--  --}}
                            <div class="form-group">
                                <label for="zipcode" class="col-md-4 control-label">zipcode</label>

                                <div id="zipErrorMsg" style="color: red; font-size: 14px; display: none;"></div>

                                <input id="zipcode" type="number" list="zipcodeList" pattern="/^-?\d+\.?\d*$/"
                                    class="form-control" name="zipcode" value="{{ $user->zipcode }}">
                                <datalist id="zipcodeList"></datalist>
                                @error('zipcode')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>


                            <div class="form-group">
                                <label for="plan_duration">Plan Duration</label>
                                <!-- <p>DEBUG: Selected Plan = {{ $planDuration }}</p> -->
                                <select name="plan_duration"
                                    class="form-control @error('plan_duration')
                                    invalid
                                @enderror">


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




                                @error('plan_duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="form-group">
                                <label>Channel Availability

                                </label>

                                <div style="display: flex; gap: 10px; margin-top: 8px;">
                                    <!-- Show All Channels -->
                                    <label class="radio-btn">
                                        <input type="radio" name="channel_availability" value="1"
                                            class="channel-check"
                                            {{ isset($channelRule) && $channelRule->validate == 1 ? 'checked' : '' }}>
                                        <span> Show All Channels</span>
                                    </label>

                                    <!-- Show Local Channels -->
                                    <label class="radio-btn">
                                        <input type="radio" name="channel_availability" value="2"
                                            class="channel-check"
                                            {{ isset($channelRule) && $channelRule->validate == 2 ? 'checked' : '' }}>
                                        <span>Show Local Channels</span>
                                    </label>
                                </div>
                                @error('channel_availability')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>


                            <!-- new code  -->
                            <div class="form-group">
                                <label>Connected Device

                                </label>

                                <!-- Hidden input to ensure value is submitted -->
                                <input type="hidden" name="connected_device" id="connected_device_input"
                                    value="{{ $user->connected_device ?? '' }}">

                                <div style="display: flex; gap: 10px; margin-top: 8px;">
                                    <!-- Hide -->
                                    <label class="radio-btn">
                                        <input type="radio" value="0" class="channel-checkk"
                                            {{ isset($user) && $user->connected_device == 0 ? 'checked' : '' }}>
                                        &nbsp; Hide
                                    </label>

                                    <!-- Show -->
                                    <label class="radio-btn">
                                        <input type="radio" value="1" class="channel-checkk"
                                            {{ isset($user) && $user->connected_device == 1 ? 'checked' : '' }}>
                                        &nbsp; Show
                                    </label>
                                </div>
                            </div>



                            @if ($user->is_custom_user == 1)

                                @if (count($premiumChannels) > 0)
                                    <div class="form-group">
                                        <div class="">Allow Premium Channels</div>

                                        <select id="premium_channels" name="premium_channels[]" multiple form="useradd"
                                            class="form-control">
                                            @foreach ($premiumChannels as $channel)
                                                <option value="{{ $channel->id }}"
                                                    {{ $channel->premium != null ? ($channel->premium->status == 1 ? 'selected' : '') : '' }}>
                                                    {{ $channel->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                @if (count($phxChannels) > 0)
                                    <div class="form-group">
                                        <div class="">Allow PHX Local Channels</div>

                                        <select id="phx_channels" name="phx_channels[]" multiple form="useradd"
                                            class="form-control">
                                            @foreach ($phxChannels as $channel)
                                                <option value="{{ $channel->id }}"
                                                    {{ $channel->phxLocal != null ? ($channel->phxLocal->status == 1 ? 'selected' : '') : '' }}>
                                                    {{ $channel->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                            @endif




                            <div class="form-group">
                                <div
                                    class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                    <input type="checkbox" class="custom-control-input" name="toggleswitch"
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

                    <a class="btn
                        btn-link" href="{{ url('admin/users') }}">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        function formSubmit(event) {
            Swal.fire({
                title: 'Please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#divPassword').hide();
            $('#divConfirmPassword').hide();
            $('#btnShowPassword').click(function(e) {

                // e.preventDefault();
                $('#divPassword, #divConfirmPassword').toggle();
            })
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


        const zip = document.getElementById('zipcode');
        zip.addEventListener('input', () => {
            zip.value = zip.value.toString().slice(0, 5);
        });

        document.addEventListener('DOMContentLoaded', function() {

            const form = document.getElementById('useradd'); // Replace with your form ID

            const predefinedLocalZipcodes = [
                "85120", "85215", "85205", "75662", "08872", "08904", "18344", "85022",
                "18438", "18302", "18326"
            ];

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                $('#premium_channels option:selected').each(function() {
                    formData.append('premium_channels[]', $(this).val());
                });
                $('#phx_channels option:selected').each(function() {
                    formData.append('phx_channels[]', $(this).val());
                });
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

    {{-- multi select  --}}
    <script>
        $(document).ready(function() {



            $('#premium_channels').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonWidth: '100%',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true, // <-- makes Ram and ram match
                filterBehavior: 'text' // or 'both' if you want to search by value too
            });

            $('#phx_channels').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonWidth: '100%',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true, // <-- makes Ram and ram match
                filterBehavior: 'text' // or 'both' if you want to search by value too
            });
        });
    </script>

@stop
