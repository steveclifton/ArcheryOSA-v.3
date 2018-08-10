@extends('template.default')

@section ('title')Profile @endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="btn-group pull-right">
                    {{--<ol class="breadcrumb hide-phone p-0 m-0">--}}
                        {{--<li class="breadcrumb-item"><a href="#">Ubold</a></li>--}}
                        {{--<li class="breadcrumb-item"><a href="#">Extras</a></li>--}}
                        {{--<li class="breadcrumb-item active">Profile</li>--}}
                    {{--</ol>--}}
                </div>
                <h4 class="page-title">Profile</h4>
            </div>
        </div>
    </div>
    <!-- end page title end breadcrumb -->


    <div class="row">
        <div class="col-md-4 col-lg-3">
            <div class="profile-detail card-box">
                <div>
                    <img src="{{URL::asset('/images/event1.jpg')}}" class="rounded-circle" alt="profile-image">

                    <ul class="list-inline status-list m-t-20">
                        <li class="list-inline-item">
                            <h3 class="text-primary m-b-5">3</h3>
                            <p class="text-muted">Completed Events</p>
                        </li>


                    </ul>

                    {{--<button type="button" class="btn btn-pink btn-custom btn-rounded waves-effect waves-light">Follow</button>--}}

                    <hr>
                    <h4 class="text-uppercase font-18 font-600">About Me</h4>
                    <p class="text-muted font-13 m-b-30">
                        Some public info from a public profile input text box
                    </p>

                    <div class="text-left">
                        <p class="text-muted font-13">
                            <strong>Full Name :</strong> <span class="m-l-15">Steve Clifton</span>
                        </p>

                        <p class="text-muted font-13">
                            <strong>Mobile :</strong><span class="m-l-15">0211111111</span>
                        </p>

                        <p class="text-muted font-13">
                            <strong>Email :</strong> <span class="m-l-15">steve.clifton@outlook.com</span>
                        </p>

                        <p class="text-muted font-13">
                            <strong>Location :</strong> <span class="m-l-15">New Zealand</span>
                        </p>

                        <p class="text-muted font-13">
                            <a href="https://worldarchery.org/athlete/1929/stephen-clifton">
                                <strong>World Archery Profile</strong>
                            </a>
                        </p>
                    </div>
                    <div class="button-list m-t-20">
                        <a href="javascript:;">
                            <button type="button" class="btn btn-facebook waves-effect waves-light">
                                <i class="fa fa-facebook"></i>
                            </button>
                        </a>
                        {{--<button type="button" class="btn btn-twitter waves-effect waves-light">--}}
                            {{--<i class="fa fa-twitter"></i>--}}
                        {{--</button>--}}

                        {{--<button type="button" class="btn btn-linkedin waves-effect waves-light">--}}
                            {{--<i class="fa fa-linkedin"></i>--}}
                        {{--</button>--}}

                        {{--<button type="button" class="btn btn-dribbble waves-effect waves-light">--}}
                            {{--<i class="fa fa-dribbble"></i>--}}
                        {{--</button>--}}

                    </div>
                </div>

            </div>
        </div>


        <div class="col-lg-9 col-md-8">
            <div class="row">


                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon bg-icon-success pull-left">
                            <i class="icon-trophy text-success"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="text-dark">
                                <b data-plugin="counterup">1</b>
                            </h3>
                            <p class="text-muted">NZ Ranking</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon bg-icon-warning pull-left">
                            <i class="icon-shield text-warning"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="text-dark"><b data-plugin="counterup">23 </b></h3>
                            <p class="text-muted">NZ Records</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon bg-icon-warning pull-left">
                            <i class="ion-android-star text-warning"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="text-dark"><b data-plugin="counterup">360 27x</b></h3>
                            <p class="text-muted">30m PB</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-bg-color-icon card-box">
                        <div class="bg-icon bg-icon-warning pull-left">
                            <i class="ion-android-star text-warning"></i>
                        </div>
                        <div class="text-right">
                            <h3 class="text-dark"><b data-plugin="counterup">1406</b></h3>
                            <p class="text-muted">Mens WA1440 PB</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                {{--<div class="col-md-6 col-lg-6 col-xl-3">--}}
                    {{--<div class="widget-bg-color-icon card-box">--}}
                        {{--<div class="bg-icon bg-icon-success pull-left">--}}
                            {{--<i class="icon-badge text-success"></i>--}}
                        {{--</div>--}}
                        {{--<div class="text-right">--}}
                            {{--<h3 class="text-dark"><b data-plugin="counterup">44</b></h3>--}}
                            {{--<p class="text-muted">Medal Count</p>--}}
                        {{--</div>--}}
                        {{--<div class="clearfix"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                {{--<div class="col-md-6 col-lg-6 col-xl-3">--}}
                    {{--<div class="widget-bg-color-icon card-box">--}}
                        {{--<div class="bg-icon bg-icon-warning pull-left">--}}
                            {{--<i class="icon-trophy text-warning"></i>--}}
                        {{--</div>--}}
                        {{--<div class="text-right">--}}
                            {{--<h3 class="text-dark"><b data-plugin="counterup">3652</b></h3>--}}
                            {{--<p class="text-muted">Status</p>--}}
                        {{--</div>--}}
                        {{--<div class="clearfix"></div>--}}
                    {{--</div>--}}
                {{--</div>--}}


            </div>

            <div class="row">

                <div class="col-lg-12">
                    <div class="card-box" id="records">
                        <h4 class="m-t-0 header-title">Archery NZ Records</h4>
                        <p>Click anywhere to show more records</p>
                        <table class="table table-borderless table-responsive-sm">

                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Division</th>
                                    <th>Round</th>
                                    <th>Score</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <th>{{date('d-M Y')}}</th>
                                    <td>Open Record</td>
                                    <td>Senior Compound Men</td>
                                    <td>70m</td>
                                    <td>356</td>
                                </tr>
                                <tr>
                                    <th>{{date('d-M Y')}}</th>
                                    <td>National Record</td>
                                    <td>Junior Compound Men</td>
                                    <td>WA1440</td>
                                    <td>1392</td>
                                </tr>
                                <tr>
                                    <th>{{date('d-M Y')}}</th>
                                    <td>New Zealand Record</td>
                                    <td>Junior Compound Men</td>
                                    <td>90m</td>
                                    <td>346</td>
                                </tr>


                                <tr class="showmore hidden">
                                    <th>{{date('d-M Y')}}</th>
                                    <td>New Zealand Record</td>
                                    <td>Junior Compound Men</td>
                                    <td>90m</td>
                                    <td>346</td>
                                </tr>
                                <tr class="showmore hidden">
                                    <th>{{date('d-M Y')}}</th>
                                    <td>New Zealand Record</td>
                                    <td>Junior Compound Men</td>
                                    <td>90m</td>
                                    <td>346</td>
                                </tr>
                            </tbody>

                        </table>


                    </div>

                </div>

                <script>
                    $('#records').on('click', function () {
                        console.log('toggle');
                        $('.showmore').toggle();
                    })

                </script>



                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Event Name</h4>
                        <p class="text-muted font-14 m-b-20">
                            Division: Senior Compound Men
                        </p>

                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>90m</th>
                                    <th>70m</th>
                                    <th>50m</th>
                                    <th>30m</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>{{date('d-M y')}}</th>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>1440</td>
                                </tr>
                                <tr>
                                    <th>{{date('d-M y')}}</th>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>1440</td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Event Name</h4>
                        <p class="text-muted font-14 m-b-20">
                            Division: Senior Compound Men
                        </p>

                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>90m</th>
                                <th>70m</th>
                                <th>50m</th>
                                <th>30m</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(range(1, 7) as $num)
                                <tr>
                                    <th>{{date('d-M y')}}</th>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>1440</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Event Name</h4>
                        <p class="text-muted font-14 m-b-20">
                            Division: Senior Compound Men
                        </p>

                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>90m</th>
                                <th>70m</th>
                                <th>50m</th>
                                <th>30m</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(range(1, 4) as $num)
                                <tr>
                                <th>{{date('d-M y')}}</th>
                                <td>360</td>
                                <td>360</td>
                                <td>360</td>
                                <td>360</td>
                                <td>1440</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Event Name</h4>
                        <p class="text-muted font-14 m-b-20">
                            Division: Senior Compound Men
                        </p>

                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>90m</th>
                                <th>70m</th>
                                <th>50m</th>
                                <th>30m</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(range(1, 3) as $num)
                                <tr>
                                    <th>{{date('d-M y')}}</th>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>1440</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Event Name</h4>
                        <p class="text-muted font-14 m-b-20">
                            Division: Senior Compound Men
                        </p>

                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>90m</th>
                                <th>70m</th>
                                <th>50m</th>
                                <th>30m</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(range(1, 2) as $num)
                                <tr>
                                    <th>{{date('d-M y')}}</th>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>1440</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="col-lg-6">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title">Event Name</h4>
                        <p class="text-muted font-14 m-b-20">
                            Division: Senior Compound Men
                        </p>

                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>90m</th>
                                <th>70m</th>
                                <th>50m</th>
                                <th>30m</th>
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(range(1, 5) as $num)
                                <tr>
                                    <th>{{date('d-M y')}}</th>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>360</td>
                                    <td>1440</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

            {{--<div class="row">--}}
                {{--<div class="col-12">--}}
                    {{--<div class="card-columns">--}}


                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event1.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title that wraps to a new line</h4>--}}
                                {{--<p class="card-text">This is a longer card with supporting text below as a--}}
                                    {{--natural lead-in to additional content. This content is a little bit--}}
                                    {{--longer.</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}


                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event2.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title</h4>--}}
                                {{--<p class="card-text">This card has supporting text below as a natural--}}
                                    {{--lead-in to additional content.</p>--}}
                                {{--<p class="card-text">--}}
                                    {{--<small class="text-muted">Last updated 3 mins ago</small>--}}
                                {{--</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event1.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title that wraps to a new line</h4>--}}
                                {{--<p class="card-text">This is a longer card with supporting text below as a--}}
                                    {{--natural lead-in to additional content. This content is a little bit--}}
                                    {{--longer.</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event2.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title</h4>--}}
                                {{--<p class="card-text">This card has supporting text below as a natural--}}
                                    {{--lead-in to additional content.</p>--}}
                                {{--<p class="card-text">--}}
                                    {{--<small class="text-muted">Last updated 3 mins ago</small>--}}
                                {{--</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event1.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title that wraps to a new line</h4>--}}
                                {{--<p class="card-text">This is a longer card with supporting text below as a--}}
                                    {{--natural lead-in to additional content. This content is a little bit--}}
                                    {{--longer.</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event2.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title</h4>--}}
                                {{--<p class="card-text">This card has supporting text below as a natural--}}
                                    {{--lead-in to additional content.</p>--}}
                                {{--<p class="card-text">--}}
                                    {{--<small class="text-muted">Last updated 3 mins ago</small>--}}
                                {{--</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event1.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title that wraps to a new line</h4>--}}
                                {{--<p class="card-text">This is a longer card with supporting text below as a--}}
                                    {{--natural lead-in to additional content. This content is a little bit--}}
                                    {{--longer.</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<div class="card m-b-18">--}}
                            {{--<img class="card-img-top img-fluid" src="{{URL::asset('/images/event2.jpg')}}" alt="Card image cap">--}}
                            {{--<div class="card-body">--}}
                                {{--<h4 class="card-title font-18 mt-0">Card title</h4>--}}
                                {{--<p class="card-text">This card has supporting text below as a natural--}}
                                    {{--lead-in to additional content.</p>--}}
                                {{--<p class="card-text">--}}
                                    {{--<small class="text-muted">Last updated 3 mins ago</small>--}}
                                {{--</p>--}}
                            {{--</div>--}}
                        {{--</div>--}}



                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}


        </div>

    </div>
@endsection