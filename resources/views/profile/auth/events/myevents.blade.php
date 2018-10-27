@extends('template.default')

@section ('title') My Events @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">My Events</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Entry Status</th>
                            <th>Event Status</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($myevents as $event)
                                <tr>
                                    <th scope="row">
                                        <a href="/event/results/{{$event->eventurl}}">{{$event->label}}</a>
                                    </th>
                                    <td>{{date('d F Y', strtotime($event->start))}}</td>
                                    <td>{{$event->status}}</td>
                                    <td>{{$event->eventstatus}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection