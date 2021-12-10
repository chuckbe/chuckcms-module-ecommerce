<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/css/jquery.numpad.css')}}">
    @yield('css')
    <title>{{ ChuckSite::getSite('name') }} POS</title>
</head>
<body>
    @yield('content')
    @yield('scripts') 
</body>
</html>