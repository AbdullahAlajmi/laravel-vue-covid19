<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Covid19 World Map</title>
    <link rel="stylesheet" type="text/css" href="http://jvectormap.com/css/jquery-jvectormap-2.0.1.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div id="app">
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.0.js"></script>
<script type="text/javascript" src="http://jvectormap.com/js/jquery-jvectormap-2.0.1.min.js"></script>
<script type="text/javascript" src="http://jvectormap.com/js/jquery-jvectormap-world-mill.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script src={{ asset("/js/app.js") }}></script>

</body>
</html>
