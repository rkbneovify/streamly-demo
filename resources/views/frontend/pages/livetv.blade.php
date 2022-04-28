@extends('frontend/partials/master')

@section('stylesheets-bottom')
	<style>
		nav#mainNav {
			background-color: #004E92;
		}

		
	</style>

	<link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
	<script src="{{ asset('frontend/owl.carousel.min.js') }}" ></script>

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
				loop: false,

rewind: true,
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
		
		$(document).on('click','.btn-submit',function(e){


e.preventDefault();
let channelid = $(this).data('id');


$.ajax({

	 type:'POST',

	 url:"{{ url('favorite') }}",

	 data:{channelid:channelid,
	 },

	 success:function(data){
	$('#'+channelid).html('<p>Added to favorites</p> <span data-id='+channelid+' class=" btn-remove" style="color: red;font-size:2em">♥</span>');

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

	$('#'+channelid).html('<p>Add to favorites</p> <span data-id='+channelid+' class=" btn-submit" style="color: red;font-size:2em" >♡</span>');

	 }

});



});
		});
	</script>

	<style>

		body{
			background: #F9F9F9 0% 0% no-repeat padding-box;
		}

		/* .owl-carousel .owl-item img {
		    display: block;
		    width: auto;
		    margin: 0 auto;
		    height: 1.5rem;
		} */
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

		/* .owl-stage-outer {
			height: 100%;
		} */
	</style>
@endsection

@section('content')
	
		<section class="page-section">
			
			
		@foreach($pagechannel as $key => $user)
		<div class="container">
				<h4 class="section-heading">{{$key}} <a style="font-size:15px" href="/livetv/{{$key}}">show all</a></h4> 
				<div class="row">
					<div class="live-news-carousel owl-carousel">
						@foreach ($user as $channeldet)
						
						<div class="live-news-item item" style="width:200px;height:280px">
							
							<a href="{{url('livetv/'.$channeldet->CallSign.'/'.$key)}}">
								
									<div class="channelogosame" >	<img style='background-color: #4D5659; height: 100%; width: 100%; object-fit: contain; padding:1vw' class="lazy" data-src="/uploads/channellogo/{{ $channeldet->image}}" loading="lazy"  alt="{{  $channeldet->name}}"></div>
					
							<p  style=" max-width: auto;
							padding: 0px 10px;
							overflow: hidden;
							white-space: nowrap;
							text-overflow: ellipsis;">{{  $channeldet->StationName??$channeldet->name}}</p>
						</a>
							<form> <input type="hidden" name="_token" value="{{ csrf_token() }}">

                

                <input type="hidden" name="channelid" value="{{$channeldet->id}}" class="form-control">

@if (Auth::check())
<div class="panel-footer" id="{{ $channeldet->id }}">
		{{-- {{ $channeldet->id }} --}}
		@if ($channeldet->favorited())	
		<p>Added to favorites</p> <span data-id="{{ $channeldet->id }}" class=" btn-remove" style="color: red; font-size:2em">♥</span>
		@else
		<p>Add to favorites</p> <span data-id="{{ $channeldet->id }}" class=" btn-submit" style="color: red; font-size:2em">♡</span>
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

<script>
	(function () {
			var base_url = window.location.origin;
	let url=base_url+'/api/auth/frontendepg';
			fetch(url).then(
	function(u){ return u.json();}
	).then(
	function(json){
			localStorage.setItem("mychanneldata", JSON.stringify(json.data)); 
	}
	)
})();
	</script>