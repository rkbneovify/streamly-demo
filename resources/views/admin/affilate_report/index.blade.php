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
            <h1>Affiliate report</h1>
            <div id="message"></div>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Affiliate report</li>
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
          <table id="affilate-table" class="table table-striped projects">
            <thead>
              <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Plan Name</th>
                <th>Plan Price</th>
                <th>Affilate Souce</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody> 
            @foreach ($affiliate as $key => $aff)
    @php
        try {
            $subscription = \Stripe\Subscription::retrieve($aff->stripe_subscription_id);
            $invoices_id = $subscription->latest_invoice;
            $invoices_retrive = \Stripe\Invoice::retrieve($invoices_id);
            $plans_name = $invoices_retrive->lines->data[0]->plan->nickname;
            $amount = $invoices_retrive->subtotal;
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle the exception here
            // For example, you can log the error or display a message to the user
            $plans_name = 'N/A';
            $amount = 'N/A';
        }
    @endphp
    <tr>
        <td>{{ ++$key }}</td>
        <td>{{ $aff->name }}</td>
        <td>{{ $aff->email }}</td>
        <td>{{ $plans_name }}</td>
        <td>
            @if (is_numeric($amount))
                ${{ $amount/100 }}
            @else
                N/A
            @endif
        </td>
        <td>{{ $aff->affiliate_source }}</td>
        <td>{{ date('Y-m-d h:i:s', $aff->create_date)}}</td>
        <td><button class="btn btn-primary">Pay</button></td>
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
<script type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>   

<script type="text/javascript">
  $(function() {
    $("#affilate-table").DataTable({
      "responsive": true, "lengthChange": true, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });
  });
</script>

@stop

