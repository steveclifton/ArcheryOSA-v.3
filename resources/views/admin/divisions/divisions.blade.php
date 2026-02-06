@extends('template.default')

@section ('title')Divisions @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">

                <h4 class="page-title">Divisions</h4>
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
                        <a role="button" href="/admin/divisions/create" class="btn btn-inverse waves-effect waves-light">
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
                @include('template.alerts')
                <div class="myTable table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th width="20%">Name</th>
                            <th>Organisation</th>
                            <th>Code</th>
                            <th width="10%">Sort Order</th>
                            <th>Visible</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($divisions as $division)
                                <tr>
                                    <th scope="row">
                                        <a href="/admin/divisions/update/{{$division->divisionid}}">{{$division->label}}</a>
                                    </th>
                                    <td>{{ $division->organisationname }}</td>
                                    <td>{{ strtoupper($division->code) }}</td>
                                    <td>{{ $division->sortorder }}</td>
                                    <td>
                                        @if($division->visible)
                                            <i class="fa fa-check"></i>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection
