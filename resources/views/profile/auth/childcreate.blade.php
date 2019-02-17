@extends('template.default')

@section ('title')Relation @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/profile">Profile</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/profile/children">Children</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Add</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('template.alerts')
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create new Child account</h4>

            <form class="form-horizontal myForms" action="/profile/children/create" method="POST" role="form">
                @csrf

                <div class="form-group row">
                    <label for="inputFirstname" class="col-sm-12 col-md-3 col-form-label">Firstname*</label>
                    <div class="col-md-9">
                        <input name="firstname" type="text"
                               class="form-control {{ $errors->has('firstname') ? ' is-invalid' : '' }}"
                               value="{{old('firstname')}}">
                        @if ($errors->has('firstname'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('firstname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputLastname" class="col-sm-12 col-md-3 col-form-label">Lastname*</label>
                    <div class="col-md-9">
                        <input name="lastname" type="text"
                               class="form-control {{ $errors->has('lastname') ? ' is-invalid' : '' }}"
                               id="inputLastname" value="{{old('lastname')}}">
                        @if ($errors->has('lastname'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('lastname') }}</strong>
                            </span>
                        @endif
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
                    <label class="col-sm-12 col-md-3 col-form-label">Date of Birth</label>
                    <div class="col-md-9">
                        <div class="input-group">
                            <input type="text" name="dateofbirth"
                                   class="form-control datepicker-autoclose {{ $errors->has('dateofbirth') ? 'is-invalid' : '' }}"
                                   placeholder="dd/mm/yyyy" value="{{old('dateofbirth')}}" id="">

                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event-note"></i></span>
                            </div>
                        </div><!-- input-group -->
                    </div>
                </div>


                <div class="form-group row">
                    <label for="inputMembership" class="col-sm-12 col-md-3 col-form-label">Membership Number</label>
                    <div class="col-md-9">
                        <input name="membership" type="text"
                               class="form-control {{ $errors->has('membership') ? ' is-invalid' : '' }}"
                               id="inputMembership" value="{{old('membership')}}">

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
@endsection