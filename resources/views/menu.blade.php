<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>notify-pluse-app</title>

    <!-- @viteReactRefresh
    @vite(['resources/sass/app.scss', 'resources/ts/index.tsx']) -->

</head>

<body class="antialiased">
    <div id="app">
        <p>{{ $message }}</p>
    </div>
</body>

</html>
