@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Slide</h1> {{ session()->get( 'info' ) }}
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
    <section>
    <div class="card">
        <div class="card-header">
        <div>
    <form action="{{ url('admin/slide/add') }}" enctype="multipart/form-data" method="POST">
        <input type="hidden" name="_method" value="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label for="fname">Enter Heading:</label><br>
          <input type="text" id="heading" name="heading" value="" required><br>
        <label for="lname">Enter Description:</label><br>
        <textarea rows="4" cols="50" id="description" name="description" required></textarea><br>
        <label for="lname">Status:</label><br>
            <select name="status" id="status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
            <div class="form-group">
            <label for="lname">Image Upload:</label><br>
            <input type="file" name="image" class="form-control" required>
            </div>

        <br><br><input type="submit" style="padding: 10px 30px;" class="btn btn-xs btn-primary" value="Add Slide">
</form>
</div>
</div></div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
@endsection