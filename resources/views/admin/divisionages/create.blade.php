@extends('template.default')

@section ('title')Create Division Age Group @endsection

@section('content')

  <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/admin/divisionages">Divisions Age Group</a> > <a href="javascript:;">Create</a></h4>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create New Division Age</h4>

            <form class="form-horizontal myForms" method="POST" action="/admin/divisionages/create" role="form">
                @csrf
                <div class="form-group row">
                    <label for="inputOrgName3" class="col-sm-12 col-md-3 col-form-label">Division Age Label*</label>
                    <div class="col-md-9">
                        <input name="label" type="text"
                               class="form-control{{ $errors->has('label') ? ' is-invalid' : '' }}"
                               id="inputOrgName3" value="{{old('label')}}">
                        @if ($errors->has('label'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('label') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Description</label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control" rows="5">{{old('description')}}</textarea>
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