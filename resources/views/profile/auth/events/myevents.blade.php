@extends('template.default')

@section ('title') @endsection

@section('content')
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