@extends('template.default')

@section ('title') {{ucwords($event->label) }} Results @endsection

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
                    <a href="javascript:;">{{ucwords($eventcompetition->label ?? '')}}</a>
                </h4>
	    	</div>
		</div>
	</div>
    @if (!empty($event->imagedt))

        <div class="row">
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
                                <h3><a href="#" class="text-white font-600 archeryHeadText">{{ucwords($eventcompetition->label)}}</a></h3>

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
                                <h3><a href="#" class=" archeryHeadText">{{ucwords($eventcompetition->label)}}</a></h3>
                                <p class="m-t-30"><em></em></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- col-->
        </div>
    @endif

	<div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs"> </ul>
            <div class="tab-content">
                @if (!empty($eventcompetition->filename))
                    <div>
                        <a href="/eventdownload/{{$eventcompetition->filename}}">Download Results</a>
                    </div>
                @endif
                @foreach ($evententrys as $bowtype => $ee)
                    <div class="tab-pane active" id="{{$bowtype}}">
                        @foreach($ee as $division => $archers)

                            <h5 class="tableTitle d-block d-sm-block d-md-block d-lg-none">{{$division}}</h5>
                            @php $data = reset($archers); @endphp
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered datatable-buttons" cellspacing="0" width="100%">
                                    <thead>
                                        <tr id="tabltr">
                                            <th class="d-lg-none">Archer</th>
                                            <th class="d-none d-sm-none d-md-none d-lg-block d-xl-block">{{$division}}</th>
                                            <th>{{$data->dist1. $data->unit}}</th>
                                            @if(!empty($data->dist2))<th>{{$data->dist2. $data->unit}}</th>@endif
                                            @if(!empty($data->dist3))<th>{{$data->dist3. $data->unit}}</th>@endif
                                            @if(!empty($data->dist4))<th>{{$data->dist4. $data->unit}}</th>@endif
                                            <th>Total</th>
                                        </tr>

                                    </thead>

                                    <tbody>
                                    @foreach($archers as $archer)
                                        <tr class="results">
                                            <th scope="row" width="15%">
                                                <a href="/profile/public/{{$archer->username ?? ''}}">
                                                    {{ucwords($archer->firstname . ' ' . $archer->lastname)}}
                                                </a>
                                            </th>
                                                <td width="10%">
                                                    {{$archer->dist1score}}
                                                </td>
                                                @if(!empty($data->dist2))
                                                    <td width="10%">
                                                        {{$archer->dist2score}}
                                                    </td>
                                                @endif
                                                @if(!empty($data->dist3))
                                                    <td width="10%">
                                                        {{$archer->dist3score}}
                                                    </td>
                                                @endif
                                                @if(!empty($data->dist4))
                                                    <td width="10%">
                                                        {{$archer->dist4score}}
                                                    </td>
                                                @endif
                                            <td width="10%">
                                                {{$archer->total ?? ''}}
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
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
                "order": [[ index, "desc" ]]
            });
        });
    </script>

@endsection