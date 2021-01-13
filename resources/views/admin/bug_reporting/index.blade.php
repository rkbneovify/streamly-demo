@extends('layouts.app')
@section('breadcrumb')
<li class="breadcrumb-item">
    bug_reporting
</li>
@endsection
@section('header')
<h3><i class="fa fa-list"></i> bug_reporting </h3>
@endsection
@section('tools')
{{-- <a class="btn btn-secondary" href="{{route('bug_reporting.create')}}"> --}}
    <span class="fa fa-plus"></span>
</a>
@endsection

@section('content')
<div class="row">
    @foreach($records as $record)
    <div class="col-sm-6">
        @include('cards.bug_reporting')
    </div>
    @endforeach
</div>
{!! $records->render() !!}
@endSection