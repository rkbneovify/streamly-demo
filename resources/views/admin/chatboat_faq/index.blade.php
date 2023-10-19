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
    <title>Faq List</title>
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
                <h1>Faq List</h1>
              </div>
              
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Faq List</li>
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
                      <a href="{{ url('admin/faq/create') }}" class="btn btn-success">Add New</a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>Sr No</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Category</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($chatboat_faq as $key => $faq)
                          
                          <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $faq->question_key }}</td>
                            <td>{{ $faq->question_answer }}</td>
                            <td>{{ $faq->name }}</td>
                            <td><a href="/admin/faq/view/{{ $faq->id }}" class="btn btn-xs btn-primary mb-1">View</a> 
                            <a href="/admin/faq/edit/{{ $faq->id }}" class="btn btn-xs btn-info mb-1">Edit</a> 
                            <a href="/admin/faq/destroy/{{ $faq->id }}" id="btn-delete" data-toggle="tooltip" data-id="1" data-original-title="Delete" class="deleteItem btn btn-danger btn-xs mb-1" onclick="return confirm('Are you sure you want to delete this faq ?');">Delete</a>
                            </td>
                          </tr>
                          @endforeach
                        
                        </tbody>
                       
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