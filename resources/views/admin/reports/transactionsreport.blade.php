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
    outline: 0px dotted!important;
    outline: 0px auto -webkit-focus-ring-color!important;
  }
  .modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  margin-left: 250px;
  top: 0;
  width: 81%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
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
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Transactions </h1> {{ session()->get( 'info' ) }}
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
      <div class="col-sm-6">
      <?php $count = count($alltransactions); ?>
                @if($count <= 19)
                <h6>Viewing {{$count}} of Page Number {{$page_no -1}}</h6> 
                @else
                <h6>Viewing {{$serial_no}}-{{$serial_no + 19}} of Page Number {{$page_no -1}}</h6> 
                @endif
          </div>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Transaction Details</h3>
           
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="table-responsive">

        <!-- <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name"> -->
          <table id="myTable" class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 2%">
                          S:No
                      </th>
                      <th style="width: 20%">
                          Transactions Date
                      </th>
                      <th style="width: 22%">
                          transaction Type
                      </th>
                      <th style="width: 15%">
                          Name
                      </th>
                      <th style="width: 20%">
                          Email
                      </th> 
                      <th style="width: 13%">
                          Status
                      </th> 
                      <th style="width: 4%">
                          Amount
                      </th> 
                      <th style="width: 4%">
                          View
                      </th> 
                     
                  </tr>
              </thead>
              <tbody>
              <?php 
                if($count <= 19){
                   $countloop = $count;
                }else{
                  $countloop = 20;
                }
                ?>

              @for ($i = 0; $i < $countloop; $i++)
                
                  
                        <tr data-entry-id="">
                        <td>
                        {{ $serial_no + $i }}
                      </td>
                            <td>
                                {{ $alltransactions[$i]['date'] ?? '' }}
                            </td>
                            <td>
                                {{  $alltransactions[$i]['type'] ?? '' }}
                            </td>
                            <td>
                                {{ $alltransactions[$i]['name'] ?? '' }}
                            </td>
                            <td>
                                {{ $alltransactions[$i]['email'] ?? '' }}
                            </td>
                            <td style="background-color: {{ $alltransactions[$i]['color'] }}">
                                {{ $alltransactions[$i]['status'] ?? '' }}
                            </td>
                      <td>
                               {{Cashier::formatAmount($alltransactions[$i]['amount']??'0')}}      
                      </td>
                      <td class="zipcodeBox">
                        <a href="/admin/report/transactionsreport/viewtransaction/{{$alltransactions[$i]['id']}}">
                        <input type="button" id="zipActive" value =View>
                        </a>
                    </td>

                        </tr>
                    
                @endfor
                </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      @if($count <= 19)

      @else
      <div id="paginationbutton" style="text-align: center;">
      @if($serial_no != '1')
      <button type="button"><a href="/admin/report/transactionsreport/ending_before/{{$alltransactions[0]['id']}}/{{$serial_no - 40}}/page_number={{$page_no - 2}}/">Prev</a></button>
      @endif
      @if($has_more = 'true')
      <button type="button"><a href="/admin/report/transactionsreport/starting_after/{{$alltransactions[19]['id']}}/{{$serial_no}}/page_number={{$page_no}}">Next</a></button>
      @endif  
    </div>
    @endif

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
     $('#roles').change(function(){
   table.column( 3 )
                .search( this.value )
                .draw();
   console.log("status changed",this.value);
       // table.draw();
    });

  </script> -->
@endsection
<!-- <script>
function myFunctionSort() {
  var input = document.getElementById("myInputsort");
  //console.log(input.value);
  if(input.value == 'asc'){
    window.location = "/admin/report/transactionreport/ASC"
  }else{
    window.location = "/admin/report/transactionreport/DESC"
  }
}
function funcSearch(){
  var inputvalue = document.getElementById("search").value;
  if(inputvalue){
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
  }else{
    alert("Please enter a value!!");
  }
}
function myFunction() {
  var select, option, input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
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
</script> -->