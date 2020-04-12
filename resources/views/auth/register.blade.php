@extends('template.default')

@section('title')Register @endsection

@section('content')

    <div class="account-pages"></div>
    <div class="clearfix"></div>
    <div class="wrapper-page">
        <div class="card-box">
            <div class="panel-heading">
                <h4 class="text-center"> Register </h4>
                <i class="fa fa-user-circle loginPerson"></i>
            </div>

            <div class="formInput">
                <form method="POST" class="form-horizontal m-t-20" action="{{ route('register') }}">
                    @csrf


                    <div class="form-group ">
                        <div class="col-12">
                            <input name="firstname" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" type="text" required="" placeholder="First Name" value="{{ old('firstname') }}">
                            @if ($errors->has('firstname'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('firstname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>


                    <div class="form-group ">
                        <div class="col-12">
                            <input name="lastname" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" type="text" required="" placeholder="Last Name" value="{{ old('lastname') }}">
                            @if ($errors->has('lastname'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('lastname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-12">
                            <input name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" type="email" required="" placeholder="Email" value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <input name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password" required="" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-12">
                            <input name="password_confirmation" class="form-control" type="password" required="" placeholder="Confirm Password">
                        </div>
                    </div>

                    <div class="form-group ">
                        <div class="col-12">
                            <div class="checkbox checkbox-primary">
                                <input name="addchild" id="addchild-checkbox" type="checkbox" {{ old('addchild') ? 'checked' : '' }}>
                                <label for="addchild-checkbox">
                                    Parents - Add Child
                                </label>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(function() {
                            $('#addchild-checkbox').on('change', function() {
                                $('#childaccount').toggle();
                            });
                        });
                    </script>

                    <div id="childaccount" style="display: {{ old('addchild') ? '' : 'none' }}">
                        <div class="form-group ">
                            <div class="col-12">
                                <input name="childfirstname" class="form-control{{ $errors->has('childfirstname') ? ' is-invalid' : '' }}" type="text" placeholder="Child's First Name" value="{{ old('childfirstname') }}">
                                @if ($errors->has('childfirstname'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>Firstname is required</strong>
                                </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group ">
                            <div class="col-12">
                                <input name="childlastname" class="form-control{{ $errors->has('childlastname') ? ' is-invalid' : '' }}" type="text" placeholder="Child's Last Name" value="{{ old('childlastname') }}">
                                @if ($errors->has('childlastname'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>Lastname is required</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <input name="childemail" class="form-control{{ $errors->has('childemail') ? ' is-invalid' : '' }}" type="email" placeholder="Child's Email" value="{{ old('childemail') }}">
                                @if ($errors->has('childemail'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>This email has already been taken</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                    <br>

                    <div class="form-group">
                        <div class="col-12">
                            <div class="g-recaptcha"
                                 data-sitekey="{{$sitekey}}">
                            </div>
                        </div>
                    </div>
                    @if ($errors->has('g-recaptcha-response'))
                        <div class="col-md-12">
                            <span style="color: red">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        </div>
                    @endif


                    <div class="form-group text-center m-t-40">
                        <div class="col-12">
                            <button class="btn btn-inverse btn-block text-uppercase waves-effect waves-light"
                                    type="submit">Register
                            </button>
                        </div>
                    </div>




                </form>

            </div>
        </div>

    </div>
    </div>




@endsection


