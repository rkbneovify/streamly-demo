@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>All Users <?php echo '('. count($allSession). ')'; ?></h1> {{ session()->get( 'info' ) }}
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
          <h3 class="card-title">Users Details</h3>
           
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
                      <th style="width: 1%">
                          S:No
                      </th>
                      <th style="width: 15%">
                          User Name
                      </th>
                      <th style="width: 5%">
                          Email
                      </th>
                      <th style="width: 20%">
                          No. connected device
                      </th> 
                      <th style="width: 15%">
                          Suscription Status
                      </th> 
                      <th style="width: 15%">
                          Register date
                      </th> 
                     
                      {{-- <th style="width: 8%" class="text-center">
                          Status
                      </th> --}}
                      <!-- <th style="width: 20%">
                      </th> -->
                  </tr>
              </thead>
              <tbody>
                  @foreach($allSession as $key => $user)
                  
                        <tr data-entry-id="">
                        <td>
                        {{ $loop->iteration }}
                      </td>
                            <td>
                                {{ $user->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->user->email ?? '' }}
                            </td>
                            
                            
                            <td>
                                {{ $user->activesession ?? '' }}
                            </td>
                            <td>
                                {{ $user->subscriptionStatus }}
                            </td>
                            <td>
                                {{ $user->user->email_verified_at ?? '' }}
                      </td>

                        </tr>
                    @endforeach
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