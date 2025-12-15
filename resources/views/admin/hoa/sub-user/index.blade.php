@extends('master')
@section('stylesheets')
    <style>
        .card-header span {
            color: #555;
            font-weight: 400;
            font-size: 14px;

        }

        .search-box {
            position: relative;
            width: 100%;

        }



        .search-box input {
            width: 100%;
            padding: 10px 35px;
            border: 0.7px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .search-box input:focus {
            border-color: #28a745;
            /* green */
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
            outline: none;
        }

        /* Search icon */
        .search-box .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
            pointer-events: none;
        }

        /* Clear icon */
        .search-box .clear-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: #888;
            cursor: pointer;
            display: none;

        }

        .search-box .clear-icon:hover {
            color: #333;
        }
    </style>
    <style>
        .btn-wrapper {
            width: 100%;
            display: inline-flex;
            align-items: center;
            padding: 7px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            /* background-color: #fff; */
            cursor: pointer;
            font-size: 14px;
            gap: 10px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .btn-wrapper span {
            font-weight: bold;
        }

        .btn-wrapper:hover {
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 1px solid #a5a3a3;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border: 1px solid red;
        }

        .btn-wrapper img {
            width: 20px;
            height: 20px;

        }

        .btn {
            text-decoration: none;
            color: #333;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
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

        .flex {
            display: flex;
            align-items: center;
            gap: 10px;
        }



        .device-box {
            display: flex;
            background-color: #f3f2f2;
            padding: 5px;
            width: 60px;
            height: 60px;
            justify-content: center;
            align-items: center;
            border-radius: 5px;
            /* box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25); */
        }

        .device-icon {
            width: 30px;
            height: 30px;

        }

        .device-name {
            display: flex;
            flex-direction: column;
        }

        .device-name span {
            font-size: 14px;
        }

        .img-delete {
            width: 25px;
            height: 25px;
        }

        .img-delete:hover {
            content: url("{{ asset('images/trash-red.png') }}");
            border: 1px solid red;
            border-radius: 50%;
            padding: 4px;
            width: 30px;
            height: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }



        .vertical-align {
            vertical-align: middle;
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
            transition: all 0.3s ease;

        }

        .select-wrapper:hover {

            background-color: #f9f9f9;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
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
            cursor: pointer;
        }



        .select-custom:focus {
            border-color: none;
            box-shadow: none;
        }

        .select-custom options {
            cursor: pointer;
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
@endsection
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1> Hoa Houses List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Hoa Houses List</li>
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
                        <div class="col-md-3 px-3 border-right">
                            <span>Name</span>
                            <br>
                            <label for="">{{ $user->name ?? 'N/A' }}</label>
                        </div>
                        <div class="col-md-3 px-4 border-right">
                            <span for="">Email</span>
                            <br>
                            <label for="">{{ $user->email ?? 'N/A' }}</label>
                        </div>

                        <div class="col-md-2 px-4 border-right">
                            <span for="">Role</span>
                            <br>
                            <label>{{ $user->roles->first()->name ?? 'N/A' }}</label>
                        </div>
                        <div class="col-md-2 px-4 border-right">
                            <span for="">Device Limit / House</span>
                            <br>
                            <label for="">{{ $user->device_per_house ?? 'N/A' }}</label>

                        </div>
                        <div class="col-md-2 px-4">
                            <span for="">Total Houses</span>
                            <br>
                            <label for="">{{ $houseList->total() }}/{{ $user->num_of_houses ?? 'N/A' }}</label>

                        </div>
                    </div>




                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="search-box">
                                <span class="search-icon">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" id="txtSearch"
                                    placeholder="Search by name, email, passcode">
                                <span class="clear-icon" id="btnClear">✖</span>
                            </div>


                        </div>
                        <div class="col">
                            <a href="javascript:void(0)" id="btn-show-selected">
                                <h3 id="selected" class="card-title mt-2"></h3>
                            </a>
                            <div class="row justify-content-end">
                                <div class="col-md-4 mb-3">
                                    <a
                                        href="{{ route('admin.users.session.device.export', ['id' => $user->id, 'type' => 'xlsx'] + request()->query()) }}">
                                        <div class="btn-wrapper">
                                            <img src="{{ asset('images/export.png') }}" alt="status icon">
                                            <span> Export</span>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 mb-3" id="btnDeleteAll">
                                    <div class="btn-wrapper btn-danger">
                                        <img src="{{ asset('images/trash-red.png') }}" alt="status icon">
                                        <a href="javascript:void(0)" class="btn text-danger">Delete All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="user-data">

                        @include('admin.hoa.sub-user.index-table')
                    </div>
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
    </script>
    <script>
        let storageKey = "selected_sessions";

        // Get selected IDs from localStorage
        function getSelected() {
            return JSON.parse(localStorage.getItem(storageKey)) || [];
        }

        // Save to localStorage
        function saveSelected(ids) {
            localStorage.setItem(storageKey, JSON.stringify(ids));
        }

        function removeAll() {
            localStorage.removeItem(storageKey);
            restoreSelectedData();
        }

        function restoreSelectedData() {
            let selected = getSelected();


            $('.row-checkbox').each(function() {
                $(this).prop('checked', selected.includes($(this).val()));
            });
            let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
                '.row-checkbox').length;
            $('#selectAll').prop('checked', allChecked);

            // let allChecked = $('.row-checkbox').length > 0 && $('.row-checkbox:checked').length === $(
            //     '.row-checkbox').length;
            // $('#selectAll').prop('checked', allChecked);
            // allChecked == true ? $('#btnDeleteAll').show() : $('#btnDeleteAll').hide()

            if ($('.row-checkbox:checked').length <= 0) {
                $('#selected').text("")
                $('#btnDeleteAll').hide()
            } else {
                $('#selected').text("Selected : " + $('.row-checkbox:checked').length)
                if ($('.row-checkbox:checked').length > 1) {
                    $('#btnDeleteAll').show()
                } else {
                    $('#btnDeleteAll').hide()
                }


            }
        }

        window.addEventListener("load", () => {
            const [nav] = performance.getEntriesByType("navigation");
            const hasParams = window.location.search.length > 0;

            if (nav.type !== "reload" && !hasParams) {
                removeAll();

            }


        });

        $(document).ready(function() {
            restoreSelectedData();
        });
    </script>
    <script>
        const txtSearch = document.getElementById("txtSearch");
        const btnClear = document.getElementById("btnClear");
        const selectAllCheckbox = document.getElementById('selectAll');
        const btnDeleteAll = document.getElementById('btnDeleteAll');
        const userCheckboxes = document.querySelectorAll('.row-checkbox');





        $('#btnDeleteAll').hide();
        txtSearch.addEventListener("input", () => {
            btnClear.style.display = txtSearch.value ? "block" : "none";
        });

        btnClear.addEventListener("click", () => {
            txtSearch.value = "";
            btnClear.style.display = "none";
            txtSearch.focus();
            window.location.replace(window.location.pathname);
        });



        $(document).on('change', '.row-checkbox', function() {
            let selected = getSelected();
            if ($(this).is(':checked')) {
                if (!selected.includes($(this).val())) {
                    selected.push($(this).val());
                    saveSelected(selected);
                }

            } else {


                selected = selected.filter(id => id !== $(this).val());
                saveSelected(selected);
            }

            restoreSelectedData();

        });

        $(document).on('change', '#selectAll', function() {
            let selected = getSelected();
            const userCheckboxes = $('.row-checkbox');
            if ($(this).is(':checked')) {
                userCheckboxes.each(function() {
                    this.checked = true;
                    if (!selected.includes(this.value)) {
                        selected.push(this.value);
                    }
                });
                $('#btnDeleteAll').show();
            } else {
                $('#btnDeleteAll').hide();
                userCheckboxes.each(function() {
                    this.checked = false;
                    if (selected.includes(this.value)) {
                        selected = selected.filter(id => id !== this.value);
                    }

                });

            }
            saveSelected(selected);
            restoreSelectedData();
        });

        btnDeleteAll.addEventListener('click', (e) => {
            e.preventDefault();
            const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkedBoxes.length == 0) {
                Swal.fire({
                    toast: true,
                    icon: 'error',
                    title: 'Please select at least one',
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                }).then(() => {
                    location.reload(); // reload after toast timeout
                });

            } else {
                Swal.fire({
                    title: "Are you sure?",
                    text: `Do you really want to remove sessions?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: `Yes`,
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#tbl-form').submit();
                        showLoader();
                    } else {
                        location.reload();
                    }
                });
            }



        })

        document.querySelectorAll('.btn-delete').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const url = btn.getAttribute('data-url');

                Swal.fire({
                    title: "Are you sure?",
                    text: `Do you really want to remove sessions?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: `Yes`,
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {

                        showLoader();
                        window.location.href = url;
                    } else {
                        location.reload();
                    }
                });
            });
        });

        function toggleDeviceId(element) {
            const fullId = element.getAttribute('data-full');
            const shortId = fullId.substring(0, 10) + '...';
            console.log(element.innerText);


            if (element.innerText === shortId) {
                element.innerText = fullId;
            } else {
                element.innerText = shortId;
            }
        }

        function fetchData(page = 1) {

            let search = $('input[name="search"]').val();


            $.ajax({
                url: "{{ url()->current() }}" + "?page=" + page + "&search=" + search,
                success: function(data) {
                    $('#user-data').html(data);
                    restoreSelectedData()
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
    </script>
    @if (session()->pull('remove-all'))
        <script>
            removeAll();
        </script>
    @endif
@stop
