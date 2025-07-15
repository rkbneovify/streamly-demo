@extends('master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Users</h1> {{ session()->get('info') }}
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
                    <h3 class="card-title">Users ({{ $allSession->total() }})</h3>

                    {{-- <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div> --}}
                </div>

                <div class="card-header">
                    <form class="row g-3">
                        <div class="col-md-3">
                            <select class="form-control" name="role">
                                <option selected value="">Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ Request()->role == $role->id ? 'selected' : '' }}>
                                        {{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="search" value="{{ Request()->search }}" class="form-control"
                                placeholder="Search by name, email">
                        </div>
                        <div class="col-md-2"></div>

                        <div class="col-md-1">
                            <button type="submit" class="btn btn-primary mb-3 w-100">Search</button>
                        </div>
                        <div class="col-md-1">
                            <button name="btnClear" type="button" class="btn btn-light mb-3 w-100">Clear</button>
                        </div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 1%">
                                    #
                                </th>
                                <th style="width: 20%">
                                    Role
                                </th>
                                <th style="width: 20%">
                                    Name
                                </th>
                                <th style="width: 20%">
                                    Email
                                </th>
                                <th style="width: 30%">
                                    No. connected device
                                </th>

                                {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                                <th style="width: 20%">
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allSession as $key => $user)
                                <tr data-entry-id="">
                                    <td>
                                        {{ $key + $allSession->firstItem() }}
                                    </td>
                                    <td>
                                        {{ $user->user->roles->first()->display_name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->user->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $user->user->email ?? '' }}
                                    </td>


                                    <td>
                                        {{ $user->activesession ?? '' }}
                                    </td>
                                    {{-- <td class="project-state">
                            @if ($user->is_active === 1)
                            <span class="badge badge-success">Active</span>
@elseif ($user->is_active===0)
<span class="badge badge-danger">Inactive</span>
@else
    unknown
@endif
                          
                            
                          
                      </td> --}}
                                    <td class="project-actions text-right">

                                        <a class="btn btn-xs btn-primary"
                                            href="{{ url('admin/users/connected', $user->users_id) }}">View</a>
                                        {{-- <a class="btn btn-xs btn-info" href="{{ url('admin/users/connected', $user->users_id) }}">Edit</a>
                          
                                    <form action="{{ url('admin/users/destroy', $user->users_id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form> --}}




                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="container">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            {{ $allSession->links('pagination::bootstrap-4') }}
                        </div>
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
        $(document).ready(function() {
            $('select[name="role"]').change(function() {
                let url = new URL(window.location.href);
                url.searchParams.set('role', $(this).val());
                url.searchParams.set('search', '');
                url.searchParams.set('page', '');
                window.location.href = url.toString();
            });

            $('input[name="search"]').on('input', function() {
                let value = $(this).val();
                let url = new URL(window.location.href);
                url.searchParams.set('search', $(this).val());
                window.location.href = url.toString();
                //    alert($(this).val());
            });

            $('button[name="btnClear"]').click(function() {
                // $('select[name="role"]')[0].selectedIndex = 0;
                // $('input[name="search"]').val('');
                location.href = "{{ route('admin.user.session') }}"
            })
        });
    </script>
@endsection
