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

    .input-container {
        position: relative;

    }

    .btn-clear {
        cursor: pointer;
        position: absolute;
        top: 50%;
        right: 15px;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 30px;
        color: #888;
        display: none;
    }

    .btn-clear:hover {
        color: #000;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Transactions </h1> {{ session()->get('info') }}
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

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Transaction Details</h3>


            </div>
            <div class="card-header">

                <div class="row">
                    <div class="col-md-3">
                        @php
                        $dates = explode(' - ', request()->input('daterange', ''));
                        if (count($dates) == 2) {
                        $formatted =
                        \Carbon\Carbon::createFromFormat('Y-m-d', $dates[0])->format('m/d/Y') .
                        ' - ' .
                        \Carbon\Carbon::createFromFormat('Y-m-d', $dates[1])->format('m/d/Y');
                        } else {
                        $formatted = '';
                        }

                        @endphp
                        <input type="text" class="form-control" name="daterange" value="{{ $formatted }}">
                    </div>
                    <div class="col-md-6">
                        {{-- <div class="input-conatiner">
                                <input type="text" name="search" id="search" value="{{ Request()->search }}"
                        class="form-control" placeholder="Search by name, email">
                        <span class="btn-clear" id="btnClear">×</span>
                    </div> --}}
                </div>
            </div>

        </div>

        <div id="user-data">
            @include('admin.reports.table')
        </div>

        <!-- /.card-body -->
</div>
<!-- /.card -->


</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <script>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $('#roles').change(function() {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    table.column(3)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        .search(this.value)
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        .draw();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    console.log("status changed", this.value);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    // table.draw();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                });
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script> -->
@endsection
@section('scripts')
<script>
    $(function() {
        $('#btnClear').hide();
        $('input[name="daterange"]').daterangepicker({
                opens: 'left'
            },

            function(start, end, label) {
                // console.log("A new date range was chosen: " + start.format('YYYY-MM-DD') + ' to ' + end
                //     .format('YYYY-MM-DD'));
                let url = new URL(window.location.href);
                url.searchParams.delete('page');
                url.searchParams.delete('starting_after');
                url.searchParams.delete('ending_before');
                url.searchParams.set('daterange', start.format('YYYY-MM-DD') + ' - ' + end.format(
                    'YYYY-MM-DD'));
                window.location.href = url.toString();
            });
    });

    $('input[name="daterange"]').on('cancel.daterangepicker', function(ev, picker) {

        let url = new URL(window.location.href);
        url.searchParams.delete('page');
        url.searchParams.delete('starting_after');
        url.searchParams.delete('ending_before');
        url.searchParams.delete('daterange');
        window.location.href = url.toString();
    });

    $('input[name="search"]').on('keyup', function() {
        let search = $('input[name="search"]').val();
        if (search.length > 0) {
            $('#btnClear').show();
        }
        fetchData();
    });

    $('#btnClear').click(function() {

        $('input[name="search"]').val('');
        fetchData();
        $('#btnClear').hide();

    })

    function fetchData(page = 1) {

        let search = $('input[name="search"]').val();


        $.ajax({
            url: location.href + "?page=" + page + "&search=" + search,
            success: function(data) {
                $('#user-data').html(data);
            }
        });
    }
</script>
@endsection
<!-- <script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
    {{-- @endsection --}}
        <
        !-- < script >
        function myFunctionSort() {
            var input = document.getElementById("myInputsort");
            //console.log(input.value);
            if (input.value == 'asc') {
                window.location = "/admin/report/transactionreport/ASC"
            } else {
                window.location = "/admin/report/transactionreport/DESC"
            }
        }

    function funcSearch() {
        var inputvalue = document.getElementById("search").value;
        if (inputvalue) {
            var input, filter, table, tr, td, i, txtValue;
            input = inputvalue;
            filter = input.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[3];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        } else {
            alert("Please enter a value!!");
        }
    }

    function myFunction() {
        var select, option, input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < script tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[2];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</> -->