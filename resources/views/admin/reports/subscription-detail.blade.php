<?php
use Laravel\Cashier\Cashier;
?>
@extends('master')
@section('content')
    <style>
        .divfilter {
            margin: 0px 8px 8px 8px;
            text-align: end;
        }

        #myBtn {
            background: transparent;
            border: none;
            color: #fff;
        }

        #myBtn2 {
            background: transparent;
            border: none;
            color: #fff;
        }

        #myBtn3 {
            background: transparent;
            border: none;
            color: #fff;
        }

        #myBtn4 {
            background: transparent;
            border: none;
            color: #fff;
        }

        button:focus {
            outline: 0px dotted !important;
            outline: 0px auto -webkit-focus-ring-color !important;
        }

        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            margin-left: 250px;
            top: 0;
            width: 81%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.4);
            /* Black w/ opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            overflow: auto;
            height: 35vw;
        }

        /* The Close Button */
        .close2 {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close2:hover,
        .close2:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .close {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .close3 {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close3:hover,
        .close3:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .close4 {
            text-align: end;
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close4:hover,
        .close4:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        #zipActive {
            font-size: 12px;
        }

        .alignText {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .centerItem {
            text-align: center;
        }

        .flexItems {
            display: flex;
        }

        .boxItems1 {
            display: grid;
            margin-right: 50px;
            grid-auto-flow: row;
            grid-auto-rows: 1fr;
            color: #000;
        }

        .boxItems2 {
            display: grid;
            margin-left: 50px;
            grid-auto-flow: row;
            grid-auto-rows: 1fr;
            color: #000000ad;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Subscription</h1> {{ session()->get('info') }}
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

            <div class="row">
                <div class="col-md-3">
                    <div class="card shadow-md px-4 py-4">
                        <h3> Status</h3>
                        {{ Str::ucfirst($subscription->status) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-md px-4 py-4">
                        <h3>Type </h3>
                        {{ $subscription->plan->nickname }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-md px-4 py-4">
                        <h3> Start Date</h3>
                        {{ date('d/m/Y', $subscription->created) }}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-md px-4 py-4">
                        <h3> End Date </h3>
                        {{ date('d/m/Y', $subscription->current_period_end) }}

                    </div>
                </div>

            </div>
            {{-- <div class="card">

                <div class="row">
                    <div class="col-md-3 mb-2">Subscription Id</div>
                    <div class="col-md-9 mb-2">{{ $subscription->id }}</div>
                    <div class="col-md-3 mb-2">Created At</div>
                    <div class="col-md-9 mb-2">{{ date('d/m/Y', $subscription->created) }}</div>
                    <div class="col-md-3 mb-2">Ended At</div>
                    <div class="col-md-9 mb-2">{{ date('d/m/Y', $subscription->ended_at) }}</div>
                    <div class="col-md-3 mb-2">Status</div>
                    <div class="col-md-9 mb-2">{{ $subscription->status }}</div>
                </div>


            </div> --}}
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col-md-8">
                            <h3 class="card-title">Customer Detail </h3>
                        </div>
                        <div class="col-md-4 text-right">
                            @if ($subscription->status == 'active')
                                <a href="{{ route('admin.subscriptions.user.cancel', $user->id) }}"
                                    title="Subscription Cancel">Cancel Subscription</a>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">Name</div>
                        <div class="col-md-9 mb-2">{{ $user->name }}</div>
                        <div class="col-md-3 mb-2">Email</div>
                        <div class="col-md-9 mb-2">{{ $user->email }}</div>
                        <div class="col-md-3 mb-2">Zipcode</div>
                        <div class="col-md-9 mb-2">{{ $user->zipcode }}</div>
                        <div class="col-md-3 mb-2">Stripe Mode</div>

                        <div class="col-md-9 mb-2">

                            @if ($customer->livemode)
                                <span class="badge badge-success">Live</span>
                            @else
                                <span class="badge badge-secondary">Testing</span>
                            @endif
                        </div>

                    </div>
                </div>


            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Product/Plan Detail </h3>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">Plan</div>
                        <div class="col-md-9 mb-2">{{ $subscription->plan->id }}</div>
                        <div class="col-md-3 mb-2">Name</div>
                        <div class="col-md-9 mb-2">{{ $subscription->plan->nickname }}</div>
                        <div class="col-md-3 mb-2">Amount</div>
                        <div class="col-md-9 mb-2">${{ $subscription->plan->amount / 100 }}</div>
                        <div class="col-md-3 mb-2">Interval</div>
                        <div class="col-md-9 mb-2">{{ $subscription->plan->interval_count }}
                            {{ $subscription->plan->interval }}</div>
                        <div class="col-md-3 mb-2">Created At</div>
                        <div class="col-md-9 mb-2">{{ date('d/m/Y', $subscription->plan->created) }}</div>
                        <div class="col-md-3 mb-2">Billing Scheme</div>
                        <div class="col-md-9 mb-2">{{ $subscription->plan->billing_scheme }}</div>
                    </div>
                </div>


            </div>
            {{--  --}}
            <div class="container py-4">
                <div class="row">
                    <div class="col-md-4 ">
                        <h3 class="mb-4 fw-bold">Invoice History</h3>
                    </div>
                    <div class="col-md-4 offset-md-4 text-right">
                        <a href="{{ route('admin.report.subscription.invoices', $user->id) }}" class="btn btn-link">View
                            All</a>
                    </div>
                </div>

                <div class="invoice-timeline">
                    @forelse($invoices as $inv)
                        <div class="invoice-card position-relative mb-4">
                            <div class="timeline-dot"></div>
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="mb-1">
                                                Invoice <span class="text-muted small">#{{ $inv['id'] }}</span>
                                            </h5>
                                            <div class="text-muted small mb-2">
                                                {{ \Carbon\Carbon::parse($inv['created'])->format('M d, Y • h:i A') }}
                                            </div>
                                        </div>
                                        <div>
                                            @if ($inv['paid'])
                                                <span class="badge bg-success">Paid</span>
                                            @elseif($inv['status'] === 'open' || $inv['status'] === 'unpaid')
                                                <span class="badge bg-warning text-dark">Unpaid</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($inv['status']) }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="fw-semibold mb-1">
                                        {{ number_format($inv['amount_paid'] / 100, 2) }} {{ $inv['currency'] }}
                                    </p>

                                    @if (count($inv['lines']))
                                        <ul class="small text-muted mb-3 ps-3">
                                            @foreach ($inv['lines'] as $line)
                                                <li>
                                                    {{ $line->description ?? ($line->price->product ?? 'Charge') }}
                                                    — <strong>{{ ($line->amount ?? 0) / 100 }}
                                                        {{ strtoupper($line->currency ?? $inv['currency']) }}</strong>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                    <div>
                                        @if ($inv['hosted_invoice_url'])
                                            <a href="{{ $inv['hosted_invoice_url'] }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary me-2">
                                                View Online
                                            </a>
                                        @endif
                                        @if ($inv['invoice_pdf'])
                                            <a href="{{ $inv['invoice_pdf'] }}" target="_blank"
                                                class="btn btn-sm btn-outline-secondary">
                                                PDF
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">No invoices found.</div>
                    @endforelse
                </div>
            </div>
            {{--  --}}
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
