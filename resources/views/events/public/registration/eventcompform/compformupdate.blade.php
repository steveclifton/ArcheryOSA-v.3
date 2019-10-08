@if ($event->isLeague())
    <div class="form-group row">
        <label class="col-sm-12 col-md-3 col-form-label">Division*</label>
        <div class="col-md-9">
            @foreach($divisionsfinal as $division)
                <div id="checkb" class="checkbox checkbox-primary">
                    <input name="divisionid[]" id="divids-{{$division->divisionid}}" type="checkbox" value="{{$division->divisionid}}"
                            {!! (in_array($division->divisionid, $userentrydivisions)) ? 'checked' : '' !!} disabled>
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
                    <select class="form-control" disabled readonly>
                        <option disabled selected>Not Entered</option>

                        @foreach($eventcomp->divisioncomplete as $division)
                            <option value="{{$division->divisionid}}" {!! ($userentrydivisions[$eventcomp->eventcompetitionid] ?? null) == $division->divisionid ? 'selected' : '' !!}>
                                {{$division->label}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-12 col-md-3 col-form-label">Round</label>
                <div class="col-md-9">
                    <select class="form-control" disabled readonly>
                        <option disabled selected>Not Entered</option>

                    @foreach($eventcomp->rounds as $round)
                            <option value="{{$round->roundid}}" {!! ($userentryrounds[$eventcomp->eventcompetitionid] ?? null) == $round->roundid ? 'selected' : '' !!}>
                                {{$round->label}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    @endforeach
@endif
