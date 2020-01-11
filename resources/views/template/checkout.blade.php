<!DOCTYPE html>
<html>
<head>
    @include('template.head')
    <title>Checkout | Archery OSA</title>
    @include('template.styles')

</head>


<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PL2ND3T"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>

    @include('template.navigation')

    <div class="wrapper">
        <div class="container-fluid">

            @yield('content')

            @include('template.footer')

            @include('template.scripts')

        </div>
    </div>

</body>
</html>