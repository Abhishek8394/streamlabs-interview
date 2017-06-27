<html>
	<head>
		<title>Laravel</title>
		
		<link href="{{ asset('/css/app.css') }}" rel="stylesheet">

	<!-- Fonts -->
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>


		<style>
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-weight: 100;
				font-family: 'Lato';
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 96px;
				margin-bottom: 40px;
			}

			.quote {
				font-size: 24px;
			}

		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				<div class="panel">
					<h3><a href="{{route('viewSubscriptions')}}">View OAuth Sample</a></h3>
					<p class="quote">Shows your subscribed channels on YouTube</p>
				</div>
				<div class="panel">
					<h3><a href="{{route('viewChat')}}">View Comments</a></h3>
					<p class="quote">Shows comments of a youtube video</p>
				</div>
			</div>
		</div>
	</body>
</html>
