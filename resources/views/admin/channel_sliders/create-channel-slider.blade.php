@extends('master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Add Slide</h1> {{ session()->get('info') }}
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
        <section class="container mt-1">
            <div class="card">
                <div class="card-header">
                    <div>
                        <form action="{{ url('admin/channel-slider-store') }}" enctype="multipart/form-data" method="POST">
                            <input type="hidden" name="_method" value="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {{-- <label for="fname">Enter Heading:</label><br>
                            <input type="text" id="heading" name="heading" value=""><br> --}}
                            <div class="form-group">
                                <label for="channel">Channels: *</label>
                                <select class="form-control" name="channel" id="channel">
                                    <option value="">--Select Channel--</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}">{{ $channel->CallSign }} (
                                            {{ $channel->StationName }} )</option>
                                    @endforeach
                                </select>
                                @error('channel')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="channel" id="channel-url" style="display: none">Channels URL:</label>
                                @foreach ($channels as $channel)
                                    <div id="channel-{{ $channel->id }}" class="channel-id" style="display: none">
                                        URL: {{ $channel->url }} <br>
                                        DASH URL: {{ $channel->dash_url }} <br>
                                        SMOOTH URL: {{ $channel->smooth_url }}
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group">
                                <label for="status">Status: *</label>
                                <select class="form-control" name="status" id="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="fname">Program Name: *</label><br>
                                <input class="form-control" type="text" id="program_name" name="program_name">
                                @error('program_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Enter Description: *</label>
                                <textarea rows="3" class="form-control" id="description" name="description"></textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image">Image Upload: *</label>
                                <input type="file" name="image" class="form-control-file">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="background_image">Background Image Upload:</label>
                                <input type="file" name="background_image" class="form-control-file">
                                @error('background_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Add Slide">
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('scripts')
    <script>
        $('document').ready(function() {
            $('#channel').on('change', function(e) {
                e.preventDefault();
                $('.channel-id').hide();

                let channel_id = $(this).val();
                if (channel_id) {
                    $('#channel-url').show();
                    $(`#channel-${channel_id}`).show();
                } else {
                    $('#channel-url').hide();
                    $('.channel-id').hide();
                }
            })
        });
    </script>
@endpush
