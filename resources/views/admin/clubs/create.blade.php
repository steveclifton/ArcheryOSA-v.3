@extends('template.default')

@section ('title')Create Club @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/admin/clubs;">Clubs</a> > <a href="javascript:;">Create</a></h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create New Club</h4>

            <form class="form-horizontal myForms" action="/admin/clubs/create" method="POST" role="form">
                @csrf

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Club Name*</label>
                    <div class="col-md-9">
                        <input name="label" type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}"
                               id="inputOrgName3" value="{{old('label')}}" required >
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
                                        {{old('organisationid') == $organisation->organisationid ? 'selected' : ''}}>
                                        {{$organisation->label}}
                                </option>
                            @endforeach
	                    </select>
	                </div>
	            </div>

	            <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">URL</label>
                    <div class="col-md-9">
                        <input name="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" type="url" value="{{old('url')}}">
                        @if ($errors->has('url'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('url') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Contact Person</label>
                    <div class="col-md-9">
                        <input name="contactname" type="text" class="form-control" value="{{old('contactname')}}">
                    </div>
                </div>

                 <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-12 col-md-3 col-form-label">Email</label>
                    <div class="col-md-9">
                        <input name="email" type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                               id="inputEmail3" value="{{old('email')}}">
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
                        <input name="phone" class="form-control" type="tel" value="{{old('phone')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Street</label>
                    <div class="col-md-9">
                        <input name="address" type="text" class="form-control" value="{{old('address')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Suburb</label>
                    <div class="col-md-9">
                        <input name="suburb" type="text" class="form-control" value="{{old('suburb')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">City</label>
                    <div class="col-md-9">
                        <input name="city" type="text" class="form-control" value="{{old('city')}}">
                    </div>
                </div>

                <div class="form-group row">
	                <label class="col-sm-12 col-md-3 col-form-label">Country</label>
	                <div class="col-md-9">
	                    <select name="country" class="form-control">
	                        <option value="nz">New Zealand</option>
                            <option value="au">Australia</option>
                            <option value="other">Other</option>
	                    </select>
	                </div>
	            </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Description</label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control" rows="5">{{old('description')}}</textarea>
                    </div>
                </div>

                {{--<div class="form-group row">--}}
                    {{--<label class="col-3 col-form-label">Logo</label>--}}
                    {{--<div class="col-9">--}}
                        {{--<input type="file" class="form-control">--}}
                    {{--</div>--}}
                {{--</div>--}}

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