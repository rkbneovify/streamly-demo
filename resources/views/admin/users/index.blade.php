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
              <h1>Users</h1> {{ session()->get( 'info' ) }}
              <div id="message"></div>
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
      <section class="content">
  
        <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Users</h3>
          {{-- <button id="btn-delete">Delete First Row</button> --}}
        
           
          <div class="card-tools">
            {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button> --}}
              <a class="btn btn-xs btn-primary" href="{{  url('admin/users/create') }} ">
                Add new user
            </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </div>
        </div>
          <div class="table-responsive">
            <table id="empTable" class="table table-striped projects">
                <thead>
                    <tr>
                        <th style="width: 1%">
                            #
                        </th>
                        <th style="width: 20%">
                        Email
                        </th>
                        <th style="width: 20%">
                        Name
                        </th>
                        <th style="width: 20%">
                        Role
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
    <script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script>   
    $(document).ready(function(){

        $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

      // DataTable
      $('#empTable').DataTable({
        pageLength: 100,
         processing: true,
         serverSide: true,
         ajax: "{{url('admin/user/data') }}",
         columns: [
            { data: 'id' },
            { data: 'email' },
            { data: 'name' },
            { data: 'role' },
            { data: 'status' },
        { data: 'action',name: 'action', orderable: false, searchable: false },

        {data: 'action2', name: 'action2', orderable: false, searchable: false},
            
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
        },{       
            "render": function(data, type, row,meta) {
                return meta.row+1;
             }, "targets": [0]
        },{       
            "render": function(data, type, row) {
                // console.log("role",data[0].display_name);
                return data[0].display_name?data[0].display_name:'No user role';
             }, "targets": [3]
        }
        ]
      });


    $('body').on('click', '.deleteItem', function () {
     
     var Item_id = $(this).data("id");
     var $tr = $(this).closest('tr');
    //  alert(Item_id);exit;
     if (confirm('Are you sure you want to delete this?')) {
     $.ajax({
         type: "DELETE",
         url: "{{ url('admin/users/destroy') }}"+'/'+Item_id,
         success: function (data) {
            $tr.find('td').fadeOut(1000,function(){ 
                            $tr.remove();                 
                        });

                        alert(JSON.stringify(data));
                            $('#message').html(JSON.stringify(data)).fadeIn('slow');
     
     $('#message').delay(5000).fadeOut('slow');   
         },
         error: function (data) {
             console.log('Error:', data);
         }
     }); }
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
                    url:"{{url('admin/users/updateall') }}",
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
 
