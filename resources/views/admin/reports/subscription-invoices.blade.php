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
                        <h1>Invoice History</h1> {{ session()->get('info') }}
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

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice ID</th>
                            <th>Status</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $index => $invoice)
                            <tr>
                                {{-- Row number with pagination offset --}}
                                <td>{{ ($invoices->currentPage() - 1) * $invoices->perPage() + $index + 1 }}</td>

                                <td>{{ $invoice->id }}</td>

                                <td>
                                    <span
                                        class="badge 
                            @if ($invoice->status == 'paid') bg-success
                            @elseif($invoice->status == 'open') bg-primary
                            @elseif($invoice->status == 'draft') bg-secondary
                            @elseif($invoice->status == 'void') bg-dark
                            @elseif($invoice->status == 'uncollectible') bg-danger @endif">
                                        {{ ucfirst($invoice->status) }}
                                    </span>
                                </td>

                                <td>{{ number_format($invoice->amount_due / 100, 2) }}</td>

                                <td>{{ strtoupper($invoice->currency) }}</td>

                                <td>{{ \Carbon\Carbon::createFromTimestamp($invoice->created)->format('d/m/Y H:i') }}</td>

                                <td>
                                    {{-- View invoice (hosted URL) --}}
                                    @if (!empty($invoice->hosted_invoice_url))
                                        <a href="{{ $invoice->hosted_invoice_url }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            View
                                        </a>
                                    @endif



                                    @if (empty($invoice->hosted_invoice_url))
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No invoices found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination links --}}
                <div class="row justify-content-end">
                    <div class="d-flex justify-content-center">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>


        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
