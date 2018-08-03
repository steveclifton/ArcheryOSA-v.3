@extends('template.default')

@section ('title')Competitions @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a> > <a href="javascript:;">Competitions</a></h4>
            </div>
        </div>
    </div>

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Competitions</h4>

            <form class="form-horizontal myForms treeForm" method="POST" action="/admin/competitions/create" role="form">
                @csrf

                <div class="form-group row">
                    <label for="inputOrgName3" class="col-sm-12 col-md-3 col-form-label">Event Competition Name*</label>
                    <div class="col-md-9">
                        <input name="label" type="text"
                               class="form-control{{ $errors->has('label') ? ' is-invalid' : '' }}" id="inputOrgName3"
                               value="{{old('label')}}">
                        @if ($errors->has('label'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('label') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Description</label>
                    <div class="col-md-9">
                        <textarea name="description" class="form-control" rows="5">{{old('description')}}</textarea>
                    </div>
                </div>



                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Competitions</label>
                    <div class="col-md-9">
                        <div class="">
                            <div class="card-box">
                                <h4 class="text-dark header-title m-t-0 m-b-30">Check the competition for this </h4>

                                <div id="checkTree">
                                    @foreach($mappedcompetitions as $orgname => $roundtype)
                                        <ul>
                                            <li data-jstree='{"opened":true, "icon": "ti-angle-right"}'>{{$orgname}}
                                                <ul>
                                                    @foreach($roundtype as $roundtype => $type)

                                                        <li data-jstree='{"opened":true, "icon": "ti-angle-right"}'>{{$roundtype}}
                                                            <ul>
                                                                @foreach($type as $t)
                                                                    <li data-roundid="{{$t->competitionid}}" data-jstree='{"icon": "ti-angle-right"}' class="round">{{$t->label}}</li>
                                                                @endforeach
                                                            </ul>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div>
                </div>

                <input name="roundids" type="hidden" id="jsfields" value="" />



                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input name="visible" id="checkbox2" type="checkbox" checked>
                            <label for="checkbox2">
                                Visible
                            </label>
                        </div>
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

    </div>




@endsection