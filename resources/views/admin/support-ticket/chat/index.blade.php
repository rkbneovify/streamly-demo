@extends('master')
@section('stylesheets')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css">

    <style>
        .scroll-box {
            max-height: none;
            overflow: visible;
        }

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

        .bg-user {
            background: #4b545c;
        }

        .bg-admin {
            background: #cccdce;
        }
    </style>
    <style>
        #editor {
            background: #fff;
        }

        .ql-toolbar {
            border-radius: .25rem .25rem 0 0;
        }

        .ql-container {
            border-radius: 0 0 .25rem .25rem;
            min-height: 250px;
        }

        .card-footer {
            padding: 1.5rem;
        }
    </style>
    <style>
        #attachmentDropzone .dz-preview {
            position: relative;
        }

        #attachmentDropzone .dz-remove {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 22px;
            height: 22px;
            line-height: 20px;
            text-align: center;
            border-radius: 50%;
            background: #dc3545;
            color: #fff !important;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            z-index: 1000;
        }

        #attachmentDropzone .dz-remove:hover {
            background: #c82333;
            color: #fff !important;
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
            <div class="scroll-box">
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
                                Priority: &nbsp;&nbsp;&nbsp; <span
                                    class="badgex badge-secondary">{{ ucfirst($data->priority) }}</span>

                                &nbsp;&nbsp;&nbsp; Status: &nbsp;&nbsp;&nbsp; <span
                                    class="badgex badge-secondary">{{ ucwords($data->status) }}</span>
                            </div>
                            <div class="text-muted">
                                Platform: <strong>{{ $data->platform }}</strong>

                            </div>


                        </div>

                        <!-- Ticket Description -->
                        <div class="card-body border-bottom">
                            {!! $data->messages->first()->message !!}
                        </div>

                        <!-- Conversation -->
                        <div class="card-body">


                            <!-- User Reply -->
                            @foreach ($data->messages->skip(1) as $item)
                                <div class="media">

                                    @if (in_array($item->user->roles->first()->name, ['admin', 'superadmin']))
                                        <div class="rounded-circle bg-admin text-white d-flex align-items-center justify-content-center mr-3"
                                            style="width:50px;height:50px;font-size:16px;">
                                            {{ getAvatarInitials($item->user->name) }}
                                        </div>
                                    @else
                                        <div class="rounded-circle bg-user text-white d-flex align-items-center justify-content-center mr-3"
                                            style="width:50px;height:50px;font-size:16px;">
                                            {{ getAvatarInitials($item->user->name) }}
                                        </div>
                                    @endif


                                    <div class="media-body">

                                        <div class="d-flex justify-content-between">
                                            <h5 class="font-weight-bold mb-2">
                                                {{ $item->user->name }}
                                                <small class="text-muted"> &#45;
                                                    ({{ ucfirst($item->user->roles->first()->name) }})
                                                </small>
                                            </h5>

                                            <small class="text-muted">

                                                {{ $item->created_at->format('M d, Y H:i') }}
                                            </small>
                                        </div>

                                        <p class="mb-0">
                                            {!! $item->message !!}
                                        </p>

                                    </div>

                                </div>
                            @endforeach


                        </div>
                        <!-- end -->
                        <!-- footer -->
                        <div class="card-footer bg-white text-right">

                            <button type="button" class="btn btn-primary" id="replyBtn">
                                <i class="fa fa-reply"></i> Reply
                            </button>

                        </div>

                        <div class="card-footer bg-white d-none" id="replySection">

                            <form id="replyForm" action="{{ route('admin.ticket.reply', $data->ticket_number) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label>Reply</label>
                                    <div id="editor" style="height:250px;"></div>
                                    <input type="hidden" name="message" id="message">
                                    @error('message')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>



                                <div class="form-group">
                                    <label>Attachments</label>

                                    <div id="attachmentDropzone" class="dropzone border rounded">
                                        <div class="dz-message">
                                            <i class="fa fa-cloud-upload fa-2x"></i>
                                            <br>
                                            Drag & Drop files here
                                            <br>
                                            <small>or click to browse</small>
                                        </div>
                                    </div>


                                </div>


                                <div class="text-right">
                                    <button type="button" class="btn btn-secondary mr-2" id="cancelReply">
                                        Cancel
                                    </button>

                                    <button type="submit" class="btn btn-success">
                                        Send Reply
                                    </button>
                                </div>

                            </form>

                        </div>
                        <!---end-->

                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('scripts')
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script>
        Dropzone.autoDiscover = false;
        let myDropzone;
        $(function() {



            myDropzone = new Dropzone("#attachmentDropzone", {
                url: "/dummy",
                autoProcessQueue: false,
                maxFiles: 3,
                uploadMultiple: true,
                addRemoveLinks: true,
                dictRemoveFile: "✕",
                acceptedFiles: ".jpg,.jpeg,.png",
                parallelUploads: 10,
                dictInvalidFileType: "Only JPG, JPEG and PNG images are allowed.",
                paramName: "attachments"
            });

            $('#replyBtn').click(function() {
                $('#replySection').removeClass('d-none').hide().slideDown(300);
                $(this).hide();

                // Scroll to reply form
                $('html, body').animate({
                    scrollTop: $('#replySection').offset().top - 20
                }, 500);
            });

            $('#cancelReply').click(function() {
                quill.setText('');
                myDropzone.removeAllFiles(true);
                $('#replyForm .dynamic-attachment').remove();
                // $('#replySection').slideUp(300, function() {
                //     // $(this).addClass('d-none');
                //     // $('#replyBtn').show();
                // });
            });

        });



        $('#replyForm').on('submit', function(e) {

            e.preventDefault();
            $('#message').val(quill.root.innerHTML);


            if (quill.getText().trim().length == 0) {
                Swal.fire({
                    toast: true,
                    position: "top-end",
                    icon: "error",
                    title: "Reply is reqired",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showCloseButton: true,
                });
                return;
            }
            // Remove old hidden inputs
            $(this).find('input[name="attachment[]"]').remove();

            // Add selected files
            myDropzone.getAcceptedFiles().forEach(function(file) {

                const dt = new DataTransfer();
                dt.items.add(file);

                const input = document.createElement('input');
                input.type = 'file';
                input.name = 'attachment[]';
                input.files = dt.files;
                input.style.display = 'none';
                input.classList.add('dynamic-attachment');

                document.getElementById('replyForm').appendChild(input);
            });

            $('.btn-success').prop('disabled', true).text('Sending...');
            showLoader();

            this.submit();
        });
    </script>
    <script>
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Write your reply...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    [{
                        'header': [1, 2, 3, false]
                    }],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    ['blockquote', 'code-block'],
                    ['link'],
                    [{
                        'align': []
                    }],
                    ['clean']
                ]
            }
        });

        document.querySelector('form').onsubmit = function() {
            document.querySelector('#message').value = quill.root.innerHTML;
        };
    </script>
@endsection
