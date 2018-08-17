
@extends('template.default')

@section ('title') @endsection

@section('content')

    <div class="col-md-12 homePageBanner">

        {{-- <p class="text-muted m-b-30 font-13">A slideshow component for cycling through elements, like a carousel.</p> --}}

        <div class="panel panel-default text-center d-lg-none text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybannerdt3.jpg')}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="text-white font-600">Archery OSA<br>Rankings</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em></em></p>
                            {{--<button class="btn btn-inverse btn-sm m-t-40">Latest Results</button>--}}
                        </div><!-- /.item -->
                    </div><!-- /#tiles-slide-2 -->
                </div>
            </div> <!-- panel-body -->
        </div><!-- Panel -->

        <div class="panel panel-default text-center desktopOnlyImg d-none d-lg-block text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybannerdt3.jpg')}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="archeryHeadText">Archery OSA<br>Rankings</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em></em></p>
                            {{--<button class="btn btn-inverse btn-sm m-t-40">Latest Results</button>--}}
                        </div><!-- /.item -->
                    </div><!-- /#tiles-slide-2 -->
                </div>
            </div> <!-- panel-body -->
        </div>
    </div> <!-- col-->



    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                {{-- <h4 class="page-title">Upcoming Events</h4> --}}
            </div>
        </div>
    </div>
    <h3>Coming Soon!</h3>
	{{--<div class="row">--}}
        {{--<div class="col-lg-12">--}}
            {{--<ul class="nav nav-tabs tabs">--}}
                {{--<li class="nav-item tab">--}}
                    {{--<a href="#compound" data-toggle="tab" aria-expanded="false" class="nav-link active show">--}}
                        {{--Compound--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<li class="nav-item tab">--}}
                    {{--<a href="#recurve" data-toggle="tab" aria-expanded="true" class="nav-link">--}}
                        {{--Recurve--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<li class="nav-item tab">--}}
                    {{--<a href="#barebow" data-toggle="tab" aria-expanded="true" class="nav-link">--}}
                        {{--Barebow--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--</ul>--}}
            {{--<ul class="nav nav-tabs tabs">--}}
                {{--<li class="nav-item tab">--}}
                    {{--<a href="#compound" data-toggle="tab" aria-expanded="false" class="nav-link active show">--}}
                        {{--Female--}}
                    {{--</a>--}}
                {{--</li>--}}
                {{--<li class="nav-item tab">--}}
                    {{--<a href="#recurve" data-toggle="tab" aria-expanded="true" class="nav-link">--}}
                        {{--Male--}}
                    {{--</a>--}}
                {{--</li>--}}
            {{--</ul>--}}

            {{--<div class="tab-content">--}}
                {{--<div class="tab-pane active" id="compound">--}}
                	{{--<h5 class="tableTitle">Events</h5>--}}
                    {{--<div class="table-responsive">--}}
                        {{--<table class="table table-bordered table-hover">--}}
                            {{--<thead class="thead-light">--}}
                                {{--<tr>--}}
                                    {{--<th>Archer</th>--}}
                                    {{--<th>18m</th>--}}
                                    {{--<th>Total</th>--}}
                                    {{--<th>10+X</th>--}}
                                    {{--<th>X</th>--}}
                                    {{--<th>Average</th>--}}
                                    {{--<th>Handicap</th>--}}
                                    {{--<th>Points</th>--}}
                                    {{--<th>Total Points</th>--}}
                                {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                                {{--<tr>--}}
                                    {{--<th scope="row">Adam Niziol</th>--}}
                                    {{--<td>299</td>--}}
                                    {{--<td>299</td>--}}
                                    {{--<td>29</td>--}}
                                    {{--<td>18</td>--}}
                                    {{--<td>297.36</td>--}}
                                    {{--<td>301.64</td>--}}
                                    {{--<td>0</td>--}}
                                    {{--<td>39</td>--}}
                                {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="tab-pane" id="recurve">--}}
                	{{--<h5 class="tableTitle">Events</h5>--}}
                    {{--<div class="table-responsive">--}}
                        {{--<table class="table table-bordered table-hover">--}}
                            {{--<thead class="thead-light">--}}
                                {{--<tr>--}}
                                    {{--<th>Archer</th>--}}
                                    {{--<th>18m</th>--}}
                                    {{--<th>Total</th>--}}
                                    {{--<th>10+X</th>--}}
                                    {{--<th>X</th>--}}
                                    {{--<th>Average</th>--}}
                                    {{--<th>Handicap</th>--}}
                                    {{--<th>Points</th>--}}
                                    {{--<th>Total Points</th>--}}
                                {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                                {{--<tr>--}}
                                    {{--<th scope="row">Adam Niziol</th>--}}
                                    {{--<td>299</td>--}}
                                    {{--<td>299</td>--}}
                                    {{--<td>29</td>--}}
                                    {{--<td>18</td>--}}
                                    {{--<td>297.36</td>--}}
                                    {{--<td>301.64</td>--}}
                                    {{--<td>0</td>--}}
                                    {{--<td>39</td>--}}
                                {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="tab-pane" id="barebow">--}}
                	{{--<h5 class="tableTitle">Events</h5>--}}
                    {{--<div class="table-responsive">--}}
                        {{--<table class="table table-bordered table-hover">--}}
                            {{--<thead class="thead-light">--}}
                                {{--<tr>--}}
                                    {{--<th>Archer</th>--}}
                                    {{--<th>18m</th>--}}
                                    {{--<th>Total</th>--}}
                                    {{--<th>10+X</th>--}}
                                    {{--<th>X</th>--}}
                                    {{--<th>Average</th>--}}
                                    {{--<th>Handicap</th>--}}
                                    {{--<th>Points</th>--}}
                                    {{--<th>Total Points</th>--}}
                                {{--</tr>--}}
                            {{--</thead>--}}
                            {{--<tbody>--}}
                                {{--<tr>--}}
                                    {{--<th scope="row">Adam Niziol</th>--}}
                                    {{--<td>299</td>--}}
                                    {{--<td>299</td>--}}
                                    {{--<td>29</td>--}}
                                    {{--<td>18</td>--}}
                                    {{--<td>297.36</td>--}}
                                    {{--<td>301.64</td>--}}
                                    {{--<td>0</td>--}}
                                    {{--<td>39</td>--}}
                                {{--</tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}

@endsection