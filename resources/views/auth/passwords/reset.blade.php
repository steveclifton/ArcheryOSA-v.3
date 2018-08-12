@extends('template.default')

@section ('title')Reset Password @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card-box">
                <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Reset Password</h4>
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="/password/reset" aria-label="{{ __('Reset Password') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group row">
                        <label for="label" class="col-sm-12 col-md-3 col-form-label">Email Address</label>
                        <div class="col-md-9">

                            <input name="email" type="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   id="inputOrgName3" value="{{old('email')}}" required>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="label" class="col-sm-12 col-md-3 col-form-label">Password</label>
                        <div class="col-md-9">

                            <input name="password" type="password"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   id="inputOrgName3" required>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="label" class="col-sm-12 col-md-3 col-form-label">Password Confirmation</label>
                        <div class="col-md-9">

                            <input name="password_confirmation" type="password"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   id="inputOrgName3" required>

                        </div>
                    </div>

                    <div class="form-group mb-0 justify-content-start row">
                        <div class="col-sm-12 col-md-3 col-form-label"></div>
                        <div class="col-3">
                            <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">
                                Reset Password
                            </button>
                        </div>

                    </div>

                </form>
            </div>
        </div>

    </div>

@endsection
