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

                <form class="form-horizontal myForms" action="{{ route('password.email') }}" method="POST" role="form">
                    @csrf

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
