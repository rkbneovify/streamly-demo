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
                    <h1>All Genre ({{ count($users) }})</h1>
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
        @if(session()->has('error'))
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
                <h3 class="card-title">All Genres Here</h3>
                <button class="addgenre" onclick="window.location.href = '/admin/addgenre';" type="button">Add Genre</button>
            </div>
            <div class="card-body">
                <table id="masterTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>
                            <a href="/admin/deletegenre/{{ $user->id }}" onclick="return confirm('Are you sure you want to delete this item?')">Delete</a>
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

