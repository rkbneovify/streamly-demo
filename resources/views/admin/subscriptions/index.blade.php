@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Subscription</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Subscription</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptions/create') }} ">
                                        Add new subscription
                                    </a>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Subscription</h3>

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
                          User Name
                      </th>
                      <th style="width: 20%">
                          Subscription
                      </th>
                      <th style="width: 20%">
                          Start Date
                      </th><th style="width: 20%">
                          Expiry Date
                      </th>
                      
                      <th style="width: 10%" >
                          Status
                      </th>
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
              <tbody>
              @forelse ($users as $user)
              <tr>
              <td>#</td>
              
    <td>{{ $user->user->name }}</td>
    <td>{{ $user->stripe_plan }}</td>
    <td>{{ $user->created_at }}</td>
    <td>{{ $user->renews_at }}</td>
    <td >
                            @if ($user->status===1)
                            <span class="badge badge-success">Active</span>
@elseif ($user->status===0)
<span class="badge badge-danger">Inactive</span>
@else
    unknown
@endif
                          
                            
                          
                      </td>
    <td>
                                
                                    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptions/view', $user->id) }}">
                                        {{ trans('view') }}
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ url('admin/subscriptions/edit', $user->id) }}">
                                        {{ trans('edit') }}
                                    </a>

                                    <!-- <form action="{{ url('admin/subscriptions/destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form> -->
                          
                                
                            </td></tr>
@empty
    <p>No Subscription</p>
@endforelse
                  
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