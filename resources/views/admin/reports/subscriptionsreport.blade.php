@extends('master')
@section('stylesheets')
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
@endsection
@section('content')
    <style>
        .divfilter {
            margin: 0px 8px 8px 8px;
            text-align: end;
        }

        #myBtn {
            background: transparent;
            border: none;
            color: #fff;
        }

        #myBtn2 {
            background: transparent;
            border: none;
            color: #fff;
        }

        #myBtn3 {
            background: transparent;
            border: none;
            color: #fff;
        }

        #myBtn4 {
            background: transparent;
            border: none;
            color: #fff;
        }

        button:focus {
            outline: 0px dotted !important;
            outline: 0px auto -webkit-focus-ring-color !important;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            margin-left: 250px;
            top: 0;
            width: 81%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            overflow: auto;
            height: 35vw;
        }

        /* The Close Button */
        .close2 {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close2:hover,
        .close2:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .close {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .close3 {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close3:hover,
        .close3:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .close4 {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close4:hover,
        .close4:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .heightBox {
            height: 30px;
            font-size: 14px;
        }

        .fontboxWeight {
            font-weight: 400 !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>All Subscriptions</h1>
                        {{ session()->get('info') }}
                    </div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->

        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="card-title">Subscriptions</h3>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="status">
                                <option value="">Select status</option>
                                <option value="active" {{ request()->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="canceled" {{ request()->status == 'canceled' ? 'selected' : '' }}>Canceled
                                </option>

                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="input-conatiner">
                                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                                    class="form-control" placeholder="Search by email">
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
                                        href="{{ route('admin.report.subscription.export', ['type' => 'xlsx'] + request()->query()) }}">Export
                                        to Excel</a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.report.subscription.export', ['type' => 'csv'] + request()->query()) }}">Export
                                        to CSV</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div id="user-data">

                    @include('admin.reports.subscription-report-table')
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    <script>
        $('#btnClear').hide();

        function fetchData(page = 1) {

            let search = $('input[name="search"]').val();
            // let role = $('select[name="role"]').val();

            $.ajax({
                url: location.href + "?page=" + page + "&search=" + search,
                success: function(data) {
                    $('#user-data').html(data);
                }
            });
        }
        $(document).ready(function() {

            $('select[name="status"]').on('change', function() {
                let url = new URL(window.location.href);
                if ($(this).val() === '') {
                    url.searchParams.delete('status');
                } else {
                    url.searchParams.set('status', $(this).val());
                }
                window.location.href = url.toString();
            })

            $(document).on('keyup', 'input[name="search"]', function() {
                let search = $('input[name="search"]').val();
                if (search.length > 0) {
                    $('#btnClear').show();
                }
                fetchData();
            });
            $('#btnClear').click(function() {

                $('input[name="search"]').val('');
                fetchData();
                $('#btnClear').hide();

            })



        })
    </script>
@endsection
