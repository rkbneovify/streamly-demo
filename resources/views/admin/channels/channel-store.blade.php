@extends('master')
@section('stylesheets')
    <style>
        video {
            width: 100%;
        }

        .dash-video-player {
            position: relative;
            /* This position relative is needed to position the menus */
            width: 640px;
            margin: 0 auto;
        }

        .hide_column {
            display: none;
        }
    </style>
    <link href="{{ asset('dist/css/jquery.dataTables.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/controlbar/controlbar.css') }}">
    <script defer src="{{ asset('frontend/dashjs/dash.all.min.js') }}"></script>
    <script defer src="{{ asset('frontend/dashjs/dash.mss.min.js') }}"></script>
    <script defer src="{{ asset('frontend/controlbar/ControlBar.js') }}"></script>
@stop
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-3">
                        <h1>Channel Store</h1> {{ session()->get('info') }}
                    </div>
                    <div class="col-sm-3">
                        <a class="btn btn-xs btn-primary" href="{{  url('admin/channelstore') }} ">
                            Refresh
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Channel Store</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="card">
                <div class="table-responsive">
                    <table id="data-table" class="table table-striped projects">
                        <thead>
                            <tr>
                                <th style="width: 20%">
                                    Store Name
                                </th>
                                <th style="width: 20%">
                                    Store Url
                                </th>

                                <th style="width: 20%">
                                    Server Status
                                </th>
                            </tr>
                        </thead>
                        @foreach ($channelStore as $store)
                            <tr>
                                <td>
                                    {{ $store['store_name'] }}
                                </td>
                                <td>
                                    {{ $store['store_url'] }}
                                </td>
                                <td>
                                    @if ($store['status'] == 'active')
                                        <span class="dotgreen"></span> Active
                                    @else
                                        <span class="dotred"></span> Inactive
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@section('scripts')

    <script defer type="text/javascript" src="{{ asset('dist/js/select2.full.min.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
    <script defer type="text/javascript" src="{{ asset('dist/js/jquery.dataTables.min.js') }}"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <script type="text/javascript" src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
@stop
