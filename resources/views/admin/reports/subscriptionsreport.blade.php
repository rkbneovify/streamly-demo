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

        .heightBox {
            height: 30px;
            font-size: 14px;
        }

        .fontboxWeight {
            font-weight: 400 !important;
        }
    </style>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>All Subscriptions</h1>
                        {{ session()->get('info') }}
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
                    <h3 class="card-title">Subscriptions</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="table-responsive">

                    <!-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> -->
                    <table id="myTable" class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 2%">
                                    Sl:No
                                </th>
                                <th style="width: 25%">
                                    Plan
                                </th>


                                <th style="width: 5%">
                                    Email
                                </th>
                                <th style="width: 5%">
                                    Zipcode
                                </th>
                                <th style="width: 20%">
                                    Status
                                </th>
                                <th style="width: 25%">
                                    Start Date
                                </th>
                                <th style="width: 25%">
                                    Expiration Date
                                </th>

                                {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                                <!-- <th style="width: 20%">
                                                                                                                                                                                                                                                                                              </th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transaction as $key => $item)
                                <tr>
                                    <td>{{ $key + $transaction->firstItem() }}</td>
                                    <td>{{ $item->plan_name }} <br> {{ $item->plan_desc }} </td>
                                    <td>{{ $item->user->email }}</td>
                                    <td>{{ $item->user->zipcode }}</td>
                                    <td>{{ $item->stripe_status }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->trial_ends_at ?? ($item->renews_at ?? $item->ends_at) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="card-footer text-body-secondary">
                        <div class="container px-5 mt-5">
                            <div class="row justify-content-end">
                                {{ $transaction->links() }}
                            </div>
                        </div>
                    </div>
                    {{--  --}}
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
