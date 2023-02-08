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
.heightBox {
  height: 30px;
  font-size: 14px;
}
.fontboxWeight {
  font-weight: 400!important;
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Subscriptions ({{ $count = count($resultCount['data']) }})</h1> {{ session()->get( 'info' ) }}
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
    <div>
<label class="fontboxWeight" for="timeframe"><b>Select Date Range:</b> Start Date</label>
<input class="heightBox" type="date" id="from" name="timeframe">
<label class="fontboxWeight" for="timeframe">End Date</label>
<input class="heightBox" type="date" id="to" name="timeframe">
<label class="fontboxWeight" for="timeframe">Type</label>
<select class="heightBox" name="" id="valuetf" onchange="">
        <option value="all">All</option>
  <option value="active">New Subscribers</option>
  <option value="canceled">Cancelled Subscribers</option>
</select>
<button class="heightBox" style="padding: 0px 8px;" onclick="timeFrame()">Apply</button>
</div>
</section>
    <div class="row" style="margin: 1px;">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
      <h3>{{ $newuserCount = count($resultCount['newuser']) }}</h3>

        <p>NEW USERS</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag"></i>
      </div>
      <!-- <a href=" https://streamlytv.com/admin/users" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
   <!-- Trigger/Open The Modal -->
<button id="myBtn">View Details 
<i class="fas fa-arrow-circle-right"></i>
</button>
<!-- The Modal -->
<div id="myModal" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div style="display: flex;">
  <p style="color: #000; font-weight: bold; width: 100%;">NEW USERS</p>
    <span class="close">&times;</span>
</div>
    <div>
    <table class="table table-striped projects" style="width: 100%;">
    <thead><tr><th style="width: 20%; color:#000;">S:No</th><th style="width: 30%; color:#000;">Name</th><th style="width: 50%; color:#000;">E-Mail</th></tr></thead>
    <tbody>
    @for ($i = 0; $i < $newuserCount; $i++)
    <tr>
    <td style="color:#000;">{{ $i + 1}}</td>
    <td style="color:#000;">{{ $resultCount['newuser'][$i]['name'] ?? '' }}</td>
    <td style="color:#000;">{{ $resultCount['newuser'][$i]['email'] ?? '' }}</td>
    </tr>
    @endfor
    </tbody>
</table>
  </div>
  </div>

</div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
      <h3>{{ $renewedUsers = count($resultCount['reneweduser']) }}</h3>

        <p>RENEWED USERS</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars"></i>
      </div>
      <!-- <a href=" https://streamlytv.com/admin/channelstatus" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
    <!-- Trigger/Open The Modal -->
<button id="myBtn2">View Details 
<i class="fas fa-arrow-circle-right"></i>
</button>
<!-- The Modal -->
<div id="myModal2" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div style="display: flex;">
  <p style="color: #000; font-weight: bold; width: 100%;">RENEWED USERS</p>
    <span class="close2">&times;</span>
</div>
    <div>
    <table class="table table-striped projects" style="width: 100%;">
    <thead><tr><th style="width: 20%; color:#000;">S:No</th><th style="width: 30%; color:#000;">Name</th><th style="width: 50%; color:#000;">E-Mail</th></tr></thead>
    <tbody>
    @for ($i = 0; $i < $renewedUsers; $i++)
    <tr>
    <td style="color:#000;">{{ $i + 1}}</td>
    <td style="color:#000;">{{ $resultCount['reneweduser'][$i]['name'] ?? '' }}</td>
    <td style="color:#000;">{{ $resultCount['reneweduser'][$i]['email'] ?? '' }}</td>
    </tr>
    @endfor
    </tbody>
</table>
  </div>
  </div>

</div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $terminatedUsers = count($resultCount['terminateduser']) }}</h3>

        <p>TERMINATED USERS</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add"></i>
      </div>
      <!-- <a href=" https://streamlytv.com/admin/subscriptions" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
      <!-- Trigger/Open The Modal -->
<button id="myBtn3">View Details 
<i class="fas fa-arrow-circle-right"></i>
</button>
<!-- The Modal -->
<div id="myModal3" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div style="display: flex;">
  <p style="color: #000; font-weight: bold; width: 100%;">TERMINATED USERS</p>
    <span class="close3">&times;</span>
</div>
    <div>
    <table class="table table-striped projects" style="width: 100%;">
    <thead><tr><th style="width: 20%; color:#000;">S:No</th><th style="width: 30%; color:#000;">Name</th><th style="width: 50%; color:#000;">E-Mail</th></tr></thead>
    <tbody>
    @for ($i = 0; $i < $terminatedUsers; $i++)
    <tr>
    <td style="color:#000;">{{ $i + 1}}</td>
    <td style="color:#000;">{{ $resultCount['terminateduser'][$i]['name'] ?? '' }}</td>
    <td style="color:#000;">{{ $resultCount['terminateduser'][$i]['email'] ?? '' }}</td>
    </tr>
    @endfor
    </tbody>
</table>
  </div>
  </div>

</div>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $overdueUsers = count($resultCount['overdueuser']) }}</h3>

        <p>OVERDUE USERS</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph"></i>
      </div>
      <!-- <a href=" https://streamlytv.com/admin/hlsmonitor" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a> -->
      <!-- Trigger/Open The Modal -->
<button id="myBtn4">View Details 
<i class="fas fa-arrow-circle-right"></i>
</button>
<!-- The Modal -->
<div id="myModal4" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div style="display: flex;">
  <p style="color: #000; font-weight: bold; width: 100%;">OVERDUE USERS</p>
    <span class="close4">&times;</span>
</div>
    <div>
    <table class="table table-striped projects" style="width: 100%;">
    <thead><tr><th style="width: 20%; color:#000;">S:No</th><th style="width: 30%; color:#000;">Name</th><th style="width: 50%; color:#000;">E-Mail</th></tr></thead>
    <tbody>
    @for ($i = 0; $i < $overdueUsers; $i++)
    <tr>
    <td style="color:#000;">{{ $i + 1}}</td>
    <td style="color:#000;">{{ $resultCount['overdueuser'][$i]['name'] ?? '' }}</td>
    <td style="color:#000;">{{ $resultCount['overdueuser'][$i]['email'] ?? '' }}</td>
    </tr>
    @endfor
    </tbody>
</table>
  </div>
  </div>

</div>
    </div>
  </div>
  <!-- ./col -->
</div>
<div style="margin-bottom: 10px;" class="content">
<input type="text" id="search" placeholder="Search by email.." name="search">
<button style="padding: 5px;" onclick="funcSearch()"><i class="fa fa-search"></i></button>
</div>
<div style="display: flex; justify-content: space-between;">
<div class="divfilter">Sort data by subscription date:
<select name="" id="myInputsort" onchange="myFunctionSort()">
<option value="">Sort</option>
      <option value="desc">Descending</option>
  <option value="asc">Ascending</option>
</select>
</div>
<div class="divfilter">Filter With Subscription Type:
<select name="" id="myInput" onchange="myFunction()">
        <option value="">All</option>
  <option value="active">Active</option>
  <option value="canceled">Cancelled</option>
  <option value="trialing">Trialing</option>
</select>
</div>
</div>
<script>
  // Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

var modal2 = document.getElementById("myModal2");

// Get the button that opens the modal
var btn2 = document.getElementById("myBtn2");

// Get the <span> element that closes the modal
var span2 = document.getElementsByClassName("close2")[0];

// When the user clicks the button, open the modal 
btn2.onclick = function() {
  modal2.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
  modal2.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
  }
}

var modal3 = document.getElementById("myModal3");

// Get the button that opens the modal
var btn3 = document.getElementById("myBtn3");

// Get the <span> element that closes the modal
var span3 = document.getElementsByClassName("close3")[0];

// When the user clicks the button, open the modal 
btn3.onclick = function() {
  modal3.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span3.onclick = function() {
  modal3.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal3) {
    modal3.style.display = "none";
  }
}

var modal4 = document.getElementById("myModal4");

// Get the button that opens the modal
var btn4 = document.getElementById("myBtn4");

// Get the <span> element that closes the modal
var span4 = document.getElementsByClassName("close4")[0];

// When the user clicks the button, open the modal 
btn4.onclick = function() {
  modal4.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span4.onclick = function() {
  modal4.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal4) {
    modal4.style.display = "none";
  }
}
</script>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Subscriptions Details</h3>
           
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
                      <th style="width: 25%">
                          Subscription Start Date
                      </th>
                      <th style="width: 20%">
                          Subscription Type
                      </th>
                      <th style="width: 5%">
                          Email
                      </th> 
                      <th style="width: 5%">
                          Zipcode
                      </th> 
                      <th style="width: 25%">
                          Renewel/Expiration Date
                      </th> 
                     
                      {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                      <!-- <th style="width: 20%">
                      </th> -->
                  </tr>
              </thead>
              <tbody>
              @for ($i = 0; $i < $count; $i++)
                
                  
                        <tr data-entry-id="">
                        <td>
                        {{ $i + 1 }}
                      </td>
                            <td>
                                {{ $resultCount['data'][$i]['start_at'] ?? '' }}
                            </td>
                            <td>
                                {{  $resultCount['data'][$i]['stripe_status'] ?? '' }}
                            </td>
                            
                            
                            <td>
                                {{ $resultCount['data'][$i]['email'] ?? '' }}
                            </td>
                            <td>
                                {{ $resultCount['data'][$i]['zipcode'] ?? '' }}
                            </td>
                      <td>
                                {{ $resultCount['data'][$i]['trial_ends_at'] ?? $resultCount['data'][$i]['cancle_at'] ?? $resultCount['data'][$i]['null'] }}
                      </td>

                        </tr>
                    
                @endfor
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
<script>
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
function timeFrame() {
  var from = document.getElementById("from").value;
  var to = document.getElementById("to").value;
  var value = document.getElementById("valuetf").value;

  if(from && to){
    //  console.log(from);
    //  console.log(to);
    //  console.log(value);

     window.location = "/admin/report/subscriptionsreport/search/from="+from+"&to="+to+"/"+value+""
  }else{
    alert("Please select a date range!!");
  }
}
</script>