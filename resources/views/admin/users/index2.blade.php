@extends('master')
@section('stylesheets')
    <link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <style>
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 25px;
        }

        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .custom-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            /* Grey when inactive */
            transition: 0.4s;
            border-radius: 25px;
        }

        .custom-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 3.5px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.custom-slider {
            background-color: #28a745;
            /* Green when active */
        }

        input:checked+.custom-slider:before {
            transform: translateX(24px);
        }
    </style>
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

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Users</h1> {{ session()->get('info') }}
                        <div id="message"></div>
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
                    {{-- <h3 class="card-title">Users</h3> --}}



                    <div class="row justify-content-between mt-3 mb-0 mb-3">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <select name="bulk_action" id="sel_bulk_action" class="form-control">
                                        <option value="">Bulk Action</option>
                                        <option value="activate">Activate</option>
                                        <option value="inactivate">Inactivate</option>
                                        <option value="delete">Delete</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">

                                        <select name="roles" id="sel_roles" class="form-control">
                                            <option value="">Select Role</option>
                                            @foreach ($roles as $id => $pack)
                                                <option value="{{ $pack }}"
                                                    {{ request('role') == $pack ? 'selected' : '' }}>
                                                    {{ ucfirst($pack) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select name="mode" id="sel_mode" class="form-control">
                                        <option value="">Select Mode</option>
                                        <option value="Live" {{ request('mode') == 'Live' ? 'selected' : '' }}>Live
                                        </option>
                                        <option value="Testing" {{ request('mode') == 'Testing' ? 'selected' : '' }}>Testing
                                        </option>

                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="status" id="sel_status" class="form-control">
                                        <option value="">Select Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>

                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="daterange"
                                        value="{{ formatDaterange(request('daterange')) }}">
                                </div>

                                <div class="col-md-4">
                                    <div class="input-conatiner">
                                        <input type="text" name="search" id="search" value="{{ Request()->search }}"
                                            class="form-control" placeholder="Search by name, email">
                                        <span class="btn-clear" id="btnClear">×</span>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="col-md-3 text-center text-lg-right">
                            <a class="btn btn-primary btn-sm mb-2 w-50" href="{{ url('admin/users/create') }} ">
                                Add New User
                            </a>

                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle w-50" type="button"
                                    id="exportDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Export
                                </button>
                                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                                    <a class="dropdown-item export-link"
                                        href="{{ route('admin.users.export', ['type' => 'xlsx'] + request()->query()) }}">Export
                                        to Excel</a>
                                    <a class="dropdown-item export-link"
                                        href="{{ route('admin.users.export', ['type' => 'csv'] + request()->query()) }}">Export
                                        to CSV</a>

                                </div>
                            </div>



                        </div>


                    </div>



                    <form id="tbl-form" method="POST" action="{{ route('admin.users.bulk.action') }}">
                        @csrf

                        <input hidden type="text" name="action">
                        <div id="user-data">

                            @include('admin.users.partials.new-table')
                        </div>

                    </form>
                    {{-- table end --}}
                    <div class="row justify-content-end mt-3">
                        {{ $users->withQueryString()->links() }}
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
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'DD/MM/YYYY'
                }
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('DD/MM/YYYY') +
                    ' to ' + end.format('DD/MM/YYYY'));
            });

            $('input[name="daterange"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate
                    .format('DD-MM-YYYY'));

                //console.log("apply clicked");
                const dateRange = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate
                    .format('YYYY-MM-DD');
                let url = new URL(window.location.href);
                let params = url.searchParams;
                let page = parseInt(params.get("page")) || 1;


                if (page > 1) {
                    // reset URL (remove params except page)
                    url.search = "";
                    url.searchParams.delete("page");
                    url.searchParams.set("daterange", dateRange);
                } else {
                    // just add role param
                    url.searchParams.set("daterange", dateRange);
                }

                window.location.href = url.toString();


            });

            $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {
                const today = new Date();
                const day = String(today.getDate()).padStart(2, '0');
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const year = today.getFullYear();
                $(this).val(day + '/' + month + '/' + year + ' - ' + day + '/' + month + '/' + year);
                let url = new URL(window.location.href);
                let params = url.searchParams;
                let page = parseInt(params.get("page")) || 1;
                if (page > 1) {
                    // reset URL (remove params except page)
                    url.search = "";
                    url.searchParams.delete("page");
                    url.searchParams.delete("daterange");
                } else {
                    // just add role param
                    url.searchParams.delete("daterange");
                }
                window.location.href = url.toString();


            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAll');
            const userCheckboxes = document.querySelectorAll('.row-checkbox');

            selectAllCheckbox.addEventListener('change', function() {
                userCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.custom-switch input[type="checkbox"]').on('change', function() {
                var userId = $(this).closest('tr').find('.row-checkbox').val();
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you really want to change the status?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, change it!",
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {

                        showLoader();
                        let url = "{{ route('admin.users.status', ':id') }}";
                        url = url.replace(':id', userId);
                        window.location.href = url;

                    } else {
                        location.reload();
                    }
                });


            });
        });

        $(document).on('click', '.btn-user-delete', function(e) {
            e.preventDefault();

            let $btn = $(this); // store the clicked button
            let url = $btn.data('url');

            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to delete this user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
            }).then((result) => {
                if (result.isConfirmed) {

                    showLoader();
                    // Redirect to URL
                    window.location.href = url;
                } else {
                    location.reload();
                }
            });
        });
        $(document).on('change', '#sel_bulk_action', function(e) {
            e.preventDefault();
            let selectedIds = $('.row-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            if (selectedIds.length == 0) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'Please select at least one user',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    location.reload(); // reload after toast timeout
                });

            } else {

                let action = $(this).val();
                if (!action) {
                    return; // No action selected
                }
                Swal.fire({
                    title: "Are you sure?",
                    text: `Do you really want to ${action} selected users?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: `Yes, ${action} them!`,
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Set action value in hidden input
                        $('input[name="action"]').val(action);
                        // Submit the form
                        $('#tbl-form').submit();
                        showLoader();
                    } else {
                        location.reload();
                    }
                });
            }
        });

        $(document).on('change', '#sel_roles', function(e) {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            let page = parseInt(params.get("page")) || 1;


            if (page > 1) {
                // reset URL (remove params except page)
                url.search = "";
                url.searchParams.delete("page");
                url.searchParams.set("role", $(this).val());
            } else if ($(this).val() == "") {
                url.searchParams.delete("role");
            } else {
                // just add role param
                url.searchParams.set("role", $(this).val());
            }

            window.location.href = url.toString();
        });

        $(document).on('change', '#sel_mode', function(e) {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            let page = parseInt(params.get("page")) || 1;


            if (page > 1) {
                // reset URL (remove params except page)
                url.search = "";
                url.searchParams.delete("page");
                url.searchParams.set("mode", $(this).val());
            } else if ($(this).val() == "") {
                url.searchParams.delete("mode");
            } else {
                // just add role param
                url.searchParams.set("mode", $(this).val());
            }

            window.location.href = url.toString();
        });

        $(document).on('change', '#sel_status', function(e) {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            let page = parseInt(params.get("page")) || 1;


            if (page > 1) {
                // reset URL (remove params except page)
                url.search = "";
                url.searchParams.delete("page");
                url.searchParams.set("status", $(this).val());
            } else if ($(this).val() == "") {
                url.searchParams.delete("status");
            } else {
                // just add role param
                url.searchParams.set("status", $(this).val());
            }

            window.location.href = url.toString();
        });
    </script>

    <script>
        $('#btnClear').hide();

        function fetchData(page = 1) {

            let search = $('input[name="search"]').val();


            $.ajax({
                url: "{{ url()->current() }}" + "?page=" + page + "&search=" + search,
                success: function(data) {
                    $('#user-data').html(data);
                }
            });
        }



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
    </script>
@stop
