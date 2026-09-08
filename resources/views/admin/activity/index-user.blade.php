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
            padding: 6px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            /* background-color: #fff; */
            cursor: pointer;
            font-size: 14px;
        }

        .btn-wrapper:hover {
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .nav-pills .nav-link {
            border-radius: 20px;
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
                        <h1>Activities</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Home</a></li>
                            <li class="breadcrumb-item active">Activities</li>
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
                        <div class="col-md-4 px-3 border-right">
                            <span>Name</span>
                            <br>
                            <label for="">{{ $user->name ?? '' }}</label>
                        </div>
                        <div class="col-md-4 px-4 border-right">
                            <span for="">Email</span>
                            <br>
                            <label>{{ $user->email ?? '' }}</label>
                        </div>

                        <div class="col-md-4 px-4">
                            <span for="">Role</span>
                            <br>
                            <label>{{ $user->roles->first()->name }}</label>
                        </div>

                    </div>




                </div>
                {{-- <div class="card-header">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="search-box">
                                <span class="search-icon">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" id="txtSearch"
                                    placeholder="Search by device name, platform, zipcode">
                                <span class="clear-icon" id="btnClear">✖</span>
                            </div>


                        </div>
                        <div class="col">
                            <a href="javascript:void(0)" id="btn-show-selected">
                                <h3 id="selected" class="card-title mt-2"></h3>
                            </a>
                            <div class="row justify-content-end">
                                <div class="col-md-4 mb-3">
                                    <div class="btn-wrapper">
                                        <img src="{{ asset('images/export.png') }}" alt="status icon">
                                        <a href="{{ route('admin.users.session.device.export', ['id' => $user->id, 'type' => 'xlsx'] + request()->query()) }}"
                                            class="btn">Export</a>
                                    </div>
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
                </div> --}}
                {{-- <div class="card-body p-0">

                </div> --}}
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- /.tabs -->
            <div class="container mb-3">
                <div class="d-flex align-items-center">
                    <strong class="mr-3 text-nowrap">Events:</strong>

                    <ul class="nav nav-pills mb-0 flex-nowrap" id="myTab" role="tablist">
                        @foreach ($events as $key => $item)
                            <li class="nav-item mx-1">
                                <a class="nav-link {{ $item == request()->event ? 'active' : '' }}" id="{{ $key }}-tab"
                                    data-toggle="pill" href="#{{ $key }}" role="tab" data-event="{{ $item }}">
                                    {{ $item }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <hr />
            <!-- /.tabs -->

            <div class="overflow-auto">
                <div id="user-data">
                    @include('admin.activity.table')
                </div>
            </div>

            <div class="mt-4">
                {{ $data->links() }}
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
<script>
    $(document).on('click', 'a[data-toggle="pill"]', function () {
        const event = $(this).data('event');

        const url = new URL(window.location.href);
        url.searchParams.set('event', event);

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


        $('.row-checkbox').each(function () {
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

    $(document).ready(function () {
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
        fetchData();
    });



    $(document).on('change', '.row-checkbox', function () {
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

    $(document).on('change', '#selectAll', function () {
        let selected = getSelected();
        const userCheckboxes = $('.row-checkbox');
        if ($(this).is(':checked')) {
            userCheckboxes.each(function () {
                this.checked = true;
                if (!selected.includes(this.value)) {
                    selected.push(this.value);
                }
            });
            $('#btnDeleteAll').show();
        } else {
            $('#btnDeleteAll').hide();
            userCheckboxes.each(function () {
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

    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
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
            success: function (data) {
                $('#user-data').html(data);
                restoreSelectedData()
            }
        });
    }



    $(document).on('keyup', 'input[name="search"]', function () {
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