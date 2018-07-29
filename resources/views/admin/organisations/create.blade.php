@extends('template.default')

@section ('title')Create Organisation @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/admin/clubs">Organisations</a> > <a href="javascript:;">Create</a></h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create New Organisation</h4>

            <form class="form-horizontal myForms" action="/admin/organisation/create" method="POST" role="form">
                @csrf

                <div class="form-group row">
                    <label for="inputOrgName3"
                           class="col-sm-12 col-md-3 col-form-label{{ $errors->has('label') ? ' is-invalid' : '' }}">
                            Organisation Name*</label>
                    <div class="col-md-9">
                        <input name="label" type="OrgName" class="form-control" id="inputOrgName3" value="{{old('label')}}">
                        @if ($errors->has('label'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('label') }}</strong>
                            </span>
                        @endif
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
                        <input name="email" type="email"
                               class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="inputEmail3" value="{{old('email')}}">
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
                    <label class="col-sm-12 col-md-3 col-form-label">Description</label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control" rows="5">{{old('description')}}</textarea>
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