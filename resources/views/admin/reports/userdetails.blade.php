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
                        <h1>All Users {{ $allSession->total() }}</h1> {{ session()->get('info') }}
                    </div>
                    <!-- <div class="form-group">
                                                                                                                                                                                                <label><strong>Filter by Userrole :</strong></label>
                                                                                                                                                                                                                 <select name="roles" id="roles" class="form-control">
                                                                                                                                                                                                                 <option>choose user role</option>
                                                                                                                                                                                                      
                                                                                                                                                                                                      <option value=>
                                                                                                                                                                                                      active
                                                                                                                                                                                                      </option>
                                                                                                                                                                                                     
                                                                                                                                                                                                    </select>
                                                                                                                                                                                            </div> -->
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
                    <h3 class="card-title">Users Details</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
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
                    </div>
                </div>
               

                <div id="user-data">

                    @include('admin.reports.user-detail-table')
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
@endsection
