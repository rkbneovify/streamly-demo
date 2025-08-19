<?php
use Laravel\Cashier\Cashier;
?>
@extends('master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Customer Invoices</h1> {{ session()->get('info') }}
                    </div>
                    <!-- <div class="form-group">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <label><strong>Filter by Userrole :</strong></label>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <select name="roles" id="roles" class="form-control">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 <option>choose user role</option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <option value=>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      active
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      </option>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </select>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </div> -->
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

            <div class="container">


                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subscription Id</th>
                                <th>Status</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Invoice</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subscriptions->data as $index => $sub)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $sub->id }}</td>
                                    <td>
                                        <span
                                            class="badge 
                                @if ($sub->status === 'active') bg-success 
                                @elseif($sub->status === 'canceled') bg-danger 
                                @else bg-secondary @endif">
                                            {{ ucfirst($sub->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $sub->items->data[0]->plan->nickname ?? 'N/A' }}</td>
                                    <td>
                                        ${{ number_format($sub->items->data[0]->plan->amount / 100, 2) }}
                                        / {{ $sub->items->data[0]->plan->interval }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::createFromTimestamp($sub->start_date)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::createFromTimestamp($sub->current_period_end)->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        @if (!empty($sub->latest_invoice))
                                            @php
                                                $invoice = \Stripe\Invoice::retrieve($sub->latest_invoice);
                                            @endphp
                                            <a href="{{ $invoice->hosted_invoice_url }}" class="btn btn-sm btn-link"
                                                target="_blank">
                                                View Invoice
                                            </a>
                                        @else
                                            <span class="text-muted">No invoice</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No subscriptions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Controls -->
                <nav>
                    <ul class="pagination justify-content-center">
                        @if (!empty($subscriptions->data) && request('page', 1) > 1)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ route('admin.report.subscription.invoices', [
                                        'page' => request('page', 1) - 1,
                                        'ending_before' => $subscriptions->data[0]->id,
                                        'id' => $id,
                                    ]) }}">
                                    Previous
                                </a>
                            </li>
                        @endif

                        @if ($subscriptions->has_more)
                            <li class="page-item">
                                <a class="page-link"
                                    href="{{ route('admin.report.subscription.invoices', [
                                        'page' => request('page', 1) + 1,
                                        'starting_after' => end($subscriptions->data)->id,
                                        'id' => $id,
                                    ]) }}">
                                    Next
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
