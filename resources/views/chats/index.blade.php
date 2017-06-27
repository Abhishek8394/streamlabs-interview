@extends('app')
@section('content')
	<div class="container">
		@if(isset($error))
			<span class="panel-danger">{{$error}}</span>
		@endif
		{!! Form::open(['url'=>route('fetchChats'), 'method'=>'post']) !!}
			<div class="form-group">
				<input class='form-control' name='urlbar' id='urlbar' placeholder = 'url of youtube live stream' required="true">
			</div>
			<div class="">
				<button type="submit" class="btn btn-primary" >View Chats</button>
			</div>
		{!! Form::close() !!}
	</div>
@endsection