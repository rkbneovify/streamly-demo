@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>zipcode</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">zipcode</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptions/create') }} ">
                                        Add new zipcode 
                                    </a>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">zipcode</h3>

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
                      city
                      </th>
                      <th style="width: 20%">
                      zipcode
                      </th>
                      <th style="width: 20%">
                      state
                      </th><th style="width: 20%">
                       status
                      </th>
                      
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
              <tbody>
              @if(!empty($zipcodes) && $zipcodes->count())

@foreach($zipcodes as $key => $zipcode)

    <tr>
    <td>{{ $zipcode->id }}</td>
              
              <td>{{ $zipcode->city }}</td>
              <td>{{ $zipcode->zipcode }}</td>
              <td>{{ $zipcode->state }}</td>
              <td >
                            @if ($zipcode->status===1)
                            <span class="badge badge-success">Active</span>
@elseif ($zipcode->status===0)
<span class="badge badge-danger">Inactive</span>
@else
    unknown
@endif
                          
                            
                          
                      </td>
 
              <td>
                                          
                                              <a class="btn btn-xs btn-primary" href="{{  url('admin/zipcode/view', $zipcode->id) }}">
                                                  {{ trans('view') }}
                                              </a>
          
                                              <a class="btn btn-xs btn-info" href="{{ url('admin/zipcode/edit', $zipcode->id) }}">
                                                  {{ trans('edit') }}
                                              </a>
          
                                              <!-- <form action="{{ url('admin/subscriptions/destroy', $zipcode->id) }}" method="POST" onsubmit="return confirm('{{ trans('are You Sure') }}');" style="display: inline-block;">
                                                  <input type="hidden" name="_method" value="DELETE">
                                                  <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                  <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                              </form> -->
                                    
                                          
                                      </td>

    </tr>

@endforeach

@else

<tr>

    <td colspan="10">There are no data.</td>

</tr>

@endif
                </tbody>
          </table>
          {{ $zipcodes->links() }}
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection