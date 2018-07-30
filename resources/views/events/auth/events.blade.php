@extends('template.default')

@section ('title')Events @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Events</h4>
            </div>
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
                        <a role="button" href="/events/create" class="btn btn-inverse waves-effect waves-light ">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>Add
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
            <div class="col-lg-12">
                <div class="card-box">

                    <div class=" myTable table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Visible</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>42</td>
                                        <th scope="row"><a href="">Dunedin Archery Club</a></th>
                                        <td></td>
                                        <td><i class="fa fa-check"></i></td>

                                    </tr>
                                    <tr>
                                        <td>41</td>
                                        <th scope="row"><a href="">Rosebank Archery Club</a></th>
                                        <td></td>
                                        <td><i class="fa fa-check"></i></td>

                                    </tr>
                                   <tr>
                                        <td>40</td>
                                        <th scope="row"><a href="">Grey Goose Wing Archery Society</a></th>
                                        <td></td>
                                        <td><i class="fa fa-check"></i></td>
                                    </tr>

                                    </tbody>
                        </table>
                    </div>
                </div>
            </div>

@endsection