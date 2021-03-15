@extends('master')
@section('stylesheets')
{{-- <link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/css/jquery.dataTables.min.css" integrity="sha512-1k7mWiTNoyx2XtmI96o+hdjP8nn0f3Z2N4oF/9ZZRgijyV4omsKOXEnqL1gKQNPy2MTSP9rIEWGcH/CInulptA==" crossorigin="anonymous" />
    @stop

    @section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>zipcode</h1> {{ session()->get( 'info' ) }}
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
      <a class="btn btn-xs btn-primary" href="{{  url('admin/subscriptions/create') }} ">
                                          Add new zipcode 
                                      </a>
  
        <!-- Default box -->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">zipcode</h3>
  
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                <i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body p-0">
            <table id="empTable" class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            #
                        </th>
                        <th style="width: 20%">
                        city
                        </th>
                        <th style="width: 20%">
                        zipcode
                        </th>
                        <th style="width: 20%">
                        state
                        </th><th style="width: 20%">
                        status
                        </th>
                        <th>Action</th>
                        <th>Check All <input type="checkbox" class='checkall' id='checkall'><input type="button" class='update_record' id="1" value='Activate selected' ><input type="button" class='update_record' id="0" value='InActivate selected' ></th>
    
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
    {{-- <script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous"></script>
    <script>   
    $(document).ready(function(){

      // DataTable
      $('#empTable').DataTable({
        pageLength: 100,
         processing: true,
         serverSide: true,
         ajax: "{{url('admin/zipcode/category2') }}",
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
       // Check all 
   $('#checkall').click(function(){
      if($(this).is(':checked')){
         $('.update_check').prop('checked', true);
      }else{
         $('.update_check').prop('checked', false);
      }
   });

   $(document).on('click', '.update_record', function(){
        var id = [];var status;
        if(confirm("Are you sure you want to Update this data?"))
        {
            $('.update_check:checked').each(function(){
                id.push($(this).val());
            });
            if(id.length > 0)
            {   
              status= this.id;
                $.ajax({
                    url:"{{url('admin/zipcode/updateall') }}",
                    method:"get",
                    data:{id:id,status:status},
                    success:function(data)
                    {
                        alert(data.message);
                        console.log(data.message);
                        $('#empTable').DataTable().ajax.reload();
                    },
          error: function (xhr, status, error) {
            alert(xhr.responseJSON.message);
          //   $.each(xhr.responseJSON.errors, function (key, item) 
          // {
          //   $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
          // });
          xhr.responseJSON.errors!== undefined?console.table(xhr.responseJSON.errors):console.table(xhr);
          }
                });
            }
            else
            {
                alert("Please select atleast one checkbox");
            }
        }
    });

    });
  </script>
    @stop
 
