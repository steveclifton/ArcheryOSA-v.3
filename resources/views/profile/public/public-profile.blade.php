@extends('template.default')

@section ('title') {{$user->getFullname() }} | Profile @endsection

@section('content')


    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                {{--                <h4 class="page-title"> Profile</h4>--}}
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="profile-detail card-box">
                <div>
                    <img src="{{URL::asset('/images/avatargrey.png')}}" alt="user" class="rounded-circle">

                    <ul class="list-inline status-list m-t-20">
                        <li class="list-inline-item">
                            <h3 class="text-primary m-b-5">{{$eventcount}}</h3>
                            <p class="text-muted">Events</p>
                        </li>

                        <li class="list-inline-item">
                            <h3 class="text-success m-b-5">{{$scorecount}}</h3>
                            <p class="text-muted">Rounds Shot</p>
                        </li>
                    </ul>

                    {{--                    <button type="button" class="btn btn-pink btn-custom btn-rounded waves-effect waves-light">Follow</button>--}}

                    <hr>
                    @if(!empty($aboutme))
                        <h4 class="text-uppercase font-18 font-600">About Me</h4>
                        <p class="text-muted font-13 m-b-30">
                           111111
                        </p>
                    @endif
                    <div class="text-left">
                        <p class="text-muted font-13"><strong>Full Name :</strong> <span class="m-l-15">{{$user->getFullname()}}</span></p>

                        @if(0)
                            <p class="text-muted font-13"><strong>Mobile :</strong><span class="m-l-15">(123) 123 1234</span></p>
                            <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15">coderthemes@gmail.com</span></p>
                            <p class="text-muted font-13"><strong>Location :</strong> <span class="m-l-15">USA</span></p>
                        @endif

                    </div>


                    <div class="button-list m-t-20">
                        @if (!empty($user->facebook))
                            <button type="button" class="btn btn-facebook waves-effect waves-light">
                                <i class="fa fa-facebook"></i>
                            </button>
                        @endif

                        @if (!empty($user->instagram))
                            <button type="button" class="btn btn-instagram waves-effect waves-light">
                                <i class="fa fa-instagram"></i>
                            </button>
                        @endif


                    </div>
                </div>

            </div>

            {{--            <div class="card-box">--}}
            {{--                <h4 class="m-t-0 m-b-20 header-title">--}}
            {{--                    <b>Gear</b>--}}
            {{--                </h4>--}}
            {{--                @foreach(range(1,10) as $i)--}}
            {{--                <div class="friend-list">--}}
            {{--                        <a href="#">--}}
            {{--                            <img src="{{URL::asset('/images/avatargrey.png')}}" class="rounded-circle thumb-md" alt="">--}}
            {{--                        </a>--}}
            {{--                    <span>sdfinsdf usdf</span>--}}
            {{--                </div>--}}
            {{--                @endforeach--}}

            {{--            </div>--}}
        </div>


        <div class="col-lg-9 col-md-8">
            <div class="card-box">
                <h2>Results</h2>

                <div class="row">
                    <div class="col-lg-12">
                        <ul class="nav nav-tabs tabs">
                            @if (!empty($finalresults['events']))
                                <li class="nav-item tab">
                                    <a href="#events" data-toggle="tab" aria-expanded="false" class="nav-link active show">
                                        Events
                                    </a>
                                </li>
                            @endif

                            @if (!empty($finalresults['leagues']))
                                <li class="nav-item tab">
                                    <a href="#leagues" data-toggle="tab" aria-expanded="false" class="nav-link">
                                        Leagues
                                    </a>
                                </li>
                            @endif

                        </ul>
                        @php $class = 'active show'; @endphp
                        <div class="tab-content">
                            @if (!empty($finalresults['events']))
                                <div class="tab-pane {{$class}}" id="events">
                                    @php $class = ''; @endphp
                                    @foreach ($finalresults['events'] as $key => $value)
                                        @php @list($name, $date) = explode('|', $key); @endphp
                                        <h5 class="tableTitle">{{$name ?? ''}}</h5>
                                        <span>{{$date ?? ''}}</span>

                                        <div class="table-responsive" style="padding-bottom: 20px">
                                            <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                                                @php $data = reset($value) @endphp
                                                <thead>
                                                    <tr id="tabltr">
                                                        @foreach(array_keys($data) as $key)
                                                            @php list($key) = explode('|', $key) @endphp
                                                            <th>{{ $key }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr class="results">
                                                        @foreach ($data as $data)
                                                            <th scope="row" width="15%">{!! $data !!}</th>
                                                        @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <hr>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            @if (!empty($finalresults['leagues']))
                                <div class="tab-pane {{$class}}" id="leagues">

                                    @foreach($finalresults['leagues'] as $key => $value)
                                        @php @list($name, $date) = explode('|', $key); @endphp
                                        <h5 class="tableTitle">{{$name ?? ''}}</h5>
                                        <span>{{$date ?? ''}}</span>

                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                                            @php $data = reset($value); @endphp
                                            <thead>
                                                <tr id="tabltr">
                                                    <th>Week</th>
                                                    <th>Division</th>
                                                    <th>{{$data->dist1 . $data->unit}}</th>
                                                    <th>10s+X</th>
                                                    <th>X</th>
                                                    <th>Points</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($value as $archer)
                                                <tr class="results">
                                                    <td width="10%">
                                                        {{intval($archer->week)}}
                                                    </td>
                                                    <td width="10%">
                                                        {{($archer->divisionname)}}
                                                    </td>
                                                    <td width="10%">
                                                        {{intval($archer->dist1score)}}
                                                    </td>
                                                    <td width="10%">
                                                        {{intval($archer->inners)}}
                                                    </td>
                                                    <td width="10%">
                                                        {{intval($archer->max)}}
                                                    </td>
                                                    <td width="10%">
                                                        {{intval($archer->points ?? 0)}}
                                                    </td>

                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                            <hr>
                                        </div>
                                    @endforeach
                                </div>
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection