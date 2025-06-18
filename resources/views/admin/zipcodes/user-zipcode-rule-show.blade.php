@extends('master')
@section('stylesheets')
    {{-- <link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css"
        integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA=="
        crossorigin="anonymous" />
@stop

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>User zipcode Rule</h1>
                        <h5 style="color:green">{{ session()->get('info') }}</h5>
                    </div>
                    <div id="message"></div>

                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">User zipcode Rule</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <a class="btn btn-xs btn-primary" href="{{ url('admin/userzipcoderule/create') }} ">
                Add new User zipcode Rule
            </a>

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User zipcode Rule</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        {{-- <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fas fa-times"></i></button> --}}
                    </div>
                </div>
                <div class="card-body p-0">
                    <table id="empTable" class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    User
                                </th>
                                <th style="width: 20%">
                                    zipcode
                                </th>
                                <th style="width: 20%">
                                    Validation rule
                                </th>
                                <th style="width: 20%">
                                    Created at
                                </th>
                                <th style="width: 20%">
                                    Updated at
                                </th>
                                <th style="width: 20%">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        @foreach ($UserZipcodeRule as $rule)
                            <tr>
                                <td>{{ $rule->id }}</td>
                                <td>{{ $rule->email }}</td>
                                <td>{{ $rule->zipcode }}</td>
                                <td>{{ $rule->validate }}</td>
                                <td>{{ $rule->created_at }}</td>
                                <td>{{ $rule->updated_at }}</td>
                                <td>
                                    <a href="/admin/userzipcoderule/edit/{{ $rule->id }}"
                                        class="btn btn-xs btn-info">Edit</a>
                                    <a href="#" class="btn btn-xs btn-danger deleteItem"
                                        data-id="{{ $rule->id }}">Delete</a>
                                </td>

                            </tr>
                        @endforeach
                    </table>

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
        $('body').on('click', '.deleteItem', function() {
            var Item_id = $(this).data("id");
            var $tr = $(this).closest('tr');

            if (confirm('Are you sure you want to delete this?')) {
                $.ajax({
                    type: "DELETE",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ url('admin/userzipcoderule/delete') }}/" + Item_id,
                    success: function(data) {
                        if (data.status === 'success') {
                            // Remove row only if delete was successful
                            $tr.find('td').fadeOut(1000, function() {
                                $tr.remove();
                            });
                            $('#message').html('<div class="alert alert-success">' + data.message +
                                '</div>').fadeIn('slow');
                        } else {
                            $('#message').html('<div class="alert alert-warning">' + data.message +
                                '</div>').fadeIn('slow');
                        }

                        $('#message').delay(5000).fadeOut('slow');
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#message').html(
                                '<div class="alert alert-danger">Server error. Please try again.</div>')
                            .fadeIn('slow');
                        $('#message').delay(5000).fadeOut('slow');
                    }
                });
            }
        });
    </script>
@stop
