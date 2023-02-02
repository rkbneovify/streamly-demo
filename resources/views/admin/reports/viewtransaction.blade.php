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
            <h1>View Transaction</h1> {{ session()->get( 'info' ) }}
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
    <div class="card">
        <div class="card-header">
          <h3 class="card-title">Payment {{Cashier::formatAmount($singletransaction[0]['amount']??'0', $singletransaction[0]['currency']??'USD')}}</h3>
          <h3 style="float: right; color:{{$singletransaction[0]['color']}}">{{$singletransaction[0]['status']}}</h3>
</div>
<div class="card-header alignText">
    <h3 class="card-title centerItem"><b>Name</b> <br>{{$singletransaction[0]['name']}}</h3>
    <img src="/uploads/icons/line.png" width="">
    <h3 class="card-title centerItem"><b>Last Update</b> <br>{{$singletransaction[0]['date']}}</h3>
    <img src="/uploads/icons/line.png" width="">
    <h3 class="card-title centerItem"><b>Risk Evaluation</b> <br>{{$singletransaction[0]['risk_level']}}</h3>
    <img src="/uploads/icons/line.png" width="">
    <h3 class="card-title centerItem"><b>Payment Method</b> <br><img src="/uploads/icons/creditcard.png" width="40"> ****{{$singletransaction[0]['last4']}}</h3>
</div>
<div style="display: inline-grid;" class="card-header">
    <h3 style="font-weight: bold; margin: 15px 0px;" class="card-title">Payment Details</h3>
    <div class="flexItems">
<div class="boxItems1">
    <h3 style="margin: 5px 0px;" class="card-title">Transaction Id:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">Name:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">Email:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">Transaction At:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">Card Expire Date:</h3>
</div>
<div class="boxItems2">
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['id']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['name']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['email']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['date']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['exp_month']}}/{{$singletransaction[0]['exp_year']}}</h3>
</div>
</div>
    <div style="display:flex; justify-content: space-between;">
    <h3 style="margin: 20px 0px;" class="card-title">Download Recipt -></h3>
    <a target="blank" href="{{$singletransaction[0]['receipt_url']}}">
    <h3 style="margin: 20px 0px;" class="card-title">Click Here To Download</h3>
</a>
</div>
</div>
<div style="display: inline-grid;" class="card-header">
    <h3 style="font-weight: bold; margin: 15px 0px;" class="card-title">Address Details</h3>
<div class="flexItems">
<div class="boxItems1">
    <h3 style="margin: 5px 0px;" class="card-title">City:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">Country:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">line1:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">line2:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">Postal Code:</h3>
    <h3 style="margin: 5px 0px;" class="card-title">State:</h3>
</div>
<div class="boxItems2">
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['address']['country']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['address']['city']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['address']['line1']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['address']['line2']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['address']['postal_code']}}</h3>
    <h3 style="margin: 5px 0px;" class="card-title">{{$singletransaction[0]['address']['state']}}</h3>
</div>
</div>
</div>
</div>
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