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
        <div class="card-body p-0">
          <table id="data-table" class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                          User Name
                      </th>
                      <th style="width: 20%">
                          Role
                      </th>
                      <th style="width: 30%">
                          Email
                      </th>
                      
                      <th style="width: 8%" class="text-center">
                          Status
                      </th>
                      <th style="width: 20%">
                      </th>
                  </tr>
              </thead>
              {{-- <tbody>
                  @foreach($users as $key => $user)
                  
                        <tr data-entry-id="{{ $user->id }}">
                        <td>
                          #
                      </td>
                            <td>
                                {{ $user->name ?? '' }}
                            </td>
                            <td> @foreach ($user->roles as $role)
                                {{  $role->display_name}}
                                @endforeach
                            </td>
                            
                            <td>
                                {{ $user->email ?? '' }}
                            </td>
                            <td class="project-state">
                            @if ($user->is_active===1)
                            <span class="badge badge-success">Active</span>
@elseif ($user->is_active===0)
<span class="badge badge-danger">Inactive</span>
@else
    unknown
@endif
                          
                            
                          
                      </td>
                      <td class="project-actions text-right">
                          
                          <a  class="btn btn-xs btn-primary" href="{{ url('admin/users/view', $user->id) }}">View</a>
                          <a class="btn btn-xs btn-info" href="{{ url('admin/users/edit', $user->id) }}">Edit</a>
                          
                                    <form action="{{ url('admin/users/destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('Are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form>
                                

                         
                          
                      </td>

                        </tr>
                    @endforeach
                </tbody> --}}
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
  //
// Pipelining function for DataTables. To be used to the `ajax` option of DataTables
//
$.fn.dataTable.pipeline = function ( opts ) {
    // Configuration options
    var conf = $.extend( {
        pages: 5,     // number of pages to cache
        url: '',      // script url
        data: null,   // function or object with parameters to send to the server
                      // matching how `ajax.data` works in DataTables
        method: 'GET' // Ajax HTTP method
    }, opts );
 
    // Private variables for storing the cache
    var cacheLower = -1;
    var cacheUpper = null;
    var cacheLastRequest = null;
    var cacheLastJson = null;
 
    return function ( request, drawCallback, settings ) {
        var ajax          = false;
        var requestStart  = request.start;
        var drawStart     = request.start;
        var requestLength = request.length;
        var requestEnd    = requestStart + requestLength;
         
        if ( settings.clearCache ) {
            // API requested that the cache be cleared
            ajax = true;
            settings.clearCache = false;
        }
        else if ( cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper ) {
            // outside cached data - need to make a request
            ajax = true;
        }
        else if ( JSON.stringify( request.order )   !== JSON.stringify( cacheLastRequest.order ) ||
                  JSON.stringify( request.columns ) !== JSON.stringify( cacheLastRequest.columns ) ||
                  JSON.stringify( request.search )  !== JSON.stringify( cacheLastRequest.search )
        ) {
            // properties changed (ordering, columns, searching)
            ajax = true;
        }
         
        // Store the request for checking next time around
        cacheLastRequest = $.extend( true, {}, request );
 
        if ( ajax ) {
            // Need data from the server
            if ( requestStart < cacheLower ) {
                requestStart = requestStart - (requestLength*(conf.pages-1));
 
                if ( requestStart < 0 ) {
                    requestStart = 0;
                }
            }
             
            cacheLower = requestStart;
            cacheUpper = requestStart + (requestLength * conf.pages);
 
            request.start = requestStart;
            request.length = requestLength*conf.pages;
 
            // Provide the same `data` options as DataTables.
            if ( typeof conf.data === 'function' ) {
                // As a function it is executed with the data object as an arg
                // for manipulation. If an object is returned, it is used as the
                // data object to submit
                var d = conf.data( request );
                if ( d ) {
                    $.extend( request, d );
                }
            }
            else if ( $.isPlainObject( conf.data ) ) {
                // As an object, the data given extends the default
                $.extend( request, conf.data );
            }
 
            return $.ajax( {
                "type":     conf.method,
                "url":      conf.url,
                "data":     request,
                "dataType": "json",
                "cache":    false,
                "success":  function ( json ) {
                    cacheLastJson = $.extend(true, {}, json);
 
                    if ( cacheLower != drawStart ) {
                        json.data.splice( 0, drawStart-cacheLower );
                    }
                    if ( requestLength >= -1 ) {
                        json.data.splice( requestLength, json.data.length );
                    }
                     
                    drawCallback( json );
                }
            } );
        }
        else {
            json = $.extend( true, {}, cacheLastJson );
            json.draw = request.draw; // Update the echo for each response
            json.data.splice( 0, requestStart-cacheLower );
            json.data.splice( requestLength, json.data.length );
 
            drawCallback(json);
        }
    }
};
 
// Register an API method that will empty the pipelined data, forcing an Ajax
// fetch on the next draw (i.e. `table.clearPipeline().draw()`)
$.fn.dataTable.Api.register( 'clearPipeline()', function () {
    return this.iterator( 'table', function ( settings ) {
        settings.clearCache = true;
    } );
} );
 
$(function() {
  $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
  var table = $('#data-table').DataTable({
      pageLength: 100,
        processing: true,
        serverSide: true,
        ajax: $.fn.dataTable.pipeline({
                url: '{{ url('admin/user/data') }}',
                pages: 20 // number of pages
            }),
        columns: [
            { data: 'DT_RowIndex', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'roles', name: 'roles' },
            { data: 'email', name: 'email' },
            { data: 'is_active', name: 'is_active' ,orderable:false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        columnDefs: [
        {       
            "render": function(data, type, row) {
                if (row.is_active==1) {
                    return '<span class="dotgreen"></span> Active';
                }
                else if (row.is_active==0) {
                    return '<span class="dotred"></span> Inactive';
                }
                else {
                    return 'unknown';
                }
             }, "targets": [4]
        }
        ]
    });

    $('body').on('click', '.deleteItem', function () {
     
     var Item_id = $(this).data("id");
     var $tr = $(this).closest('tr');
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
//  $('body').on('click', '#btn-delete', function(){
//   table
//         .row( $(this).parents('tr') )
//         .remove()
//         .clearPipeline().draw();
// });
});
</script>
@stop
