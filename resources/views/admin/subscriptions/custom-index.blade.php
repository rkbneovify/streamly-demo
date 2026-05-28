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
<style>
    .select-wrapper {
        width: 100%;
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        background-color: #fff;
        cursor: pointer;
        font-size: 14px;
    }

    .select-wrapper:hover {
        background-color: #f9f9f9;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .select-wrapper img {
        width: 20px;
        height: 20px;
    }

    .select-custom {
        width: 100%;
        padding: 4px 10px;
        border: none;
        /* border-radius: 4px; */
        font-size: 13.8px;
        font-weight: 600;
        color: #333;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        /* Remove default arrow for most browsers */
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: none;
    }

    .select-custom:focus {
        border-color: none;
        box-shadow: none;
    }

    .btn-add-user {
        text-decoration: none;
        color: #333;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
    }

    .input-daterange {
        width: 100%;
        padding: 4px 10px;
        border: none;
        /* border-radius: 4px; */
        font-size: 13.8px;
        font-weight: 600;
        color: #333;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        /* Remove default arrow for most browsers */
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: none;
    }

    .custom-input {
        width: 100%;
        padding: 10px 40px 10px 15px !important;
        border: none;
        /* border-radius: 4px; */
        font-size: 13.8px;
        font-weight: 600;
        color: #333;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        /* Remove default arrow for most browsers */
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background: none;
    }

    .input-custom {
        padding: 18px 10px !important;
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
                        <h1>Custom Subscription</h1> {{ session()->get('info') }}
                    </div>
                    <div class="col-sm-6">

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

                        <div class="col-md-2 mb-3">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/user-mode.png') }}" alt="status icon">
                                <select name="status" id="sel_status" class="select-custom">
                                    <option value="">Select Status</option>
                                    @foreach ($statusTypes as $status)
                                        <option value="{{ $status }}"
                                            {{ Request()->status == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="input-conatiner">
                                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                                    class="form-control input-custom" placeholder="Search by name, email">
                                <span class="btn-clear" id="btnClear">×</span>
                            </div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/export.png') }}" alt="status icon">
                                <a href="{{ route('admin.subscriptions.users.export', ['type' => 'xlsx'] + request()->query()) }}"
                                    class="btn-add-user">Export</a>
                            </div>
                        </div>

                        {{-- <div class="col-md-2 mb-3">
                            <a class="btn btn-primary w-100" href="{{ url('admin/subscriptions/create') }} ">
                                New Subscription
                            </a>
                        </div> --}}

                        <div class="col-md-2 mb-3">
                            <a class=" btn btn-warning" href="{{ url('sync-custom-subscription') }}">Subscription Sync</a>
                        </div>
                    </div>

                    <div id="user-data">

                        @include('admin.subscriptions.partials.custom-table')
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
