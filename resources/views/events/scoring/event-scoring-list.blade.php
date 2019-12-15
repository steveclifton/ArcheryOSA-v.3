@extends('template.default')

@section ('title'){{$event->label}} Scoring @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                    /
                    <a href="javascript:;">Scoring</a>
                </h4>
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
                            <th width="20%">Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($eventcompetitions as $comp)
                            <tr>
                                <td scope="row" >
                                    <a href="/event/manage/scoring/{{$event->eventurl}}/{{$comp->eventcompetitionid}}">{{$comp->label}}</a>
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