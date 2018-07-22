@extends('template.default')

@section ('title')Rounds @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Rounds</h4>
            </div>
            <!--  <div class="page-title-box"> -->
            <div class="container" style="margin-left: 0; padding-left: 0;">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group row" style="display:block; margin-left: 0; margin-right: 1em; text-align: left;">
                            <div class="col-md-12">
                                <input class="form-control" placeholder="Search" type="search" name="search">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <a role="button" href="/admin/rounds/create" class="btn btn-inverse waves-effect waves-light">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>Add
                        </a>
                    </div>
                </div>
            </div>
            <!-- </div> -->
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <p>Rounds are the individual distance shoots created which can be added to tournaments</p>
                <div class="myTable table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Visible</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row"><a href="">WA Kiwi</a></th>
                            <td>WA25</td>
                            <td>World Archery 1440 25meters</td>
                            <td><i class="fa fa-check"></i></td>

                        </tr>
                        <tr>
                            <th scope="row"><a href="">WA 1440 Horsham</a></th>
                            <td>WA40</td>
                            <td>World Archery 1440 40meters</td>
                            <td><i class="fa fa-check"></i></td>

                        </tr>
                        <tr>
                            <th scope="row"><a href="">WA1440 Intermediate</a></th>
                            <td>WA55</td>
                            <td>World Archery 1440 55meters</td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection