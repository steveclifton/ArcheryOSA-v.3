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

    <div class="col-md-12" style="padding:0;">

        {{-- <p class="text-muted m-b-30 font-13">A slideshow component for cycling through elements, like a carousel.</p> --}}

        <div class="panel panel-default text-center text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybanner.jpg')}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="text-white font-600">Archery OSA</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em></em></p>
                            <button class="btn btn-inverse btn-sm m-t-40">Latest Results</button>
                        </div><!-- /.item -->


                    </div><!-- /#tiles-slide-2 -->
                </div>
            </div> <!-- panel-body -->
        </div><!-- Panel -->
    </div> <!-- col-->

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                {{-- <h4 class="page-title">Upcoming Events</h4> --}}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs">
                @if(Auth::check())
                    <li class="nav-item tab">
                        <a href="#myevents" data-toggle="tab" aria-expanded="true" class="nav-link">
                            My Events
                        </a>
                    </li>
                @endif
                <li class="nav-item tab">
                    <a href="#upcoming" data-toggle="tab" aria-expanded="false" class="nav-link active show">
                        Upcoming Events
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                @if(Auth::check())
                    <div class="tab-pane" id="myevents">
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
                            <tr>
                                <th scope="row">2018 Indoor League Series</th>
                                <td>13-07-2018</td>
                                <td>Open</td>
                            </tr>
                            <tr>
                                <th scope="row">2018 ADAA Indoor Championships</th>
                                <td>13-07-2018</td>
                                <td>Open</td>
                            </tr>
                            <tr>
                                <th scope="row">2018 Something Event</th>
                                <td>21-07-2018</td>
                                <td>Open</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                <div class="tab-pane active" id="upcoming">
                    <div class="card-columns">
                        @foreach(array_slice($upcomingevents, 0, 3) as $event)
                            <a href="/event/details/{{$event->eventurl}}">
                                <div class="card m-b-20">
                                    <img class="card-img-top img-fluid" src="{{URL::asset('/images/' . $event->imagedt)}}"
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
    {{--  </div>
    </div> --}}


@endsection
