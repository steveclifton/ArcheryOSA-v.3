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

                @if (!empty($event->imagedt))
                    <div class="form-group row justify-content-end">
                        <div class=" col-md-9">
                            <img src="{{URL::asset('/images/events/' . $event->imagedt)}}" alt="" style="width: 100px; height: 100px">
                        </div>
                    </div>
                @endif


                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <label class="control-label">Image (1024px X 640px)</label>
                    <input name="imagedt" type="file" class="filestyle" data-iconname="fa fa-cloud-upload" id="filestyle-6" tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);">
                        <div class="bootstrap-filestyle input-group">
                            <input type="text" class="form-control " placeholder="" disabled="" value="{{$event->imagedt ?? ''}}">
                            <span class="group-span-filestyle input-group-btn" tabindex="0">
                                <label for="filestyle-6" class="btn btn-default btn-inverse ">
                                    <span class="icon-span-filestyle fa fa-cloud-upload"></span>
                                    <span class="buttonText">Choose file</span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>


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