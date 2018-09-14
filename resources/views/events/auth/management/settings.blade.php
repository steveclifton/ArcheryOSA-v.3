@extends('template.default')

@section ('title')Settings @endsection

@section('content')



    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/events/manage">Events</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/events/manage/{{$event->eventurl}}">{{ ucwords($event->label) }}</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Settings</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('template.alerts')
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Event Settings</h4>

            <form class="form-horizontal myForms" action="/events/manage/settings/{{$event->eventurl}}" method="POST" role="form" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="eventid" value="{{$event->eventid}}">

                @if(!empty($leagueweeks))
                    <div class="form-group row">
                        <label class="col-sm-12 col-md-3 col-form-label">Current League Week</label>
                        <div class="col-md-9">
                            <select name="currentweek" id="$currentweek" class="form-control">
                                @foreach(range(1, $leagueweeks) as $week)
                                    <option value="{{$week}}" {!! ($currentweek ?? -1) == $week ? 'selected' : '' !!}>
                                        {{ 'Week ' . $week }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Event Status</label>
                    <div class="col-md-9">
                        <select name="eventstatusid" class="form-control">

                            @foreach($eventstatuses as $status)
                                <option value="{{$status->eventstatusid}}"
                                        {{$event->eventstatusid == $status->eventstatusid ? 'selected' : ''}}>
                                    {{$status->label}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Entries Limit</label>
                    <div class="col-md-9">
                        <input name="entrylimit" type="tel" placeholder="Optional"
                               class="form-control" value="{{$event->entrylimit ?? old('entrylimit')}}">
                        <span class="help-block"><small>Number of spots available for the event</small></span>

                    </div>

                </div>
                <br>

                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input name="adminnotifications" id="adminemail" type="checkbox" {{$event->adminnotifications ? 'checked' : ''}}>
                            <label for="adminemail">
                                Email Notifications
                            </label>

                        </div>
                        <span class="help-block"><small>This will enable entry notification emails</small></span>

                    </div>
                </div>
                <br>

                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input name="showoverall" id="emailshow" type="checkbox" {{$event->showoverall ? 'checked' : ''}}>
                            <label for="emailshow">
                                Show Overall
                            </label>

                        </div>
                        <span class="help-block"><small>This creates an 'Overall' results section, combining results into 1 overall result</small></span>

                    </div>
                </div>
                <br>







                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input name="dateofbirth" id="dateofbirth" type="checkbox" {{$event->dateofbirth ? 'checked' : ''}}>
                            <label for="dateofbirth">
                                Date of Birth Required
                            </label>

                        </div>

                    </div>
                </div>
                <br>

                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input name="visible" id="checkbox2" type="checkbox" {{$event->visible ? 'checked' : ''}}>
                            <label for="checkbox2">
                                Active
                            </label>
                            @if (session('visible'))
                                <div class="alert alert-danger">
                                    Cannot be active at this stage
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <hr><br>

                @if (!empty($event->imagedt))
                    <div class="form-group row justify-content-end">
                        <div class=" col-md-9">
                            <img src="{{URL::asset('/images/events/' . $event->imagedt)}}" alt="" style="width: 400px; height: 200px">
                        </div>
                    </div>
                @endif
                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="form-group">
                            <label for="uploadDesktop">Image Desktop (1024x641px)</label>
                            <input name="imagedt" type="file" class="form-control-file" id="uploadDesktop">
                        </div>
                    </div>
                </div>


                @if (!empty($event->imagebanner))
                    <div class="form-group row justify-content-end">
                        <div class=" col-md-9">
                            <img src="{{URL::asset('/images/events/' . $event->imagebanner)}}" alt="" style="width: 400px; height: 200px">
                        </div>
                    </div>
                @endif
                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="form-group">
                            <label for="uploadBanner">Image Banner(1471x200px)</label>
                            <input name="imagebanner" type="file" class="form-control-file" id="uploadBanner">
                        </div>
                    </div>
                </div>


                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Save</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection