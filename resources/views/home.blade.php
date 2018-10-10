@extends('template.default')

@section ('title')Home @endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                {{-- <h4 class="page-title">Upcoming Events</h4> --}}
            </div>
        </div>
    </div>

    <div class="col-md-12 homePageBanner">

        {{-- <p class="text-muted m-b-30 font-13">A slideshow component for cycling through elements, like a carousel.</p> --}}

        <div class="panel panel-default text-center d-lg-none text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybanner.jpg')}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="text-white font-600">Archery OSA</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em></em></p>
                            <a href="{{route('results')}}" class="btn btn-inverse waves-effect waves-light">Latest Results!</a>
                        </div><!-- /.item -->
                    </div><!-- /#tiles-slide-2 -->
                </div>
            </div> <!-- panel-body -->
        </div><!-- Panel -->

        <div class="panel panel-default text-center desktopOnlyImg d-none d-lg-block text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybannerdt1.jpg')}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="archeryHeadText">Archery OSA</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em></em></p>
                            <a href="{{route('results')}}" class="btn btn-inverse waves-effect waves-light">Latest Results!</a>
                        </div><!-- /.item -->
                    </div><!-- /#tiles-slide-2 -->
                </div>
            </div> <!-- panel-body -->
        </div>
    </div> <!-- col-->

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
            </div>
        </div>
    </div>

    {{--Upcoming--}}
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs">

                <li class="nav-item tab">
                    <a href="#upcoming" data-toggle="tab" aria-expanded="true" class="nav-link active show">
                        Upcoming
                    </a>
                </li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="upcoming">
                    <div class="card-columns">
                        @foreach(array_slice($upcomingevents, 0, 3) as $event)
                            <a href="/event/details/{{$event->eventurl}}">
                                <div class="card m-b-20">
                                    <img class="card-img-top img-fluid" src="{{URL::asset('/images/events/' . $event->imagedt)}}"
                                         alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title font-18 mt-0">{{$event->label}}</h4>
                                        <p class="card-text">Start : {!! date('d F Y', strtotime($event->start)) !!}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if (!empty(array_slice($upcomingevents, 3)))
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
                                @foreach(array_slice($upcomingevents, 3) as $event)
                                    <tr>
                                        <th scope="row"><a href="/event/details/{{$event->eventurl}}">{{$event->label}}</a></th>
                                        <td>{{date('d F Y', strtotime($event->start))}}</td>
                                        <td class="text-success">{{$event->eventstatus}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h4 class="page-title"><a href="/events">See all events</a></h4>
                    @endif
                </div>
            </div>
        </div>
    </div>


    @if(Auth::check())
        {{--My Events--}}
        <div class="row">
            <div class="col-lg-12">
                <ul class="nav nav-tabs tabs">
                    <li class="nav-item tab">
                        <a href="#myevents" data-toggle="tab" aria-expanded="true" class="nav-link">
                            My Events
                        </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active" id="myevents">
                            <div class="card-columns">
                                @foreach(array_slice($myevents, 0, 3) as $event)
                                    <a href="/event/details/{{$event->eventurl}}">
                                        <div class="card m-b-20">
                                            <img class="card-img-top img-fluid" src="{{URL::asset('/images/events/' . $event->imagedt)}}"
                                                 alt="Card image cap">
                                            <div class="card-body">
                                                <h4 class="card-title font-18 mt-0">{{$event->label}}</h4>
                                                <p class="card-text">Start : {!! date('d F Y', strtotime($event->start)) !!}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                            @if (!empty(array_slice($upcomingevents, 3)))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Start</th>
                                                <th>Entry Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(array_slice($myevents, 3) as $event)
                                            <tr>
                                                <th scope="row"><a href="{{route('event', ['eventurl'=>$event->eventurl])}}">{{$event->label}}</a></th>
                                                <td>{{date('d F Y', strtotime($event->start))}}</td>
                                                <td>{{$event->status}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
    @endif


    {{--Results--}}
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs">
                <li class="nav-item tab">
                    <a href="#results" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Results
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="results">
                    <div class="card-columns">
                        @foreach(array_slice($resultevents, 0, 3) as $event)
                            <a href="/event/details/{{$event->eventurl}}">
                                <div class="card m-b-20">
                                    <img class="card-img-top img-fluid" src="{{URL::asset('/images/events/' . $event->imagedt)}}"
                                         alt="Card image cap">
                                    <div class="card-body">
                                        <h4 class="card-title font-18 mt-0">{{$event->label}}</h4>
                                        <p class="card-text">Start : {!! date('d F Y', strtotime($event->start)) !!}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    @if (!empty(array_slice($resultevents, 3)))
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
                                @foreach(array_slice($resultevents, 3) as $event)
                                    <tr>
                                        <th scope="row"><a href="/event/details/{{$event->eventurl}}">{{$event->label}}</a></th>
                                        <td>{{date('d F Y', strtotime($event->start))}}</td>
                                        <td class="text-success">{{$event->eventstatus}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h4 class="page-title"><a href="/events">See all events</a></h4>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
