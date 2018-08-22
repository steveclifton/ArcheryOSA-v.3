@extends('template.default')

@section ('title')Previous Events @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title "><a href="/events">Events Results</a></h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">

            <div class="card-columns">
                @foreach(array_slice($events, 0, 3) as $event)
                    <a href="/event/results/{{$event->eventurl}}">
                        <div class="card m-b-20">
                            <img class="card-img-top img-fluid" src="{{URL::asset('/images/' . $event->imagedt)}}"
                                 alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title font-18 mt-0">{{$event->label}}</h4>
                                <p class="card-text">Start : {!! date('d F Y', strtotime($event->start)) !!}</p>
                                <p class="card-text">Finish : {!! date('d F Y', strtotime($event->end)) !!}</p>
                                <p class="card-text">Status : {{$event->eventstatus}}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if(!empty(array_slice($events, 3)))
        <!-- Section-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <h4 class="page-title">Events</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">

                    @if (!empty(array_slice($events, 1)))
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Start</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(array_slice($events, 3) as $event)
                                    <tr>
                                        <th scope="row"><a href="/event/results/{{$event->eventurl}}">{{$event->label}}</a></th>
                                        <td>{{date('d F Y', strtotime($event->start))}}</td>
                                        <td class="text-success">{{$event->eventstatus}}</td>
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