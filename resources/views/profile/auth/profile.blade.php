@extends('template.default')

@section ('title')Profile @endsection

@section('content')

    <div class="row">

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="/profile/mydetails">
                    <div class="db-social-box">
                        <span class="fa fa-address-book-o"></span>
                        <h5>Edit Profile</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="/profile/relationships">
                    <div class="db-social-box">

                        <span class="fa fa-address-book"></span>
                        <h5>Relationships</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="#">
                    <div class="db-social-box">
                        <span class="fa fa-files-o"></span>
                        <h5>Memberships</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="/profile/children">
                    <div class="db-social-box">
                        <span class="fa fa-child"></span>
                        <h5>Childrens Accounts</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="/profile/myevents">
                    <div class="db-social-box">
                        <span class="fa fa-calendar"></span>
                        <h5>My Events</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="#">
                    <div class="db-social-box">
                        <span class="fa fa-bullseye"></span>
                        <h5>My Results</h5>
                    </div>
                </a>
            </div>

        </div>

@endsection