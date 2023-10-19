@extends('master')
@section('stylesheets')
@section('content')
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faq Add</title>
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
                <h1>Faq Add</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="#">Home</a></li>
                  <li class="breadcrumb-item active">Faq Add</li>
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
               
                  <form action="{{ url('admin/faq/store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                    <div class="card-body">

                       <div class="form-group">
                        <label for="exampleInputFirstName">Category</label>
                        <select class="form-control" name="faq_category">
                          <option value="">Please Select Faq category</option>
                          <?php foreach($chatboat_category as $category){ ?>  
                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                          <?php } ?>
                          </select>
                        @error('faq_category')
                          <div class="error_msg btn-sm text-danger">{{ $message }}</div>
                        @enderror
                      </div>

                      <div class="form-group">
                        <label for="exampleInputFirstName">Question</label>
                        <input type="text" name="question_key" class="form-control" placeholder="Question Name" value="" >
                        @error('question_key')
        						      <div class="error_msg btn-sm text-danger">{{ $message }}</div>
            						@enderror
                      </div>

                      <div class="form-group">
                        <label for="exampleInputFirstName">Answer</label>
                        <textarea name="question_answer" class="form-control" rows="5"></textarea>
                        
                        @error('question_answer')
                          <div class="error_msg btn-sm text-danger">{{ $message }}</div>
                        @enderror
                      </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                      <button type="submit" class="btn btn-primary">Save</button>
                      <a href="{{ url('/admin/faq') }}" class="btn btn-danger">Cancel</a>
                    </div>
                  </form>
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
