@extends('master')
@section('content')
<style>
  .divfilter {
    margin: 0px 8px 8px 8px;
    text-align: end;
  }
  #activeshow {
    display: none;
  }
  .divfilter {
    margin: 0px 8px 8px 8px;
    text-align: end;
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
#zipActive {
  font-size: 10px;
  color: blue;
}
.zipcodeBox {
  display: flex;
  justify-content: space-around;
}
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Zipcode</h1> {{ session()->get( 'info' ) }}
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

    <div class="divfilter">Filter With Status Type:
<select name="cars" id="myInput" onchange="myFunctionchange()">
        <option value="">All</option>
  <option value="active">Active</option>
</select>
</div>
<script>
  function myFunctionchange(){
    var e = document.getElementById("myInput");
    var value = e.value;
    if(value == 'active'){
      $("#activeshow").show();
      $("#maintable").hide();
      $("#paginationbutton").hide();
    }else{
      $("#activeshow").hide();
      $("#maintable").show();
      $("#paginationbutton").show();
    }
  }
</script>
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
    <!-- @for ($i = 0; $i < 1; $i++) -->
    <tr>
    <td style="color:#000;" id="sno"></td>
    <td style="color:#000;" id="zipName"></td>
    <td style="color:#000;" id="zipEmail"></td>
    </tr>
    <!-- @endfor -->
    </tbody>
</table>
  </div>
</div>
</div>
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">All Zipcode</h3>
           
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 5%">
                          S:No
                      </th>
                      <th style="width: 15%">
                          Zipcode
                      </th>
                      <th style="width: 15%">
                          City
                      </th>
                      <th style="width: 15%">
                          State
                      </th>
                      <th style="width: 25%">
                          Country
                      </th>
                      <th style="width: 15%">
                          users in zipcode
                      </th>
                      <th style="width: 10%">
                          Status
                      </th> 
                     
                      {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                      <!-- <th style="width: 20%">
                      </th> -->
                  </tr>
              </thead>
              <tbody id="maintable">
              <?php $count = count($resultsData['data']); ?>
              @for ($i = 0; $i < $count; $i++)
                
                  
                <tr data-entry-id="">
                <td>
                {{ $resultsData['firstItem'] + $i }}
              </td>
                    <td>
                        {{ $resultsData['data'][$i]['zipcode'] ?? '' }}
                    </td>
                    <td>
                        {{ $resultsData['data'][$i]['city'] ?? '' }}
                    </td>
                    <td>
                        {{ $resultsData['data'][$i]['state'] ?? '' }}
                    </td>
                    <td>
                        {{ $resultsData['data'][$i]['country'] ?? '' }}
                    </td>
                    <td class="zipcodeBox">
                        {{ $resultsData['data'][$i]['userCount'] ?? '' }}
                        <input type="button" id="zipActive" value =View_Users onClick=openForm(`{{$resultsData['data'][$i]['zipcode']}}`)>
                    </td>
                    <td>
                        {{  $resultsData['data'][$i]['status'] ?? '' }}
                    </td>
                    
                    
                    <td>
                        <!-- {{ $array[$i]['email'] ?? '' }} -->
                    </td>

                </tr>
            
        @endfor
                </tbody>
                <tbody id="activeshow">
              <?php $countActive = count($resultsData['activezipcode']); ?>
              @for ($i = 0; $i < $countActive; $i++)
                
                  
                <tr data-entry-id="">
                <td>
                {{ $i + 1 }}
              </td>
                    <td>
                        {{ $resultsData['activezipcode'][$i]['zipcode'] ?? '' }}
                    </td>
                    <td>
                        {{ $resultsData['activezipcode'][$i]['city'] ?? '' }}
                    </td>
                    <td>
                        {{ $resultsData['activezipcode'][$i]['state'] ?? '' }}
                    </td>
                    <td>
                        {{ $resultsData['activezipcode'][$i]['country'] ?? '' }}
                    </td>
                    <td class="zipcodeBox">
                        {{ $resultsData['activezipcode'][$i]['userCount'] ?? '' }}
                        <input type="button" id="zipActive" value =View_Users onClick=openForm(`{{$resultsData['data'][$i]['zipcode']}}`)>
                    </td>
                    <td>
                        {{  $resultsData['activezipcode'][$i]['status'] ?? '' }}
                    </td>
                    
                    
                    <td>
                        <!-- {{ $array[$i]['email'] ?? '' }} -->
                    </td>

                </tr>
            
        @endfor
                </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
      <div id="paginationbutton" style="text-align: center;">
      @if($resultsData['current_page'] != '1')
      <button type="button"><a href="{{ $resultsData['prev_page_url'] }}">Prev</a></button>
      @endif
      <button type="button"><a href="{{ $resultsData['next_page_url'] }}">Next</a></button>
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
 
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>
function openForm(val) {
  var modal = document.getElementById("myModal");
  modal.style.display = "block";

  // When the user clicks on <span> (x), close the modal
  var span = document.getElementsByClassName("close")[0];
  span.onclick = function() {
  modal.style.display = "none";
  document.getElementById('sno').innerHTML = "";
  document.getElementById('zipName').innerHTML = "";
  document.getElementById('zipEmail').innerHTML = "";
  }

  var myHeaders = new Headers();
myHeaders.append("Accept", "application/json");
myHeaders.append("Cookie", "");

var formdata = new FormData();

var requestOptions = {
  method: 'GET',
  headers: myHeaders,
  redirect: 'follow'
};

fetch("/admin/report/zipcodeusers/"+val+"", requestOptions)
  .then(response => response.json())
  .then(result => {
    for (var counter = 0; counter < result.length; counter++) {
          var name = result[counter].name;
          var email = result[counter].email;

          var node = document.createElement("P");
          var textnode = document.createTextNode(counter + 1);
          node.appendChild(textnode);
          document.getElementById("sno").appendChild(node);

          var node = document.createElement("P");
          var textnode = document.createTextNode(name);
          node.appendChild(textnode);
          document.getElementById("zipName").appendChild(node);

          var node = document.createElement("P");
          var textnode = document.createTextNode(email);
          node.appendChild(textnode);
          document.getElementById("zipEmail").appendChild(node);
    }
  })
  .catch(error => console.log('error', error));
}

//   var modal = document.getElementById("myModal");

// // Get the button that opens the modal
// var btn = document.getElementById("2");

// // Get the <span> element that closes the modal
// var span = document.getElementsByClassName("close")[0];

// // When the user clicks the button, open the modal 
// btn.onclick = function() {
//   modal.style.display = "block";
// }

// // When the user clicks on <span> (x), close the modal
// span.onclick = function() {
//   modal.style.display = "none";
// }

// // When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//   if (event.target == modal) {
//     modal.style.display = "none";
//   }
// }

     $('#roles').change(function(){
   table.column( 3 )
                .search( this.value )
                .draw();
   console.log("status changed",this.value);
       // table.draw();
    });

  </script>
@endsection