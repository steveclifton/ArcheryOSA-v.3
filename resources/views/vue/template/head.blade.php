<head>
    <title>@yield('title', 'Admin ')| Archery OSA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="description" content="@yield('description','Archery OSA is a free online archery event management system which allows you to run your archery events completely online')"/>
    <meta property="og:description" content="@yield('description','Archery OSA is a free online archery event management system which allows you to run your archery events completely online')" />
    <link rel="canonical" href="https://archeryosa.com/@yield('url', '')" itemprop="url">
    <link rel="canonical" href="https://archeryosa.com/@yield('url', '')">
    <meta property="og:url" content="https://archeryosa.com/@yield('url', '')" />
    <meta property="og:image" content="{{URL::asset('/images/archeryosablack.jpg')}}">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="ArcheryOSA" />
    <meta property="og:site_name" content="ArcheryOSA" />
    <link rel="icon" href="{{URL::asset('/images/favion.ico')}}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{URL::asset('/images/favion.ico')}}" type="image/x-icon" />


    @if (getenv('APP_LIVE') && !empty(getenv('GOOGLE_UA')))
        <script src='https://www.google.com/recaptcha/api.js'></script>

        <script async src="https://www.googletagmanager.com/gtag/js?id={{getenv('GOOGLE_UA')}}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{getenv('GOOGLE_UA')}}');
        </script>
    @endif

    @if (getenv('APP_LIVE') && !empty(getenv('GOOGLE_GTM')))
        <script>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','{{getenv('GOOGLE_GTM')}}');
        </script>
    @endif


    <link href="/vue/css/app.css" rel="stylesheet" type="text/css" />
    <link href="/vue/css/all.css" rel="stylesheet" type="text/css" />
    <link href="/vue/css/icons.min.css" rel="stylesheet" type="text/css" />

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>