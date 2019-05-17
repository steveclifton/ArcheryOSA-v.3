@foreach($entries as $entry)
    <tr data-compid="{{$entry->entrycompetitionid}}">
        <td width="10%" align="center">
            <div class="form-group row">
                <div class="col-md-12">
                    <input name="target" type="text" class="targetAss form-control" value="{{$entry->target ?? old('target')}}" required >
                </div>
            </div>
            <span class="alert-success" style="display: none;">Updated!</span>

        </td>
        <td>
            {{ucwords($entry->fullname)}}
        </td>

        <td>{{$entry->divisionname}}</td>

        <td align="center">

            <div class="form-group row">
                <div class="col-md-11">
                    <input name="note" type="text" class="targetNote form-control" value="{{$entry->info ?? old('info')}}" required >
                </div>
            </div>
            <span class="alert-success" style="display: none;">Updated!</span>
        </td>
    </tr>

@endforeach