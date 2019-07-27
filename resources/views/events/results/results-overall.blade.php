@extends('template.default')

@section ('title') {{ucwords($event->label) }} Overall Results @endsection

@section('content')

    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet">



    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/events">Events</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/event/results/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Overall</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-12 homePageBanner">
        <div class="panel panel-default text-center d-lg-none text-white slider-bg m-b-0"
             style="background-position:center !important;
                     background-size:contain !important;
                     background-size: cover !important;
                     background-repeat: no-repeat;
                     width: 100%;
                     background: url({{asset('images/events/' . $event->imagedt)}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="text-white font-600">{{ucwords($event->label)}}</a></h3>

                            <p class="m-t-30"><em></em></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default text-center desktopOnlyImg d-none d-lg-block text-black slider-bg m-b-0"
             style="background-position:center !important;
                     background-size:contain !important;
                     background-size: cover !important;
                     background-repeat: no-repeat;
                     width: 100%; background: url({{asset('images/events/' . $event->imagedt)}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="archeryHeadText">{{ucwords($event->label)}}</a></h3>
                            <p class="m-t-30"><em></em></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- col-->



    <div class="row">
        <div class="col-sm-3 weekSelector">
        </div>
    </div>

    <h3>Overall Results</h3>
	<div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs">
                @php $i = 1; @endphp
                @foreach($finalResults as $bowtype => $e)
                    <li class="nav-item tab">
                        <a href="#{{$bowtype}}" data-toggle="tab" aria-expanded="false" class="nav-link {!! $i++ === 1 ? 'active' : '' !!}  show">
                            {{ucwords($bowtype)}}
                        </a>
                    </li>
                @endforeach
            </ul>

    <div class="tab-content">
        @php $i = 1; @endphp
        @foreach ($finalResults as $bowtype => $divisions)
            <div class="tab-pane {!! $i++ === 1 ? 'active' : '' !!}" id="{{$bowtype}}">
                @foreach($divisions as $division => $rounds)
                    @php $division = explode('-', $division); $division = reset($division); @endphp
                    <h5 class="tableTitle">{{$division}}</h5>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                                @php $data = reset($rounds['results']) @endphp
                                <thead>
                                    <tr id="tabltr">
                                        @foreach(array_keys($data) as $key)
                                            <th>{{$key}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rounds['results'] as $archers)
                                        <tr class="results">
                                        @foreach ($archers as $data)
                                            <th scope="row" width="15%">{!! $data !!}</th>
                                        @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>

                @endforeach
            </div>
        @endforeach
    </div>

        </div>
    </div>


@endsection