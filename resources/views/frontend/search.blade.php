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
	 img {
			display: block;
			width: auto;
			margin: 0 auto;
			height: 1.5rem;
	}

	.full-width {
		background: #000000 0% 0% no-repeat padding-box;
	}

	.col-centered {
			margin: 0 auto;
			position: relative;
	}

	.channel-detail {
		position: absolute;
			top: 20%;
			right: -10%;
			color: #ffffff;
			padding: 20px;
			background: #333333 0% 0% no-repeat padding-box;
			box-shadow: 0px 3px 6px #00000029;
			opacity: 1;
			backdrop-filter: blur(30px);
			-webkit-backdrop-filter: blur(30px);
	}

	.channel-detail:before {
			content: '';
			width: 0px;
			height: 0px;
			background-color: transparent;
			z-index: 999;
			display: block;
			left: -20px;
			position: absolute;
			border-left: 10px solid transparent;
			border-top: 10px solid transparent;
			border-right: 10px solid #333333;
			border-bottom: 10px solid transparent;
	}

	.live-news-item {
			padding: 2rem 0;
			text-align: center;
			background: #FFFFFF 0% 0% no-repeat padding-box;
			box-shadow: 0px 3px 6px #00000029;
			border-radius: 8px;
	}

	.custom-row .row{
		margin-top: 2rem;
	}

	.live-news-item:after {
			content: 'live';
			position: absolute;
			right: 12px;
			top: 12px;
			color: white;
			background: #E10C26 0% 0% no-repeat padding-box;
			border-radius: 4px 0px 0px 4px;
			padding: 0px 5px;
			line-height: 1;
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
		});
</script>
@endsection
@section('content')
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
</div>

@endsection