<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Competition Date</label>
    <div class="col-md-9">
        <select name="date" class="form-control">
            @foreach($event->daterange as $date)
                <option value="{{$date->format('Y-m-d')}}"
                        {!! (old('date') ?? $competition['date'] ) == $date->format('Y-m-d') ? 'selected' : ''!!}>
                    {{ $date->format('D d F') }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Competition Sequence</label>
    <div class="col-md-9">
        <select name="sequence" class="form-control">
            @foreach(range(1, count($eventcompetitions) + 1) as $i)
                <option value="{{$i}}"
                        {!! (old('sequence') ?? $competition['sequence'] ?? (count($eventcompetitions) + 1) ) == $i ? 'selected' : ''!!}>
                    {{ $i }}
                </option>
            @endforeach
        </select>
        <span>The order in which this competition occurs in the event</span>
    </div>
</div>