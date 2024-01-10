@extends('template.default')

@section ('title')Upcoming Events @endsection

@section('content')

    @include('events.public.include.upcoming')

    <div class="row" style="padding-bottom: 10px; height: auto !important;">
        <div class="col-12 text-center mx-auto my-0 py-2 px-2" style="height: auto !important;">
            <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4820276379493226"
                    crossorigin="anonymous"></script>
            <!-- Upcoming-Events-Ad -->
            <ins class="adsbygoogle"
                 style="display:block"
                 data-ad-client="ca-pub-4820276379493226"
                 data-ad-slot="1948476066"
                 data-ad-format="auto"
                 data-full-width-responsive="true"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>

@endsection