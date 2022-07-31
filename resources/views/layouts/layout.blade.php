<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>再翻訳河原</title>
    @vite(['resources/js/app.js'])
</head>

<body>
    @include('parts.header')
    @yield('content')

</body>

</html>
