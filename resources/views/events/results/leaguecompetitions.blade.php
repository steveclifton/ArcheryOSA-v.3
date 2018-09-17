@extends('template.default')

@section ('title'){{ucwords($event->label)}} Competitions @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/events">Events</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/event/details/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Results</a>
                </h4>
            </div>
        </div>
    </div>

    <!-- Section-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h5 class="page-title">League Competition Results</h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                @include('template.alerts')
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="40%">Event Competition</th>
                                <th width="40%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($overall))
                                <tr>
                                    <td scope="row" >
                                        <a href="/event/results/{{$event->eventurl}}/overall">Overall</a>
                                    </td>
                                    <td scope="row">
                                        <a href="/event/results/{{$event->eventurl}}/overall">See Results</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td scope="row" >
                                        <a href="/event/results/{{$event->eventurl}}/handicap">Handicap</a>
                                    </td>
                                    <td scope="row">
                                        <a href="/event/results/{{$event->eventurl}}/handicap">See Results</a>
                                    </td>
                                </tr>
                            @endif

                        @foreach(range(1, $eventcompetition->currentweek) as $week)
                            <tr>
                                <td scope="row" >
                                    <a href="/event/results/{{$event->eventurl}}/{{$week}}">Week {{$week}}</a>
                                </td>
                                <td scope="row">
                                    <a href="/event/results/{{$event->eventurl}}/{{$week}}">See Results</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection