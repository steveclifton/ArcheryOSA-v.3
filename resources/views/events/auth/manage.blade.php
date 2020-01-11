@extends('template.default')

@section ('title')Manage Event @endsection



@section('content')


    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage">Events</a>
            /
            <a href="javascript:;">{{ucwords($event->label)}}</a>
        </h4>
    </div>

    @if($event->eventtypeid !== 4 && $eventcompetitions->isEmpty())
        <div class="alert alert-warning">
            Competition missing, please select 'Add Competitions' to continue
        </div>
    @endif

    @include('template.alerts')

    <div class="row">

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/update/{{$event->eventurl}}">
                <div class="db-social-box topLine">
                    <span class="fa fa-edit"></span>
                    <h5>Edit Details</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/settings/{{$event->eventurl}}">
                <div class="db-social-box topLine">
                    <span class="fa fa-cogs"></span>
                    <h5>Settings</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/competitions/{{$event->eventurl}}">
                <div class="db-social-box topLine">
                    <span class="fa fa-bullseye"></span>
                    <h5>{!! $eventcompetitions->isEmpty() ? 'Add' : 'Edit' !!} Competitions</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="javascript:;" onclick="alert('Coming Soon')" data-href="/events/manage/eventcosts/{{$event->eventurl}}">
                <div class="db-social-box bottomLine" style="background-color: lightgrey; opacity: 0.3">
                    <span class="md-local-grocery-store"></span>
                    <h5>Costs</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/evententries/{{$event->eventurl}}">
                <div class="db-social-box bottomline">
                    <span class="fa fa-users"></span>
                    <h5>Entries</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/event/scoring/{{$event->eventurl}}">
                <div class="db-social-box bottomLine">
                    <span class="fa fa-clipboard"></span>
                    <h5> Scoring</h5>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/exports/{{$event->eventurl}}">
                <div class="db-social-box topLine">
                    <span class="fa fa-print"></span>
                    <h5>Exports</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/eventadmins/{{$event->eventurl}}">
                <div class="db-social-box topLine">
                    <span class="md-account-child"></span>
                    <h5> Admins</h5>
                </div>
            </a>
        </div>


        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/communication/{{$event->eventurl}}">
                <div class="db-social-box topLine">
                    <span class="fa fa-envelope-open"></span>
                    <h5> Communications</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4">
            <a href="/events/manage/targetallocations/{{$event->eventurl}}">
                <div class="db-social-box bottomLine">
                    <span class="md-account-child"></span>
                    <h5>Target Allocations</h5>
                </div>
            </a>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-4" >
            <a href="javascript:;" onclick="alert('Coming Soon')" data-href="/events/manage/eventpayments/{{$event->eventurl}}">
                <div class="db-social-box bottomLine" style="background-color: lightgrey; opacity: 0.3">
                    <span class="md-attach-money"></span>
                    <h5>Entry Payments</h5>
                </div>
            </a>
        </div>
    </div>
@endsection