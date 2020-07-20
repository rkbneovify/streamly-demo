@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}

		
	</style>
	<!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.2/owl.theme.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.2/owl.theme.min.css"> -->

	<link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
	<script src="{{ asset('frontend/owl.carousel.min.js') }}" ></script>

	<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.js"></script> -->
	<script>
		/*$('.live-news-carousel').owlCarousel({
		    center: true,
		    items:6,
		    loop:true,
		    margin:10,
		    nav: true,
		    responsive:{
		        600:{
		            items:4
		        }
		    }

		});*/
		$(document).ready(function() {
		var owl = $('.owl-carousel');
		$.ajaxSetup({

headers: {

		'X-CSRF-TOKEN': $("input[name=_token]").val()

}

});
			owl.owlCarousel({
				slideBy: 6,
				loop:true,
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
		
		$(document).on('click','.btn-submit',function(e){


e.preventDefault();
let channelid = $(this).data('id');


$.ajax({

	 type:'POST',

	 url:"{{ url('favorite') }}",

	 data:{channelid:channelid,
	 },

	 success:function(data){
	$('#'+channelid).html('<p>Added to favorite</p> <button data-id='+channelid+' class="btn btn-danger btn-remove" >Remove</button>');

	 }

});



});	
$(document).on('click','.btn-remove',function(e){
	



e.preventDefault();
let channelid = $(this).data('id');

$.ajax({

	 type:'POST',

	 url:"{{ url('unfavorite') }}",

	 data:{channelid:channelid,
	 },

	 success:function(data){

	$('#'+channelid).html('<p>Add to favorite</p> <button data-id='+channelid+' class="btn btn-success btn-submit" >Add</button>');

	 }

});



});
		});
	</script>

	<style>

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}

		.owl-carousel .owl-item img {
		    display: block;
		    width: auto;
		    margin: 0 auto;
		    height: 1.5rem;
		}

		.owl-item {
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

		.owl-stage-outer {
			height: 100%;
		}
	</style>
@endsection

@section('content')
	<section class="page-section">
		@foreach($pagechannel as $key => $user)
		<div class="container">
				<h4 class="section-heading">{{$key}}</h4>
				<div class="row">
					<div class="live-news-carousel owl-carousel">
						@foreach ($user as $channeldet)
						
						<div class="live-news-item">
							<a href="{{url('livetv/'.$channeldet->callSign.'/'.$key)}}"><img src="{{  $channeldet->logo}}">
							<p>{{  $channeldet->callSign}}</p></a>
							<form> <input type="hidden" name="_token" value="{{ csrf_token() }}">

                

                <input type="hidden" name="channelid" value="{{$channeldet->id}}" class="form-control">

@if (Auth::check())
<div class="panel-footer" id="{{ $channeldet->id }}">
		{{-- {{ $channeldet->id }} --}}
		@if ($channeldet->favorited())	
		<p>Added to favorite</p> <button data-id="{{ $channeldet->id }}" class="btn btn-danger btn-remove" >Remove</button>
		@else
		<p>Add to favorite</p> <button data-id="{{ $channeldet->id }}" class="btn btn-success btn-submit" >Add</button>
		@endif
		
</div>
@endif
</form>
						</div> 
						@endforeach
					</div>
				</div>
		</div>
    @endforeach

		
	</section>
	
@endsection