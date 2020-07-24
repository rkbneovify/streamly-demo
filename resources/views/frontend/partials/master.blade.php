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
<script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>
<script  src="{{ asset('frontend/popper.min.js') }}" ></script> 
<script  src="{{ asset('frontend/bootstrap.min.js') }}" ></script>

<link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
<script src="{{ asset('frontend/script.js') }}" ></script>

@yield('stylesheets-bottom')

</body>
</html>