<div class="form-group row">
    <label for="inputLabel" class="col-sm-12 col-md-3 col-form-label">Competition Name*</label>
    <div class="col-md-9">
        <input name="label" type="text"
               class="form-control{{ $errors->has('label') ? ' is-invalid' : '' }}" id="inputLabel"
               value="{{old('label') ?? $competition['label'] ?? null}}">
        <div id="nameerror" class="alert alert-danger hidden">Please name this competition</div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Cost*</label>
    <div class="col-md-9">
        <input name="cost" type="text"
               class="form-control{{ $errors->has('cost') ? ' is-invalid' : '' }}" id="inputLabel"
               value="{{old('cost') ?? $competition['cost'] ?? null}}">
        <div id="costerror" class="alert alert-danger hidden">Value must be a number</div>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Location</label>
    <div class="col-md-9">
        <textarea name="location" class="form-control" rows="5" placeholder="Optional">{{old('location') ?? $competition['location'] ?? ''}}</textarea>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Schedule</label>
    <div class="col-md-9">
        <textarea name="schedule" class="form-control" rows="5" placeholder="Optional">{{old('schedule') ?? $competition['schedule'] ?? ''}}</textarea>
    </div>
</div>