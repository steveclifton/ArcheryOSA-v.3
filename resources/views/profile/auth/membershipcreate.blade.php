@extends('template.default')

@section ('title')Membership @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/profile">Profile</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/profile/memberships">Memberships</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Add</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('template.alerts')
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create Membership</h4>

            <form class="form-horizontal myForms" action="/profile/membership/create/" method="POST" role="form">
                @csrf
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Organisation</label>
                    <div class="col-md-9">
                        <select name="userid" class="form-control">

                            <option value="{{Auth::id()}}"
                                    {{ (old('userid')) == Auth::id() ? 'selected' : ''}}>
                                {{ Auth::user()->getFullname() }}
                            </option>

                            @foreach(Auth::user()->getChildren() as $child)
                                <option value="{{$child->userid}}"
                                        {{ (old('userid')) == $child->userid ? 'selected' : ''}}>
                                    {{ $child->getFullName() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="form-group row">
                    <label for="inputMembership" class="col-sm-12 col-md-3 col-form-label">Membership Number*</label>
                    <div class="col-md-9">
                        <input name="membership" type="text"
                               class="form-control {{ $errors->has('membership') ? ' is-invalid' : '' }}"
                               id="inputMembership" value="{{old('membership')}}" required>

                        @if ($errors->has('membership'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('membership') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Organisation</label>
                    <div class="col-md-9">
                        <select name="organisationid" class="form-control">
                            <option value="0">None</option>
                            @foreach($organisations as $organisation)
                                <option value="{{$organisation->organisationid}}"
                                        {{ (old('organisationid')) == $organisation->organisationid ? 'selected' : ''}}>
                                    {{ $organisation->label }}
                                </option>
                            @endforeach
                        </select>
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