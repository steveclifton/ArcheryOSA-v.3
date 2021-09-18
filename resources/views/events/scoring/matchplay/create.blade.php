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
                    <label class="col-sm-12 col-md-3 col-form-label">Round*</label>
                    <div class="col-md-9">
                        <select name="roundid" class="form-control" required>
                            <option disabled selected>Choose..</option>
                            @foreach($matchplayRounds as $mpr)
                                <option value="{{$mpr->roundid}}">{{$mpr->label}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                @php $mec = $event->getMatchplayEventCompetitions() @endphp
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Event Competition*</label>
                    <div class="col-md-9">
                        <select name="eventcompetitionid" class="form-control" id="eventCompSelect" required>
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
                        <select name="divisionid" class="form-control" id="divisionIdSelect" style="display: {{count($mec) > 1 ? 'none' : 'block'}}" required>
                            <option disabled selected>Choose..</option>
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

                        <span id="divisionIdSelectSpan" style="display: {{count($mec) > 1 ? 'none' : 'block'}}">Choose the Division for this Matchplay Event</span>

                        <script>
                            $(function() {

                                var select = $('#eventCompSelect');

                                select.on('change', function() {
                                    var ecid = select.find(":selected").val()

                                    $('#divisionIdSelect').show();
                                    $('#divisionIdSelectSpan').show();

                                    $('#divisionIdSelect').children('option').hide();
                                    $('#divisionIdSelect').children('option[data-ecid="' + ecid + '"]').show();
                                });
                            });
                        </script>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Gender*</label>
                    <div class="col-md-9">
                        <select name="type" class="form-control" required>
                            <option value="o" selected>Open</option>
                            <option value="m">Mens</option>
                            <option value="f">Womens</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Archer Count*</label>
                    <div class="col-md-9">
                        <input name="count" type="text" class="form-control {{ $errors->has('count') ? ' is-invalid' : '' }}" value="{{old('count')}}" required>
                        @if ($errors->has('count'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('count') }}</strong>
                            </span>
                        @endif
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