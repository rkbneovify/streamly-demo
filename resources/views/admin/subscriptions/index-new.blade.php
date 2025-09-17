<style>
    .input-container {
        position: relative;

    }

    .btn-clear {
        cursor: pointer;
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 30px;
        color: #888;
        display: none;
    }

    .btn-clear:hover {
        color: #000;
    }
</style>
@extends('master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Subscription</h1> {{ session()->get('info') }}
                    </div>
                    <div class="col-sm-6">
                        <div class="card-tools">

                            <a class="btn btn-xs btn-primary" href="{{ url('admin/subscriptions/create') }} ">
                                Add new subscription
                            </a>
                            <a class=" btn btn-xs btn-warning" href="{{ url('stripesync') }}">Stripe Expiry Date Sync</a>
                        </div>
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Subscription</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="card card-primary card-outline">

                <div class="card-body">
                    <div class="row mb-3">
                        {{-- <div class="col-md-3">
                            <select class="form-control" name="plan">
                                <option value="">Select Plan</option>
                                @foreach ($plankey as $plan)
                                    <option value="{{ $plan }}" {{ Request()->plan == $plan ? 'selected' : '' }}>
                                        {{ ucfirst($plan) }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-md-3">
                            <select class="form-control" name="status">
                                <option value="">Select Status</option>
                                @foreach ($statusTypes as $status)
                                    <option value="{{ $status }}"
                                        {{ Request()->status == $status ? 'selected' : '' }}>
                                        {{ ucfirst($status) }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-container ">
                                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                                    class="form-control" placeholder="Search by name, email">
                                <span class="btn-clear" id="btnClear">×</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <a class="dropdown-item"
                                        href="{{ route('admin.subscriptions.users.export', ['type' => 'xlsx'] + request()->query()) }}">Export
                                        to Excel</a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.subscriptions.users.export', ['type' => 'csv'] + request()->query()) }}">Export
                                        to CSV</a>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="user-data">

                        @include('admin.subscriptions.partials.table-new2')
                    </div>





                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#btnClear').hide();

            $(document).on('keyup', 'input[name="search"]', function() {
                let search = $(this).val();
                if (search.length > 0) {
                    $('#btnClear').show();
                }
                // alert($(this).val());
                fetchData();
            });
            $('select[name="plan"]').change(function() {
                let url = new URL(window.location.href);
                url.searchParams.set('plan', $(this).val());
                window.location.href = url.toString();
            });
            $('select[name="status"]').change(function() {
                let url = new URL(window.location.href);
                url.searchParams.set('status', $(this).val());
                window.location.href = url.toString();
            });
            $('#btnClear').click(function() {

                $('input[name="search"]').val('');
                fetchData();
                $('#btnClear').hide();

            });
            $('#btnReset').click(function() {

                window.location.href = window.location.href.split('?')[0];
            });
            // end
        });



        function fetchData(page = 1) {
            let url = window.location.href;
            let search = $('input[name="search"]').val();


            $.ajax({
                url: url + "?page=" + page + "&search=" + search,
                success: function(data) {
                    $('#user-data').html(data);
                }
            });
        }
    </script>
@endsection
