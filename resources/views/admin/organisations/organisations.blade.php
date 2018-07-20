@extends('template.default')

@section ('title')Organisations @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">

                <h4 class="page-title">Organisations</h4>
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
                        <a role="button" href="/admin/organisations/create" class="btn btn-inverse waves-effect waves-light">
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

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Parent Organisation</th>
                            <th>Visible</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">Archery NZ</th>
                            <td>Archery New Zealand</td>
                            <td>World Archery</td>
                            <td><i class="fa fa-check"></i></td>

                        </tr>
                        <tr>
                            <th scope="row">Auckland District Archery Association</th>
                            <td>ADAA</td>
                            <td>Archery NZ</td>
                            <td><i class="fa fa-check"></i></td>

                        </tr>
                        <tr>
                            <th scope="row">ECBOPAA</th>
                            <td>East Coast Bay of Plenty Archery Association</td>
                            <td>Archery NZ</td>
                            <td><i class="fa fa-check"></i></td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>


@endsection