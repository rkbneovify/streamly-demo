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
			owl.owlCarousel({
				
				loop:true,
			    margin:10,
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
			})
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
			padding: 2rem;
			text-align: center;
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
@endsection

@section('content')
	<section class="page-section">
		<div class="container">
				<h4 class="section-heading">Live News</h4>
				<div class="row">
					<div class="live-news-carousel owl-carousel">
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
					</div>
				</div>
		</div>


		<div class="container">
				<h4 class="section-heading">Sports</h4>
				<div class="row">
					<div class="live-news-carousel arrow-custom-car owl-carousel">
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
					</div>
				</div>
		</div>

		<div class="container">
				<h4 class="section-heading">Kids</h4>
				<div class="row">
					<div class="live-news-carousel arrow-custom-car owl-carousel">
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
					</div>
				</div>
		</div>

		<div class="container">
				<h4 class="section-heading">Movies</h4>
				<div class="row">
					<div class="live-news-carousel arrow-custom-car owl-carousel">
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/NoPath.png') }}">
							<p>Commercial news</p>
						</div>
						<div class="live-news-item">
							<img src="{{ asset('frontend/img/newsmaxtv.png') }}">
							<p>Commercial news</p>
						</div>
					</div>
				</div>
		</div>
		
	</section>
	
@endsection