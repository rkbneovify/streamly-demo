@extends('master')
@section('content')

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
 
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Allowed Zipcode</h3>
           
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
                      <th style="width: 20%">
                          S:No
                      </th>
                      <th style="width: 25%">
                          zipcode
                      </th>
                      <th style="width: 25%">
                          users in zipcode
                      </th>
                      <th style="width: 30%">
                          channels in zipcode
                      </th> 
                     
                      {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                      <!-- <th style="width: 20%">
                      </th> -->
                  </tr>
              </thead>
              <tbody>
              <?php $count = count($array); ?>
              @for ($i = 0; $i < $count; $i++)
                
                  
                <tr data-entry-id="">
                <td>
                {{ $i + 1 }}
              </td>
                    <td>
                        {{ $array[$i]['zipcode'] ?? '' }}
                    </td>
                    <td>
                        {{  $array[$i]['userCount'] ?? '' }}
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