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

            </form>
        </div>
    </div>





@endsection