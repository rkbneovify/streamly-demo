@extends('master')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Update Slide</h1> {{ session()->get('info') }}
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
                        <form action="{{ url('admin/channel-slider-edit', $slideUpdate->id) }}" method="POST"
                            enctype="multipart/form-data">
                            <input type="hidden" name="_method" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            {{-- <label for="fname">Enter Heading:</label><br>
                            <input type="text" id="heading" name="heading" value=""><br> --}}
                            <div class="form-group">
                                <label for="channel">Channels: *</label>
                                <select class="form-control" name="channel" id="channel">
                                    <option value="">--Select Channel--</option>
                                    @foreach ($channels as $channel)
                                        <option value="{{ $channel->id }}"
                                            {{ $slideUpdate->channel_id == $channel->id ? 'selected' : '' }}>
                                            {{ $channel->CallSign }} ( {{ $channel->StationName }} )</option>
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
                                    <option value="active" {{ $slideUpdate->status == 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="inactive" {{ $slideUpdate->status == 'inactive' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                                @error('status')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="fname">Program Name: *</label><br>
                                <input class="form-control" type="text" id="program_name" name="program_name" value= "{{ $slideUpdate->program_name }}">
                                @error('program_name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Enter Description: *</label>
                                <textarea rows="3" class="form-control" id="description" name="description">{{ $slideUpdate->description ?? '' }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="image">Image Upload: </label>
                                <input type="file" name="image" class="form-control-file">
                                <img class="mt-3" src="{{ $slideUpdate->image }}" height="170" width="350">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="background_image">Background Image Upload: </label>
                                <input type="file" name="background_image" class="form-control-file">
                                <img class="mt-3" src="{{ $slideUpdate->background_image }}" height="170" width="350">
                                @error('background_image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <br>
                            <input type="submit" class="btn btn-primary" value="Update Slide">
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
