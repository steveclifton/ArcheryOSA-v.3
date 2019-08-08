@extends('template.default')

@section ('title')Event Results @endsection

@section('content')

    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}"    rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}"     rel="stylesheet">

    <div class="row">
		<div class="col-sm-12">
	    	<div class="page-title-box">
                <h4 class="page-title">
                    <a href="/event/results/{{$event->eventurl}}">Results</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Overall</a>
                </h4>
	    	</div>
		</div>
	</div>
    @if (!empty($event->imagebanner))
    <div class="col-md-12 homePageBanner">
        <div class="panel panel-default text-center d-lg-none text-white slider-bg m-b-0"
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
                            <h1>
                                <a href="#" class="text-white font-600">{{ucwords($event->label)}}</a>
                            </h1>
                            <p class="m-t-30"><em>{{ucwords($eventcompetition->label)}}</em></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default text-center desktopOnlyImg d-none d-lg-block text-white slider-bg m-b-0"
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
                            <h1>
                                <a href="#" class="text-white font-600">{{ucwords($event->label)}}</a>
                            </h1>
                            <p class="m-t-30"><em>{{ucwords($eventcompetition->label)}}</em></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- col-->
    @endif


    <div class="row">
        <div class="col-sm-3 weekSelector">
        </div>
    </div>


	<div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs">
                @php $i = 1; @endphp
                @foreach($evententrys as $bowtype => $e)
                    <li class="nav-item tab">
                        <a href="#{{$bowtype}}" data-toggle="tab" aria-expanded="false" class="nav-link {!! $i++ === 1 ? 'active' : '' !!}  show">
                            {{ucwords($bowtype)}}
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @php $i = 1; @endphp
                @foreach ($evententrys as $bowtype => $ee)
                    <div class="tab-pane {!! $i++ === 1 ? 'active' : '' !!}" id="{{$bowtype}}">
                        @foreach($ee as $division => $archers)

                            <h5 class="tableTitle">{{$division}}</h5>
                            @php $data = reset($archers); @endphp


                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                                        <thead>
                                            <tr id="tabltr">
                                                <th>Archer</th>
                                                <th>Average</th>
                                                <th>Top 10 Total</th>
                                                <th>Top 10 Points</th>
                                            </tr>

                                        </thead>

                                        <tbody>
                                        @foreach($archers as $archer)
                                            <tr class="results">
                                                <th scope="row" width="15%">
                                                    <a href="/profile/public/{{$archer->username}}">
                                                        {{ucwords($archer->firstname . ' ' . $archer->lastname)}}
                                                    </a>
                                                </th>
                                                <td width="10%">
                                                    {{number_format($archer->average->average ?? 0, 2)}}
                                                </td>
                                                <td width="10%">
                                                    {{intval($archer->top10->total ?? 0)}}
                                                </td>
                                                <td width="10%">
                                                    {{intval($archer->top10points->points ?? 0)}}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <hr>
                                </div>

                        @endforeach
                    </div>
                @endforeach
            </div>

        </div>
    </div>











    <script src="{{URL::asset('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.keyTable.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.select.min.js')}}"></script>

    <script src="{{URL::asset('/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.print.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            //Buttons examples
            var index = $('#tabltr').find('th:last').index();
            var table = $('.datatable-buttons').DataTable({
                lengthChange: false,
                bPaginate: false,
                bInfo : false,
                searching : false,
                "order": [[ index, "desc" ], [index -1, "desc"]]
                // buttons: ['excel', 'pdf']
            });
            //
            // table.buttons().container()
            //     .appendTo('.datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>



@endsection