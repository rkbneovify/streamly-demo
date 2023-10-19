@extends('master')
@section('stylesheets')

<link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@stop
@section('content')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Category List</title>
  </head>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Category List</h1>
              </div>
              
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Category List</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <div class="col-6">
          @if($message = Session::get('success'))
            <div class="alert alert-success">
              <span>{{ $message }}</span>
            </div>
          @endif
        </div>
        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div class="card">
                  <div class="card">
                    <div class="card-header text-right">
                      <a href="{{ url('admin/chatboat_category/create') }}" class="btn btn-success">Add New</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No</th>
                            <th>Category Name</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($chatboat_category as $key => $category)
                          
                          <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $category->name }}</td>
                            <td><a href="/admin/chatboat_category/view/{{ $category->id }}" class="btn btn-xs btn-primary">View</a> 
                            <a href="/admin/chatboat_category/edit/{{ $category->id }}" class="btn btn-xs btn-info">Edit</a> 
                            <a href="/admin/chatboat_category/destroy/{{ $category->id }}" id="btn-delete" data-toggle="tooltip" data-id="1" data-original-title="Delete" class="deleteItem btn btn-danger btn-xs " onclick="return confirm('Are you sure you want to delete this Category ?');">Delete</a>
                            </td>
                          </tr>
                          @endforeach
                        
                        </tbody>
                        <tfoot>
                          <tr>
                            <th>Sr No</th>
                            <th>Category Name</th>
                            <th>Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                </div>
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      
      <!-- Footer -->
     

    </div>
    <!-- ./wrapper -->
  </body>
</html>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}" defer>
</script>
<script type="text/javascript">
    $(document).ready(function() {
    $('#example1').dataTable();
    });
  </script> 