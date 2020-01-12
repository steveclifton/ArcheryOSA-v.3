@extends('template.default')

@section ('title')Update Entry @endsection

@section('content')

    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/evententries/{{$event->eventurl}}">Event Entries</a>
        </h4>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">

            @include('template.alerts')

            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Update Archer</h4>



            <form class="form-horizontal myForms treeForm"
                  method="POST"
                  action="/event/registration/update/admin/{{$event->eventurl}}"
                  role="form">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input name="eventid" type="hidden" value="{{$event->eventid}}">
                <input name="userid" type="hidden" value="{{$evententry->userid}}">


                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Firstname*</label>
                    <div class="col-md-9">
                        <input name="firstname" type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}"
                               value="{{old('firstname') ?? ucwords($evententry->firstname)}}" required >
                        @if ($errors->has('firstname'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('firstname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Lastname*</label>
                    <div class="col-md-9">
                        <input name="lastname" type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}"
                               value="{{old('lastname') ?? ucwords($evententry->lastname)}}" required >
                        @if ($errors->has('lastname'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('lastname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Email*</label>
                    <div class="col-md-9">
                        <input name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               value="{{old('email') ?? $evententry->email }}" required >
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Bib Number</label>
                    <div class="col-md-9">
                        <input name="bib" type="text" class="form-control{{ $errors->has('bib') ? ' is-invalid' : '' }}"
                               value="{{old('bib') ?? $evententry->bib ?? ''}}"
                               placeholder="Leave blank if unused">
                        @if ($errors->has('bib'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('bib') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                @if ($event->dateofbirth)
                    <div class="form-group row">
                        <label for="label" class="col-sm-12 col-md-3 col-form-label">Date of Birth*</label>
                        <div class="col-md-9">

                            <input type="text" name="dateofbirth" class="form-control datepicker-autoclose {{ $errors->has('dateofbirth') ? 'is-invalid' : '' }}"
                                   placeholder="Date of Birth"
                                   value="{{old('dateofbirth') ?? $evententry->dateofbirth ?? ''}}">

                            @if ($errors->has('dateofbirth'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('dateofbirth') }}</strong>
                            </span>
                            @endif
                            <span class="help-block"><small>Required for event registration</small></span>

                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Membership{{$event->membershiprequired ? '*' : ''}}</label>
                    <div class="col-md-9">
                        <input name="membership" type="text" class="form-control"
                               value="{{old('membership') ?? $evententry->membership ?? '' }}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Phone</label>
                    <div class="col-md-9">
                        <input name="phone" type="text" class="form-control"
                               value="{{old('phone') ?? $evententry->phone ?? ''}}"  >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Address</label>
                    <div class="col-md-9">
                        <textarea name="address" class="form-control" rows="2">{{old('address')  ?? $evententry->address ?? ''}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Country*</label>
                    <div class="col-md-9">
                        <select name="country"
                                class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" required>

                            <option value="NZL">New Zealand</option>
                            <option value="AUS">Australia</option>
                            <option disabled>__________________</option>
                            @foreach($countrys as $country)
                                <option value="{{$country->iso_3166_3}}"
                                        {!! (old('country') ?? $evententry->country ) == $country->iso_3166_3 ? 'selected' : '' !!}>
                                    {{$country->name}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('country'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('country') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Notes</label>
                    <div class="col-md-9">
                        <textarea name="notes" class="form-control" rows="2">{{old('notes') ?? $evententry->notes}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Club</label>
                    <div class="col-md-9">
                        <select name="clubid" class="form-control">
                            <option value="0">None</option>
                            @foreach($clubs as $club)
                                <option value="{{$club->clubid}}"
                                        {!! (old('clubid') ?? $evententry->clubid ) == $club->clubid ? 'selected' : '' !!}>
                                    {{$club->label}}
                                </option>
                            @endforeach
                        </select>
                        {{--<span class="help-block"><small>Select an organisation the division belongs to</small></span>--}}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Payment Type</label>
                    <div class="col-md-9">
                        <select name="paymenttype" class="form-control">
                            <option value="other" {{$evententry->paymenttype == 'other' ? 'selected' : ''}}>Other</option>
                            <option value="cc" {{$evententry->paymenttype == 'cc' ? 'selected' : ''}}>Credit Card</option>
                            <option value="bt" {{$evententry->paymenttype == 'bt' ? 'selected' : ''}}>Bank Transfer</option>
                        </select>
                        {{--<span class="help-block"><small>Select an organisation the division belongs to</small></span>--}}
                    </div>
                </div>

                @if(!empty($event->schoolrequired))
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">School*</label>
                        <div class="col-md-9">
                            <select name="schoolid"
                                    class="form-control {{ $errors->has('schoolid') ? 'is-invalid' : '' }}" required>
                                <option disabled selected>Pick a School</option>
                                @foreach($schools as $school)
                                    <option value="{{$school->schoolid}}"
                                            {!! ( (old('schoolid') ?? $evententry->schoolid) == $school->schoolid) ? 'selected' : '' !!}>
                                        {{$school->label}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('schoolid'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('schoolid') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                @endif

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Gender*</label>
                    <div class=" col-md-9">
                        <div class="radio radio-primary">
                            <input name="gender" id="radio1" type="radio" value="m" {{$evententry->gender == 'm' ? 'checked' : ''}}>
                            <label for="radio1">
                                Male
                            </label><br>
                            <input name="gender" id="radio2" type="radio" value="f" {{$evententry->gender == 'f' ? 'checked' : ''}}>
                            <label for="radio2">
                                Female
                            </label>
                        </div>
                    </div>
                </div>

                @if (!empty($event->pickup))
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Airport Pickup</label>
                        <div class="col-md-9">
                            <div id="checkb" class="checkbox checkbox-primary">
                                <input name="pickup" type="checkbox" id="pickupc" {!! !empty($evententry->gender) ? 'selected' : '' !!}>
                                <label for="pickupc">
                                    Required
                                </label>
                            </div>
                            <span class="help-block"><small>Please let us know if you need airport transport</small></span>

                        </div>
                    </div>
                @endif

                <div id="eventcompforms">
                    @include('events.public.registration.eventcompform.compformupdate-admin')
                </div>

                @if (!empty($event->mqs))
                    <br>
                    <hr>
                    @php
                        $oldmqs = old('mqs');
                        if (!is_array($oldmqs)) {
                            $oldmqs = json_decode($evententry->details);
                            $oldmqs = $oldmqs->mqs ?? [];
                        }
                    @endphp
                    <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">MQS Scores</h4>

                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">MQS Score 1*</label>
                        <div class="col-md-6">
                            <input name="mqs[]" type="text" class="form-control" value="{{!empty($oldmqs[0]) ? $oldmqs[0] : 0 }}" required >
                            <span class="help-block"><small>Leave as 0 if not applicable</small></span>

                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">MQS Score 2*</label>
                        <div class="col-md-6">
                            <input name="mqs[]" type="text" class="form-control" value="{{!empty($oldmqs[1]) ? $oldmqs[1] : 0 }}" required >
                            <span class="help-block"><small>Leave as 0 if not applicable</small></span>

                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">MQS Score 3*</label>
                        <div class="col-md-6">
                            <input name="mqs[]" type="text" class="form-control" value="{{!empty($oldmqs[2]) ? $oldmqs[2] : 0 }}" required >
                            <span class="help-block"><small>Leave as 0 if not applicable</small></span>

                        </div>

                    </div>
                @endif

                <hr>
                <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Ianseo Settings</h4>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Individual Qual Round</label>
                    <div class="col-md-9">
                        <select name="individualqualround" class="form-control">
                            <option value="1" {!! $evententry->individualqualround == 1 ? 'selected' : '' !!}>Yes</option>
                            <option value="0" {!! $evententry->individualqualround == 0 ? 'selected' : '' !!}>No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Team Qual Round</label>
                    <div class="col-md-9">
                        <select name="teamqualround" class="form-control">
                            <option value="1" {!! $evententry->teamqualround == 1 ? 'selected' : '' !!}>Yes</option>
                            <option value="0" {!! $evententry->teamqualround == 0 ? 'selected' : '' !!}>No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Individual Final</label>
                    <div class="col-md-9">
                        <select name="individualfinal" class="form-control">
                            <option value="1" {!! $evententry->individualfinal == 1 ? 'selected' : '' !!}>Yes</option>
                            <option value="0" {!! $evententry->individualfinal == 0 ? 'selected' : '' !!}>No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Team Final</label>
                    <div class="col-md-9">
                        <select name="teamfinal" class="form-control">
                            <option value="1" {!! $evententry->teamfinal == 1 ? 'selected' : '' !!}>Yes</option>
                            <option value="0" {!! $evententry->teamfinal == 0 ? 'selected' : '' !!}>No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Mixed Team Final</label>
                    <div class="col-md-9">
                        <select name="mixedteamfinal" class="form-control">
                            <option value="1" {!! $evententry->mixedteamfinal == 1 ? 'selected' : '' !!}>Yes</option>
                            <option value="0" {!! $evententry->mixedteamfinal == 0 ? 'selected' : '' !!}>No</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Sub Class</label>
                    <div class="col-md-9">
                        <select name="subclass" class="form-control">
                            <option value="NZ" {!! $evententry->subclass == 'NZ' ? 'selected' : '' !!}>ArcheryNZ Paid Member</option>
                            <option value="IN" {!! $evententry->subclass == 'IN' ? 'selected' : '' !!}>Internernational Competitor (non-ArcheryNZ but affiliated to World Archery)</option>
                            <option value="OP" {!! $evententry->subclass == 'OP' ? 'selected' : '' !!}>Open category (non-ArcheryNZ but permitted to shoot as a one-off exception)</option>
                        </select>
                    </div>
                </div>


                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Update</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/registration.js')}}"></script>
@endsection