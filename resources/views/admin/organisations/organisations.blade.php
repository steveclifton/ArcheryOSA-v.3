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
                @include('template.alerts')
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Visible</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($organisations as $organisation)
                                <tr>
                                    <th scope="row"><a href="javascript:;">{{$organisation->label}}</a></th>
                                    <td>{{$organisation->email}}</td>
                                    <td>
                                        @if($organisation->visible)
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