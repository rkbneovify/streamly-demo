@extends('master')
@section('stylesheets')
<link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    @stop
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Channel</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Channel</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <a class="btn btn-xs btn-primary" href="{{  url('admin/channelstatus/create') }} ">
                                        Add new Channel
                                    </a>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Channel</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-0">
          <table id="data-table" class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                      name
                      </th>
                      <th style="width: 20%">
                      url
                      </th>

                      <th style="width: 20%">
                      genres
                      </th>
                      <th style="width: 20%">
                      status
                      </th>
                      
                      <th style="width: 20%">
                        image
                        </th>
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
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

@section('scripts')

<script type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>   
<script>
$('.select2').select2();
</script>
<script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>   
<script>
$(function() {
    $('#data-table').DataTable({
      pageLength: 100,
        processing: true,
        serverSide: true,
        ajax: '{!! url('admin/channelstatus/channeldata') !!}',
    
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name',orderable: true },
            { data: 'url', name: 'url' },
            { data: 'fullName', name: 'fullName' ,searchable: true},
            
            { data: 'status', name: 'status' ,orderable:false},
            { data: 'photo', name: 'photo',orderable: false, searchable: false },
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        columnDefs: [
        {       
            "render": function(data, type, row) {
                if (row.status==1) {
                  return '<span class="dotgreen"></span> Active';
                }
                else if (row.status==0) {
                  return '<span class="dotred"></span> Inactive';
                }
                else {
                    return '<span class="label label-default">unknown</span>';
                }
             }, "targets": [4]
        },
        {
    "targets": 2,
    "data": "url",
    "render": function ( data, type, row, meta ) {
      return type === 'display' && data.length > 15 ?
        '<span title="'+data+'">'+data.substr( 0, 12 )+'...</span>' :
        data;
    }
  } 
        
        ]
    });
});
</script>
@stop
