<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
	<!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>{{ config('app.name', 'Whisper') }}</title>
	<!--favicon-->
  <link rel="icon" href="{{ asset('assets/images/whisper-icon.png') }}" type="image/png" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
	<!-- Icons CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/icons.css') }}" />
	<!-- App CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
</head>

<body class="bg-login">
	<!-- wrapper -->
	<div class="wrapper">
		{{ $slot }}
	</div>
	<!-- end wrapper -->
</body>

</html>
