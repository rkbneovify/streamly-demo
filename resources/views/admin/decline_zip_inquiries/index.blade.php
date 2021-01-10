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
            <h1>Decline zip inquiries</h1> {{ session()->get( 'info' ) }}
            <div id="message"></div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">declinezipinquiries</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
 
    <!-- Main content -->
    <section class="content">

    
      <!-- Default box -->
      <div class="card">
        <div class="card-body p-0">
            <table id="data-table" class="table table-striped projects">
        <thead>
            <tr>
                <th>No</th>
                <th>Zip</th>
                <th>Email</th>
                <th>city</th>
                <th>State</th>
                <th>Frequency</th>
                <th>Created</th>
                {{-- <th width="100px">Action</th> --}}
            </tr>
        </thead>
        <tbody>
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
<script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>   

<script type="text/javascript">
  
$(function() {
  $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

  var table = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('admin/declinezipinquiries/data') }}",
        columns: [
            {data: 'id', name: 'id'},
            {data: 'zip', name: 'zip'},
            {data: 'email', name: 'email'},
            {data: 'city', name: 'city'},
            {data: 'state', name: 'state'},
            {data: 'frequency', name: 'frequency'},
            {data: 'updated_at', name: 'updated_at' },
            // {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });
    
  });
</script>

@stop

