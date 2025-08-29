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
                                    <input type="text" class="form-control" name="search"
                                        placeholder="Search by email or name" value="{{ request('search') }}">
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

                    {{-- <div class="table-responsive">
                        <table id="empTable" class="table table-striped projects">
                            <thead>
                                <tr>
                                    <th style="width: 1%">
                                        #
                                    </th>
                                    <th style="width: 20%">
                                        Email
                                    </th>
                                    <th style="width: 20%">
                                        Name
                                    </th>
                                    <th style="width: 20%">
                                        Role
                                    </th>
                                    <th style="width: 20%">
                                        status
                                    </th>
                                    <th>Action</th>
                                    <th>Check All <input type="checkbox" class='checkall' id='checkall'><input
                                            type="button" class='update_record' id="1"
                                            value='Activate selected'><input type="button" class='update_record'
                                            id="0" value='InActivate selected'></th>

                                </tr>
                            </thead>
                        </table>

                    </div> --}}

                    <form id="tbl-form" method="POST" action="{{ route('admin.users.bulk.action') }}">
                        @csrf

                        <input hidden type="text" name="action">

                        <div class="table-responsive">
                            <table class="table table-striped projects">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th>
                                            Name
                                        </th>
                                        <th>
                                            Email
                                        </th>

                                        <th>
                                            Role
                                        </th>
                                        <th>
                                            User Type
                                        </th>
                                        <th>
                                            Status
                                        </th>
                                        <th>
                                            Active <br> Devices
                                        </th>
                                        <th>
                                            Created At
                                            <a href="{{ request()->get('page', 1) > 1
                                                ? request()->url() . '?sort_date=' . (request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc')
                                                : request()->fullUrlWithQuery([
                                                    'sort_date' => request()->get('sort_date', 'desc') === 'desc' ? 'asc' : 'desc',
                                                ]) }}"
                                                class="text-primary ms-1">↑↓</a>
                                        </th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $key => $user)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="row-checkbox" name="user_ids[]"
                                                    value="{{ $user->id }}">
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.view', $user->id) }}">
                                                    <div class="mb-2"
                                                        style="width:40px; height:40px;
                                            background:{{ $key % 2 == 0 ? '#3498db' : '#e67e22' }};
                                            color:white; display:flex; align-items:center; justify-content:center;
                                            border-radius:50%; font-weight:bold;">
                                                        {{ getAvatarName($user->name) }}
                                                    </div>


                                                    {{ ucwords(Str::limit($user->name, 10, '...')) }}
                                                </a>
                                            </td>
                                            <td>

                                                {{ limitEmailName($user->email, 10, '...') }}
                                            </td>
                                            <td>
                                                {{ $user->roles->pluck('name')->join(', ') }}
                                            </td>
                                            <td>
                                                @if ($user->mode == 'Live')
                                                    Live
                                                @else
                                                    Testing
                                                @endif

                                            </td>
                                            <td>
                                                @if ($user->is_active)
                                                    <label class="custom-switch">
                                                        <input type="checkbox" checked>
                                                        <span class="custom-slider"></span>
                                                    </label>
                                                @else
                                                    <label class="custom-switch">
                                                        <input type="checkbox">
                                                        <span class="custom-slider"></span>
                                                    </label>
                                                @endif

                                            </td>
                                            <td>
                                                <a href="{{ route('admin.users.connected.devices', $user->id) }}">
                                                    @if ($user->rooms_count > 0)
                                                        {{ $user->sessions_count ?? 0 }} / {{ $user->rooms_count ?? 0 }}
                                                    @else
                                                        {{ $user->sessions_count ?? 0 }} / 5
                                                    @endif
                                                </a>

                                            </td>
                                            <td>
                                                {{ $user->created_at->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                <a class="btn btn-secondary btn-sm" title="Edit User"
                                                    href="{{ route('admin.users.edit', $user->id) }}">
                                                    <i class="fas fa-pencil-alt">
                                                    </i>

                                                </a>
                                                <a href="{{ route('admin.users.delete', $user->id) }}"
                                                    title="Delete User"
                                                    data-url="{{ route('admin.users.delete', $user->id) }}"
                                                    class="btn btn-danger btn-sm btn-user-delete">
                                                    <i class="fas fa-trash">
                                                    </i>

                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                            </table>
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
    <script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roles');
            const exportLinks = document.querySelectorAll('.export-link');

            roleSelect.addEventListener('change', function() {
                let role = this.value;
                exportLinks.forEach(function(link) {
                    let url = new URL(link.href, window.location.origin);
                    if (role && role.toLowerCase() !== '') {
                        url.searchParams.set('role', role);
                    } else {
                        url.searchParams.delete('role');
                    }
                    link.href = url.toString();
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // DataTable
            var table = $('#empTable').DataTable({
                pageLength: 100,
                processing: true,
                serverSide: true,
                ajax: "{{ url('admin/user/data') }}",
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'is_active'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'action2',
                        name: 'action2',
                        orderable: false,
                        searchable: false
                    },

                ],
                columnDefs: [{
                    "render": function(data, type, row) {
                        if (row.status == 1) {
                            return '<span class="dotgreen"></span> Active';
                        } else if (row.status == 0) {
                            return '<span class="dotred"></span> Inactive';
                        } else {
                            return '<span class="label label-default">unknown</span>';
                        }
                    },
                    "targets": [4]
                }, {
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    },
                    "targets": [0]
                }, {
                    "render": function(data, type, row) {
                        // console.log("role",data[0].display_name);
                        return data[0].display_name ? data[0].display_name : 'No user role';
                    },
                    "targets": [3]
                }]
            });


            $('body').on('click', '.deleteItem', function() {

                var Item_id = $(this).data("id");
                var $tr = $(this).closest('tr');
                //  alert(Item_id);exit;
                if (confirm('Are you sure you want to delete this?')) {
                    $.ajax({
                        type: "DELETE",
                        url: "{{ url('admin/users/destroy') }}" + '/' + Item_id,
                        success: function(data) {
                            $tr.find('td').fadeOut(1000, function() {
                                $tr.remove();
                            });

                            alert(JSON.stringify(data));
                            $('#message').html(JSON.stringify(data)).fadeIn('slow');

                            $('#message').delay(5000).fadeOut('slow');
                        },
                        error: function(data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
            $('#roles').change(function() {
                table.column(3)
                    .search(this.value)
                    .draw();
                console.log("status changed", this.value);

                // table.draw();
            });

            // Check all 
            $('#checkall').click(function() {
                if ($(this).is(':checked')) {
                    $('.update_check').prop('checked', true);
                } else {
                    $('.update_check').prop('checked', false);
                }
            });

            $(document).on('click', '.update_record', function() {
                var id = [];
                var status;
                if (confirm("Are you sure you want to Update this data?")) {
                    $('.update_check:checked').each(function() {
                        id.push($(this).val());
                    });
                    if (id.length > 0) {
                        status = this.id;
                        $.ajax({
                            url: "{{ url('admin/users/updateall') }}",
                            method: "get",
                            data: {
                                id: id,
                                status: status
                            },
                            success: function(data) {
                                alert(data.message);
                                console.log(data.message);
                                $('#empTable').DataTable().ajax.reload();
                            },
                            error: function(xhr, status, error) {
                                alert(xhr.responseJSON.message);
                                //   $.each(xhr.responseJSON.errors, function (key, item) 
                                // {
                                //   $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
                                // });
                                xhr.responseJSON.errors !== undefined ? console.table(xhr
                                    .responseJSON.errors) : console.table(xhr);
                            }
                        });
                    } else {
                        alert("Please select atleast one checkbox");
                    }
                }
            });

        });


        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roles');

            roleSelect.addEventListener('change', function() {
                if (this.value === 'choose user role') {
                    location.reload(); // reload on "choose user role"
                } else {
                    this.form.submit(); // auto-submit when role selected
                }
            });
        });
    </script>
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
                console.log("cancel clicked");


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
            } else {
                // just add role param
                url.searchParams.set("status", $(this).val());
            }

            window.location.href = url.toString();
        });
    </script>
@stop
