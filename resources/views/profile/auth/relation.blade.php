@extends('template.default')

@section ('title')Relation @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/profile">Profile</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/profile/relationships">My Relationships</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Add</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('template.alerts')
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create new Relationship</h4>

            <form class="form-horizontal myForms" action="/profile/relationships/request" method="POST" role="form">
                @csrf

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


                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Request</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection