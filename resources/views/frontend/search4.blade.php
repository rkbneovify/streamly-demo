@extends('frontend/partials/master')

@section('stylesheets-bottom')

<link rel="stylesheet" href="{{ asset('frontend/owl.carousel.min.css') }}" />
<link rel="stylesheet" href="{{ asset('frontend/owl.theme.default.css') }}" />
<script src="{{ asset('frontend/owl.carousel.min.js') }}" ></script>

    <!-- Import typeahead.js -->
    {{-- <script src="{{ asset('frontend/autocomplete/typeahead.bundle.js')}}"></script> --}}
    <script src="{{ asset('frontend/autocomplete/typeahead.bundle.min.js')}}"></script>
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

<script type="text/javascript">
  $(document).ready(function() {
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
    
    $('#search').typeahead({
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
      });
</script>
@endsection
@section('content')
<section class="page-section">
	{{-- <div class="container" style="margin-top: 50px;">
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
</div> --}}

<div class="container">
  <form class="mx-2 my-auto d-inline w-100">
<div class="input-group">
  <input type="text" id="search" class="form-control typeahead" size="50" placeholder="...">
   <span class="input-group-append">
   <button class="btn btn-outline-secondary" type="button">GO</button>
   </span>
</div>
</form>
</div>
</section>
<section class="page-section"></section>
@endsection