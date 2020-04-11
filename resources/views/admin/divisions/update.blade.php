@extends('template.default')

@section ('title'){{$division->label}} @endsection

@section('content')

  <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/admin/divisions;">Divisions</a> > <a href="javascript:;">Update</a></h4>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Update Division</h4>

            <form class="form-horizontal myForms" method="POST" action="/admin/divisions/update/{{$division->divisionid}}" role="form">
                @csrf
                <div class="form-group row">
                    <label for="inputOrgName3" class="col-sm-12 col-md-3 col-form-label">Division*</label>
                    <div class="col-md-9">
                        <input name="label" type="text"
                               class="form-control{{ $errors->has('label') ? ' is-invalid' : '' }}"
                               id="inputOrgName3" value="{{$division->label ?? old('label')}}">
                        @if ($errors->has('label'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('label') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
	                <label class="col-sm-12 col-md-3 col-form-label">Parent Organisation</label>
	                <div class="col-md-9">
	                    <select name="organisationid" class="form-control">
                            <option value="0">None</option>
                            @foreach($organisations as $organisation)
                                <option value="{{$organisation->organisationid}}"
                                    {!! $division->organisationid == $organisation->organisationid ? 'selected' : '' !!}>
                                    {{$organisation->label}}
                                </option>
	                        @endforeach
	                    </select>
	                    <span class="help-block"><small>Select an organisation the division belongs to</small></span>
	                </div>
	            </div>

                 <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Code*</label>
                    <div class="col-md-9">
                        <input name="code" type="text"
                               class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ $division->code ?? old('code')}}">
                        @if ($errors->has('code'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Age Group</label>
                    <div class="col-md-9">
                        <select name="age" class="form-control">
                            <option value="none">None</option>
                                @foreach($divisionages as $divage)
                                    <option value="{{$divage->class}}"
                                            {!! old('age') ?? $division->age == $divage->class ? 'selected' : '' !!}>
                                        {{ucwords($divage->label)}}
                                    </option>
                                @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Bow Type</label>
                    <div class="col-md-9">
                        <select name="bowtype" class="form-control">
                            @php $bowtypes = [
                                    'C' => 'Compound',
                                    'R' => 'Recurve',
                                    'L' => 'Longbow',
                                    'B' => 'Barebow',
                                    'X' => 'Crossbow'
                                    ]; @endphp
                            @foreach($bowtypes as $bowtype)
                                <option value="{{$bowtype}}"
                                        {!! (old('bowtype') ?? $division->bowtype) == strtolower($bowtype) ? 'selected' : '' !!}>
                                    {{ucwords($bowtype)}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Class</label>
                    <div class="col-md-9">
                        <select name="class" class="form-control">
                            @php $bowtypes = [
                                    'C' => 'Compound',
                                    'R' => 'Recurve',
                                    'L' => 'Longbow',
                                    'BB' => 'Barebow',
                                    'XB' => 'Crossbow'
                                    ]; @endphp
                            @foreach($bowtypes as $key => $bowtype)
                                <option value="{{$key}}"
                                        {!! (old('class') ?? $division->class) == $key ? 'selected' : '' !!}>
                                    {{ucwords($bowtype)}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Description</label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control" rows="5">{{ $division->description ?? old('description')}}</textarea>
                    </div>
                </div>

                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input name="visible" id="checkbox2" type="checkbox" {{ !empty($division->visible) ? 'checked' : '' }}>
                            <label for="checkbox2">
                                Visible
                            </label>
                        </div>
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

</div>

@endsection