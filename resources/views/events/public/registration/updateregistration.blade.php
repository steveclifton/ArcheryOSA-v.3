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


            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Competitions</h4>
            <p style="text-align: center">Add or update the competitions for your event. <br>You <strong>must</strong> save the changes before changing the date</p>

            <form class="form-horizontal myForms treeForm"
                  method="POST"
                  action="/event/registration/update/{{$event->eventurl}}"
                  role="form">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input name="eventid" type="hidden" value="{{$event->eventid}}">
                <input name="userid" type="hidden" value="{{$user->userid}}">



                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Firstname*</label>
                    <div class="col-md-9">
                        <input name="firstname" type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}"
                               value="{{ old('firstname') ?? $evententry->firstname}}" required >
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
                               value="{{old('lastname') ?? $evententry->lastname ?? ucwords($user->lastname)}}" required >
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
                               value="{{ old('email') ?? $evententry->email ?? ucwords($user->email)}}" required >
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                @if ($event->dateofbirth)
                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Date of Birth*</label>
                    <div class="col-md-9">

                        <input type="text" name="dateofbirth" class="form-control {{ $errors->has('dateofbirth') ? 'is-invalid' : '' }}"
                               placeholder="dd-mm-yyyy"
                               value="{{ old('dateofbirth') ?? $evententry->dateofbirth ?? $user->dateofbirth ?? ''}}"
                               id="datepicker-autoclose">

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
                               value="{{ old('membership') ?? $evententry->membership }}"  >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Phone</label>
                    <div class="col-md-9">
                        <input name="phone" type="text" class="form-control"
                               value="{{ old('phone') ?? $evententry->phone ?? $user->phone ?? ''}}"  >
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Address</label>
                    <div class="col-md-9">
                        <textarea name="address" class="form-control" rows="2">{{ old('address') ?? $evententry->address }}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Notes</label>
                    <div class="col-md-9">
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') ?? $evententry->notes}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Club</label>
                    <div class="col-md-9">
                        <select name="clubid" class="form-control  {{ $errors->has('clubid') ? 'is-invalid' : '' }}">
                            @if(empty($event->clubrequired)) <option value="0">None</option> @endif
                            @foreach($clubs as $club)
                                <option value="{{$club->clubid}}"
                                        {!!  (old('clubid') ?? $evententry->clubid) == $club->clubid ? 'selected' : '' !!}>
                                    {{$club->label}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('clubid'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('clubid') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                @if ($event->isLeague() || $multipledivisions)
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Division*</label>
                        <div class="col-md-9">
                            @foreach($divisionsfinal as $division)
                                <div id="checkb" class="checkbox checkbox-primary">
                                    <input name="multipledivs[]" id="divids-{{$division->divisionid}}" type="checkbox" value="{{$division->divisionid}}"
                                            {!! in_array($division->divisionid, $divisions) ? 'checked' : '' !!}>
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
                                <option disabled selected >Pick one</option>
                                @foreach($divisionsfinal as $division)
                                    <option value="{{$division->divisionid}}"
                                            {!! (old('divisionid') ?? $evententry->divisionid) == $division->divisionid ? 'selected' : '' !!}>
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

                @if ($event->eventtypeid == 2)
                    <input name="roundids" type="hidden" id="jsfields" value="{{$leaguecompround}}"/>
                @else
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Competitions*</label>
                        <div class="col-md-9">
                            <div class="">
                                <div class="card-box">
                                    <h4 class="text-dark header-title m-t-0 m-b-30">Select the competitions you wish to enter</h4>
                                    @php $i = 1 @endphp
                                    <div id="checkTree">
                                        @foreach($competitionsfinal as $date => $eventcompetition)
                                            <ul>
                                                <li data-jstree='{"opened":{{$i++ == 1 ? 'true' : 'false'}}, "icon": "ion-calendar"}'>{{date('D d F', strtotime($date))}}
                                                <ul>
                                                    @foreach($eventcompetition->rounds as $round)
                                                        <li data-eventcompetitionid="{{$eventcompetition->eventcompetitionid}}"
                                                            data-roundid="{{$round->roundid}}"
                                                            data-jstree='{"opened":true, "icon": "ion-star",
                                                            "selected":"{{ !empty($entrycompetitionids[$eventcompetition->eventcompetitionid][$round->roundid]) ? 'true' : '' }}"
                                                            }'>{{$round->label}}
                                                    @endforeach
                                                </ul>
                                                </li>
                                            </ul>
                                        @endforeach
                                    </div>

                                </div>
                                </div><!-- end col -->
                            <div id="comperror" class="alert alert-danger hidden">Please select at least 1 competition</div>
                        </div>
                    </div>
                    <input name="roundids" type="hidden" id="jsfields" value="" />
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

    <script src="{{URL::asset('/js/events/registration.js')}}"></script>
@endsection