@extends('template.default')

@section ('title') {{ucwords($event->label) }} Overall Results @endsection

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
        <div class="tab-content" style="padding: 0%">
            <div class="tab-pane active" id="">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                        <thead>
                            <tr id="tabltr">
                                <th>Name</th>
                                @foreach ($competitionlabels as $label)
                                    <th>{{$label}}</th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($results as $division => $divisionresults)
                                <tr>

                                    <td style="background-color: #add8e680">{{$division}}</td>

                                    @foreach($divisionresults['rounds'] as $round)
                                        <td style="background-color: #add8e680">{{$round}}</td>
                                    @endforeach
                                    @php unset($divisionresults['rounds']) @endphp

                                    <td style="background-color: #add8e680"></td>
                                </tr>

                                @foreach ($divisionresults as $result)
                                    <tr class="results">

                                        <th scope="row" width="15%">{!! $result['archer'] !!}</th>
                                        @php unset($result['archer']) @endphp

                                        @foreach ($result as $key => $r)
                                            @if (in_array($key, ['total', 'inners', 'xcount'])) @continue; @endif

                                            <th scope="row" width="15%">{{$r}}</th>
                                        @endforeach

                                        <th scope="row" width="15%">{!! $result['total'] !!}</th>
                                        @php unset($result['total']) @endphp
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