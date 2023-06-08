<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
 
    <title>Laravel Vue JS File Upload Example</title>
 
    @vite('resources/css/app.css')
 
</head>
<body>
    <div id="app">
 
        <main class="py-4">
            @yield('content')
        </main>
 
    </div>
    @vite('resources/js/app.js')
</body>
</html>