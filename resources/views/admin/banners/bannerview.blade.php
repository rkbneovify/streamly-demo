@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Banners ({{ $count = count($banner) }})</h1> {{ session()->get( 'info' ) }}
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
          <h3 class="card-title">Banner Details</h3>
           
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 5%">
                          S:No
                      </th>
                      <th style="width: 10%">
                          Image
                      </th>
                      <th style="width: 15%">
                          Heading
                      </th>
                      <th style="width: 20%">
                          Description
                      </th> 
                      <th style="width: 5%">
                          Status
                      </th> 
                      <th style="width: 10%">
                          Edit
                      </th> 
                      <th style="width: 5%">
                          Delete
                      </th> 
                  </tr>
              </thead>
              <tbody>
                  @for($i = 0; $i < $count; $i++)
                  
                        <tr data-entry-id="">
                        <td>
                        {{ $i + 1 }}
                      </td>
                           
                      <td>
                      <img src="/uploads/banner/{{$banner[$i]->image}}" height="50" width="70">
                                <!-- {{ $banner[$i]->image ?? '' }} -->
                            </td>
                            
                            <td>
                                {{ $banner[$i]->text ?? 'null' }}
                            </td>
                            <td>
                                {{ $banner[$i]->description ?? 'null' }}
                            </td>
                            <td>
                                {{ $banner[$i]->status ?? '' }}
                            </td> 
                            <td>
                                <a href="details/{{$banner[$i]->id}}" class="btn btn-xs btn-primary">View / Update</a>
                            </td> 
                            <td>
                            <form action="{{ url('/admin/banner/delete', $banner[$i]->id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                            </td> 
                        </tr>
                    @endfor
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
 <script>
    function slideDelete(){
        var result = confirm("Are you sure! want to delete?");
        if (result) {
           //Logic to delete the item
        }
    }
 </script>
@endsection