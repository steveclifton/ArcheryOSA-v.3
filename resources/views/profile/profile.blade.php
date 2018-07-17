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
                <a href="#">
                    <div class="db-social-box">

                        <span class="fa fa-handshake-o"></span>
                        <h5>Relationships</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="#">
                    <div class="db-social-box">
                        <span class="fa fa-id-badge"></span>
                        <h5>Memberships</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="#">
                    <div class="db-social-box">
                        <span class="fa fa-id-card-o"></span>
                        <h5>Childrens Accounts</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="#">
                    <div class="db-social-box">
                        <span class="fa fa-hand-lizard-o"></span>
                        <h5>Cat Accounts</h5>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="#">
                    <div class="db-social-box">
                        <span class="fa fa-snowflake-o"></span>
                        <h5>Dog Accounts</h5>
                    </div>
                </a>
            </div>

        </div>

@endsection