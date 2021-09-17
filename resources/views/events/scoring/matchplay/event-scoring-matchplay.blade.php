@extends('template.default')

@section ('title'){{$event->label}} Scoring @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/event/scoring/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                    /
                    <a href="javascript:;">Matchplay</a>
                </h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs"></ul>

            <div class="tab-content">
                <div style="margin-tops: 20px">
                    <div class="alert hidden" role="alert" ></div>
                </div>
                <a role="button" href="/event/matchplay/{{$event->eventurl}}/create" class="myButton btn btn-success">Create Matchplay Event</a>

                <div class="tab-pane active" id=""><br>
                    <div class="card-box">
                        @include('template.alerts')
                        <p></p>
                        <div class="myTable table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>Round</th>
                                    <th>Division</th>
                                    <th>Entries</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($event->getMatchplayEvents() as $matchplayEvent)
                                    <tr>
                                        <th scope="row">
                                            <a href="/event/matchplay/{{$event->eventurl}}/{{$matchplayEvent->id}}">{{$matchplayEvent->getRoundGenderLabel()}}</a>
                                        </th>
                                        <td>{{$matchplayEvent->getDivision()->label ?? ''}}</td>
                                        <td>{{$matchplayEvent->count}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

@endsection