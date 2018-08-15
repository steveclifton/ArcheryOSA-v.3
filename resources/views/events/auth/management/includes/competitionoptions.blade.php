<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Scoring Level</label>
    <div class="col-md-9">
        <select name="scoringlevel" id="scoringlevel" class="form-control">
            @foreach($scoringlevels as $level)
                <option value="{{$level->scorelevelid}}" {{ (old('scoringlevel') ?? $competition['scoringlevel']) == $level->scorelevelid ? 'selected' : ''}}>
                    {{ $level->label }}
                </option>
            @endforeach
        </select>
    </div>
</div>

@if(!is_null($leagueweeks))
    <div class="form-group row">
        <label class="col-sm-12 col-md-3 col-form-label">Current League Week</label>
        <div class="col-md-9">
            <select id="scoringlevel" class="form-control">
                @foreach(range(1, $leagueweeks) as $week)
                    <option value="{{$week}}" {!! $competition['currentweek'] ?? -1 == $week ? 'selected' : '' !!}>
                        {{ 'Week ' . $week }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
@endif
<div class="form-group row justify-content-end">
    <div class=" col-md-9">
        <div class="checkbox checkbox-primary">
            <input name="scoringenabled" id="checkbox1" type="checkbox" {{ (old('scoringenabled') ?? $competition['scoringenabled']) ? 'checked' : ''}}>
            <label for="checkbox1">
                Scoring Enabled
            </label>
        </div>
    </div>
</div>

{{--<div class="form-group row justify-content-end">--}}
    {{--<div class=" col-md-9">--}}
        {{--<div class="checkbox checkbox-primary">--}}
            {{--<input name="ignoregenders" id="checkbox2" type="checkbox" {{ (old('ignoregenders') ?? $competition['ignoregenders']) ? 'checked' : ''}}>--}}
            {{--<label for="checkbox2">--}}
                {{--Ignore Genders--}}
            {{--</label>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

<div class="form-group row justify-content-end">
    <div class=" col-md-9">
        <div class="checkbox checkbox-primary">
            <input name="visible" id="checkbox3" type="checkbox" {{ (old('visible') ?? $competition['visible']) ? 'checked' : ''}}>
            <label for="checkbox3">
                Visible
            </label>
        </div>
    </div>
</div>
