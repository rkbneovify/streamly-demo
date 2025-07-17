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
                        <h1>Users with rooms</h1> {{ session()->get('info') }}
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Users with rooms</li>
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
                    <h3 class="card-title">Users with rooms</h3>


                </div>
                <div class="card-header">
                    <div class="row">

                        <div class="col-md-5">
                            <div class="input-conatiner">
                                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                                    class="form-control" placeholder="Search by name, email">
                                <span class="btn-clear" id="btnClear">×</span>
                            </div>
                        </div>
                        <div class="col-md-2"></div>


                    </div>
                </div>
                <div class="card-body p-0">

                    <div id="user-data">

                        @include('admin.hotels.partials.users-table')
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
        $('#btnClear').hide();

        function fetchData(page = 1) {

            let search = $('input[name="search"]').val();
            // let role = $('select[name="role"]').val();

            $.ajax({
                url: "{{ route('admin.hotel.room') }}" + "?page=" + page + "&search=" + search,
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
        $('select[name="role"]').change(function() {
            let url = new URL(window.location.href);
            url.searchParams.set('role', $(this).val());
            window.location.href = url.toString();
        });
        $('#btnClear').click(function() {

            $('input[name="search"]').val('');
            fetchData();
            $('#btnClear').hide();

        })
    </script>
@endsection
