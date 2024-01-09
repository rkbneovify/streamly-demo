@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>SubscriptionPack</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">SubscriptionPack</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptions/create') }} ">
                                        Add new contracts pack
                                    </a>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Hotel Contracts</h3>

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
                          Name
                      </th>
                      <th style="width: 20%">
                          Duration(in months)
                      </th>
                      <th style="width: 20%">
                          Rate
                      </th><th style="width: 20%">
                      Created on
                      </th>
                      
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
              <tbody>
              @forelse ($subscriptionpack as $subscriptionpack)
              <tr>
              <td>#</td>
              
    <td>{{ $subscriptionpack->name }}</td>
    <td>{{ $subscriptionpack->duration }}</td>
    <td>{{ $subscriptionpack->rate }}</td>
    <td>{{ $subscriptionpack->created_on }}</td>
    
    <td>
                                
                                    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptionpack/view', $subscriptionpack->id) }}">
                                        {{ trans('view') }}
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ url('admin/subscriptionpack/edit', $subscriptionpack->id) }}">
                                        {{ trans('edit') }}
                                    </a>

                                    <!-- <form action="{{ url('admin/subscriptions/destroy', $subscriptionpack->id) }}" method="POST" onsubmit="return confirm('{{ trans('are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form> -->
                          
                                
                            </td></tr>
@empty
    <p>No Subscription Pack</p>
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
