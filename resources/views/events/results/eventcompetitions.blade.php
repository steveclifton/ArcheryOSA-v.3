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
                <h5 class="page-title">Competition Results</h5>
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
                                <th width="20%">Date</th>
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
                                    <td></td>
                                </tr>
                            @endif

                        @foreach($eventcompetitions as $comp)
                            <tr>
                                <td scope="row" >
                                    @if (!empty($comp->score))
                                        <a href="/event/results/{{$event->eventurl}}/{{$comp->eventcompetitionid}}">{{$comp->label}}</a>
                                    @else
                                        <a href="javascript:;">{{$comp->label}}</a>
                                    @endif
                                </td>
                                <td scope="row">
                                    @if (!empty($comp->score))
                                        <a href="/event/results/{{$event->eventurl}}/{{$comp->eventcompetitionid}}">See Results</a>
                                    @else
                                        No Results Yet
                                    @endif
                                </td>
                                <td>
                                    {{$comp->date}}
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