@extends('master')
@section('content')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Gracenote</h1> {{ session()->get( 'info' ) }}
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Gracenote</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <a class="btn btn-xs btn-primary" href="{{  url('admin/channels/create') }} ">
                                        Add new Gracenote entery
                                    </a>

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Gracenote</h3>

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
                      StationName
                      </th>
                      <th style="width: 20%">
                      CallSign
                      </th>
                      <th style="width: 20%">
                      Rating
                      </th>
                      <th style="width: 20%">
                      EventType
                      </th>
                      <th style="width: 20%">
                      EventID
                      </th>
                      <th style="width: 20%">
                      StartDate
                      </th>
                      <th style="width: 20%">
                      StartTime
                      </th>
                      <th style="width: 20%">
                      StartDateTime
                      </th>
                      <th style="width: 20%">
                      RunLenHour
                      </th>
                      <th style="width: 20%">
                      RunLenMinute
                      </th>
                      <th style="width: 20%">
                      EventTitle
                      </th>
                      <th style="width: 20%">
                      EventDescription
                      </th>
                      <th style="width: 20%">
                      UnixStartDate
                      </th>
                      <th style="width: 20%">
                      TimeZoneName
                      </th>
                      <th style="width: 20%">
                      EndDate
                      </th>
                      <th style="width: 20%">
                      EndaDateTime
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
    <td>{{ $record->EventType }}</td>
    <td>{{ $record->EventID }}</td>
    <td>{{ $record->StartDate }}</td>
    <td>{{ $record->StartTime }}</td>
    <td>{{ $record->StartDateTime }}</td>
    <td>{{ $record->RunLenHour }}</td>
    <td>{{ $record->RunLenMinute }}</td>
    <td>{{ $record->EventTitle }}</td>
    <td>{{ $record->EventDescription }}</td>
    <td>{{ $record->UnixStartDate }}</td>
    <td>{{ $record->TimeZoneName }}</td>
    <td>{{ $record->EndDate }}</td>
    <td>{{ $record->CallSign }}</td>
    <td>{{ $record->EndaDateTime }}</td>

    
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