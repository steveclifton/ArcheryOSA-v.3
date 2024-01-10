<div class="row" style="padding-top: 20px; padding-bottom: 10px">
    <div class="col-lg-12">
        <h3 style="text-align: center">
            Upcoming Events
        </h3>

        <div class="tab-content" style="background: white; border: 2px solid lightgrey;">
            @if (!empty($upcomingevents))
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Start</th>
                            <th>Region</th>
                            <th>Type</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($upcomingevents as $event)
                            <tr>
                                <th scope="row">
                                    <a href="/event/details/{{$event->eventurl}}">
                                        {{strlen($event->label) < 44 ? $event->label : (substr($event->label, 0, 44) . "...") }}
                                    </a>
                                </th>
                                <td>{{date('d M', strtotime($event->start))}}</td>
                                <td>{{$event->region}}</td>
                                <td>{!! $event->level !!}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

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
