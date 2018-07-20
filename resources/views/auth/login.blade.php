@extends('template.default')

@section ('title')Login @endsection

@section('content')
    <!-- login form start -->
        {{--<div class="account-pages"></div>--}}
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="card-box">
                <div class="panel-heading">
                    <h4 class="text-center"> Sign In </h4>
                    <i class="fa fa-user-circle loginPerson"></i>
                </div>


                <div class="formInput">
                    <form method="POST" class="form-horizontal m-t-20" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group ">
                            <div class="col-12">
                                <input name="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" type="text" required="" value="{{ old('email') }}" placeholder="Email">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-12">
                                <input name="password" class="form-control {{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" required="" placeholder="Password">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group ">
                            <div class="col-12">
                                <div class="checkbox checkbox-primary">
                                    <input name="remember" id="checkbox-signup" type="checkbox" {{ old('remember') ? 'checked' : '' }}>
                                    <label for="checkbox-signup">
                                        Remember me
                                    </label>
                                </div>

                            </div>
                        </div>

                        <div class="form-group text-center m-t-40">
                            <div class="col-12">
                                <button class="btn btn-inverse btn-block text-uppercase waves-effect waves-light"
                                        type="submit">Log In
                                </button>
                            </div>
                        </div>

                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-12">
                                <a href="/resetpassword" class="text-dark"><i class="fa fa-lock m-r-5"></i> Forgot
                                    your password?</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <p>Don't have an account? <a href="/register" class="text-primary m-l-5"><b>Sign Up</b></a>
                    </p>

                </div>
            </div>

        </div>

@endsection
