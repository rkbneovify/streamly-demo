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
                  <?php $data = $chatboat_faq[0]; 
                  $access = $data->cat_id; ?>
                  
                  <div class="card-body">
                    
                    <div class="form-group">
                        <label for="exampleInputFirstName">Category</label>
                        <select class="form-control" name="faq_category" readonly>
                          <option value="">Please Select Faq category</option>
                          <?php foreach($chatboat_category as $category){ ?>  
                            <option <?php echo ($category->id == $access) ? 'selected' : ''; ?> value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                          <?php } ?>
                          </select>
                       
                      </div>

                      <div class="form-group">
                        <label for="exampleInputFirstName">Question</label>
                        <input type="text" name="question_key" class="form-control" placeholder="Question Name" value="{{$data->question_key}}" readonly>
                      
                      </div>

                      <div class="form-group">
                        <label for="exampleInputFirstName">Answer</label>
                        <textarea name="question_answer" class="form-control" rows="5" readonly>{{$data->question_answer}}</textarea>
                      </div>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer">
                    <a href="{{ url('admin/faq') }}" class="btn btn-danger">Cancel</a>
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