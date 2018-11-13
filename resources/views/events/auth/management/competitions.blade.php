@extends('template.default')

@section ('title')Competitions @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/events/manage">Events</a>
                        <i class="ion-arrow-right-c"></i>
                    <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                        <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Competitions</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">

            @include('template.alerts')

            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Competitions</h4>
            <p style="text-align: center">Add or update the competitions for your event. <br>You <strong>must</strong> save the changes before changing the date</p>

            <form class="form-horizontal myForms treeFormCompetitions" method="POST"
                  action="/events/manage/competitions/{{$formaction}}/{{$event->eventurl}}" role="form">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input name="eventid" type="hidden" value="{{$event->eventid}}">

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Competitions</label>
                    <div class="col-md-9">
                        <select id="eventcompetitions" class="form-control">
                            <option value="createnew">Create New</option>
                            @foreach($eventcompetitions as $eventcompetition)
                                <option value="{{$eventcompetition->date}}"
                                        data-cid="{{$eventcompetition->eventcompetitionid}}"
                                    {!! $eventcompetition->eventcompetitionid == $competition['eventcompetitionid'] ? 'selected' : '' !!}
                                    >{{ucwords($eventcompetition->label)}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="ajaxFormReplace">
                    @include('events.auth.management.includes.dateselect')

                    @include('events.auth.management.includes.competitiontext')

                    @include('events.auth.management.includes.competitiontree')

                    @include('events.auth.management.includes.competitionoptions')
                </div>



                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/divisions.js')}}"></script>

@endsection