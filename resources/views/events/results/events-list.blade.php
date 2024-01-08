@extends('template.default')

@section ('title')Previous Events @endsection

@section('content')
    @if(!empty($events))

        <div class="row" style="padding-top: 20px; padding-bottom: 10px">
            <div class="col-lg-12">
                <h3 style="text-align: center">
                    Completed Events
                </h3>

                <div class="tab-content" style="background: white; border: 2px solid lightgrey;">

                    @if (!empty($events))
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <th scope="row"><a href="/event/results/{{$event->eventurl}}">{{$event->label}}</a></th>
                                        <td>{{date('d M Y', strtotime($event->start))}}</td>
                                        <td>{{date('d M Y', strtotime($event->end))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif
@endsection