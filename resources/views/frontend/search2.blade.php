@extends('frontend/partials/master')

@section('stylesheets-bottom')

<link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
<script src="{{ asset('frontend/owl.carousel.min.js') }}" ></script>
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

</style>

<script type="text/javascript">
		$(document).ready(function () {
		 
				$('#search').on('keyup',function() {
						var query = $(this).val(); 
						$.ajax({
							 
								url:"{{ route('autocomplete') }}",
					
								type:"GET",
							 
								data:{'search':query},
							 
								success:function (data) {
									
										$('#search_list').html(data);
								}
						})
						// end of ajax call
				});

				
				$(document).on('click', 'li', function(){
					
						var value = $(this).text();
						$('#search').val(value);
						$('#search_list').html("");
						// alert(value);
				});
				var owl = $('.owl-carousel');
				owl.owlCarousel({
				slideBy: 6,
				loop:true,
				autoWidth:true, autoHeight:true,
			    margin:20,
			    nav: true,
			    responsiveClass:true,
			    responsive:{
			        0:{
			            items:1,
			            nav:true
			        },
			        600:{
			            items:3,
			            nav:false
			        },
			        1000:{
			            items:6,
			            nav:true,
			            loop:false
			        }
			    }
			});
		});
</script>
@endsection
@section('content')
	
<section>
	<div class="container" style="margin-top: 50px;">
		<div class="row">
				<div class="col-lg-3"></div>
				<div class="col-lg-6">
						<h4 class="text-center">Autocomplete Search Box with <br> Laravel + Ajax + jQuery</h4><hr>
						<div class="form-group">
								<label>Type a search name</label>
								<input type="text" name="search" id="search" placeholder="Enter search name" class="form-control">
						</div>
						<div id="search_list"></div>                    
				</div>
				<div class="col-lg-3"></div>
		</div>
		<div class="row">
			<div class="filter">	Genre:Filter
				<div class="live-news-carousel owl-carousel">
					@foreach ($genre as $genrename)
					
					<div class="live-news-item item" style="width:75px;height:25px">
						
				
						<p>{{  $genrename->name??$genrename->name}}</p>
				
					</div> 
					@endforeach
				</div></div>
		</div><div class="row">
				<div class="container">
					<div class="filter">	Channel:Filter
						<div class="live-news-carousel owl-carousel">
							@foreach ($channels as $genrename)
							
							<div class="live-news-item item" style="width:75px;height:25px">
								
						
								<p>{{  $genrename->stationName??$genrename->name}}</p>
						
							</div> 
							@endforeach
						</div></div>
				</div>
				
		
		</div>
</div>
</section>
<section>
	<div class="container" style="margin-top: 50px;">
	<form action="{{ url('/search2') }}">
		<div class="row">
			<div class="col-md-4">
				<input class="form-control form-control-sm" type="search" name="q" value="{{ $q }}">
			</div>
	
			<div class="col-md-2 col-3">
				<select name="sortBy" class="form-control form-control-sm" value="{{ $sortBy }}">
					@foreach(['id', 'name', 'email', 'notes'] as $col)
						<option @if($col == $sortBy) selected @endif value="{{ $col }}">{{ ucfirst($col) }}</option>
					@endforeach
				</select>
			</div>
	
			<div class="col-md-2 col-3">
				<select name="orderBy" class="form-control form-control-sm" value="{{ $orderBy }}">
					@foreach(['asc', 'desc'] as $order)
						<option @if($order == $orderBy) selected @endif value="{{ $order }}">{{ ucfirst($order) }}</option>
					@endforeach
				</select>
			</div>
	
			<div class="col-md-2 col-3">
				<select name="perPage" class="form-control form-control-sm" value="{{ $perPage }}">
					@foreach(['20','50','100','250'] as $page)
						<option @if($page == $perPage) selected @endif value="{{ $page }}">{{ $page }}</option>
					@endforeach
				</select>
			</div>
	
			<div class="col-md-2 col-3">
				<button type="submit" class="w-100 btn btn-sm bg-blue">Filter</button>
			</div>
		</div>
	</form></div>
</section>
@endsection