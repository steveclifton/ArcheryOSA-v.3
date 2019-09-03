@php
    $arr =[];
    if (!empty(old('roundids'))) {
        $arr = explode(',', old('roundids'));
    }
    else if (!empty($competition['roundids'])) {
        $arr = json_decode($competition['roundids']);
    }

@endphp
<div class="form-group row">
    <label class="col-sm-12 col-md-3 col-form-label">Rounds*</label>
    <div class="col-md-9">
        <div class="">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0 m-b-30">Select the rounds required for this day</h4>
                @if(!empty($entries))<h6 class="text-danger header-title m-t-0 m-b-30">Entries exist - cannot change rounds</h6>@endif

                <div id="checkTree">
                @foreach($mappedrounds as $orgname => $roundtype)
                    <ul>
                        <li data-jstree='{"checkbox_disabled":{{empty($entries) ? 'false' : 'true'}}, "opened":true, "icon": "ti-angle-right"}'>{{$orgname}}
                            <ul>
                            @foreach($roundtype as $rtype => $type)
                                <li data-jstree='{"checkbox_disabled":{{empty($entries) ? 'false' : 'true'}}, "opened":false, "icon": "ti-angle-right"}'>{{$rtype}}
                                    <ul>
                                    @foreach($type as $t)
                                        <li data-roundid="{{$t->roundid}}"
                                            data-jstree='{"checkbox_disabled":{{empty($entries) ? 'false' : 'true'}}, "icon":"ti-angle-right", "selected":"{!!in_array($t->roundid, $arr)!!}"}'
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

<input name="roundids" type="hidden" id="roundfields" value="" />

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
                <h6 class="text-danger header-title m-t-0 m-b-30">Removing divisions may change existing event entrys</h6>
                <div id="checkTreeDivisions">
                    @foreach($mappeddivisions as $orgname => $divisions)
                        <ul>
                            <li data-jstree='{"opened":true, "icon": "ti-angle-right"}'>{{$orgname}}
                                <ul>
                                    @foreach($divisions as $bowtype => $divs)
                                        <li data-jstree='{"opened":false, "icon": "ti-angle-right"}'>{{$bowtype}}
                                            <ul>
                                                @foreach($divs as $t)
                                                    <li data-divisionid="{{$t->divisionid}}"
                                                        data-jstree='{ "icon":"ti-angle-right",
                                                        "selected":"{!!in_array($t->divisionid, $arr)!!}"}'
                                                        class="division">{{$t->label}}</li>
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
        <div id="diverror" class="alert alert-danger hidden">Please select at least 1 division</div>
    </div>
</div>
<input name="divisionids" type="hidden" id="divisionfields" value="" />


