@extends('master')
@section('stylesheets')
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
<style>
.select-wrapper {
width: 100%;
display: inline-flex;
align-items: center;
padding: 6px 10px;
border: 1px solid #ccc;
border-radius: 6px;
background-color: #fff;
cursor: pointer;
font-size: 14px;
}

.select-wrapper:hover {
background-color: #f9f9f9;
box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.select-wrapper img {
width: 20px;
height: 20px;
}

.select-custom {
width: 100%;
padding: 4px 10px;
border: none;
/* border-radius: 4px; */
font-size: 13.8px;
font-weight: 600;
color: #333;
outline: none;
transition: border-color 0.2s, box-shadow 0.2s;
/* Remove default arrow for most browsers */
appearance: none;
-webkit-appearance: none;
-moz-appearance: none;
background: none;
}

.select-custom:focus {
border-color: none;
box-shadow: none;
}

.btn-add-user {
text-decoration: none;
color: #333;
padding: 4px 10px;
border-radius: 4px;
font-size: 14px;
font-weight: 600;
}

.input-daterange {
width: 100%;
padding: 4px 10px;
border: none;
/* border-radius: 4px; */
font-size: 13.8px;
font-weight: 600;
color: #333;
outline: none;
transition: border-color 0.2s, box-shadow 0.2s;
/* Remove default arrow for most browsers */
appearance: none;
-webkit-appearance: none;
-moz-appearance: none;
background: none;
}

.custom-input {
width: 100%;
padding: 10px 40px 10px 15px;
border: none;
/* border-radius: 4px; */
font-size: 13.8px;
font-weight: 600;
color: #333;
outline: none;
transition: border-color 0.2s, box-shadow 0.2s;
/* Remove default arrow for most browsers */
appearance: none;
-webkit-appearance: none;
-moz-appearance: none;
background: none;
}
</style>
@stop
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
<div class="container-fluid">
<div class="row mb-2">
<div class="col-sm-6">
<h1>Zipcode</h1> {{ session()->get( 'info' ) }}
</div>
<div class="col-sm-6">
<ol class="breadcrumb float-sm-right">
<li class="breadcrumb-item"><a href="/">Home</a></li>
<li class="breadcrumb-item active">Zipcode</li>
</ol>
</div>
</div>
</div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
{{-- <a class="btn btn-xs btn-primary" href="{{ url('admin/subscriptions/create') }} ">
Add new zipcode
</a> --}}

<!-- Default box -->
<div class="card">
<div class="card-header">
<div class="row mt-2 px-2">
<div class="col-md-2 mb-3">
<div class="select-wrapper">
<img src="{{ asset('images/user-mode.png') }}" alt="status icon">
<select name="limit" id="sel_limit" class="select-custom">
<option value="10" {{ request()->limit == 10 ? 'selected' : '' }}>Show 10</option>
<option value="30" {{ request()->limit == 30 ? 'selected' : '' }}>Show 30</option>
<option value="50" {{ request()->limit == 50 ? 'selected' : '' }}>Show 50</option>
<option value="100" {{ request()->limit == 100 ? 'selected' : '' }}>Show 100</option>
</select>
</div>
</div>
<div class="col-md-2 mb-3">

<h3 class="card-title mt-2">Total : {{ $zipcode->total() }}</h3>

</div>
<div class="col-md-2 mb-3">
<a href="javascript:void(0)" id="btn-show-selected">
<h3 id="selected" class="card-title mt-2"></h3>
</a>

</div>

</div>
</div>
<div class="card-body p-0">
<table class="table ">
<thead>
<tr>
<th>
<input type="checkbox" class="custom-checkbox" id="selectAll">
</th>
<th>Id</th>
<th>City</th>
<th>State</th>
<th>Zipcode</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
@foreach ($zipcode as $key => $item)
<tr>
<td>
<input type="checkbox" class="row-checkbox custom-checkbox"
value="{{ $item->id }}">
</td>
<td>{{ $item->id }}</td>
<td>{{ $item->city??"N/A" }}</td>
<td>{{ $item->state??"N/A" }}</td>
<td>{{ $item->zipcode??"N/A" }}</td>
<td>
@if ($item->status == 1)
<span class="custom-badge badge-active">Active</span>
@else
<span class="custom-badge badge-inactive">Inactive</span>
@endif
</td>
<td>
<a class="btn btn-xs btn-primary" href="/admin/zipcode/view/{{ $item->id }}">View</a>
<a class="btn btn-xs btn-info" href="/admin/zipcode/edit/{{ $item->id }}">Edit</a>
</td>

</tr>
@endforeach
</table>
<div class="d-flex justify-content-end mt-5 mr-1">
{{ $zipcode->withQueryString()->links() }}
</div>
</div>
<!-- /.card-body -->
</div>
<!-- /.card -->

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection

@section('scripts')
<script>
$(document).on('change', '#sel_limit', function(e) {
let url = new URL(window.location.href);
let params = url.searchParams;
let page = parseInt(params.get("page")) || 1;


if (page > 1) {
// reset URL (remove params except page)
url.search = "";
url.searchParams.delete("page");
url.searchParams.set("limit", $(this).val());
} else if ($(this).val() == "") {
url.searchParams.delete("limit");
} else {

url.searchParams.set("limit", $(this).val());
}

window.location.href = url.toString();
});
</script>
@stop
