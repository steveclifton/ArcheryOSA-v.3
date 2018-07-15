<!DOCTYPE html>
<html>
<head>
    @include('template.head')
    <title>@yield('title')| Archery OSA</title>
    @include('template.styles')

</head>


<body>

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