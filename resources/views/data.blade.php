@foreach($posts as $post)

<div>

	<p><a href="">{{ $post->name }}</a></p>

	<p>{{$post->url }}</p>






	<hr style="margin-top:5px;">

</div>

@endforeach