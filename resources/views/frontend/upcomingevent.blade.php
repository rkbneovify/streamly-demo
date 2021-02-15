@extends('frontend/partials/master')

@section('stylesheets-bottom')

<style>
	nav#mainNav {
		background-color: #004E92;
	}
</style>

<style>

	body{
		background: #F9F9F9 0% 0% no-repeat padding-box;
	}


</style>
<style>
		.channelogosame{

display: block;
margin: 0 auto;
height: 100px;
width: 100px;
}

.item {

		background-color: white;
	padding: 2rem 0;
	text-align: center;
	background: #FFFFFF 0% 0% no-repeat padding-box;
	box-shadow: 0px 3px 6px #00000029;
	border-radius: 8px;
}

.owl-nav {
	position: absolute;
	top: 0;
	width: 100%;
	line-height: 0.7;
	font-size: 2rem;
	height: 100%;
	z-index: -1;
}

.owl-nav button.disabled span {
	color: #21252961;
	cursor: auto;
}

.owl-nav button.disabled span:focus {
	outline: none;
}

.owl-nav button.owl-prev {
	float: left;
	position: relative;
	left: -20px;
}

.owl-nav button {
	top: 30%;
}

.owl-nav button.owl-next {
	float: right;
	position: relative;
	right: -20px;
}

.page-section {
	padding: 8rem 0;
}

.arrow-custom-car button.owl-prev {
	float: right !important;
	top: -30px !important;
}

.arrow-custom-car button.owl-next {
	top: -30px;
}

.tt-input-group {
    width: 100%;
}
</style>

@endsection
@section('content')

<section class="page-section">
	<div class="container mt-2 mb-3">
		<div class="row">
				<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<h4 class="text-center mb-5"> Upcoming Timings of the {{$records[0]->EventTitle??'Unknown'}}</h4>
						<table class="table table-striped mt-2">
								<thead>
										<th> Show </th>
										<th> Show Description </th>
										<th> StartDateTime </th>
								</thead>
								<tbody>
										@foreach($records as $record)
										<tr>
												<td> {{ $record->EventTitle ??'Unknown'}} </td>
												<td> {{ $record->EventDescription??'Unknown' }} </td>
												<td> {{ $record->StartDateTime ??'Unknown'}} </td>
										</tr>
										@endforeach
								</tbody>
						</table>
						{{ $records->links() }}
				</div>
		</div>
</div>
</section>
@endsection