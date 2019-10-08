@extends('template.default')

@section ('title')Event Registration @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/event/details/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                        <i class="ion-arrow-right-c"></i>
                    <a href="/event/register/{{$event->eventurl}}">Archers</a>
                </h4>
            </div>
        </div>
    </div>


    <!-- Section-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h5 class="page-title">Select the competition to enter</h5>
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
                                <th width="50%">Event Competition</th>
                                <th width="40%">Date</th>
                                <th width="">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($eventcompetitions as $eventcompetition)
                                <tr>
                                    <th scope="row">
                                        <a href="/event/registration/{{$event->eventurl}}/{{ $eventcompetition->username }}/{{ $eventcompetition->eventcompetitionid }}">{{$eventcompetition->label}}</a>
                                    </th>

                                    <td class="">{{date('d F Y', strtotime($eventcompetition->date))}}</td>
                                    <td class="text-{{getEntryStatusText($eventcompetition->entrystatusid)}}">
                                        {{ $eventcompetition->status }}
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
