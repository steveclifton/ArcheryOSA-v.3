@if ($event->isLeague())
    <div class="form-group row">
        <label class="col-sm-12 col-md-3 col-form-label">Division*</label>
        <div class="col-md-9">
            @foreach($divisionsfinal as $division)
                <div id="checkb" class="checkbox checkbox-primary">
                    <input name="divisionid[]" id="divids-{{$division->divisionid}}" type="checkbox" value="{{$division->divisionid}}"
                            {!! (is_array(old('divisionid')) && in_array($division->divisionid, old('divisionid'))) ? 'selected' : '' !!}>
                    <label for="divids-{{$division->divisionid}}">
                        {{$division->label}}
                    </label>
                </div>

            @endforeach
            @if ($errors->has('divisionid'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('divisionid') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <input name="eventcompetitionid" type="hidden" id="" value="{{$leaguecompround}}"/>
@else
    <br>
    @foreach($eventcomps as $eventcomp)
        @php
            $olddivisionid = old('divisionid');
            $olddivisionid = !empty($olddivisionid[$eventcomp->eventcompetitionid]) ? $olddivisionid[$eventcomp->eventcompetitionid] : null;

            $oldroundid = old('roundids');
            $oldroundid = !empty($oldroundid[$eventcomp->eventcompetitionid]) ? $oldroundid[$eventcomp->eventcompetitionid] : null;
        @endphp

        <hr>
        <div class="eventcompclass">
        <h5 style="text-align: center">{{$eventcomp->label . ' - ' . date('d F', strtotime($eventcomp->date))}}</h5>
            <div class="form-group row">
                <label class="col-sm-12 col-md-3 col-form-label">Division</label>
                <div class="col-md-9">
                    <select name="divisionid[{{$eventcomp->eventcompetitionid}}]" class=" form-control {{ $errors->has('divisionid') ? 'is-invalid' : '' }}" >
                        <option value=""  selected>Select one</option>
                        @foreach($eventcomp->divisioncomplete as $division)
                            <option value="{{$division->divisionid}}"
                                    {!! $olddivisionid == $division->divisionid ? 'selected' : '' !!}>
                                {{$division->label}}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('divisionid'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('divisionid') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-3 col-form-label">Round</label>
                <div class="col-md-9">
                    <select name="roundids[{{$eventcomp->eventcompetitionid}}]" class="form-control {{ $errors->has('divisionid') ? 'is-invalid' : '' }}" >
                        <option value="" selected>Select one</option>
                        @foreach($eventcomp->rounds as $round)
                            <option value="{{$round->roundid}}"
                                    {!! $oldroundid == $round->roundid ? 'selected' : '' !!}>
                                {{$round->label}}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('divisionid'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('divisionid') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        </div>

    @endforeach
@endif
