@if ($event->isLeague())
    <div class="form-group row">
        <label class="col-sm-12 col-md-3 col-form-label">Division*</label>
        <div class="col-md-9">
            @foreach($divisionsfinal as $division)
                <div id="checkb" class="checkbox checkbox-primary">
                    <input name="divisionid[]" id="divids-{{$division->divisionid}}" type="checkbox" value="{{$division->divisionid}}"
                            {!! (in_array($division->divisionid, $userentrydivisions)) ? 'checked' : '' !!} >
                    <label for="divids-{{$division->divisionid}}">
                        {{$division->label}}
                    </label>
                </div>
            @endforeach
        </div>
    </div>

@else

    <br>
    @foreach($eventcomps as $eventcomp)

        <hr>
        <div class="eventcompclass">
        <h5 style="text-align: center">{{$eventcomp->label . ' - ' . date('d F', strtotime($eventcomp->date))}}</h5>
            <div class="form-group row">
                <label class="col-sm-12 col-md-3 col-form-label">Division</label>
                <div class="col-md-9">
                    <select name="divisionid[{{$eventcomp->eventcompetitionid}}]" class="form-control" >
                        @if(!empty($userentryrounds[$eventcomp->eventcompetitionid]))
                            <option value="remove">*Remove*</option>
                        @else
                            <option>Not Entered</option>
                        @endif

                        @foreach($eventcomp->divisioncomplete as $division)
                            <option value="{{$division->divisionid}}" {!! ($userentrydivisions[$eventcomp->eventcompetitionid] ?? null) == $division->divisionid ? 'selected' : '' !!}>
                                {{$division->label}}
                            </option>
                        @endforeach
                    </select>
                    @if(!empty($userentryrounds[$eventcomp->eventcompetitionid]))
                        <span class="help-block" style="color: red">
                            <small>Setting the value to be Remove will remove this entry completely</small>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-3 col-form-label">Round</label>
                <div class="col-md-9">
                    <select name="roundids[{{$eventcomp->eventcompetitionid}}]" class="form-control" >
                        @if(!empty($userentryrounds[$eventcomp->eventcompetitionid]))
                            <option value="remove">*Remove*</option>
                        @else
                            <option>Not Entered</option>
                        @endif

                        @foreach($eventcomp->rounds as $round)
                            <option value="{{$round->roundid}}" {!! ($userentryrounds[$eventcomp->eventcompetitionid] ?? null) == $round->roundid ? 'selected' : '' !!}>
                                {{$round->label}}
                            </option>
                        @endforeach
                    </select>
                    @if(!empty($userentryrounds[$eventcomp->eventcompetitionid]))
                        <span class="help-block" style="color: red">
                            <small>Setting the value to be Remove will remove this entry completely</small>
                        </span>
                    @endif
                </div>
            </div>
        </div>

    @endforeach
@endif
