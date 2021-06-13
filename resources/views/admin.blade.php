<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>{{ config('app.name', 'Laravel') }}</title>

		<!-- Fonts -->
		<link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">

		<!-- Styles -->
		<link rel="stylesheet" href="https://unpkg.com/@coreui/coreui/dist/css/coreui.min.css">
		<link rel="stylesheet"
			href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css"
			integrity="sha512-n+g8P11K/4RFlXnx2/RW1EZK25iYgolW6Qn7I0F96KxJibwATH3OoVCQPh/hzlc4dWAwplglKX8IVNVMWUUdsw=="
			crossorigin="anonymous" referrerpolicy="no-referrer" />
		<link rel="stylesheet" href="{{ mix('css/app.css') }}">

		<!-- Scripts -->
		@larasearchHead(true)
		@routes
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
		<script src="{{ mix('js/app.js') }}" defer></script>
	</head>
	<body>
		@inertia
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"
			integrity="sha512-yUNtg0k40IvRQNR20bJ4oH6QeQ/mgs9Lsa6V+3qxTj58u2r+JiAYOhOW0o+ijuMmqCtCEg7LZRA+T4t84/ayVA=="
			crossorigin="anonymous" referrerpolicy="no-referrer"></script>
		<script src="https://unpkg.com/@popperjs/core@2"></script>
		<script src="https://unpkg.com/@coreui/coreui/dist/js/coreui.min.js"></script>
	</body>
</html>
