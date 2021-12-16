<?php use App\Models\Genre;
$genres = Genre::pluck('name', 'id');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	

	<meta charset="utf-8">
	{{-- <meta name="viewport" content="width=device-width, initial-scale=1"> --}}

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>{{ config('app.name', 'Streamly') }}</title>
	<link rel="icon" type="image/png" href="{{ asset('frontend/img/favicon/streamy_favicon_16.png') }}"/>
	
	<!-- Bootstrap style -->
	@yield('top-stylesheets')
	<link rel="stylesheet" href="{{ asset('frontend/bootstrap.min.css') }}" />

	<!-- Bootstrap script -->

	@yield('stylesheets')
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E50Q5Q9J8D"></script>
@if (Auth::check())
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

	gtag('config', 'G-E50Q5Q9J8D', {
  'user_id': {{Auth::user()->id}}
});
</script>
@else
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-E50Q5Q9J8D');
	
</script>
@endif
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
	new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
	j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
	'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-T3Z4VHS');</script>
	<!-- End Google Tag Manager -->
	<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-T3Z4VHS"
	height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

</head>
<body>

@include('frontend.partials.header')

@yield('content')

@include('frontend.partials.footer',['genres'=>$genres])
<script  src="{{ asset('frontend/jquery-3.5.1.min.js') }}" ></script> 
{{-- <script src='http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.5/jquery-ui.min.js'></script> --}}
{{-- <script defer src="{{ asset('frontend/jquery-ui.min.js') }}" ></script>  --}}
<script defer src="{{ asset('frontend/popper.min.js') }}" ></script> 
<script defer src="{{ asset('frontend/bootstrap.min.js') }}" ></script>

<link rel="stylesheet" href="{{ asset('frontend/style.css') }}" />
<script defer src="{{ asset('frontend/script.js') }}" ></script>

    <!-- Import typeahead.js -->
    <script defer src="{{ asset('frontend/autocomplete/typeahead.bundle.min.js')}}"></script>
@yield('stylesheets-bottom')

</body>
</html>


<style>
  .typeahead::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #fafafa;
  opacity: 1; /* Firefox */
}
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

				var output='';
    var url='';
      var bloodhound = new Bloodhound({
      datumTokenizer: Bloodhound.tokenizers.whitespace,
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      remote: {
        url: '/user/find?q=%QUERY%',
        wildcard: '%QUERY%'
      },
    });
    
    $('#search-typehead').typeahead({
      hint: true,
      highlight: true,
      minLength: 1
    }, {
      name: 'users',
      source: bloodhound,
      display: function(data) {
        console.log(data);
        return data.StationName  //Input value to be set when you select a suggestion. 
      },
      templates: {
        empty: [
          '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
        ],
        header: [
          '<div class="list-group search-results-dropdown">'
        ],
        suggestion: function(data) {
          if(data.type=='event'){
            url='/upcomingevent/'+data.CallSign+'/'+data.StationName;
            // console.log(url);
           output='<a href="'+url+'"<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item"><img src="../frontend/autocomplete/tv_show.png" width="70px" height="70px"> Show: '+ data.StationName + '</div></a></div>'
          }else{
            // if(data.CallSign==null){
            //   url='/livetv/'+data.StationName+'/'+data.genrename;
            // }else{
            //   url='/livetv/'+data.CallSign+'/'+data.genrename;
            // }
            url='/livetv/'+data.CallSign+'/'+data.genrename;
            
            // if (!empty($row->callSign)){
            //         $url=$FRONTEND_URL.'/livetv/'.$row->callSign.'/'.$row->genrename['0'];}else{
            //             $url=$FRONTEND_URL.'/livetv/'.$row->name.'/'.$row->genrename['0']; 
            //         }
            // console.log(url);
            output='<a href="'+url+'"<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item"> <img src="'+data.image+'" width="auto" height="70px">Channel: '+ data.StationName + '</div></a></div>'
          }
        return output;
        }
      }
    });
			 /**
							$('#search-btn-header').on('keyup',function() {
											var query = $(this).val(); 
											$.ajax({
													 
															url:"{{ route('autocomplete') }}",
									
															type:"GET",
													 
															data:{'search':query},
													 
															success:function (data) {
																	// console.log(data);
																			$('#search_list').html(data);
															}
											})
											// end of ajax call
							});
							*/
	
							
							$(document).on('click', 'li', function(){
									
											var value = $(this).text();
											$('#search').val(value);
											$('#search_list').html("");
											// alert(value);
							});
			});
	</script>