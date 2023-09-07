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
                    <h1>All Payments Here</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">All Payments</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payments Information</h3>
            </div>
            <div class="card-body">
                <table id="paymentTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>User Email</th>
                            <th>User Name</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <!-- <tbody>
                        @foreach($paylinks as $paylink)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $paylink->email }}</td>
                            <td>{{ $paylink->name }}</td>
                            <td>$ {{ $paylink->amount / 100 }}</td>
                            @if($paylink->status == 0)
                            <td style="color: red;">Not Done</td>
                            @else
                            <td style="color: green;">Done</td>
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
      $('#paymentTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{url('/salesuser/paymentlistpagination') }}",
         columns: [
            { data: 'id' },
            { data: 'email' },
            { data: 'name' },
            // { data: 'amount' },
            {
            data: 'amount',
            render: function (data) {
               return '<span>$ {{ $paylink->amount / 100 }}</span>'
            }
            },
            {
            data: 'status',
            render: function (data) {
                if(data == 1){
                   return '<span style="color: green;">Done</span>';
                }else{
                    return '<span style="color: red;">Not Done</span>';
                }
                // return data == null ? 'Inactive' : 'Active';
            }
          }            
         ],
      });

    });
  </script>
@stop
