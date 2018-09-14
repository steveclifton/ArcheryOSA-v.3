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

<div class="form-group row justify-content-end">
    <div class=" col-md-9">
        <div class="checkbox checkbox-primary">
            <input name="scoringenabled" id="checkbox1" type="checkbox" {{ (old('scoringenabled') ?? $competition['scoringenabled']) ? 'checked' : ''}}>
            <label for="checkbox1">
                Scoring Enabled (Required for Open Scoring)
            </label>
        </div>
    </div>
</div>

