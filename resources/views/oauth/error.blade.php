@extends('app')
@section('content')
	<div class="container">
	<div class="jumbotron">
		<h2 class="display-3">Error accessing data</h2>
		<div class="lead">
			Got <b>{{$error}}</b> from server<br>
			Please login with your <a href="{{$authurl}}">Google Account</a> and provide the requested permissions.
		</div>
	</div>
	</div>
@endsection