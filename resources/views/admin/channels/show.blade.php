@extends('master')

@section('stylesheets') 
<link href="{{ asset('dist/css/select2.min.css') }}" rel="stylesheet">@stop
@section('content')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User view</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">View channel</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">General</h3>

              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fas fa-minus"></i></button>
              </div>
            </div>
          
            <div class="card-body">
            {{-- <div class="form-group">
                            <label for="name" class="col-md-4 control-label">Genre</label>
                            
                                    <label class="label label-success">{{ $channel->genre->name?? "unknown" }}</label>
                                
                        </div> --}}
              <div class="form-group">
                <label for="inputName">Name</label>
                <input type="text" id="inputName" class="form-control" value="{{ $channel->name }}" disabled>
              </div>
              <div class="form-group">
                <label for="inputurl">url</label>
                <input type="text" id="inputurl" class="form-control" value="{{ $channel->url }}" disabled>
              </div>
              <div class="form-group{{ $errors->has('genres') ? ' has-error' : '' }}">
              <label for="genres" class="col-md-4 control-label">genres</label>
              
<select name="genres[]" id="genres" class="form-control select2" multiple="multiple" disabled>
@foreach($genres as $id => $genre)
                        @if(in_array($id, $arrVal))
                        <option value="{{ $id }}" selected="true">{{ $genre }}</option>
                        @else
                        <option value="{{ $id }}">{{ $genre }}</option>
                        @endif 
                    @endforeach
                </select>
         

              @if ($errors->has('genres'))
              <span class="help-block">
                <strong>{{ $errors->first('genres') }}</strong>
              </span>
              @endif

            </div>
              <div class="form-group">
                <label for="inputStatus">Status</label>
                @if ($channel->status===1)
                <input type="text" id="inputurl" class="form-control" value="Active " disabled>
@elseif ($channel->status===0)
<input type="text" id="inputurl" class="form-control" value="Inactive " disabled>
@else
<input type="text" id="inputurl" class="form-control" value="Unknown " disabled>
@endif
                
              </div>
              <div class="form-group">
                <label for="inputLogo">Logo</label>
                @if ($channel->image!='')
              <a href="{{asset("/uploads/channellogo/".$channel->image)}}" target="_blank"><img src='{{asset("/uploads/channellogo/".$channel->image)}}' width="70px" height="70px"></a>
@else

<input type="text" id="inputurl" class="form-control" value="No logo found " disabled>
@endif
                
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      <!-- </div>
      <div class="row">
        <div class="col-12">
          <a href="#" class="btn btn-secondary">Cancel</a>
          <input type="submit" value="Create new Porject" class="btn btn-success float-right">
        </div>
      </div> -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('scripts')

<script  type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>   
<script>
$('.select2').select2();
</script>
@stop
