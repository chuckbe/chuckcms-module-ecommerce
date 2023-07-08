<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/css/bootstrap.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/jquery.numpad.css')}}">
    {{-- <link rel="icon" type="image/x-icon" href="{{ URL::to('favicon.ico') }}" /> --}}
    @yield('css')
    <title>{{ ChuckSite::getSite('name') }}</title>
    {{-- <link rel="manifest" href="{{ asset('manifest.json') }}"> --}}
</head>
<body>
@yield('content')

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
{{ csrf_field() }}
</form>

<script>
    // if ('serviceWorker' in navigator) {
    //     window.addEventListener('load', function() {
    //         navigator.serviceWorker.register('/sw.js');
    //     });
    // }
</script>
<script src="https://kit.fontawesome.com/e23a04b30b.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bluebird/3.3.5/bluebird.min.js"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/jquery.min.js')}}"></script>

<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/popper.min.js')}}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/bootstrap.min.js')}}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/cptable.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/cputils.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/zip-full.min.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/JSESCPOSBuilder.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/JSPrintManager.js') }}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/jquery.numpad.js')}}" type="text/javascript"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/onScan.js')}}"></script>
<script src="{{asset('chuckbe/chuckcms-module-ecommerce/scripts/sweetalert2.all.min.js')}}"></script>
{{-- <script src="{{asset('chuckbe/chuckcms-module-order-form/scripts/offline.min.js')}}"></script> --}}
@yield('scripts')
</body>
</html>
