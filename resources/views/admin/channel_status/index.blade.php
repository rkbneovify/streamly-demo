  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Channel status</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">channel status</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <!-- <a class="btn btn-xs btn-primary" href="{{  url('admin/channels/create') }} ">
                                        Add new Gracenote entery
                                    </a> -->

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">channel status</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                      Channel Name
                      </th>
                      <th style="width: 20%">
                      Channel url
                      </th>
                      <th style="width: 20%">
                      Status
                      </th>
                      
                      <th style="width: 20%">
                      Action
                      </th>
                  </tr>
              </thead>
              <tbody>
              @forelse ($record as $record)
              <tr>
              <td>#</td>
              
    <td>{{ $record->StationName }}</td>
    <td>{{ $record->CallSign }}</td>
    <td>{{ $record->Rating }}</td>

    
    <td>
                                
                                    <a class="btn btn-xs btn-primary" href="{{  url('admin/channels/view', $record->id) }}">
                                        {{ trans('view') }}
                                    </a>

                                    <a class="btn btn-xs btn-info" href="{{ url('admin/channels/edit', $record->id) }}">
                                        {{ trans('edit') }}
                                    </a>

                                    <!-- <form action="{{ url('admin/subscriptions/destroy', $record->id) }}" method="POST" onsubmit="return confirm('{{ trans('are You Sure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                    </form> -->
                          
                                
                            </td></tr>
@empty
    <p>No Channel </p>
@endforelse
                  
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
@endsection