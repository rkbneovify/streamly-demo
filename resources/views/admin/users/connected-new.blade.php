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
            background-color: #fff;
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



        .vertical-align {
            vertical-align: middle;
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
                        <h1>Device details of {{ $allSession[0]->user->name ?? '' }}</h1> {{ session()->get('info') }}
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Connected device details</li>
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
                            <label for="">{{ $allSession[0]->user->name ?? '' }}</label>
                        </div>
                        <div class="col-md-3 px-4 border-right">
                            <span for="">Email</span>
                            <br>
                            <label>{{ $allSession[0]->user->email ?? '' }}</label>
                        </div>

                        <div class="col-md-3 px-4 border-right">
                            <span for="">Role</span>
                            <br>
                            <label>{{ $allSession->first()->user->roles->first()->name }}</label>
                        </div>
                        <div class="col-md-3 px-4">
                            <span for="">Total Devices</span>
                            <br>
                            @if ($allSession->first()->user->roles->first()->id == 5)
                                <label>{{ $allSession->count() . '/' . $totalRooms }}</label>
                            @else
                                <label>{{ $allSession->count() . '/5' }}</label>
                            @endif

                        </div>
                    </div>




                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <div class="search-box">
                                <span class="search-icon">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="txtSearch" placeholder="Search by device name, type">
                                <span class="clear-icon" id="btnClear">✖</span>
                            </div>

                        </div>
                        <div class="col">
                            <div class="row justify-content-end">
                                <div class="col-md-4 mb-3">
                                    <div class="btn-wrapper">
                                        <img src="{{ asset('images/export.png') }}" alt="status icon">
                                        <a href="{{ route('admin.users.export', ['type' => 'xlsx'] + request()->query()) }}"
                                            class="btn">Export</a>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="btn-wrapper btn-danger">
                                        <img src="{{ asset('images/trash-red.png') }}" alt="status icon">
                                        <a href="{{ route('admin.users.export', ['type' => 'xlsx'] + request()->query()) }}"
                                            class="btn text-danger">Delete All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="" id="cbAll">
                                    </th>

                                    <th>
                                        Device Name
                                    </th>


                                    @if ($allSession->first()->user->roles->first()->id == 5)
                                        <th>
                                            Hotel room
                                        </th>
                                    @endif

                                    <th>
                                        Device Type
                                    </th>
                                    <th>
                                        Created At
                                    </th>
                                    <th>
                                        Action
                                    </th>




                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allSession as $key => $user)
                                    <tr>
                                        <td style="vertical-align: middle;">
                                            <input type="checkbox" name="" id="cbAll">
                                        </td>



                                        <td>

                                            <div class="flex">
                                                <div class="device-box">
                                                    <img class="device-icon" src="{{ asset('images/desktop.png') }}"
                                                        alt="device icon">

                                                </div>
                                                <div class="device-name">
                                                    @if ($user->device_type === 'web')
                                                        <b> Web</b>
                                                    @else
                                                        <b> {{ $user->pdInfo->personal_devicename ?? '' }}</b>
                                                    @endif
                                                    <span>
                                                        ID : {{ $user->device_id ?? '' }}
                                                    </span>
                                                </div>
                                            </div>




                                        </td>

                                        @if ($allSession->first()->user->roles->first()->id == 5)
                                            <td style="vertical-align: middle;"> @isset($user->hotel)
                                                    {{ $user->hotel->hotel_room ?? '' }}
                                                @endisset
                                            </td>
                                        @endif

                                        <td style="vertical-align: middle;">
                                            @if (!empty($user->device_title))
                                                {{ $user->device_title ?? '' }} - {{ $user->device_model ?? '' }}

                                                ({{ $user->build_version ?? '' }})
                                            @else
                                                {{ detectDeviceByUserAgent($user->useragent ?? '') }}
                                            @endif


                                        </td>
                                        <td style="vertical-align: middle;">
                                            {{ $user->created_on ?? '' }}
                                        </td>
                                        <td style="vertical-align: middle;">
                                            <a href="#">
                                                <img class="img-delete" src="{{ asset('images/trash.png') }}"
                                                    alt="Delete Icon">
                                            </a>
                                        </td>



                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        $(function() {
            $('.update_record').prop('disabled', true);
            // Check all 
            $('#checkall').click(function() {
                if ($(this).is(':checked')) {
                    $('.update_check').prop('checked', true);
                    $('.update_record').prop('disabled', false);
                } else {
                    $('.update_check').prop('checked', false);
                    $('.update_record').prop('disabled', true);
                }
            });

            const checkboxes = document.querySelectorAll(".update_check");


            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener("change", () => {
                    const checkedCount = document.querySelectorAll(".update_check:checked").length;
                    const uncheckedCount = document.querySelectorAll(".update_check:not(:checked)")
                        .length;
                    if (checkboxes.length <= checkedCount) {
                        $('#checkall').prop('checked', true);
                    } else if (checkboxes.length >= uncheckedCount) {
                        $('#checkall').prop('checked', false);
                    }


                    if (checkedCount > 0) {
                        $('.update_record').prop('disabled', false);
                    } else {
                        $('.update_record').prop('disabled', true);
                    }

                });
            });

            $(document).on('click', '.update_record', function() {

                var id = [];
                var status;



                if (confirm("Are you sure you want to Update this data?")) {
                    $('.update_check:checked').each(function() {
                        id.push($(this).val());
                    });
                    console.log("id", id);
                    if (id.length > 0) {

                        status = this.id;
                        $.ajax({
                            url: "{{ url('admin/multisessiondestroy') }}",
                            headers: {
                                'x-csrf-token': '{{ csrf_token() }}'
                            },
                            method: "post",
                            data: {
                                id: id,
                                status: status
                            },
                            success: function(data) {
                                console.log(data);
                                alert(data.message);
                                // console.log(data.message);
                                // console.log('data',data);
                                $.each(id, function(index, value) {
                                    // alert( index + ": " + value );
                                    $('#' + value + '').fadeTo(400, 0, function() {
                                        $(this).remove();
                                    });
                                });
                            },
                            error: function(xhr, status, error) {
                                alert(xhr.responseJSON.message);
                                console.log(xhr.responseJSON);
                                //   $.each(xhr.responseJSON.errors, function (key, item) 
                                // {
                                //   $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
                                // });
                            }
                        });
                    } else {
                        alert("Please select atleast one checkbox");
                    }
                }
            });
        });
    </script>

    <script>
        const txtSearch = document.getElementById("txtSearch");
        const btnClear = document.getElementById("btnClear");
        txtSearch.addEventListener("input", () => {
            btnClear.style.display = txtSearch.value ? "block" : "none";
        });

        btnClear.addEventListener("click", () => {
            txtSearch.value = "";
            btnClear.style.display = "none";
            txtSearch.focus();
        });
    </script>
@stop
