@extends('template.default')

@section ('title')Update Entry @endsection

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
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Membership</label>
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


                @php
                    $divArr = explode(',', $evententry->divisionid);
                @endphp

                @if ($event->isLeague() || $multipledivisions)
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Division*</label>
                        <div class="col-md-9">
                            @foreach($divisionsfinal as $division)
                                <div id="checkb" class="checkbox checkbox-primary">
                                    <input name="multipledivs[]" id="divids-{{$division->divisionid}}" type="checkbox" value="{{$division->divisionid}}"
                                            {!! in_array($division->divisionid, $divArr) ? 'checked' : '' !!}>
                                    <label for="divids-{{$division->divisionid}}">
                                        {{$division->label}}

                                    </label>
                                </div>

                            @endforeach
                            @if ($errors->has('divisionid'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('divisionid') }}</strong>
                            </span>
                            @endif
                            {{--<span class="help-block"><small>Select an organisation the division belongs to</small></span>--}}
                        </div>
                    </div>
                    <input type="hidden" name="divisionid" id="mDivid">
                @else
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Division*</label>
                        <div class="col-md-9">
                            <select name="divisionid" class="form-control {{ $errors->has('divisionid') ? 'is-invalid' : '' }}" required>
                                <option disabled selected>Pick one</option>
                                @foreach($divisionsfinal as $division)
                                    <option value="{{$division->divisionid}}"
                                            {!! (old('divisionid') ?? $evententry->divisionid ) == $division->divisionid ? 'selected' : '' !!}>
                                        {{$division->label}}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('divisionid'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('divisionid') }}</strong>
                            </span>
                            @endif
                            {{--<span class="help-block"><small>Select an organisation the division belongs to</small></span>--}}
                        </div>
                    </div>
                @endif




                <div class="form-group row justify-content-end">
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

                @if ($event->isLeague())
                    <input name="roundids" type="hidden" id="jsfields" value="{{$leaguecompround}}"/>
                @else
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Competitions*</label>
                        <div class="col-md-9">
                            <div class="">
                                <div class="alert-danger">WARNING - changing a users rounds or divisions will result it scores being lost for effected <br>
                                changes, if scores already exist, please check scores after any changes.</div>
                                <br>
                                <div class="card-box">
                                    <h4 class="text-dark header-title m-t-0 m-b-30">Select the competitions you wish to enter</h4>
                                    @php $i = 1 @endphp
                                    <div id="checkTree">
                                        @foreach($competitionsfinal as $date => $eventcompetition)
                                            <ul>
                                                <li data-jstree='{"opened":true, "icon": "ion-calendar"}'>{{date('D d F', strtotime($date))}}
                                                    @foreach($eventcompetition as $label => $ec)
                                                    <ul>
                                                        <li data-jstree='{"opened":true, "icon": "ion-calendar"}'>{{$label}}
                                                            <ul>
                                                            @foreach($ec->rounds as $round)
                                                                <li data-eventcompetitionid="{{$ec->eventcompetitionid}}"
                                                                    data-roundid="{{$round->roundid}}"
                                                                    data-jstree='{"opened":true, "icon": "ion-star",
                                                                    "selected":"{{ !empty($entrycompetitionids[$ec->eventcompetitionid][$round->roundid]) ? 'true' : '' }}"
                                                                    }'>{{$round->label}}
                                                            @endforeach
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                    @endforeach
                                                </li>
                                            </ul>
                                        @endforeach
                                    </div>

                                </div>
                            </div><!-- end col -->
                            <div id="comperror" class="alert alert-danger hidden">Please select at least 1 competition</div>
                        </div>
                    </div>
                    <input name="roundids" type="hidden" id="jsfields" value=""/>
                @endif


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