<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/css/jquery.numpad.css')}}">
    @yield('css')
    <title>{{ ChuckSite::getSite('name') }} POS</title>
</head>
<body>
    @yield('content')
    <script src="https://kit.fontawesome.com/e23a04b30b.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/jquery.min.js')}}"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/cptable.js') }}"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/cputils.js') }}"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/zip-full.min.js') }}"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/JSESCPOSBuilder.js') }}"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/JSPrintManager.js') }}"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/jquery.numpad.js')}}" type="text/javascript"></script>
    <script src="{{asset('chuckbe/chuckcms-module-ecommerce/js/onScan.js')}}"></script>

    @yield('scripts') 
</body>
</html>