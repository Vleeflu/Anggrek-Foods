<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Anggrek Foods') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('Logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body>
    <div id="app" data-page="{{ json_encode($page) }}"></div>
</body>
</html>
