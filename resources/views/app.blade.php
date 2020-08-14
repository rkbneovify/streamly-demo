
    <meta name="csrf-token" content="{{ csrf_token() }}">
{{-- 
    <!DOCTYPE html>
    <html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Streamly</title>
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head> --}}

    <link rel="stylesheet" href="{{ asset('frontend/bootstrap.min.css') }}" />
    @include('frontend.partials.header') 
	
<style>
    nav#mainNav {
        background-color: #004E92;
    }

    
</style>

    @section('content')
    <section class="page-section custom-row">
    
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible alert-position">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
    </div>
    @endif
    @if(session()->has('message'))
    <div class="alert alert-danger alert-dismissible alert-position">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session()->get('message') }}
    </div>
    @endif
    
       
        <section class="page-section">
            {{-- {{ Auth::user()->name }}
          <p>{{$login}} </p>   --}}
            <div class="container">
                <div id="dutylist"></div>

                
                    
    
    
    
            </div>
            
        </section>
    
@endsection
<section class="page-section">  <div class="container"><div id="dutylist"></div>

                
                    
    
    
    
            </div></section>
<script async src="{{ asset('frontend/jquery-3.5.1.min.js') }}" ></script> 
 {{-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> --}}
   
<script defer src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script>
{{-- <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script> --}}

<script async src="{{ asset('frontend/popper.min.js') }}" ></script> 
<script async src="{{ asset('frontend/bootstrap.min.js') }}" ></script>
<link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
<script>window.addEventListener('load', function() {
     
    jQuery('.logouttheuser').on('click', function(){
        jQuery('#Logout').modal('show');
    });
    
})</script>
{{-- <script  src="{{ asset('frontend/script.js') }}" ></script> --}}
<script defer src="{{ asset('js/app.js') }}"></script>
<script>
    var action = "{{ env("APP_URL") }}"
    function logoutUser() {
    window.location.href = '/logout';
}
console.log(Intl.DateTimeFormat().resolvedOptions().timeZone)
</script>

@include('frontend.partials.footer')