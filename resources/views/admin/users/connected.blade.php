@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Device details of {{$allSession[0]->user->name ?? ''}}</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Connected device details</li>
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
          <h3 class="card-title">User: {{$allSession[0]->user->name ?? ''}}</h3><br>
          <h3 class="card-title">Email: {{$allSession[0]->user->email ?? ''}}</h3>
           
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
                          Device id
                      </th>
                      <th style="width: 20%">
                          Device Name
                      </th>
                      <th style="width: 20%">
                      User agent
                      </th>
                      <th style="width: 20%">
                      Created on
                      </th>
                      {{-- <th style="width: 30%">
                          Email
                      </th> --}}
                      
                      {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                      <th>Check All <input type="checkbox" class='checkall' id='checkall'><input type="button" class='update_record' id="0" value='InActivate selected' ></th>
    
                      <th style="width: 20%">
                      </th>
                  </tr>
              </thead>
              <tbody>
                  @foreach($allSession as $key => $user)
                  
                        <tr id='{{$user->id}}' data-entry-id="">
                        <td>
                          #
                      </td>
                      <td>
                       {{ $user->device_id ?? '' }}
                    </td>
                            
                            
                            <td>
                                {{ $user->device_name ?? '' }}
                            </td>
                            <td>
                                {{ $user->useragent ?? '' }}
                            </td>
                            <td>
                                {{ $user->created_on ?? '' }}
                            </td>
                            {{-- <td class="project-state">
                            @if ($user->is_active===1)
                            <span class="badge badge-success">Active</span>
@elseif ($user->is_active===0)
<span class="badge badge-danger">Inactive</span>
@else
    unknown
@endif
                          
                            
                          
                      </td> --}}
                      <td><input type='checkbox' class='update_check' id='delcheck_{{$user->id}}'  value='{{$user->id}}'></td>
                      <td class="project-actions text-right">
                          
                          {{-- <a  class="btn btn-xs btn-primary" href="{{ url('admin/users/connected', $user->users_id) }}">View</a>
                          <a class="btn btn-xs btn-info" href="{{ url('admin/users/connected', $user->users_id) }}">Edit</a> --}}
                          
                                    <form action="{{ url('admin/users/sessiondestroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="access_token" value="{{ $user->access_token }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                                

                         
                          
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
@section('scripts')
<script>

$(function() {
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
            console.log("id",id); 
            if(id.length > 0)
            {   
              
              status= this.id;
                $.ajax({
                    url:"{{url('admin/multisessiondestroy') }}",
                    headers: {'x-csrf-token': '{{ csrf_token() }}'},
                    method:"post",
                    data:{id:id,status:status},
                    success:function(data)
                    { 
                      console.log(data);
                        alert(data.message);
                        // console.log(data.message);
                        // console.log('data',data);
                        $.each(id, function( index, value ) {
                         // alert( index + ": " + value );
                          $('#'+value+'').fadeTo(400, 0, function () { 
                           $(this).remove();
                            });
                       });
                    },
          error: function (xhr, status, error) {
            alert(xhr.responseJSON.message);
            console.log(xhr.responseJSON);
          //   $.each(xhr.responseJSON.errors, function (key, item) 
          // {
          //   $("#errors").append("<li class='alert alert-danger'>"+item+"</li>")
          // });
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