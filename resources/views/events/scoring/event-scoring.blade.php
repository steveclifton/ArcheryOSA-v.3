@extends('template.default')

@section ('title'){{$event->label}} Scoring @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/event/scoring/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                    /
                    <a href="javascript:;">{{ucwords($eventcompetition->label)}}</a>
                </h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs"></ul>

            <div class="tab-content">
                <div style="margin-tops: 20px">
                    <div class="alert hidden" role="alert" ></div>
                </div>
                <a role="button" href="javascript:;" class="myButton btn btn-danger">Save Results</a>

                @if (Auth::check() && Auth::id() == 1)
                    <a role="button" href="javascript:;" class="triggerTest btn btn-warning">TEST RESULTS</a>
                @endif

                <div class="tab-pane active" id=""><br>
                    @foreach($evententrys as $bowtype => $entries)
                        @foreach($entries as $division => $rounds)

                            <h5 class="tableTitle">{{$division}}</h5>

                            @foreach($rounds as $aa)
                                @php $data = reset($aa) @endphp
                                <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Archer</th>
                                        <th>{{$data->dist1. $data->unit}}</th>
                                        @if(!empty($data->dist2))<th>{{$data->dist2. $data->unit}}</th>@endif
                                        @if(!empty($data->dist3))<th>{{$data->dist3. $data->unit}}</th>@endif
                                        @if(!empty($data->dist4))<th>{{$data->dist4. $data->unit}}</th>@endif
                                        <th>Total</th>
                                        <th>10+X</th>
                                        <th>X</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($aa as $a)

                                        <tr class="results"
                                            data-entryhash="{{$a->hash}}"
                                            data-entrycompetitionid="{{$a->entrycompetitionid}}"
                                            data-fsid="{{ ($a->fsid ?? null) }}">


                                            <th scope="row" width="15%">{{ucwords($a->firstname . ' ' . $a->lastname)}}</th>

                                            <td width="10%" data-type="distance" data-max="{{$data->dist1max}}" data-value="{{$data->dist1}}">
                                                <input type="text" class="form-control distInp" data-key="dist1score"  value="{{ ($a->dist1score ?? 0) }}" placeholder="">

                                                @if(!$event->isLeague())
                                                    <i class="md-add-box showMore"></i>
                                                    <div class="hidden">
                                                        Hits<input type="text" class="form-control" data-key="dist1hits" value="{{ ($a->dist1hitsscore ?? '') }}" data-type="hits" placeholder="Hits">
                                                        10+X<input type="text" class="form-control" data-key="dist1inners" value="{{ ($a->dist1innersscore ?? '') }}" data-type="inners" placeholder="10">
                                                        X<input type="text" class="form-control" data-key="dist1max" value="{{ ($a->dist1maxscore ?? '') }}" data-type="max" placeholder="X">
                                                    </div>
                                                @endif
                                            </td>
                                            @if(!empty($data->dist2))
                                                <td width="10%" data-type="distance" data-max="{{$data->dist2max}}" data-value="{{$data->dist2}}">
                                                    <input type="text" class="form-control distInp" data-key="dist2score" value="{{ ($a->dist2score ?? 0) }}" placeholder="">

                                                    @if(!$event->isLeague())
                                                        <i class="md-add-box showMore"></i>
                                                        <div class="hidden">
                                                            Hits<input type="text" class="form-control" data-key="dist2hits" value="{{ ($a->dist2hitsscore ?? '') }}" data-type="hits" placeholder="Hits">
                                                            10+X<input type="text" class="form-control" data-key="dist2inners" value="{{ ($a->dist2innersscore ?? '') }}" data-type="inners" placeholder="10">
                                                            X<input type="text" class="form-control" data-key="dist2max" value="{{ ($a->dist2maxscore ?? '') }}" data-type="max" placeholder="X">
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
                                            @if(!empty($data->dist3))
                                                <td width="10%" data-type="distance" data-max="{{$data->dist3max}}" data-value="{{$data->dist3}}">
                                                    <input type="text" class="form-control distInp" data-key="dist3score" value="{{ ($a->dist3score ?? 0) }}" placeholder="">
                                                    @if (!$event->isLeague())
                                                        <i class="md-add-box showMore"></i>
                                                        <div class="hidden">
                                                            Hits<input type="text" class="form-control" data-key="dist3hits" value="{{ ($a->dist3hitsscore ?? '') }}" data-type="hits" placeholder="Hits">
                                                            10+X<input type="text" class="form-control" data-key="dist3inners" value="{{ ($a->dist3innersscore ?? '') }}" data-type="inners" placeholder="10">
                                                            X<input type="text" class="form-control" data-key="dist3max" value="{{ ($a->dist3maxscore ?? '') }}" data-type="max" placeholder="X">
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif
                                            @if(!empty($data->dist4))
                                                <td width="10%" data-type="distance" data-max="{{$data->dist4max}}" data-value="{{$data->dist4}}">
                                                    <input type="text" class="form-control distInp" data-key="dist4score" value="{{ ($a->dist4score ?? 0) }}" placeholder="">
                                                    @if(!$event->isLeague())
                                                        <i class="md-add-box showMore"></i>
                                                        <div class="hidden">
                                                            Hits<input type="text" class="form-control" data-key="dist4hits" value="{{ ($a->dist4hitsscore ?? '') }}" data-type="hits" placeholder="Hits">
                                                            10+X<input type="text" class="form-control" data-key="dist4inners" value="{{ ($a->dist4innersscore ?? '') }}" data-type="inners" placeholder="10">
                                                            X<input type="text" class="form-control" data-key="dist4max" value="{{ ($a->dist4maxscore ?? '') }}" data-type="max" placeholder="X">
                                                        </div>
                                                    @endif
                                                </td>
                                            @endif

                                            <td width="10%" data-type="sum" data-value="total">
                                                <input type="text" class="form-control totalInp" data-key="total" value="{{ ($a->total ?? '') }}">
                                            </td>

                                            <td width="10%" data-type="sum" data-value="inners">
                                                <input type="text" class="form-control" data-key="inners" value="{{ ($a->inners ?? '') }}">
                                            </td>
                                            <td width="10%" data-type="sum" data-value="max">
                                                <input type="text" class="form-control" data-key="max" value="{{ ($a->max ?? '') }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                                @endforeach
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>

        <input type="hidden" id="event" value="/events/scoring/{{$event->eventurl}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            $(function () {
                var outstanding = false;

                window.onbeforeunload = function() {

                    if (outstanding) {
                        return "You have unsaved changes";
                    }
                    return;
                };


                // store the url
                var eventurl = $('#event').val();

                // toggle showing the additional hits 10s x
                $(document).on('click', '.showMore', function () {
                    $(this).siblings('div').toggle();
                });

                $(document).on('keyup', '.form-control', function () {
                    outstanding = true;

                    var parent = $(this).closest('tr').children('td');
                    var total = 0;

                    parent.each(function() {
                        if ($(this).attr('data-type') == 'distance') {
                            var value = parseInt($(this).find('.distInp').val());
                            total += value;
                        }

                    });
                    parent.find('.totalInp').val(total);
                    $(this).closest('tr').addClass('hasScored');

                    $('.myButton').removeClass('btn-danger').addClass('btn-success');

                });

                var isSaving = false;

                // on score submit
                $(document).on('click', '.myButton', function (e) {

                    if (isSaving) {
                        console.log('saving....');
                        return;
                    }

                    isSaving = true;

                    $('.alert').addClass('hidden').removeClass('alert-danger').removeClass('alert-success');
                    $('th').each(function() {
                       $(this).removeClass('error');
                    });

                    // get all the results
                    var results = $('.hasScored');

                    // array for data to be sent
                    var sendJson = [];

                    var errors       = false;
                    var errormessage = ['<h6>Errors - Unable to submit scores</h6>'];

                    // loop over each row
                    results.each(function() {

                       var entryhash          = $(this).attr('data-entryhash');
                       var entrycompetitionid = $(this).attr('data-entrycompetitionid');
                       var fsid               = $(this).attr('data-fsid');

                       if (typeof entryhash == 'undefined') {
                           return;
                       }

                       // create new object with required data
                       var jsonData = {
                           entryhash: entryhash,
                           entrycompetitionid: entrycompetitionid,
                           fsid: fsid
                       };

                       // get the rows td
                       var children = $(this).children('td');

                       var totalScore = 0;
                       children.each(function() {
                            // Remove errors
                           $(this).find('input').removeClass('error');

                           // check to see its a distance score, thats all we want
                           if ($(this).attr('data-type') == 'distance') {

                               var distMax = parseInt($(this).attr('data-max'));
                               var key    = $(this).find('input').attr('data-key');
                               var score   = parseInt($(this).find('input').val());

                               if (score > distMax) {
                                   errors = true;
                                   errormessage.push('- Scores exceed max for round<br>');
                                   $(this).find('input').addClass('error');
                                   return;
                               }

                               // Add the score
                               jsonData[key] = score;

                               // add the score to the total
                               totalScore += score;

                               var extras  = $(this).find('div').children('input');

                               extras.each(function () {
                                   var key = $(this).attr('data-key');

                                   switch ($(this).attr('data-type')) {
                                      case 'hits':
                                          jsonData[key] = parseInt($(this).val());
                                          break;

                                      case 'inners':
                                          jsonData[key] = parseInt($(this).val());
                                          break;

                                      case 'max':
                                          jsonData[key] = parseInt($(this).val());
                                          break;
                                  }
                               });

                               return;
                           }
                           else {
                               // Not a distance result - will be a total or distance max/inner
                               var key = $(this).attr('data-value');
                               var score = parseInt($(this).find('input').val());

                               jsonData[key] = score;

                           }
                       });

                       if (typeof jsonData.total == 'undefined' || jsonData.total != totalScore) {
                           errors = true;
                           errormessage.push('- Scores and total do not match<br>');
                           $(this).find('th').addClass('error');
                           return;
                       }

                       sendJson.push(jsonData) ;
                   });

                    if (errors) {
                        $('.alert').addClass('alert-danger').html(errormessage.join('')).removeClass('hidden');
                        return;
                    }

                    if (sendJson.length <= 0) {
                        $('.alert').addClass('alert-danger').html('No results to submit').removeClass('hidden');
                        return;
                    }


                    $.ajax({
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: eventurl,
                        data: {
                            data : JSON.stringify(sendJson)
                        }
                    }).done(function( json ) {

                        if (json.success) {
                            outstanding = false;

                            $('.alert').addClass('alert-success').html('Scores Entered Succesfully').removeClass('hidden');
                            setTimeout(function (e) {
                                location.reload();
                            }, 1000);

                        }
                        else {
                            $('.alert').addClass('alert-danger').html(errormessage.join('')).removeClass('hidden');
                            setTimeout(function (e) {
                                location.reload();
                            }, 100);

                            isSaving = false;

                        }
                    });
                });
            });

            $('.triggerTest').on('click', fakeScores);

            function fakeScores() {
                for (var i = 1; i < 5; i++) {
                    $('input[data-key="dist' + i + 'score"]').each(function(e) {

                        $(this).val(
                            Math.floor(
                                Math.floor(Math.random() * 359)
                            )
                        );

                        $(this).keyup();

                        $(this).parent().find('.hidden').children('input').each(function () {
                            $(this).val(
                                Math.floor(
                                    Math.floor(Math.random() * 35)
                                )
                            )
                        });

                        $(this).parent().parent().find('td[data-value="inners"]').find('input').each(function () {
                            $(this).val(
                                Math.floor(
                                    Math.floor(Math.random() * 35)
                                )
                            )
                        });

                        $(this).parent().parent().find('td[data-value="max"]').find('input').each(function () {
                            $(this).val(
                                Math.floor(
                                    Math.floor(Math.random() * 35)
                                )
                            )
                        });
                    });
                }
            }


        </script>

@endsection