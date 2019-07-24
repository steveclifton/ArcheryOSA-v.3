@extends('template.default')

@section ('title')Update Event @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/events/manage">Events</a>
                        <i class="ion-arrow-right-c"></i>
                    <a href="/events/manage/{{$event->eventurl}}">{{ ucwords($event->label) }}</a>
                        <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Update</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('template.alerts')
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Update Event</h4>

            <form class="form-horizontal myForms" action="/events/manage/update/{{$event->eventurl}}" method="POST" role="form">
                @csrf

                <input type="hidden" name="eventid" value="{{$event->eventid}}">
                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Event Name*</label>
                    <div class="col-md-9">
                        <input name="label" type="text" class="form-control{{ $errors->has('label') ? ' is-invalid' : '' }}"
                               id="inputOrgName3" value="{{ $event->label ?? old('label')}}" required >
                        @if ($errors->has('label'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('label') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Event Type</label>
                    <div class="col-md-9">
                        <select name="eventtypeid" class="form-control" disabled>
                            @foreach($eventtypes as $eventtype)
                                <option value="{{$eventtype->eventtypeid}}"
                                        {{ $event->eventtypeid == $eventtype->eventtypeid ? 'selected' : ''}}>
                                    {{ $eventtype->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Event Level</label>
                    <div class="col-md-9">
                        <select name="level" class="form-control">
                            @foreach($eventlevels as $level)
                                <option value="{{$level}}"
                                        {{ (old('level') ?? $event->level ?? '') == $level ? 'selected' : ''}}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Organisation</label>
                    <div class="col-md-9">
                        <select name="organisationid" class="form-control">
                            <option value="0">None</option>
                            @foreach($organisations as $organisation)
                                <option value="{{$organisation->organisationid}}"
                                        {{ $event->organisationid == $organisation->organisationid ? 'selected' : ''}}>
                                    {{$organisation->label}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Host Club</label>
                    <div class="col-md-9">
                        <select name="clubid" class="form-control">
                            <option value="0">None</option>
                            @foreach($clubs as $club)
                                <option value="{{$club->clubid}}"
                                        {{ $event->clubid == $club->clubid ? 'selected' : ''}}>
                                    {{$club->label}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Start Date*</label>
                    <div class="col-md-9">
                        <input type="text" name="start" class="datepicker-autoclose form-control {{ $errors->has('start') ? 'is-invalid' : '' }}"
                               placeholder="Choose Date" value="{{ date('d-m-Y', strtotime($event->start)) ?? old('start')}}" id="datepicker-autoclose">

                        @if ($errors->has('start'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('start') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">End Date*</label>
                    <div class="col-md-9">
                        <input type="text" name="end" class="datepicker-autoclose form-control {{ $errors->has('end') ? 'is-invalid' : '' }}"
                               placeholder="Choose Date" value="{{ date('d-m-Y', strtotime($event->end)) ?? old('end')}}" id="datepicker-autoclose">

                        @if ($errors->has('end'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('end') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                @php
                    $date = (!empty($event->entryclose) && $event->entryclose != '1970-01-01') ? date('d-m-Y', strtotime($event->entryclose)) : old('entryclose');
                @endphp
                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Entries Close</label>
                    <div class="col-md-9">
                        <input type="text" name="entryclose" class="datepicker-autoclose form-control {{ $errors->has('entryclose') ? 'is-invalid' : '' }}"
                               placeholder="Choose Date"
                               value="{{ $date }}"
                               id="datepicker-autoclose">

                        @if ($errors->has('entryclose'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('entryclose') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Contact Person</label>
                    <div class="col-md-9">
                        <input name="contactname" type="text" class="form-control" value="{{ $event->contactname ?? old('contactname')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-12 col-md-3 col-form-label">Email*</label>
                    <div class="col-md-9">
                        <input name="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                               id="inputEmail3" value="{{ $event->email ?? old('email')}}">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Phone</label>
                    <div class="col-md-9">
                        <input name="phone" class="form-control" type="tel" value="{{ $event->phone ?? old('phone')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Location/Address</label>
                    <div class="col-md-9">
                        <textarea name="location" class="form-control" rows="2">{{ $event->location ?? old('location')}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Cost</label>
                    <div class="col-md-9">
                        <input name="cost" class="form-control{{ $errors->has('cost') ? ' is-invalid' : '' }}" type="text" value="{{ $event->cost ?? old('cost')}}">
                        @if ($errors->has('cost'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('cost') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Bank Account</label>
                    <div class="col-md-9">
                        <input name="bankaccount" class="form-control{{ $errors->has('bankaccount') ? ' is-invalid' : '' }}"
                               type="text" value="{{ $event->bankaccount ?? old('bankaccount')}}">
                        @if ($errors->has('bankaccount'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('bankaccount') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Bank Reference</label>
                    <div class="col-md-9">
                        <input name="bankreference" class="form-control{{ $errors->has('bankreference') ? ' is-invalid' : '' }}"
                               type="text" value="{{ $event->bankreference ?? old('bankreference')}}">
                        @if ($errors->has('bankreference'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('bankreference') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Info</label>
                    <div class="col-md-9">
                        <textarea name="info" class="form-control" rows="5">{{ $event->info ?? old('info')}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Schedule</label>
                    <div class="col-md-9">
                        <textarea name="schedule" class="form-control" rows="5">{{ $event->schedule ?? old('schedule')}}</textarea>
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

@endsection