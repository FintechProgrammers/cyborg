<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ Config::get('app.name', 'Laravel') }} | @yield('title')</title>

    @include('admin.layouts.partials._styles')
</head>

<body>

    @include('admin.layouts.partials._scripts')
</body>

</html>
