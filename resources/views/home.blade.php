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
             style="background: url(https://www.streamsongresort.com/wp-content/uploads/2016/01/archery-hero-1800.jpg);">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="text-white font-600">Welcome to Archery OSA!</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em>Our Most recent event was bla bla bla</em></p>
                            <button class="btn btn-inverse btn-sm m-t-40">See Scores</button>
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
                <li class="nav-item tab">
                    <a href="#myevents" data-toggle="tab" aria-expanded="true" class="nav-link">
                        My Events
                    </a>
                </li>
                <li class="nav-item tab">
                    <a href="#upcoming" data-toggle="tab" aria-expanded="false" class="nav-link active show">
                        Upcoming Events
                    </a>
                </li>
                <li class="nav-item tab">
                    <a href="#previous" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Previous Events
                    </a>
                </li>

            </ul>

            <div class="tab-content">
                <div class="tab-pane" id="myevents">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Dates</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">2018 Indoor League Series</th>
                                <td>Nationwide</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">2018 ADAA Indoor Championships</th>
                                <td>MGAC Indoor Range, 149 Royal Road, Massey, Auckland</td>
                                <td>13-07-2018</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                                <td>21-07-2018</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane active" id="upcoming">

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
                                @foreach($upcomingevents as $event)
                                    <tr>
                                        <th scope="row"><a href="/event/details/{{$event->eventurl}}">{{$event->label}}</a></th>
                                        <td>{{date('d F Y', strtotime($event->start))}}</td>
                                        <td class="text-success">{{$event->status}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="previous">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Dates</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">2018 Indoor League Series</th>
                                <td>Nationwide</td>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">2018 ADAA Indoor Championships</th>
                                <td>MGAC Indoor Range, 149 Royal Road, Massey, Auckland</td>
                                <td>13-07-2018</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Larry</td>
                                <td>21-07-2018</td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    {{--  </div>
    </div> --}}


@endsection
