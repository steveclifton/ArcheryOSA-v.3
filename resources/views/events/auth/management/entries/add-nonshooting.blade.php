@extends('template.default')

@section ('title')Add Entry @endsection

@section('content')

    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage">Events</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/evententries/{{$event->eventurl}}">Event Entries</a>
        </h4>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">

            @include('template.alerts')

            <div class="form-group row">
                <label for="label" class="col-sm-12 col-md-3 col-form-label">Search archer by name</label>
                <div class="col-md-9">
                    <input id="searchUser" name="firstname" type="text" class="form-control" required>
                </div>
            </div>

            <div class="form-group row" id="searchResults" style="display: none;">
                <label for="label" class="col-sm-12 col-md-3 col-form-label">Results</label>
                <div class="col-md-9">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tableData">

                        </tbody>
                    </table>
                </div>
            </div>


            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Add Archer</h4>



            <form class="form-horizontal myForms treeForm"
                  method="POST"
                  action="/event/registration/create/admin/{{$event->eventurl}}"
                  role="form">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input name="eventid" type="hidden" value="{{$event->eventid}}">
                <input name="userid" type="hidden" value="{{old('userid')}}">



                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Firstname*</label>
                    <div class="col-md-9">
                        <input name="firstname" type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}"
                               value="{{old('firstname')}}" required >
                        @if ($errors->has('firstname'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('firstname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Lastname*</label>
                    <div class="col-md-9">
                        <input name="lastname" type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}"
                               value="{{old('lastname')}}" required >
                        @if ($errors->has('lastname'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('lastname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Email*</label>
                    <div class="col-md-9">
                        <input name="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               value="{{old('email')}}"
                                placeholder="Leave blank if unknown">
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                @if ($event->dateofbirth)
                    <div class="form-group row">
                        <label for="label" class="col-sm-12 col-md-3 col-form-label">Date of Birth*</label>
                        <div class="col-md-9">

                            <input type="text" name="dateofbirth" class="form-control datepicker-autoclose {{ $errors->has('dateofbirth') ? 'is-invalid' : '' }}"
                                   placeholder="Date of Birth"
                                   value="{{old('dateofbirth')}}">

                            @if ($errors->has('dateofbirth'))
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('dateofbirth') }}</strong>
                            </span>
                            @endif
                            <span class="help-block"><small>Required for event registration</small></span>

                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Membership{{$event->membershiprequired ? '*' : ''}}</label>
                    <div class="col-md-9">
                        <input name="membership" type="text" class="form-control"
                               value="{{old('membership')}}">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="label" class="col-sm-12 col-md-3 col-form-label">Phone</label>
                    <div class="col-md-9">
                        <input name="phone" type="text" class="form-control"
                               value="{{old('phone')}}"  >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Address</label>
                    <div class="col-md-9">
                        <textarea name="address" class="form-control" rows="2">{{old('address')}}</textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Country*</label>
                    <div class="col-md-9">
                        <select name="country"
                                class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" required>

                            <option value="NZL">New Zealand</option>
                            <option value="AUS">Australia</option>
                            <option disabled>__________________</option>
                            @foreach($countrys as $country)
                                <option value="{{$country->iso_3166_3}}"
                                        {!! old('country') == $country->iso_3166_3 ? 'selected' : '' !!}>
                                    {{$country->name}}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('country'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('country') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Notes</label>
                    <div class="col-md-9">
                        <textarea name="notes" class="form-control" rows="2">{{old('notes')}}</textarea>
                    </div>
                </div>


                @if (!empty($event->pickup))
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Airport Pickup</label>
                        <div class="col-md-9">
                            <div id="checkb" class="checkbox checkbox-primary">
                                <input name="pickup" type="checkbox" id="pickupc" {!! old('pickup')  ? 'selected' : '' !!}>
                                <label for="pickupc">
                                    Required
                                </label>
                            </div>
                            <span class="help-block"><small>Please let us know if you need airport transport</small></span>

                        </div>
                    </div>
                @endif

                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Enter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{URL::asset('/js/events/registration.js')}}"></script>
    <script src="{{URL::asset('/js/admin/registration.js')}}"></script>

@endsection
