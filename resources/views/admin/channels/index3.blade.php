@extends('master')
@section('stylesheets')
    <style>
        video {
            width: 100%;
        }

        .dash-video-player {
            position: relative;
            /* This position relative is needed to position the menus */
            width: 640px;
            margin: 0 auto;
        }

        .hide_column {
            display: none;
        }
    </style>
    <link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/controlbar/controlbar.css') }}">
    <script defer src="{{ asset('frontend/dashjs/dash.all.min.js') }}"></script>
    <script defer src="{{ asset('frontend/dashjs/dash.mss.min.js') }}"></script>
    <script defer src="{{ asset('frontend/controlbar/ControlBar.js') }}"></script>
    <style>
        .custom-badge {
            font-size: 14px;
            font-weight: 500;
            border-radius: 14px;
            padding: 5px 10px;
            display: inline-block;
        }

        .badge-active {
            color: #578f6d;
            background-color: #dff5e8;
        }

        .badge-inactive {
            color: #cf6773;
            background-color: #ffebf0;
        }

        .table thead {
            background-color: #fafafa;
        }

        .table thead tr th {
            font-size: 14px;
            color: #7d7d7d;
        }

        .table tbody tr td {
            font-size: 16px;
            color: #383838;
            font-weight: 500;

        }

        .badge-light {
            background: none;
        }
    </style>
    <style>
        .select-wrapper {
            width: 100%;
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
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
            padding: 10px 40px 10px 15px;
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

        .input-custom {

            padding: 20px 12px;
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
                        <h1>Channel</h1> {{ session()->get('info') }}
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Channel</li>
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
                        <div class="col-md-3 mb-2">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/check-all-square.png') }}" alt="Bulk Action Icon">
                                <select name="bulk_action" id="sel_bulk_action" class="select-custom">
                                    <option value="">Bulk Action</option>
                                    <option value="activate">Activate</option>
                                    <option value="inactivate">Inactivate</option>

                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/user-mode.png') }}" alt="status icon">
                                <select name="status" id="sel_status" class="select-custom">
                                    <option value="">Select Status</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/user-mode.png') }}" alt="status icon">
                                <select name="status" id="sel_availability" class="select-custom">
                                    <option value="">Select Availability</option>
                                    <option value="all" {{ request('availability') == 'all' ? 'selected' : '' }}>All
                                    </option>
                                    <option value="local" {{ request('availability') == 'local' ? 'selected' : '' }}>
                                        Local</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="select-wrapper">
                                <img src="{{ asset('images/user-mode.png') }}" alt="status icon">
                                <select name="status" id="sel_genres" class="select-custom">
                                    <option value="">Select Genres</option>
                                    @foreach ($genres as $item)
                                        <option value="{{ $item }}"
                                            {{ request()->genre == $item ? 'selected' : '' }}>
                                            {{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="input-conatiner">
                                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                                    class="form-control input-custom" placeholder="Search by name, station, callsign">
                                <span class="btn-clear" id="btnClear">×</span>
                            </div>
                        </div>
                    </div>
                </div>



                <div id="user-data">
                    @include('admin.channels.index3-table')
                </div>

                <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- Modal -->




        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')

    <script defer type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
    <script defer type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        // functions
        let storageKey = "selected_channels";

        // Get selected IDs from localStorage
        function getSelected() {
            return JSON.parse(localStorage.getItem(storageKey)) || [];
        }

        // Save to localStorage
        function saveSelected(newIds) {
            if (!Array.isArray(newIds)) {
                newIds = [newIds]; // handle single ID input
            }

            let selected = getSelected();

            // Add new IDs at the beginning if not already present
            newIds.forEach(id => {
                if (!selected.includes(id)) {
                    selected.unshift(id); // add to start
                }
            });

            localStorage.setItem(storageKey, JSON.stringify(selected));
        }

        function removeAll() {
            localStorage.removeItem(storageKey);
            restoreSelectedData();
        }

        function restoreSelectedData() {
            let selected = getSelected();
            if (selected.length > 0) {
                document.getElementById('selected').textContent = "Selected : " + selected.length;
            } else {
                document.getElementById('selected').textContent = "";
            }

            const userCheckboxes = document.querySelectorAll('.row-checkbox');
            userCheckboxes.forEach(checkbox => {
                selected.includes(checkbox.value) ? checkbox.checked = true : checkbox.checked = false;

            });
            let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
                '.row-checkbox').length;
            $('#selectAll').prop('checked', allChecked);
        }

        function loadSelected() {
            let selected = getSelected();

            if (selected.length > 0) {
                // Start with the current URL (preserve existing params)
                const url = new URL(window.location.href);

                // Remove old selected[] params
                url.searchParams.delete("selected[]");

                // Add only currently selected IDs
                selected.reverse().forEach(id => {
                    url.searchParams.append("selected[]", id);
                });

                // Reload with updated query string (keeping other params intact)
                window.location.href = url.toString();
            }
        }

        function fetchData(page = 1) {

            let search = $('input[name="search"]').val();


            $.ajax({
                url: "{{ url()->current() }}" + "?" + window.location.search.replace(/^\?/, '') + "&search=" +
                    search + "&page=" + page,
                success: function(data) {
                    $('#user-data').html(data);

                    restoreSelectedData();

                    // Set #selectAll checked only if all visible checkboxes are checked (current page)
                    let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
                        '.row-checkbox').length;
                    $('#selectAll').prop('checked', allChecked);
                    console.log($('.row-checkbox:checked').length);


                }
            });



        }
    </script>

    <script>
        $(document).on('change', '#sel_limit', function(e) {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            let page = parseInt(params.get("page")) || 1;


            if (page > 1) {
                // reset URL (remove params except page)
                url.search = "";
                url.searchParams.delete("page");
                url.searchParams.set("limit", $(this).val());
            } else if ($(this).val() == "") {
                url.searchParams.delete("limit");
            } else {

                url.searchParams.set("limit", $(this).val());
            }

            window.location.href = url.toString();
        });
        $(document).on('change', '#sel_genres', function(e) {
            let url = new URL(window.location.href);
            let params = url.searchParams;
            let page = parseInt(params.get("page")) || 1;


            if (page > 1) {
                // reset URL (remove params except page)
                url.search = "";
                url.searchParams.delete("page");
                url.searchParams.set("genre", $(this).val());
            } else if ($(this).val() == "") {
                url.searchParams.delete("genre");
            } else {

                url.searchParams.set("genre", $(this).val());
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

                url.searchParams.set("status", $(this).val());
            }

            window.location.href = url.toString();
        });

        $(document).on('change', '#sel_availability', function(e) {

            let url = new URL(window.location.href);
            let params = url.searchParams;
            let page = parseInt(params.get("page")) || 1;


            if (page > 1) {
                // reset URL (remove params except page)
                url.search = "";
                url.searchParams.delete("page");
                url.searchParams.set("availability", $(this).val());
            } else if ($(this).val() == "") {
                url.searchParams.delete("availability");
            } else {

                url.searchParams.set("availability", $(this).val());
            }

            window.location.href = url.toString();
        });
        $(document).on('keyup', 'input[name="search"]', function() {
            let search = $('input[name="search"]').val();
            if (search.length > 0) {
                $('#btnClear').show();

            } else {
                $('#btnClear').hide();
                loadSelected();

            }
            fetchData();

        });
        $('#btnClear').click(function() {

            $('input[name="search"]').val('');
            $('input[name="search"]').focus();
            fetchData();
            $('#btnClear').hide();

            loadSelected();
        })

        $(document).ready(function() {
            $("#search").focus();
            $("#checkAll").on("change", function() {
                $(".row-checkbox").prop("checked", $(this).prop("checked"));
            });

            $(".row-checkbox").on("change", function() {
                if ($(".row-checkbox:checked").length === $(".row-checkbox").length) {
                    $("#checkAll").prop("checked", true);
                } else {
                    $("#checkAll").prop("checked", false);
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".toggle-url").forEach(function(el) {
                el.addEventListener("click", function() {
                    let shortUrl = this.querySelector(".short-url");
                    let fullUrl = this.querySelector(".full-url");

                    if (fullUrl.classList.contains("d-none")) {
                        shortUrl.classList.add("d-none");
                        fullUrl.classList.remove("d-none");
                    } else {
                        shortUrl.classList.remove("d-none");
                        fullUrl.classList.add("d-none");
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".toggle-zipcodes").forEach(function(el) {
                el.addEventListener("click", function() {
                    let id = this.getAttribute("data-id");
                    let row = document.getElementById("zipcodes-" + id);
                    row.classList.toggle("d-none");
                });
            });
        });
    </script>
@stop
