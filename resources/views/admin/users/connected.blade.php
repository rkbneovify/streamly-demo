@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Device details of {{$allSession[0]->user->name ?? ''}}</h1> {{ session()->get( 'info' ) }}
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
          <h3 class="card-title">User: {{$allSession[0]->user->name ?? ''}}</h3>
           
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                          Device id
                      </th>
                      <th style="width: 20%">
                          Device Name
                      </th>
                      {{-- <th style="width: 30%">
                          Email
                      </th> --}}
                      
                      {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                      <th style="width: 20%">
                      </th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($allSession as $key => $user)
                  
                        <tr data-entry-id="">
                        <td>
                          #
                      </td>
                      <td>
                       {{ $user->device_id ?? '' }}
                    </td>
                            
                            
                            <td>
                                {{ $user->device_name ?? '' }}
                            </td>
                            {{-- <td class="project-state">
                            @if ($user->is_active===1)
                            <span class="badge badge-success">Active</span>
@elseif ($user->is_active===0)
<span class="badge badge-danger">Inactive</span>
@else
    unknown
@endif
                          
                            
                          
                      </td> --}}
                      <td class="project-actions text-right">
                          
                          {{-- <a  class="btn btn-xs btn-primary" href="{{ url('admin/users/connected', $user->users_id) }}">View</a>
                          <a class="btn btn-xs btn-info" href="{{ url('admin/users/connected', $user->users_id) }}">Edit</a> --}}
                          
                                    <form action="{{ url('admin/users/sessiondestroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="access_token" value="{{ $user->access_token }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                                

                         
                          
                      </td>

                        </tr>
                    @endforeach
                </tbody>
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