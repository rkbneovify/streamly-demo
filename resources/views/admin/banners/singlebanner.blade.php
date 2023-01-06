@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Banner Details</h1> {{ session()->get( 'info' ) }}
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
        <div style="display: flex;" class="card-header">
        <div style="width: 50%;">
    <form action="{{ url('admin/banner/update', $bannerUpdate[0]->id) }}" method="POST">
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <label for="fname">Heading:</label><br>
          <input type="text" id="heading" name="heading" value= "{{ $bannerUpdate[0]->text }}" ><br>
        <label for="lname">Description:</label><br>
        <textarea rows="4" cols="50" id="description" name="description">{{ $bannerUpdate[0]->description }}</textarea><br>
        <label for="lname">Status:</label><br>
        @if( $bannerUpdate[0]->status == 'active' )
            <select name="status" id="status">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
        
        @else
            <select name="status" id="status">
              <option value="inactive">Inactive</option>
              <option value="active">Active</option>
            </select>
        
        @endif
        <br><br><input type="submit" style="padding: 10px 30px;" class="btn btn-xs btn-primary" value="Update">
</form>
</div><div style="width: 50%;">
<label for="fname">Image view on device:</label><br>
<div style="text-align: center;">
<img style="position: absolute;" src="/uploads/banner/{{$bannerUpdate[0]->image}}" width="180">
        <img style="z-index: 1; position: relative;" src="/uploads/banner/template_mobile.png" height="380" width="180">
</div>
</div>
</div></div>
<!-- <div class="form-group">
              <label for="exampleInputFile">File input</label>
              <div class="input-group">
                <div class="custom-file">
                  <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                  <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                </div>
                <div class="input-group-append">
                  <span class="input-group-text" id="">Upload</span>
                </div>
              </div>
            </div> -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
@endsection