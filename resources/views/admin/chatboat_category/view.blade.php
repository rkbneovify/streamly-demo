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
    <title>Category View</title>
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
                <h1>Category View</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Category View</li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <!-- left column -->
              <div class="col-md-6">
                <!-- general form elements -->
                <div class="card card-primary">
                  <?php $data = $chatboat_category[0]; ?>
                  
                  @csrf
                  <div class="card-body">
                    <div class="form-group">
                      <label for="exampleInputFirstName">Category Name</label>
                      <input type="text" name="name" class="form-control" value="{{$data->name}}" readonly>
                    </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <a href="{{ url('admin/chatboat_category') }}" class="btn btn-danger">Cancel</a>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.row -->
          </div><!-- /.container-fluid -->
        </section>
      </div>
      <!-- /.content-wrapper -->
      
      

    </div>
    <!-- ./wrapper -->
  </body>
</html>
@endsection