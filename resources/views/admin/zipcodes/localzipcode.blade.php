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
              <h1>Local zipcode</h1> {{ session()->get( 'info' ) }}
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">zipcode</li>
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
            <h3 class="card-title">zipcode</h3>
  
            <div class="card-tools">
            
                <a class="btn btn-xs btn-primary" href="{{  url('admin/zipcode/map') }} ">
                  See on map
              </a>
                      </div>
          </div>
          <div class="card-body p-0">
            <table id="" class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            #
                        </th>
                        <th style="width: 20%">
                        zipcode
                        </th>
                        </th><th style="width: 20%">
                        status
                        </th>
                        <th>Action</th>
                        <!-- <th>Check All <input type="checkbox" class='checkall' id='checkall'><input type="button" class='update_record' id="1" value='Activate selected' ><input type="button" class='update_record' id="0" value='InActivate selected' ></th> -->
    
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
    <script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script>   
    $(document).ready(function(){

      // DataTable
      $('#empTable').DataTable({
        // pageLength: 100,
         processing: true,
         serverSide: true,
         ajax: "",
         columns: [
            { data: 'id' },
            { data: 'city' },
            { data: 'zipcode' },
            { data: 'state' },
            { data: 'status' },
        { data: 'action', orderable: false, searchable: false },
        { data: 'action2' , orderable: false, searchable: false},
            
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
        }
        ]
      });


       



    });
  </script>
    @stop
 
