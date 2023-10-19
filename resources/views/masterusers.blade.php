@extends('master')
@section('stylesheets')
<link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    @stop
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Master Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Master Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session()->get('error') }}
        </div>
        @elseif(session()->has('info'))
        <div class="alert alert-success alert-dismissible ">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ session()->get('info') }}
        </div>
        @endif
            <div class="card-header">
                <h3 class="card-title">Master User Information</h3>
            </div>
            <div class="card-body">
                <table id="masterTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>User Email</th>
                            <th>Register By</th>
                            <th>Status</th>
                            <th>Resend Verification link</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $user->user_email }}</td>
                            <td>{{ $user->register_by }}</td>
                            @if($user->email_verified_at == null)
                            <td style="color: red;">Inactive</td>
                            <td>
                            <a href="/salesuser/resendVerificationEmail/{{$user->user_email}}">Click Here</a>
                            </td>
                            @else
                            <td style="color: green;">Active</td>
                            <td>NA</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody> -->
                </table>
            </div>
        </div>
    </section>
</div>
@endsection
@section('scripts')
    <script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script>   
    $(document).ready(function(){

      // DataTable
      $('#masterTable').DataTable({
        // pageLength: 100,
         processing: true,
         serverSide: true,
         ajax: "{{url('/salesuser/masteruserpagination') }}",
         columns: [
            { data: 'id' },
            { data: 'user_email' },
            { data: 'register_by' },
            // { data: 'email_verified_at' },
            {
            data: 'email_verified_at',
            render: function (data) {
                if(data == null){
                   return '<span style="color: red;">Inactive</span>';
                }else{
                    return '<span style="color: green;">Active</span>';
                }
                // return data == null ? 'Inactive' : 'Active';
            }
        },
        {
            data: 'email_verified_at',
            render: function (data, type, row) {
                if(data == null){
                   return '<a href="/salesuser/resendVerificationEmail/' + row.user_email + '">Click Here</a>';
                }else{
                    return '<span>NA</span>';
                }
                // return data == null ? 'Inactive' : 'Active';
            }
        }
       
            
         ],
        // columnDefs: [
        // {       
        //     "render": function(data, type, row) {
        //         if (row.status==1) {
        //             return '<span class="dotgreen"></span> Active';
        //         }
        //         else if (row.status==0) {
        //             return '<span class="dotred"></span> Inactive';
        //         }
        //         else {
        //             return '<span class="label label-default">unknown</span>';
        //         }
        //      }, "targets": [4]
        // }
        // ]
      });


       // Check all 
//    $('#checkall').click(function(){
//       if($(this).is(':checked')){
//          $('.update_check').prop('checked', true);
//       }else{
//          $('.update_check').prop('checked', false);
//       }
//    });

//    $(document).on('click', '.update_record', function(){
//         var id = [];var status;
//         if(confirm("Are you sure you want to Update this data?"))
//         {
//             $('.update_check:checked').each(function(){
//                 id.push($(this).val());
//             });
//             if(id.length > 0)
//             {   
//               status= this.id;
//                 $.ajax({
//                     url:"{{url('admin/zipcode/updateall') }}",
//                     method:"get",
//                     data:{id:id,status:status},
//                     success:function(data)
//                     {
//                         alert(data.message);
//                         console.log(data.message);
//                         $('#empTable').DataTable().ajax.reload();
//                     },
//           error: function (xhr, status, error) {
//             alert(xhr.responseJSON.message);
//           //   $.each(xhr.responseJSON.errors, function (key, item) 
//           // {
//           //   $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
//           // });
//           xhr.responseJSON.errors!== undefined?console.table(xhr.responseJSON.errors):console.table(xhr);
//           }
//                 });
//             }
//             else
//             {
//                 alert("Please select atleast one checkbox");
//             }
//         }
//     });



    });
  </script>
@stop