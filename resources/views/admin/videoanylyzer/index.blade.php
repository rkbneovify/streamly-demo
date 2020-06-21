@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>HLS analyzer</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">HLS analyzer</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
 
    <!-- Main content -->
    <section class="content">
{{-- 
    <a class="btn btn-xs btn-primary" href="{{  url('admin/users/create') }} ">
                                        Add new user
                                    </a> --}}
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Users</h3>
           
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-0">
<table class="table table-striped projects">
  <thead>
      <tr>
          <th style="width: 1%">
              #
          </th>
          <th style="width: 20%">
            url
          </th>
          <th style="width: 20%">
            isStreaming
          </th>
          <th style="width: 30%">
            isHLS
          </th><th style="width: 30%">
            Resolution
          </th>
          
          <th style="width: 8%" class="text-center">
            hls_manifest_type
           </th>

          <th style="width: 8%" class="text-center">
            isEncrypted
           </th>
          {{-- <th style="width: 20%">
          </th>  --}}
      </tr>
  </thead>
  <tbody>
      @foreach($outputs as $key => $output)
      
            <tr data-entry-id="{{ 'g' }}">
            <td>
              #
          </td>
                <td>
                    {{ $output['url'] ?? '' }}
                </td>
                <td> 
                    {{  $output['isStreaming'] ?? ''}}
                    
                </td>
                
                <td>
                    {{ $output['isHLS'] ?? '' }}
                </td>
                <td>
                    {{ $output['variants'] ?? '' }}
                </td>
                <td class="project-state">
                @if ($output['hls_manifest_type']=='MasterPlaylist')
                <span class="badge badge-success">MasterPlaylist</span>
@elseif ($output['hls_manifest_type']=='MediaPlaylist')
<span class="badge badge-danger">MediaPlaylist</span>
@else
unknown
@endif
              
                
              
          </td>
                <td class="project-state">
                @if ($output['isEncrypted']=='Yes')
                <span class="badge badge-success">Yes</span>
@elseif ($output['isEncrypted']=='No')
<span class="badge badge-danger">No</span>
@else
unknown
@endif
              
                
              
          </td>
          <td class="project-actions text-right">
{{--               
              <a  class="btn btn-xs btn-primary" href="{{ url('admin/users/view', $output->id) }}">View</a>
              <a class="btn btn-xs btn-info" href="{{ url('admin/users/edit', $output->id) }}">Edit</a>
              
                        <form action="{{ url('admin/users/destroy', $output->id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                        </form>
                     --}}

             
              
          </td>

            </tr>
        @endforeach
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
@endsection