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
                <div class="tab-pane active" id="">
                    <h5 class="tableTitle d-block d-sm-block d-md-block d-lg-none">Divison</h5>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                                <thead>
                                    <tr id="tabltr">
                                        <th>Name</th>
                                        <th>Neroli Fairhall Memorial Triple WA 1440 - 26 Oct</th>
                                        <th>Neroli Fairhall Memorial Triple WA 1440 - 27 Oct</th>
                                        <th>Neroli Fairhall Memorial Triple WA 1440 - 28 Oct</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="background-color: lightblue">Mens Compound</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue"></td>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Steve</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">559</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr>
                                        <td style="background-color: lightblue">Womens Compound</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue"></td>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Steve</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">559</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr>
                                        <td style="background-color: lightblue">Mens Recurve</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue">WA1440 70m</td>
                                        <td style="background-color: lightblue"></td>
                                    </tr>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Steve</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">559</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                    <tr class="results">
                                        <th scope="row" width="15%">Holly</th>
                                        <th scope="row" width="15%">145</th>
                                        <th scope="row" width="15%">155</th>
                                        <th scope="row" width="15%">165</th>
                                        <th scope="row" width="15%">669</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
        </div>
    </div>



@endsection