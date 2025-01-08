<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="user" content="{{ Auth::user() }}">

<!-- Favicon icon -->
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon.png')}}">
<title>Sistema de eventos QR</title>