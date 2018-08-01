@extends('template.default')

@section ('title')Event Results @endsection

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
@endsection