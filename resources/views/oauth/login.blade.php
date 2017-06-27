@extends('app')
@section('content')
	<div class="container">
	<div class="jumbotron">
		<h4 class="display-3">Login Required</h4>
		<div class="lead">
			Please login with your <a href="{{$authurl}}">Google Account</a>.
		</div>
	</div>
	</div>
@endsection