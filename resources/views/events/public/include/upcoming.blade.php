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
                                        {{strlen($event->label) < 42 ? $event->label : (substr($event->label, 0, 42) . "...") }}
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
