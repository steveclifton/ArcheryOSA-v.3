@extends('template.default')

@section ('title')Update Registration @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/events">Events</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/event/details/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Enter</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">

            @include('template.alerts')


            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Event Entry</h4>


            <br>
            <form class="form-horizontal myForms treeForm"
                  method="POST"
                  action="/event/registration/update/{{$event->eventurl}}"
                  role="form">
                @csrf

                <input name="userid" type="hidden" value="{{$user->userid}}">

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Firstname*</label>
                    <div class="col-md-9">
                        <input name="firstname" type="text" class="form-control"
                               value="{{ ucwords($evententry->firstname)}}" required >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Lastname*</label>
                    <div class="col-md-9">
                        <input name="lastname" type="text" class="form-control"
                               value="{{ucwords($evententry->lastname)}}" required >
                    </div>
                </div>


                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Email</label>
                    <div class="col-md-9">
                        <input name="email" type="text" class="form-control"
                               value="{{ucwords($evententry->email)}}" readonly disabled>
                    </div>
                </div>

                @if ($event->dateofbirth)
                    <div class="form-group row">
                        <label for="label" class="col-sm-12 col-md-3 col-form-label">Date of Birth</label>
                        <div class="col-md-9">

                            <input type="text" name="dateofbirth" class="form-control datepicker-autoclose "
                                   placeholder="dd-mm-yyyy"
                                   value="{{ $evententry->dateofbirth}}"
                                   id="datepicker-autoclose">

                            <span class="help-block"><small>Required for event registration</small></span>

                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Membership Number*</label>
                    <div class="col-md-9">
                        <input name="membership" type="text" class="form-control"
                               value="{{$evententry->membership }}" required>
                        @if (!empty($event->organisationid) && $event->organisationid == 1)
                            <span class="help-block">
                                    <small>Archery New Zealand Membership Number</small>
                                </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Phone</label>
                    <div class="col-md-9">
                        <input name="phone" type="text" class="form-control"
                               value="{{ $evententry->phone ?? ''}}">
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Address</label>
                    <div class="col-md-9">
                        <textarea name="address" class="form-control" rows="2">{{ $evententry->address }}</textarea>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Country*</label>
                    <div class="col-md-9">
                        <select name="country"
                                class="form-control " readonly disabled>

                            <option value="NZL">New Zealand</option>
                            <option value="AUS">Australia</option>
                            <option disabled>__________________</option>
                            @foreach ($countrys as $country)
                                <option value="{{$country->iso_3166_3}}"
                                        {!! ($evententry->country) == $country->iso_3166_3 ? 'selected' : '' !!}>
                                    {{$country->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Notes</label>
                    <div class="col-md-9">
                        <textarea name="notes" class="form-control" rows="2">{{ $evententry->notes}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Club</label>
                    <div class="col-md-9">
                        <select name="clubid" class="form-control">
                            @if(empty($event->clubrequired)) <option value="0">None</option> @endif
                            @foreach($clubs as $club)
                                <option value="{{$club->clubid}}"
                                        {!!  ($evententry->clubid) == $club->clubid ? 'selected' : '' !!}>
                                    {{$club->label}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @if(!empty($event->schoolrequired))
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">School*</label>
                        <div class="col-md-9">
                            <select name="schoolid"
                                    class="form-control">

                                @foreach($schools as $school)
                                    <option value="{{$school->schoolid}}"
                                            {!! ( ($evententry->schoolid) == $school->schoolid) ? 'selected' : '' !!}>
                                        {{$school->label}}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                @endif

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Gender</label>
                    <div class="col-md-9">
                        <select name="gender"
                                class="form-control" readonly="" disabled>
                            <option disabled selected>Select one</option>
                            <option value="m" {!! ($evententry->gender == 'm') ? 'selected' : '' !!}>Male</option>
                            <option value="f" {!! ($evententry->gender == 'f') ? 'selected' : '' !!}>Female</option>
                        </select>
                    </div>
                </div>

                @if (!empty($event->pickup))
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Airport Pickup*</label>
                        <div class="col-md-9">
                            <div id="checkb" class="checkbox checkbox-primary">
                                <input name="pickup" type="checkbox" id="pickupc" {!! ($evententry->pickup )  ? 'checked' : '' !!}>
                                <label for="pickupc">
                                    Required
                                </label>
                            </div>
                            <span class="help-block"><small>Please let us know if you need airport transport</small></span>
                        </div>
                    </div>
                @endif
                <br>
                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Update</button>
                    </div>
                </div>
                <br>
                <hr>

                <br>
                <h5 style="text-align: center; color: lightcoral">To change any of the below details please contact the event organiser</h5>

                <div class="widget-inline-box text-center">
                    <button type="button" class="btn btn-warning waves-effect waves-light"
                            data-toggle="modal"
                            data-target="#myModal">Contact
                    </button>
                </div>

                <div id="eventcompforms">
                    @include('events.public.registration.eventcompform.compformupdate')
                </div>

                @if (!empty($event->mqs))
                    <hr>
                    @php
                        $oldmqs = old('mqs');
                        if (!is_array($oldmqs)) {
                            $oldmqs = json_decode($evententry->details);
                            $oldmqs = $oldmqs->mqs ?? [];
                        }
                    @endphp
                    <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">MQS Scores Required</h4>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">MQS Score 1*</label>
                        <div class="col-md-6">
                            <input name="mqs[]" type="text" class="form-control" value="{{!empty($oldmqs[0]) ? $oldmqs[0] : 0 }}" disabled readonly>
                            <span class="help-block"><small>Leave as 0 if not applicable</small></span>

                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">MQS Score 2*</label>
                        <div class="col-md-6">
                            <input name="mqs[]" type="text" class="form-control" value="{{!empty($oldmqs[1]) ? $oldmqs[1] : 0 }}" disabled readonly>
                            <span class="help-block"><small>Leave as 0 if not applicable</small></span>

                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">MQS Score 3*</label>
                        <div class="col-md-6">
                            <input name="mqs[]" type="text" class="form-control" value="{{!empty($oldmqs[2]) ? $oldmqs[2] : 0 }}" disabled readonly>
                            <span class="help-block"><small>Leave as 0 if not applicable</small></span>
                        </div>
                    </div>
                @endif

            </form>
        </div>
    </div>


    <div id="myModal" class="modal fade"
         tabindex="-1" role="dialog"
         aria-labelledby="full-width-modalLabel"
         aria-hidden="true" style="display: none;">

        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="full-width-modalLabel">Contact</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <br>
                <div class="alert" id="sendingmessage">

                </div>
                <div class="modal-body">
                    <div class="col-lg-12">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="contactinput" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success waves-effect" id="submitcontact">
                        Send
                    </button>
                    <button type="button" class="btn btn-secondary waves-effect" id="closemodal" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $(function() {

            $(document).on('click', '#submitcontact', function(e){
                e.preventDefault();
                var message = $('#contactinput').val();

                $.ajax({
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/ajax/events/contact/",
                    data: {
                        entryid: '{{$evententry->entryid}}',
                        message: message
                    }
                }).done(function( json ) {

                    if (json) {

                        $('#sendingmessage').addClass(' alert-success').html("Message sent to organiser");

                        setTimeout(function () {
                            $('#closemodal').trigger('click');
                        }, 1000);

                        return;
                    }

                    $('#sendingmessage').addClass(' alert-danger').html("Message not sent, please contact info@archeryosa.com");

                });


            })

        });
    </script>

@endsection