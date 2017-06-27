@extends('app')
@section('content')
	<div class="container">
		<h1>OAuth Works</h1>
		<div>
			<img src="{{$userData['picture']}}" class="img-responsive"></img> Hello, {{$userData['name']}}!
		</div>
		<h4>Your Youtube subscriptions</h4>
		@if(count($subscriptions['modelData']['items'])>0)
			<ul>
				@foreach($subscriptions['modelData']['items'] as $item)
					<li>
						<div>
							<strong>{{$item['snippet']['title']}}</strong>
						</div>
					</li>
				@endforeach
			</ul>
		@if($subscriptions['prevPageToken']!=null)
			<a href="{{route('viewSubscriptions')}}?pageToken={{$subscriptions['prevPageToken']}}" class="btn btn-default">Previous</a>
		@endif
		@if($subscriptions['nextPageToken']!=null)
			<a href="{{route('viewSubscriptions')}}?pageToken={{$subscriptions['nextPageToken']}}" class="btn btn-default">Next</a>
		@endif
		@else
			<span>No subscriptions found. Subscribe to some channels and get back here.</span>
		@endif
	</div>
@endsection