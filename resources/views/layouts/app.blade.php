<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<title>{{ config('app.name') }}</title>
	<!--favicon-->
	<link rel="icon" href="{{ asset('assets/images/whisper-icon.png') }}" type="image/png" />
	<!-- Vector CSS -->
	<link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
	<!--plugins-->
	<link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
	<script src="{{ asset('assets/js/pace.min.js') }}"></script>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
	<!-- Icons CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/icons.css') }}" />
	<!-- App CSS -->
	<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/dark-sidebar.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />

	@yield('more-styles')
</head>

<body>
	<!-- wrapper -->
	<div class="wrapper">
		<!--sidebar-wrapper-->
	  @component('components.sidebar')@endcomponent
		<!--end sidebar-wrapper-->
		<!--header-->
		@component('components.header')@endcomponent
		<!--end header-->
		<!--page-wrapper-->
		<div class="page-wrapper">
			<!--page-content-wrapper-->
			{{ $slot }}
			<!--end page-content-wrapper-->
		</div>
		<!--end page-wrapper-->
		<!--start overlay-->
		<div class="overlay toggle-btn-mobile"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<!--footer -->
		@component('components.footer')@endcomponent
		<!-- end footer -->
	</div>

	<!-- JavaScript -->
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ asset('assets/js/popper.min.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<!--plugins-->
	<script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
	<!-- Vector map JavaScript -->
	<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
	<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-in-mill.js') }}"></script>
	<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-us-aea-en.js') }}"></script>
	<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-uk-mill-en.js') }}"></script>
	<script src="{{ asset('assets/plugins/vectormap/jquery-jvectormap-au-mill.js') }}"></script>
	<script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
	<script src="{{ asset('assets/js/index2.js') }}"></script>
	<!-- App JS -->
	<script src="{{ asset('assets/js/app.js') }}"></script>

	@yield('more-scripts')
</body>

</html>
