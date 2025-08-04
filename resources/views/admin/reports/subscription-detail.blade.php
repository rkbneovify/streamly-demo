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
                        {{ $subscription->status }}
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
                        <h3> End Date</h3>
                        {{ date('d/m/Y', $subscription->ended_at) }}
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
                    <h3 class="card-title">Customer Detail </h3>

                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">Name</div>
                        <div class="col-md-9 mb-2">{{ $user->name }}</div>
                        <div class="col-md-3 mb-2">Email</div>
                        <div class="col-md-9 mb-2">{{ $user->email }}</div>
                        <div class="col-md-3 mb-2">Zipcode</div>
                        <div class="col-md-9 mb-2">{{ $user->zipcode }}</div>

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
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
