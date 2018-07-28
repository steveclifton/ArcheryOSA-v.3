@extends('template.default')

@section ('title')Manage Event @endsection

@section('content')

    <div class="row">

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="#">
                <div class="db-social-box">
                    <span class="fa fa-address-book-o"></span>
                    <h5>Edit Event Details</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="#">
                <div class="db-social-box">
                    <span class="fa fa-address-book"></span>
                    <h5>Edit Competitions</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="#">
                <div class="db-social-box">
                    <span class="fa fa-files-o"></span>
                    <h5>Event Entries</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="#">
                <div class="db-social-box">
                    <span class="fa fa-child"></span>
                    <h5>Event Communications</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="#">
                <div class="db-social-box">
                    <span class="fa fa-calendar"></span>
                    <h5>Event Scoring</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="#">
                <div class="db-social-box">
                    <span class="fa fa-bullseye"></span>
                    <h5>Exports</h5>
                </div>
            </a>
        </div>

    </div>
@endsection