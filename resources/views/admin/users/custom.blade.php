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
                        <h1>Custom Users</h1> {{ session()->get('info') }}
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
                    @if (empty(Request()->role))
                        <h3 class="card-title"> Users ({{ $users->total() }})</h3>
                    @else
                        @foreach ($roles as $role)
                            @if (Request()->role == $role->id)
                                <h3 class="card-title">{{ Str::ucfirst($role->display_name) }}
                                    ({{ $users->total() }})
                                </h3>
                            @endif
                        @endforeach
                    @endif



                </div>

                <div class="card-header">
                    <div class="row">
                        <div class="col-md-2">
                            <select class="form-control" id="bulkAction">
                                <option value="">Bulk Action</option>
                                <option value="send-mail">Send Mail</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="role">
                                <option selected value="">Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ Request()->role == $role->id ? 'selected' : '' }}>
                                        {{ Str::ucfirst($role->display_name) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <div class="input-conatiner">
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
                                        href="{{ route('admin.users.custom.export', ['type' => 'xlsx'] + request()->query()) }}">Export
                                        to Excel</a>
                                    <a class="dropdown-item"
                                        href="{{ route('admin.users.custom.export', ['type' => 'csv'] + request()->query()) }}">Export
                                        to CSV</a>

                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <div id="user-data">

                    @include('admin.users.partials.custom-table')
                </div>

                {{-- <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            {{ $allSession->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div> --}}

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
                url: "{{ route('admin.users.custom.list') }}" + "?page=" + page + "&search=" + search,
                success: function(data) {
                    $('#user-data').html(data);
                }
            });
        }


        $('select[name="role"]').change(function() {
            let url = new URL(window.location.href);
            url.searchParams.set('role', $(this).val());
            window.location.href = url.toString();
        });
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
    <script>
        $('#bulkAction').change(function() {


            if ($(this).val() != "") {

                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you really want to send this mail?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, send it!",
                    cancelButtonText: "Cancel",
                }).then((result) => {
                    if (result.isConfirmed) {

                        showLoader();
                        $('#tbl-form').submit();
                    }
                });

            }

        })
    </script>
@endsection
