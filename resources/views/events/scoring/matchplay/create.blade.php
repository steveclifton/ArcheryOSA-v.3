@extends('template.default')

@section ('title')Create Matchplay Event @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/event/matchplay/{{$event->eventurl}}">Matchplay</a> >
                    <a href="javascript:;">Create</a></h4>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('template.alerts')

            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create New Matchplay Event</h4>

            <form class="form-horizontal myForms" method="POST" action="/event/matchplay/{{$event->eventurl}}/create" role="form">
                @csrf

                <div class="form-group row">
                    <label for="inputOrgName3" class="col-sm-12 col-md-3 col-form-label">Name*</label>
                    <div class="col-md-9">
                        <input name="label" type="text"
                               class="form-control {{ $errors->has('label') ? ' is-invalid' : '' }}" id="inputOrgName3"
                               value="{{old('label')}}">
                        @if ($errors->has('label'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('label') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Gender*</label>
                    <div class="col-md-9">
                        <select name="type" class="form-control">
                            <option value="o" selected>Open</option>
                            <option value="m">Indoor</option>
                            <option value="f">Clout</option>
                        </select>
                    </div>
                </div>

                @php $mec = $event->getMatchplayEventCompetitions() @endphp
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Event Competition</label>
                    <div class="col-md-9">
                        <select name="eventcompetitionid" class="form-control" id="eventCompSelect">
                            @if(count($mec) > 1)
                                <option disabled selected>Choose..</option>
                            @endif
                            @foreach($mec as $ec)
                                <option value="{{$ec->eventcompetitionid}}"
                                        {{old('eventcompetitionid') == $ec->eventcompetitionid ? 'selected' : ''}}>
                                    {{$ec->getPrettyDate() . ' ' . $ec->label}}</option>
                            @endforeach
                        </select>
                        <br>
                        <select name="divisionid" class="form-control" id="divisionIdSelect" style="display: {{count($mec) > 1 ? 'none' : 'block'}}">
                            @foreach($event->getMatchplayEventCompetitions() as $ec)
                                @foreach($ec->getEventCompetitionDivisions() as $division)
                                    <option value="{{$division->divisionid}}"
                                            data-ecid="{{$ec->eventcompetitionid}}"
                                            {{ old('divisionid') == $division->divisionid ? 'selected' : '' }}>
                                        {{$division->label}}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>

                        <script>
                            $(function() {

                                var select = $('#eventCompSelect');

                                select.on('change', function() {
                                    var ecid = select.find(":selected").val()

                                    $('#divisionIdSelect').show();

                                    $('#divisionIdSelect').children('option').hide();
                                    $('#divisionIdSelect').children('option[data-ecid="' + ecid + '"]').show();
                                });
                            });
                        </script>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Round</label>
                   <div class="col-md-9">
                        <select name="organisationid" class="form-control">
                            <option value="0">None</option>
                            @foreach([1] as $i)
                                <option value=""
                                        {{old('organisationid') == $i ? 'selected' : ''}}>
                                    {{$i}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Round Code*</label>
                    <div class="col-md-9">
                        <input name="code" type="text" class="form-control {{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{old('code')}}">
                        @if ($errors->has('code'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('code') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Round Units</label>
                    <div class="col-md-9">
                        <select name="unit" class="form-control">
                            <option value="m" {{old('unit') == 'm' ? 'selected' : ''}}>Meters</option>
                            <option value="y" {{old('unit') == 'y' ? 'selected' : ''}}>Yards</option>
                        </select>
                    </div>
                </div>


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