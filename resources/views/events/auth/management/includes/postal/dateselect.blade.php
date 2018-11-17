<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Competition Date</label>
    <div class="col-md-9">
        <select name="date" class="form-control">
            @foreach($event->daterange as $date)
                <option value="{{$date}}"
                        {!! (old('date') ?? $competition['date'] ) == $date ? 'selected' : ''!!}>
                    {{ date('F', strtotime($date))}}
                </option>
            @endforeach
        </select>
    </div>
</div>