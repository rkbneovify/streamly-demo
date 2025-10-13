@extends('master')
@section('content')
    <style>
        .custom-badge {
            font-size: 14px;
            font-weight: 500;
            border-radius: 14px;
            padding: 5px 10px;
            display: inline-block;
        }

        .badge-active {
            color: #578f6d;
            background-color: #dff5e8;
        }

        .badge-inactive {
            color: #cf6773;
            background-color: #ffebf0;
        }

        .table thead {
            background-color: #fafafa;
        }

        .table thead tr th {
            font-size: 14px;
            color: #7d7d7d;
        }

        .table tbody tr td {
            font-size: 16px;
            color: #383838;
            font-weight: 500;

        }

        .badge-light {
            background: none;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Genres</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">All Genres</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="card">
                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible ">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session()->get('error') }}
                    </div>
                @elseif(session()->has('info'))
                    <div class="alert alert-success alert-dismissible ">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session()->get('info') }}
                    </div>
                @endif
                <div class="card-header">
                    <div class="row justify-space-between">
                        <div class="col-md-6">
                            <h3 class="card-title mt-2">Total : {{ $genres->count() }}</h3>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <button class="btn btn-success" onclick="window.location.href = '/admin/addgenre';"
                                type="button">Add
                                Genre</button>
                        </div>
                    </div>
                </div>
                <div class="responsive-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($genres as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>
                                        <div>
                                            <img src="{{ $item->image }}" alt="{{ $item->name }}"
                                                style="background:#423f3fef; padding:10px; border-radius:8px; max-width:100px;">
                                            <br>
                                            <b> {{ $item->name }}</b>
                                        </div>
                                    </td>

                                    <td>{{ $item->position }}</td>
                                    <td>
                                        <a href="{{ route('admin.channel.status', $item->id) }}"
                                            data-url="{{ route('admin.channel.status', $item->id) }}"
                                            title="Change channel status" class="btn-user-status">
                                            @if ($item->status == 1)
                                                <span class="custom-badge badge-active">Active</span>
                                            @else
                                                <span class="custom-badge badge-inactive">Inactive</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                                    <td>
                                        <a class="text-danger" href="/admin/deletegenre/{{ $item->id }}"
                                            onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
@endsection
