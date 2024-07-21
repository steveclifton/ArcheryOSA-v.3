@extends('template.default')

@section ('title')Home @endsection


@section('content')

    <div class="row">
        <div class="col-md-12 homePageBanner" style="padding-top: 20px">

            <div class="panel panel-default text-center d-lg-none text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybanner.jpg')}});">
                <div class="slider-overlay br-radius"></div>
                <div class="panel-body p-0">
                    <div class="">
                        <div id="owl-slider-2" class="owl-carousel">
                            <div class="item">
                                <h3>
                                    <a href="#" class="text-white font-600">Archery OSA</a>
                                </h3>
                                <p class="m-t-30"><em></em></p>
                                <a href="{{route('results')}}" class="btn btn-inverse waves-effect waves-light">Latest Results!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default text-center desktopOnlyImg d-none d-lg-block text-white slider-bg m-b-0"
                 style="background: url({{asset('/images/archerybannerdt1.jpg')}});">
                <div class="slider-overlay br-radius"></div>
                <div class="panel-body p-0">
                    <div class="">
                        <div id="owl-slider-2" class="owl-carousel">
                            <div class="item">
                                <h3>
                                    <a href="#" class="archeryHeadText">Archery OSA</a>
                                </h3>
                                <p class="m-t-30"><em></em></p>
                                <a href="{{route('results')}}" class="btn btn-inverse waves-effect waves-light">Latest Results!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if(Auth::check() && !empty($myevents))
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
                                        <img class="card-img-top card-image-resize img-fluid" src="{{URL::asset('/images/events/' . $event->imagedt)}}"
                                             alt="{{$event->label}}">
                                        <div class="card-body">
                                            <h4 class="card-title font-18 mt-0">{{$event->label}}</h4>
                                            <p class="card-text">Start : {!! date('d M Y', strtotime($event->start)) !!}</p>
                                            <p class="card-text">Event Level : {!! $event->level !!}</p>
                                            <p class="card-text">Region : {!! $event->region !!}</p>

                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @if (!empty(array_slice($myevents, 3)))
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
                                            <td>{{date('d M Y', strtotime($event->start))}}</td>
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


    @include('events.public.include.upcoming')

    @auth()
        <button type="button" style="display: none"
                data-toggle="modal"
                data-target="#newsmodal"></button>

        <div id="newsmodal" class="modal fade"
             tabindex="-1" role="dialog"
             aria-labelledby="full-width-modalLabel"
             aria-hidden="true" style="display: none;">

            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="full-width-modalLabel">News</h4>
                        <button type="button" class="close" data-dismiss="modal" data-hash="{{md5($message)}}" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        {!! $message  !!}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal" data-hash="{{md5($message)}}">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{URL::asset('/js/home.js')}}"></script>
    @endauth
@endsection
