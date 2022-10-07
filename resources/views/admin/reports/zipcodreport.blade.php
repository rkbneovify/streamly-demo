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
                    <td>
                        {{ $resultsData['data'][$i]['userCount'] ?? '' }}
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
                    <td>
                        {{ $resultsData['activezipcode'][$i]['userCount'] ?? '' }}
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
     $('#roles').change(function(){
   table.column( 3 )
                .search( this.value )
                .draw();
   console.log("status changed",this.value);
       // table.draw();
    });

  </script>
@endsection