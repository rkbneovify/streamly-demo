@extends('master')
@section('stylesheets')
    <style>
        .card {
            border-radius: 10px;
        }

        .card-header {
            padding: 25px;
        }

        .card-body {
            padding: 25px;
        }

        .media+.media {
            margin-top: 35px;
        }

        .badgex {
            font-size: 12px;
            padding: 6px 15px;
            border-radius: 15px;
        }

        .text-primary {
            color: #3d5afe !important;
        }

        .media-body p {
            line-height: 1.7;
        }
    </style>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Support Ticket Conversation/Chat</h1>

                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Support Ticket</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="container my-4">
                <div class="card shadow-sm">

                    <!-- Header -->
                    <div class="card-header bg-white">
                        <h4 class="font-weight-bold mb-2">
                            {{ $data->subject }}
                        </h4>

                        <div class="text-muted">
                            Ticket: <strong>{{ $data->ticket_number }}</strong>
                            &nbsp;&bull;&nbsp;
                            {{ $data->created_at->format('M d, Y H:i') }}

                            &nbsp;&bull;&nbsp;
                            <span class="badgex badge-secondary">{{ ucfirst($data->priority) }}</span>
                            &nbsp;&bull;&nbsp;
                            <span class="badgex badge-secondary">{{ ucwords($data->status) }}</span>
                        </div>
                        <div class="text-muted">
                            Platform: <strong>{{ $data->platform }}</strong>

                        </div>
                    </div>

                    <!-- Ticket Description -->
                    <div class="card-body border-bottom">
                        {!! $data->message->first()->message !!}
                    </div>

                    <!-- Conversation -->
                    <div class="card-body">

                        {{-- <!-- Support Message -->
                        <div class="media mb-5">

                            <img src="https://via.placeholder.com/60" class="rounded-circle mr-3" width="60"
                                height="60">

                            <div class="media-body">

                                <div class="d-flex justify-content-between">
                                    <h5 class="text-primary font-weight-bold mb-2">
                                        Shirin Aka
                                    </h5>

                                    <small class="text-muted">
                                        Jul 29, 2026 15:34
                                    </small>
                                </div>

                                <p>Hello,</p>

                                <p>
                                    I am in the process of looking into your issue,
                                    but I'll need a bit more time to come to a conclusion.
                                    I'll provide you with an update at the earliest opportunity.
                                </p>

                                <p class="mb-0">
                                    Best regards,<br>
                                    Shirin A.<br>
                                    Technical Support
                                </p>

                            </div>

                        </div> --}}

                        <!-- User Reply -->
                        <div class="media">

                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3"
                                style="width:50px;height:50px;font-size:16px;">
                                RB
                            </div>

                            <div class="media-body">

                                <div class="d-flex justify-content-between">
                                    <h5 class="font-weight-bold mb-2">
                                        Ram Kumar Bedia
                                    </h5>

                                    <small class="text-muted">
                                        Jul 29, 2026 16:05
                                    </small>
                                </div>

                                <p class="mb-0">
                                    Please, take your time.
                                </p>

                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
