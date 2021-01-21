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
		<script>
		
			$(document).ready(function() {
			
			$.ajaxSetup({
	
	headers: {
	
			'X-CSRF-TOKEN': $("input[name=_token]").val()
	
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
@endsection


@section('content')

<section class="page-section custom-row">

	<div class="container" style="margin-top: 4%;">
		<div class="page-header">
		<h3>My Recent</h3>
</div>
		@forelse($myRecent->chunk(6) as $chunk)
	<div class="row">
			@foreach($chunk as $channeldet)
			{{-- {{$channeldet}} --}}
			<?php $options = ''; $arrVal=explode(',',$channeldet->genres);  foreach ($genres as $id => $genre) {
				         if(in_array($id, $arrVal)){
																 
				         $options .= ''.$genre.' ';
				     } else{
				         $options .= '';}
								
						 } 
						 $genr=$options? $options:'Others'; ?>
						 
			<div class="col-md-2">
				
				<div class="live-news-item">
					<a href="{{url('livetv/'.$channeldet->callSign.'/'.$genr)}}">
					

					<img class="lazy" data-src="{{  $channeldet->logo}}" loading="lazy"  alt="{{  $channeldet->name}}">
					<p>{{  $channeldet->stationName??$channeldet->name}}</p></a>
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
				
			</div>
			@endforeach
	</div>
	@empty
	<p>You have no recent posts.</p>
@endforelse

</div>
</div>
</section>
@endsection