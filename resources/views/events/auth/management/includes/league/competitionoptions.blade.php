<div class="form-group row justify-content-end">
    <div class=" col-md-9">
        <div class="checkbox checkbox-primary">
            <input name="ignoregenders" id="checkbox3" type="checkbox"
                    {{ (old('ignoregenders') ?? $competition['ignoregenders'] ?? false) ? 'checked' : ''}}>
            <label for="checkbox3">
                Ignore Genders
            </label>
        </div>
    </div>
</div>

@if (!empty($event) && $event->eventtypeid == 2)
    <div class="form-group row justify-content-end">
        <div class=" col-md-9">
            <div class="checkbox checkbox-primary">
                <input name="multipledivisions" id="checkbox4" type="checkbox"
                        {{ (old('multipledivisions') ?? $competition['multipledivisions'] ?? false) ? 'checked' : ''}}>
                <label for="checkbox4">
                    Multiple Division Entries
                </label>
            </div>
        </div>
    </div>
@endif

<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Scoring Level</label>
    <div class="col-md-9">
        <select name="scoringlevel" id="scoringlevel" class="form-control">
            @foreach($scoringlevels as $level)
                <option value="{{$level->scorelevelid}}"
                        {{ (old('scoringlevel') ?? $competition['scoringlevel'] ?? false) == $level->scorelevelid ? 'selected' : ''}}>
                    {{ $level->label }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row justify-content-end">
    <div class=" col-md-9">
        <div class="checkbox checkbox-primary">
            <input name="scoringenabled" id="checkbox1" type="checkbox" {{ (old('scoringenabled') ?? $competition['scoringenabled'] ?? false) ? 'checked' : ''}}>
            <label for="checkbox1">
                Scoring Enabled (Required for Open/User Scoring)
            </label>
        </div>
    </div>
</div>

