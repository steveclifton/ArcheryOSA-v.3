@extends('template.default')

@section ('title')Create Round @endsection

@section('content')

<div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/admin/clubs">Rounds</a> >
                    <a href="javascript:;">Create</a></h4>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('template.alerts')

            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create New Round</h4>

            <form class="form-horizontal myForms" method="POST" action="/admin/rounds/create" role="form">
                @csrf

                <div class="form-group row">
                    <label for="inputOrgName3" class="col-sm-12 col-md-3 col-form-label">Name*</label>
                    <div class="col-md-9">
                        <input name="label" type="text"
                               class="form-control {{ $errors->has('label') ? ' is-invalid' : '' }}" id="inputOrgName3"
                               value="{{old('label')}}">
                        @if ($errors->has('label'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('label') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
	                <label class="col-sm-12 col-md-3 col-form-label">Organisation</label>
	                <div class="col-md-9">
	                    <select name="organisationid" class="form-control">
	                        <option value="0">None</option>
                            @foreach($organisations as $organisation)
                                <option value="{{$organisation->organisationid}}"
                                        {{old('organisationid') == $organisation->organisationid ? 'selected' : ''}}>
                                        {{$organisation->label}}</option>
	                        @endforeach
	                    </select>
	                </div>
	            </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Round Type</label>
                    <div class="col-md-9">
                        <select name="type" class="form-control">
                            <option value="o" selected>Outdoor</option>
                            <option value="i">Indoor</option>
                            <option value="f">Field</option>
                            <option value="c">Clout</option>
                        </select>
                    </div>
                </div>

	            <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Round Code*</label>
                    <div class="col-md-9">
                        <input name="code" type="text" class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{old('code')}}">
                        @if ($errors->has('code'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
	                <label class="col-sm-12 col-md-3 col-form-label">Round Units</label>
	                <div class="col-md-9">
	                    <select name="unit" class="form-control">
	                        <option value="m" {{old('unit') == 'm' ? 'selected' : ''}}>Meters</option>
	                        <option value="y" {{old('unit') == 'y' ? 'selected' : ''}}>Yards</option>
	                    </select>
	                </div>
	            </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">ANZ Record Map</label>
                    <div class="col-md-9">
                        <select name="anz_record_id" class="form-control">
                            <option value="0">None</option>

                            @foreach($anzRecordRounds as $recordType)
                                <option value="{{$recordType->id}}">
                                    {{$recordType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>

	            <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 1*</label>
                    <div class="col-md-9">
                        <input name="dist1" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{old('dist1')}}">
                        @if ($errors->has('dist1'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('dist1') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

	            <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 1 Max*</label>
                    <div class="col-md-9">
                        <input name="dist1max" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{old('dist1max')}}">
                        @if ($errors->has('dist1max'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('dist1max') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 1 ANZ Record Map</label>
                    <div class="col-md-9">
                        <select name="anz_record_dist1_id" class="form-control">
                            <option value="0">None</option>

                            @foreach($anzRecordRounds as $recordType)
                                <option value="{{$recordType->id}}">
                                    {{$recordType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 2</label>
                    <div class="col-md-9">
                        <input name="dist2" type="text" class="form-control" value="{{old('dist2')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 2 Max</label>
                    <div class="col-md-9">
                        <input name="dist2max" type="text" class="form-control" value="{{old('dist2max')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 2 ANZ Record Map</label>
                    <div class="col-md-9">
                        <select name="anz_record_dist2_id" class="form-control">
                            <option value="0">None</option>

                            @foreach($anzRecordRounds as $recordType)
                                <option value="{{$recordType->id}}">
                                    {{$recordType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 3</label>
                    <div class="col-md-9">
                        <input name="dist3" type="text" class="form-control" value="{{old('dist3')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 3 Max</label>
                    <div class="col-md-9">
                        <input name="dist3max" type="text" class="form-control" value="{{old('dist3max')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 3 ANZ Record Map</label>
                    <div class="col-md-9">
                        <select name="anz_record_dist3_id" class="form-control">
                            <option value="0">None</option>

                            @foreach($anzRecordRounds as $recordType)
                                <option value="{{$recordType->id}}">
                                    {{$recordType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 4</label>
                    <div class="col-md-9">
                        <input name="dist4" type="text" class="form-control" value="{{old('dist4')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 4 Max</label>
                    <div class="col-md-9">
                        <input name="dist4max" type="text" class="form-control" value="{{old('dist4max')}}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 4 ANZ Record Map</label>
                    <div class="col-md-9">
                        <select name="anz_record_dist4_id" class="form-control">
                            <option value="0">None</option>

                            @foreach($anzRecordRounds as $recordType)
                                <option value="{{$recordType->id}}">
                                    {{$recordType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Max Total*</label>
                    <div class="col-md-9">
                        <input name="totalmax" type="text" class="form-control" value="{{old('totalmax')}}">
                    </div>
                </div>

                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input name="visible" id="checkbox2" type="checkbox" checked>
                            <label for="checkbox2">
                                Visible
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-0 justify-content-start row">
                	<div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Create</button>
                    </div>

                </div>
               
            </form>
        </div>
    </div>

</div>

@endsection