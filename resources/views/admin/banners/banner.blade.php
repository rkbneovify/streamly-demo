@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Banner</h1> {{ session()->get( 'info' ) }}
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
            <div style="display: flex;">
            <div style="width: 50%">
    <form action="{{ url('admin/banner/add') }}" enctype="multipart/form-data" method="POST">
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
            <input type="file" id="imgInp" name="image" class="form-control" required>
            </div>

        <br><br><input type="submit" style="padding: 10px 30px;" class="btn btn-xs btn-primary" value="Add Slide">
</form>
</div>
<div style="width: 50%; text-align: center;">
<label for="fname">Image preview on device</label><br>
<img style="position: absolute;" id="blah" src="#" alt="" width="180"/>
<img style="z-index: 1; position: relative;" src="/uploads/banner/template_mobile.png" height="380" width="180">
</div>
</div>
</div>
</div></div>
    </section>
    <!-- /.content -->
  </div>
  <script>
imgInp.onchange = evt => {
  const [file] = imgInp.files
  if (file) {
    blah.src = URL.createObjectURL(file)
  }
}
</script>
  <!-- /.content-wrapper -->
 
@endsection