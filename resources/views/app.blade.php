<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="{{ app()->environment('local') ? mix('css/app.css') : config('app.spaces-url') . 'css/app.css' }}">
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="icon" type="image/png" href="/favicon.png">
    <script src="https://js.pusher.com/4.4/pusher.min.js"></script>
</head>
<body>
    <div id="app">
        <App></App>
    </div>
    <script src="{{ app()->environment('local') ? mix('js/app.js') : config('app.spaces-url') . 'js/app.js' }}"></script>
</body>
</html>
