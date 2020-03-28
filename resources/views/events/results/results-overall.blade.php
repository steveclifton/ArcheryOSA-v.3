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
                    <a href="/event/results/{{$event->eventurl}}">{{$event->label}}</a>
                    /
                    <a href="javascript:;">Overall</a>
                </h4>
            </div>
        </div>
    </div>

    @if (!empty($event->imagedt))
        @include('events.results.templates.event-banners')
    @endif

	<div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs"></ul>
            <div class="tab-content">

                @foreach ($finalResults as $bowtype => $divisions)
                    <div class="tab-pane active" id="{{$bowtype}}">
                        @foreach($divisions as $division => $rounds)
                            @php $division = explode('-', $division); $division = reset($division); @endphp

                            <h5 class="tableTitle d-block d-sm-block d-md-block d-lg-none">{{$division}}</h5>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                                    @php $data = reset($rounds['results']) @endphp
                                    <thead>
                                        <tr id="tabltr">
                                            <th class="d-lg-none">Archer</th>
                                            <th class="d-none d-sm-none d-md-none d-lg-block">{{$division}}</th>
                                            @foreach(array_keys($data) as $key)
                                                @if ($key == 'Archer')
                                                    @continue
                                                @else
                                                    @php list($key) = explode('|', $key); @endphp
                                                @endif
                                                <th>{{ !ctype_digit(strval($key)) ? $key : ''}}</th>
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
                            <br>
                            <br>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>



@endsection