@extends('app')
@section('head-data')
	<script
			  src="https://code.jquery.com/jquery-3.2.1.min.js"
			  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
			  crossorigin="anonymous"></script>
	<script type="text/javascript" src="{{url('/')}}/js/main.js"></script>
@endsection
@section('content')
	<div class="container">
		<span style="display:none" id="commentApiEndpoint" data-url="{{route('chatEndpoint')}}"></span>
		<span style="display:none" id="videoUrl" data-url="{{$videoUrl}}"></span>
		<span style="display:none" id="csrfToken" data-value="{{csrf_token()}}"></span>
		<h3>Showing comments for YouTube video: <a href="{{$videoUrl}}">{{$videoUrl}}</a></h3>
		@if(count($comments['modelData']['items'])>0)
			<div id="commentContainer">
				@foreach($comments['modelData']['items'] as $item)
					<div class="panel">
						<b>{{$item['snippet']['topLevelComment']['snippet']['authorDisplayName']}}</b><br>
						<p>
							{{$item['snippet']['topLevelComment']['snippet']['textDisplay']}}
						</p>
					</div>
				@endforeach
			</div>
			<!-- @if(isset($comments['prevPageToken']) && $comments['prevPageToken']!=null)
				<button  id="prevPageToken" data-token="{{$comments['prevPageToken']}}" class="btn btn-default tokenPagers">Previous</button>
			@endif -->
			@if(isset($comments['nextPageToken']) && $comments['nextPageToken']!=null)
				<button  id="nextPageToken" data-token="{{$comments['nextPageToken']}}" class="btn btn-default tokenPagers">Load More</button>
			@endif
		@else
			<div class="panel">No comments on this video yet.</div>
		@endif
	</div>
@endsection