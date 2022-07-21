<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	<meta name="description" content="Success App Demo">
	<meta name="author" content="Sreejith B S">

	<title> @yield('page_title') | Success App Demo </title>

	<link rel="apple-touch-icon" href="">
	<link rel="shortcut icon" type="image/x-icon" href="">

	@include('backend._partials.styles')

	@yield('page_styles')

</head>

<body>

	<!-- NAVBAR -->
	@include('backend._partials.navbar')

	<main class="container">
		@yield('content')
	</main>

	<!-- FOOTER -->
	@include('backend._partials.footer')

	<!-- Scripts -->
	@include('backend._partials.scripts')
	@stack('page_scripts')

</body>

</html>