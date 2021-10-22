<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('css')
    <title>{{ ChuckSite::getSite('name') }} POS</title>
</head>
<body>
    @yield('content')
    @yield('scripts') 
</body>
</html>