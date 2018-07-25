@extends('template.default')

@section ('title')Home @endsection


@section('content')

    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/upcomingevents">Upcoming Events</a></h4>
            </div>
        </div>
    </div>
    <!-- end page title  -->

    <div class="row">
        <div class="col-12">
            <div class="card-columns">
                <a href="/event/details/indoor-league-series">
                    <div class="card m-b-20">
                        <img class="card-img-top img-fluid" src="{{URL::asset('/images/archery.jpg')}}" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-18 mt-0">2018 Indoor League Series</h4>
                            <p class="card-text">Date : {!! date('d F Y') !!}</p>
                        </div>
                    </div>
                </a>

                <a href="/event/details/indoor-league-series">
                    <div class="card m-b-20">
                        <img class="card-img-top img-fluid" src="{{URL::asset('/images/archery.jpg')}}" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-18 mt-0">2018 Indoor League Series</h4>
                            <p class="card-text">Date : {!! date('d F Y') !!}</p>
                        </div>
                    </div>
                </a>

                <a href="/event/details/indoor-league-series">
                    <div class="card m-b-20">
                        <img class="card-img-top img-fluid" src="{{URL::asset('/images/archery.jpg')}}" alt="Card image cap">
                        <div class="card-body">
                            <h4 class="card-title font-18 mt-0">2018 Indoor League Series</h4>
                            <p class="card-text">Date : {!! date('d F Y') !!}</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>

    <!-- Section-Title -->
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Upcoming Events</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Enteries Close</th>
                            <th>Start</th>
                            <th>Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">2018 Indoor League Series</th>
                            <td>Nationwide</td>
                            <td></td>
                            <td>07-05-2018</td>
                            <td class="text-success">Open</td>

                        </tr>
                        <tr>
                            <th scope="row">2018 ADAA Indoor Championships</th>
                            <td>MGAC Indoor Range, 149 Royal Road, Massey, Auckland</td>
                            <td>13-07-2018</td>
                            <td>21-07-2018</td>
                            <td class="text-danger">Enteries Closed</td>

                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>21-07-2018</td>
                            <td>@twitter</td>
                            <td>@twitter</td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Previous Events</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Dates</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">2018 Indoor League Series</th>
                            <td>Nationwide</td>
                            <td></td>
                        </tr>
                        <tr>
                            <th scope="row">2018 ADAA Indoor Championships</th>
                            <td >MGAC Indoor Range, 149 Royal Road, Massey, Auckland</td>
                            <td>13-07-2018</td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>21-07-2018</td>
                        </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>


@endsection
