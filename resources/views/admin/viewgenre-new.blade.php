@extends('master')
@section('content')
    <style>
        .addgenre {
            float: right;
            color: white;
            background-color: blue;
            border: 2px;
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
                                <th>Created At</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($genres as $genre)
                                <tr>
                                    <td>{{ $genre->id }}</td>
                                    <td>
                                        <div>
                                            <img src="{{ $genre->image }}" alt="{{ $genre->name }}"
                                                style="background:#423f3fef; padding:10px; border-radius:8px; max-width:100px;">
                                            <br>
                                            <b> {{ $genre->name }}</b>
                                        </div>
                                    </td>

                                    <td>{{ $genre->position }}</td>
                                    <td>{{ date('d/m/Y', strtotime($genre->created_at)) }}</td>
                                    <td>
                                        <a href="/admin/deletegenre/{{ $genre->id }}"
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
