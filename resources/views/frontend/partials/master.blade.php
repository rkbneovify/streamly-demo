<!DOCTYPE html>
<html lang="en">
<head>
	@yield('top-stylesheets')

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<title>{{ config('app.name', 'Streamly') }}</title>

	
	<!-- Bootstrap style -->

	<link rel="stylesheet" href="{{ asset('frontend/bootstrap.min.css') }}" />

	<!-- Bootstrap script -->

	@yield('stylesheets')
</head>
<body>

@include('frontend.partials.header')

@yield('content')

@include('frontend.partials.footer')
<script  src="{{ asset('frontend/jquery-3.5.1.min.js') }}" ></script> 
{{-- <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script> --}}
<script  src="{{ asset('frontend/jquery-ui.min.js') }}" ></script> 
<script  src="{{ asset('frontend/popper.min.js') }}" ></script> 
<script  src="{{ asset('frontend/bootstrap.min.js') }}" ></script>

<link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
<script src="{{ asset('frontend/script.js') }}" ></script>

@yield('stylesheets-bottom')

</body>
</html>


<style>
	#search_list{position: absolute;
    width: auto; 
    background: white;
    border-bottom-left-radius: 10px;
    border-bottom-right-radius: 10px;
    max-height: 200px;
    overflow-y: auto;

    /* border: 1px solid gray; */

    /*This is relative to the navbar now*/
    left: 0;
    right: 0;
    top: 40px;}
	
		 
			.dddd{display: flex;
			flex: auto;
			flex-direction: column;
			min-width: 0;
			max-height: none;
			padding: 6px 0;}
	
	
	
	</style>
	<script type="text/javascript">
			$(document).ready(function () {
			 
							$('#search-btn-header').on('keyup',function() {
											var query = $(this).val(); 
											$.ajax({
													 
															url:"{{ route('autocomplete') }}",
									
															type:"GET",
													 
															data:{'search':query},
													 
															success:function (data) {
																	console.log(data);
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