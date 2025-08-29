@extends('master')
@section('content')


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User view</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">View user</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            @if ($user->user_type == 'custom')
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Personal Details</h3>


                            </div>

                            <div class="card-body">

                                <div class="form-group">
                                    <label for="inputName">Name</label>
                                    <input type="text" id="inputName" class="form-control"
                                        value="{{ $user->name ?? 'N/A' }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail">Email</label>
                                    <input type="text" id="inputEmail" class="form-control"
                                        value="{{ $user->email ?? 'N/A' }}" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="inputEstimatedBudget">Date of Birth</label>
                                    <input type="text" id="inputEstimatedBudget" class="form-control"
                                        value="{{ $user->dob ?? 'N/A' }}" disabled>
                                </div>

                                {{-- <div class="form-group">
                                <label for="inputEstimatedDuration">Country</label>
                                <input type="text" id="inputEstimatedDuration" class="form-control"
                                    value="{{ $user->country ?? 'N/A' }}" disabled>
                            </div> --}}

                                <div class="form-group">
                                    <label for="inputZip">City</label>
                                    <input type="text" class="form-control" value="{{ $user->city ?? 'N/A' }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputZip">State</label>
                                    <input type="text" class="form-control" value="{{ $user->state ?? 'N/A' }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputZip">Zip code</label>
                                    <input type="text" class="form-control" value="{{ $user->zipcode ?? 'N/A' }}"
                                        disabled>
                                </div>
                                {{-- <div class="form-group">
                                <label for="inputProjectLeader">Address</label>
                                <input type="text" id="inputProjectLeader" class="form-control"
                                    value="{{ $user->address ?? 'N/A' }}" disabled>
                            </div> --}}
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-6">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Account details</h3>


                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Role</label>
                                    <input type="text" class="form-control"
                                        value="{{ $user->roles != null ? $user->roles->first()->display_name : 'N/A' }}"
                                        disabled>

                                </div>
                                {{-- <div class="form-group">
                                    <label for="inputStatus">Status</label>
                                    @if ($user->is_active === 1)
                                        <input type="text" id="inputEmail" class="form-control" value="Active " disabled>
                                    @elseif ($user->is_active === 0)
                                        <input type="text" id="inputEmail" class="form-control" value="Inactive "
                                            disabled>
                                    @else
                                        <input type="text" id="inputEmail" class="form-control" value="Unknown "
                                            disabled>
                                    @endif

                                </div> --}}
                                <div class="form-group">
                                    <label for="inputZip">Plan Duration </label>
                                    <input type="text" class="form-control" value="{{ $planDuration ?? 'N/A' }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputZip">Channel Availability </label>
                                    @if (isset($channelRule) && $channelRule->validate == 1)
                                        <input type="text" class="form-control" value="Show All Channels" disabled>
                                    @elseif(isset($channelRule) && $channelRule->validate == 2)
                                        <input type="text" class="form-control" value="Show Local Channels" disabled>
                                    @else
                                        <input type="text" class="form-control" value="N/A" disabled>
                                    @endif

                                </div>

                                <div class="form-group">
                                    <label for="inputZip">Connected Device</label>
                                    @if (isset($user) && $user->connected_device == 0)
                                        <input type="text" class="form-control" value="Hide" disabled>
                                    @elseif(isset($user) && $user->connected_device == 1)
                                        <input type="text" class="form-control" value="Hide" disabled>
                                    @else
                                        <input type="text" class="form-control" value="N/A" disabled>
                                    @endif

                                </div>

                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Personal Details</h3>


                            </div>

                            <div class="card-body">

                                <div class="form-group">
                                    <label for="inputName">Name</label>
                                    <input type="text" id="inputName" class="form-control"
                                        value="{{ $user->name ?? 'N/A' }}" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputEmail">Email</label>
                                    <input type="text" id="inputEmail" class="form-control"
                                        value="{{ $user->email ?? 'N/A' }}" disabled>
                                </div>



                                <div class="form-group">
                                    <label for="inputZip">City</label>
                                    <input type="text" class="form-control" value="{{ $user->city ?? 'N/A' }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputZip">State</label>
                                    <input type="text" class="form-control" value="{{ $user->state ?? 'N/A' }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputZip">Zip code</label>
                                    <input type="text" class="form-control" value="{{ $user->zipcode ?? 'N/A' }}"
                                        disabled>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <div class="col-md-6">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Account details</h3>


                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="inputZip">Plan </label>
                                    <input type="text" class="form-control" value="{{ $plan->nickname ?? 'N/A' }}"
                                        disabled>
                                </div>
                                <div class="form-group">
                                    <label for="inputZip">Address </label>
                                    <input type="text" class="form-control" value="{{ $user->address ?? 'N/A' }}"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label for="inputZip">Subscription</label>
                                    <input type="text" class="form-control"
                                        value="{{ $subscription->stripe_status ?? 'N/A' }}" disabled>
                                </div>


                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            @endif
            <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
