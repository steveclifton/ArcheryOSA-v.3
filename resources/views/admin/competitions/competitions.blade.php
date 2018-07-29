@extends('template.default')

@section ('title')Competitions @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Competitions</h4>
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
                        <a role="button" href="/admin/competitions/create" class="btn btn-inverse waves-effect waves-light">
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
                <p>A competition is a collection of rounds. <br>
                    This allows event organisers to quickly add pre-defined competitions to their event
                </p>
                <div class=" myTable table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Visible</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($competitions as $competition)
                                <tr>
                                    <th scope="row"><a href="/admin/competitions/update/{{$competition->competitionid}}">{{$competition->label}}</a></th>
                                    <td>{{!empty($competition->description) ? substr($competition->description, 0, 40) . '...' : ''}}</td>
                                    <td>@if($competition->visible)<i class="fa fa-check"></i>@endif</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection