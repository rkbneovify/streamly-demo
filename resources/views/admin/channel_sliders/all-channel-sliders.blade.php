@extends('master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>All Slides ({{ $count = count($channel_slides) }})</h1> {{ session()->get('info') }}
                    </div>
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
                    <h3 class="card-title">Slide Details</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                            title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip"
                            title="Remove">
                            <i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width:2%">
                                    S.No.
                                </th>
                                <th style="width:10%">
                                    Image
                                </th>
                                <th style="width:8%">
                                    Channel Name
                                </th>
                                <th style="width:8%">
                                    Program Name
                                </th>
                                <th style="width:25%">
                                    Channel URLs
                                </th>
                                {{-- <th style="">
                                    Channel Dash URL
                                </th>
                                <th style="">
                                    Channel Smooth URL
                                </th> --}}
                                <th style="width:20%">
                                    Description
                                </th>
                                <th style="width:10%">
                                    Status
                                </th>
                                <th style="width:5%">
                                    Edit
                                </th>
                                <th style="width:5%">
                                    Delete
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($count == 0)
                                <tr>
                                    <td colspan="8"><center>No Result Found!</center></td>
                                </tr>
                            @endif
                            @for ($i = 0; $i < $count; $i++)
                                <tr data-entry-id="">
                                    <td>
                                        {{ $i + 1 }}
                                    </td>

                                    <td>
                                        <img src="{{ $channel_slides[$i]->image }}" height="50" width="70">
                                    </td>

                                    <td>
                                        {{ $channel_slides[$i]->channel->name ?? '' }}
                                    </td>
                                    <td>
                                        {{ $channel_slides[$i]->program_name ?? '' }}
                                    </td>
                                    <td>
                                        <b>URL: </b>{{ $channel_slides[$i]->channel->url ?? '' }} <br>
                                        {{-- </td>
                                    <td> --}}
                                        <b>Dash URL: </b>{{ $channel_slides[$i]->channel->dash_url ?? '' }} <br>
                                        {{-- </td>
                                    <td> --}}
                                        <b>Smooth URL: </b>{{ $channel_slides[$i]->channel->smooth_url ?? '' }}
                                    </td>
                                    <td>
                                        {{ $channel_slides[$i]->description ?? '' }}
                                    </td>
                                    <td>
                                        @if ($channel_slides[$i]->status == 'active')
                                            <span class="dotgreen"></span> Active
                                        @else
                                            <span class="dotred"></span> Inactive
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/channel-slider-edit/' . $channel_slides[$i]->id) }} "
                                            class="btn btn-xs btn-primary">Edit</a>
                                    </td>
                                    <td>
                                        <form action="{{ url('/admin/channel-slider-delete', $channel_slides[$i]->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ trans('Are you sure! you want to delete?') }}');"
                                            style="display: inline-block;">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                        </form>
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
    <script>
        function slideDelete() {
            var result = confirm("Are you sure! want to delete?");
            if (result) {
                //Logic to delete the item
            }
        }
    </script>
@endsection
