@extends('template.default')

@section ('title') {{ucwords($event->label) }} Results @endsection

@section('content')

    <style>
        body {
            line-height: 1;
            font-size: 12px;
        }
    </style>

    <div class="row">
		<div class="col-sm-12">
	    	<div class="page-title-box">
                <h4 class="page-title">
                    <a href="/event/results/{{$event->eventurl}}">{{$event->label}}</a>
                    /
                    <a href="javascript:;">{{ucwords($eventcompetition->label ?? '')}}</a>
                </h4>
	    	</div>
		</div>
	</div>
    @if (!empty($event->imagedt))
        @include('events.results.templates.eventcompetition-banners')
    @endif

    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs"></ul>
            <div class="tab-content" style="padding: 0%">
                <div class="tab-pane active" id="">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                            <tbody>
                            @foreach ($results as $division => $divisionresults)
                                <tr>
                                    <td style="background-color: #add8e680">{{$division}}</td>

                                    @php
                                        $rounds = !empty($divisionresults['rounds']) ? $divisionresults['rounds'] : [];
                                        $unit = !empty($rounds['unit']) ? $rounds['unit'] : '';
                                        unset($divisionresults['rounds'], $rounds['unit']);
                                        $ignoreKeys = [];

                                        foreach ($rounds as $key => $round) {
                                            if (empty($round)) {
                                                $ignoreKeys[] = $key;
                                                continue;
                                            }
                                            @endphp
                                                <td style="background-color: #add8e680">{{$round . ($round != 'Total' ? $unit : '')}}</td>
                                            @php
                                        }
                                    @endphp
                                    </tr>
                                @foreach ($divisionresults as $result)
                                    <tr class="results">

                                        <th scope="row" width="30%">{!! $result['archer'] !!}</th>
                                        @if (!in_array('dist1', $ignoreKeys) && $result['dist1'] !== NULL) <th scope="row" width="10%">{{ intval($result['dist1']) }}</th> @endif
                                        @if (!in_array('dist2', $ignoreKeys) && $result['dist2'] !== NULL) <th scope="row" width="10%">{{ intval($result['dist2']) }}</th> @endif
                                        @if (!in_array('dist3', $ignoreKeys) && $result['dist3'] !== NULL) <th scope="row" width="10%">{{ intval($result['dist3']) }}</th> @endif
                                        @if (!in_array('dist4', $ignoreKeys) && $result['dist4'] !== NULL) <th scope="row" width="10%">{{ intval($result['dist4']) }}</th> @endif
                                        <th scope="row" width="10%">{!! $result['total'] !!}</th>

                                    </tr>
                                @endforeach

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection