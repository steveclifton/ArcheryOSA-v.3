@php
    $arr =[];
    if (!empty(old('competitionids'))) {
        $arr = explode(',', old('competitionids'));
    }
    else if (!empty($competition['competitionids'])) {
        $arr = json_decode($competition['competitionids']);
    }
@endphp
<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Competitions*</label>
    <div class="col-md-9">
        <div class="">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0 m-b-30">Select the competitions required for this day</h4>

                <div id="checkTree">
                @foreach($mappedcompetitions as $orgname => $roundtype)
                    <ul>
                        <li data-jstree='{"opened":true, "icon": "ti-angle-right"}'>{{$orgname}}
                            <ul>
                            @foreach($roundtype as $rtype => $type)
                                <li data-jstree='{"opened":true, "icon": "ti-angle-right"}'>{{$rtype}}
                                    <ul>
                                    @foreach($type as $t)
                                        <li data-competitionid="{{$t->competitionid}}"
                                            data-jstree='{"icon":"ti-angle-right","selected":"{!!in_array($t->competitionid, $arr)!!}"}'
                                            class="round">{{$t->label}}</li>
                                    @endforeach
                                    </ul>
                            @endforeach
                            </ul>
                        </li>
                    </ul>
                @endforeach
                </div>

            </div>
        </div><!-- end col -->
        <div id="comperror" class="alert alert-danger hidden">Please select at least 1 competition</div>
    </div>
</div>

<input name="competitionids" type="hidden" id="jsfields" value="" />

@php
    $arr =[];
    if (!empty(old('divisionids'))) {
        $arr = explode(',', old('divisionids'));
    }
    else if (!empty($competition['divisionids'])) {
        $arr = json_decode($competition['divisionids']);
    }
    $i=1
@endphp
<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Divisions*</label>
    <div class="col-md-9">
        <div class="">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0 m-b-30">Select the divisions required for this day's competitions</h4>
                <div id="checkTreeDivisions">
                    @foreach($mappeddivisions as $bowtype => $division)
                    <ul>
                        <li data-jstree='{"opened":{!! $i++ == 1 ? 'true': 'false' !!}, "icon": "ti-angle-right"}'>{{ucwords($bowtype)}}
                            <ul>
                                @foreach($division as $d)
                                <li data-divisionid="{{$d->divisionid}}"
                                    data-jstree='{"opened":true,"icon":"ti-angle-right","selected":"{{ in_array($d->divisionid, $arr)}}"}'>{{$d->label}}
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                    @endforeach
                </div>
            </div>
        </div><!-- end col -->
        <div id="diverror" class="alert alert-danger hidden">Please select at least 1 division</div>
    </div>
</div>
<input name="divisionids" type="hidden" id="divisionfields" value="" />