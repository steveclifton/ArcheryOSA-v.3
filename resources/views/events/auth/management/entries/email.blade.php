@extends('template.default')

@section ('title')Email Entry @endsection

@section('content')

    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage">Events</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/evententries/{{$event->eventurl}}">Event Entries</a>
        </h4>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">

            @include('template.alerts')

            <form class="form-horizontal myForms treeForm"
                  method="POST"
                  action="/event/registration/email/admin/{{$event->eventurl}}"
                  role="form">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input name="eventid" type="hidden" value="{{$event->eventid}}">
                <input name="userid" type="hidden" value="{{$user->userid}}">


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Message</label>
                    <div class="col-md-9">
                        <textarea name="message" class="form-control" rows="4" required>{{old('message')}}</textarea>
                    </div>
                </div>

                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Send</button>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/registration.js')}}"></script>
@endsection