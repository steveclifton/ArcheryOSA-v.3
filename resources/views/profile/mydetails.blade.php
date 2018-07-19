@extends('template.default')

@section ('title')Profile @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/profile">Profile</a> > <a href="javascript:;">My Details</a></h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('updateprofile') }}">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputFirstName" class="col-form-label">First Name</label>
                            <input type="text" name="firstname" class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}" id="inputFirstName" placeholder="First Name" value="{{old('firstname') ?? Auth::user()->firstname ?? ''}}">
                            @if ($errors->has('firstname'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('firstname') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputLastName" class="col-form-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}" id="inputLastName" placeholder="Last Name" value="{{old('firstname') ?? Auth::user()->lastname ?? ''}}">
                            @if ($errors->has('lastname'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail4" class="col-form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail4" placeholder="Email" value="{{Auth::user()->email}}" disabled readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPhone" class="col-form-label">Phone</label>
                            <input type="phone" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" id="inputPhone" placeholder="Phone" value="{{old('phone') ?? Auth::user()->phone ?? ''}}">
                            @if ($errors->has('phone'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputAddress" class="col-form-label">Address</label>
                            <input type="text" name="address" class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" id="inputAddress" placeholder="Address" value="{{old('address1') ?? Auth::user()->address1 ?? ''}}">
                            @if ($errors->has('address'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('address') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputCity" class="col-form-label">City</label>
                            <input type="text" name="city" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" id="inputCity" placeholder="City" value="{{old('city') ?? Auth::user()->city ?? ''}}">
                            @if ($errors->has('city'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('city') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputZip" class="col-form-label">Postcode</label>
                            <input type="text" name="postcode" class="form-control {{ $errors->has('postcode') ? 'is-invalid' : '' }}" id="inputZip" placeholder="Postcode" value="{{old('postcode') ?? Auth::user()->postcode ?? ''}}">
                            @if ($errors->has('postcode'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('postcode') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label>Date of Birth</label>
                            <div>
                                <div class="input-group">
                                    <input type="text" name="dateofbirth" class="form-control {{ $errors->has('dateofbirth') ? 'is-invalid' : '' }}" placeholder="dd/mm/yyyy" value="{{old('dateofbirth') ?? Auth::user()->dateofbirth ?? ''}}" id="datepicker-autoclose">
                                    @if ($errors->has('dateofbirth'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('dateofbirth') }}</strong>
                                        </span>
                                    @endif
                                    <div class="input-group-append">
                                        <span class="input-group-text"><i class="md md-event-note"></i></span>
                                    </div>
                                </div><!-- input-group -->
                            </div>
                        </div>
                    </div>

                    {{--<div class="form-group row">--}}
                        {{--<label class="col-2 col-form-label">Profile Image</label>--}}
                        {{--<div class="col-4">--}}
                            {{--<input type="file" class="form-control">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="text-center">
                        <button type="submit" class="btn btn-inverse">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection