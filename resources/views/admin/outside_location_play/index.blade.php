@extends('master')
@section('stylesheets')
<link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowgroup/1.0.2/css/rowGroup.dataTables.min.css" rel="stylesheet" type="text/css" />



    @stop
@section('content')
<style>.table th,td {
    text-align: center;
  }
  </style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Outside location play</h1> {{ session()->get( 'info' ) }}
            <div id="message"></div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Outside location play</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
 
    <!-- Main content -->
    <section class="content">

    
      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Outside location play</h3>
          {{-- <button id="btn-delete">Delete First Row</button> --}}
        
           
        </div>
        <div class="card-body p-0">
          <table id="data-table" class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 10%">
                          User Name
                      </th><th style="width: 10%">
                          Zipcode
                      </th>

                      <th style="width: 20%">
                        Address
                    </th>
                      <th style="width: 10%">
                          Device info
                      </th>
                      
                      <th style="width: 20%" class="text-center">
                          Coordinates(Lat,lon)
                      </th>
                      <th style="width: 20%" class="text-center">
                        Timestamp
                      </th>
                      <th style="width: 10%">
                      </th>
                  </tr>
              </thead>          </table>
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
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>  
 <script src="https://cdn.datatables.net/rowgroup/1.0.2/js/dataTables.rowGroup.min.js"></script>
<script>
 
 
$(function() {
  $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
  var table = $('#data-table').DataTable({
      pageLength: 100,
        processing: true,
        serverSide: true,  ajax: "{{  url('admin/Outsidelocationplay/data') }}",
   

// 'zipcode',
// 'address',
// 'device_info',
// 'latitude',
// 'longitude',
// 'timestamp'
        columns: [
            { data: 'DT_RowIndex', name: 'id'},
            { data: 'user', name: 'users' },
            { data: 'zipcode', name: 'zipcode' },
            { data: 'address', name: 'address' },
            { data: 'device_info', name: 'device_info' },
            { data: 'coordinates', name: 'coordinates' ,orderable:false},

            { data: 'timestamp', name: 'timestamp' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],  order: [[1, 'asc']],rowGroup: {
            dataSrc: 'user',
            startRender: function (rows, group) {
            return group + ' (' + rows.count() + ' rows)';
        }
        }
        // columnDefs: [
        // {       
        //     "render": function(data, type, row) {
        //         if (row.is_active==1) {
        //             return '<span class="dotgreen"></span> Active';
        //         }
        //         else if (row.is_active==0) {
        //             return '<span class="dotred"></span> Inactive';
        //         }
        //         else {
        //             return 'unknown';
        //         }
        //      }, "targets": [4]
        // }
        // ]
    });

//  $('body').on('click', '#btn-delete', function(){
//   table
//         .row( $(this).parents('tr') )
//         .remove()
//         .clearPipeline().draw();
// });
});
</script>
@stop
