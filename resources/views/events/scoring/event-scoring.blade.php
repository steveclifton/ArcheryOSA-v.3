@extends('template.default')

@section ('title'){{$event->label}} Scoring @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">

                    <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="/event/scoring/{{$event->eventurl}}">{{ucwords($eventcompetition->label)}}</a>
                    <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Scoring</a>
                </h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs">
                @php $i = 1; @endphp
                @foreach($evententrys as $bowtype => $e)
                <li class="nav-item tab">
                    <a href="#{{$bowtype}}" data-toggle="tab" aria-expanded="false" class="nav-link {!! $i++ === 1 ? 'active' : '' !!}  show">
                        {{ucwords($bowtype)}}
                    </a>
                </li>
                @endforeach
            </ul>

            <div class="tab-content">
                <div style="margin-tops: 20px">
                    <div class="alert hidden" role="alert" ></div>
                </div>
            <a role="button" href="javascript:;" class="myButton btn btn-inverse btn-info waves-effect waves-light">Save Results</a>

            @php $i = 1; @endphp
            @foreach($evententrys as $bowtype => $ee)
                <div class="tab-pane {!! $i++ === 1 ? 'active' : '' !!}" id="{{$bowtype}}"><br>
                    @foreach($ee as $division => $rounds)

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
                                        data-entrycompetitionid="{{$a->entrycompetitionid}}">


                                        <th scope="row" width="15%">{{$a->firstname . ' ' . $a->lastname}}</th>
                                        <td width="10%" data-type="distance" data-max="{{$data->dist1max}}" data-value="{{$data->dist1}}" data-sid="{{!empty($a->score1) ? $a->score1->scoreid : '0' }}">
                                            <input type="text" class="form-control distInp"  value="{{!empty($a->score1) ? $a->score1->score : '0' }}" placeholder="">
                                            <i class="md-add-box showMore"></i>

                                            <div class="hidden">
                                                Hits<input type="text" class="form-control" value="{{!empty($a->score1) ? $a->score1->hits : '' }}" data-type="hits" placeholder="Hits">
                                                10+X<input type="text" class="form-control" value="{{!empty($a->score1) ? $a->score1->inners : '' }}" data-type="inner" placeholder="10">
                                                X<input type="text" class="form-control" value="{{!empty($a->score1) ? $a->score1->max : '' }}" data-type="max" placeholder="X">
                                            </div>
                                        </td>
                                        @if(!empty($data->dist2))
                                            <td width="10%" data-type="distance" data-max="{{$data->dist2max}}" data-value="{{$data->dist2}}" data-sid="{{!empty($a->score2) ? $a->score2->scoreid : '0' }}">
                                                <input type="text" class="form-control distInp" value="{{!empty($a->score2) ? $a->score2->score : '0' }}" placeholder="">
                                                <i class="md-add-box showMore"></i>

                                                <div class="hidden">
                                                    Hits<input type="text" class="form-control" value="{{!empty($a->score2) ? $a->score2->hits : '' }}" data-type="hits" placeholder="Hits">
                                                    10+X<input type="text" class="form-control" value="{{!empty($a->score2) ? $a->score2->inners : '' }}" data-type="inner" placeholder="10">
                                                    X<input type="text" class="form-control" value="{{!empty($a->score2) ? $a->score2->max : '' }}" data-type="max" placeholder="X">
                                                </div>
                                            </td>
                                        @endif
                                        @if(!empty($data->dist3))
                                            <td width="10%" data-type="distance" data-max="{{$data->dist3max}}" data-value="{{$data->dist3}}" data-sid="{{!empty($a->score3) ? $a->score3->scoreid : '0' }}">
                                                <input type="text" class="form-control distInp" value="{{!empty($a->score3) ? $a->score3->score : '0' }}" placeholder="">
                                                <i class="md-add-box showMore"></i>

                                                <div class="hidden">
                                                    Hits<input type="text" class="form-control" value="{{!empty($a->score3) ? $a->score3->hits : '' }}" data-type="hits" placeholder="Hits">
                                                    10+X<input type="text" class="form-control" value="{{!empty($a->score3) ? $a->score3->inners : '' }}" data-type="inner" placeholder="10">
                                                    X<input type="text" class="form-control" value="{{!empty($a->score3) ? $a->score3->max : '' }}" data-type="max" placeholder="X">
                                                </div>
                                            </td>
                                        @endif
                                        @if(!empty($data->dist4))
                                            <td width="10%" data-type="distance" data-max="{{$data->dist4max}}" data-value="{{$data->dist4}}" data-sid="{{!empty($a->score4) ? $a->score4->scoreid : '0' }}">
                                                <input type="text" class="form-control distInp" value="{{!empty($a->score4) ? $a->score4->score : '0' }}" placeholder="">
                                                <i class="md-add-box showMore"></i>

                                                <div class="hidden">
                                                    Hits<input type="text" class="form-control" value="{{!empty($a->score4) ? $a->score4->hits : '' }}" data-type="hits" placeholder="Hits">
                                                    10+X<input type="text" class="form-control" value="{{!empty($a->score4) ? $a->score4->inners : '' }}" data-type="inner" placeholder="10">
                                                    X<input type="text" class="form-control" value="{{!empty($a->score4) ? $a->score4->max : '' }}" data-type="max" placeholder="X">
                                                </div>
                                            </td>
                                        @endif

                                        <td width="10%" data-type="sum" data-value="total" data-sid="{{!empty($a->total) ? $a->total->scoreid : '0' }}">
                                            <input type="text" class="form-control totalInp" value="{{ !empty($a->total) ? $a->total->score : '0' }}">
                                        </td>
                                        <td width="10%" data-type="sum" data-value="max" data-sid="{{!empty($a->max) ? $a->max->scoreid : '0' }}">
                                            <input type="text" class="form-control" value="{{ !empty($a->max) ? $a->max->score : '0' }}">
                                        </td>
                                        <td width="10%" data-type="sum" data-value="inners" data-sid="{{!empty($a->inners) ? $a->inners->scoreid : '0' }}">
                                            <input type="text" class="form-control" value="{{ !empty($a->inners) ? $a->inners->score : '0' }}">
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                            @endforeach
                    @endforeach
                </div>
            @endforeach
            </div>
        </div>

        <input type="hidden" id="event" value="/events/scoring/{{$event->eventurl}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            $(function () {
                // store the url
                var eventurl = $('#event').val();

                // toggle showing the additional hits 10s x
                $(document).on('click', '.showMore', function () {
                    $(this).siblings('div').toggle();
                });

                $(document).on('keyup', '.distInp', function () {
                    var parent = $(this).closest('tr').children('td');
                    var total = 0;

                    parent.each(function() {
                        if ($(this).attr('data-type') == 'distance') {
                            var value = parseInt($(this).find('.distInp').val());
                            total += value;
                        }

                    });
                    parent.find('.totalInp').val(total);


                });


                // on score submit
                $(document).on('click', '.myButton', function () {

                    $('.alert').addClass('hidden').removeClass('alert-danger').removeClass('alert-success');
                    $('th').each(function() {
                       $(this).removeClass('error');
                    });

                    // get all the results
                    var results = $('.results');

                    // array for data to be sent
                    var sendJson = [];

                    var errors       = false;
                    var errormessage = ['<h6>Errors - Unable to submit scores</h6>'];

                    // loop over each row
                    results.each(function() {

                       var entryhash          = $(this).attr('data-entryhash');
                       var entrycompetitionid = $(this).attr('data-entrycompetitionid');
                       if (typeof entryhash == 'undefined') {
                           return;
                       }

                       // create new object with required data
                       var jsonData = {
                           entryhash:entryhash,
                           entrycompetitionid:entrycompetitionid
                       }

                       // get the rows td
                       var children = $(this).children('td');

                       // array of scores
                       jsonData.score = [];

                       var totalScore = 0;
                       var total      = 0;
                       children.each(function() {
                            // Remove errors
                           $(this).find('input').removeClass('error');

                           // check to see its a distance score, thats all we want
                           if ($(this).attr('data-type') == 'distance') {

                               var distMax = parseInt($(this).attr('data-max'));
                               var dist    = $(this).attr('data-value');
                               var score   = parseInt($(this).find('input').val());
                               var scoreid = parseInt($(this).attr('data-sid'));

                               if (score > distMax) {
                                   errors = true;
                                   errormessage.push('- Scores exceed max for round<br>');
                                   $(this).find('input').addClass('error');
                                   return;
                               }

                               // add the score to the total
                               totalScore += score;


                               var extras  = $(this).find('div').children('input');
                               var hits    = 0;
                               var inners  = 0;
                               var max     = 0;


                               extras.each(function () {
                                  switch ($(this).attr('data-type')) {
                                      case 'hits':
                                          hits = parseInt($(this).val());
                                          break;

                                      case 'inners':
                                          inners = parseInt($(this).val());
                                          break;

                                      case 'max':
                                          max = parseInt($(this).val());
                                          break;
                                  }
                               });

                               // build the cols score data
                               var data = {
                                   scoreid : scoreid,
                                   key     : dist,
                                   score   : score,
                                   hits    : hits,
                                   inners  : inners,
                                   max     : max
                               }

                               jsonData.score.push(data);
                           }
                           else {
                               var dist    = $(this).attr('data-value');
                               var score   = parseInt($(this).find('input').val());
                               var scoreid = parseInt($(this).attr('data-sid'));

                               // set the total to be the input total, to be checked later
                               if (dist == 'total') {
                                   total = score;
                               }

                               var data = {
                                   scoreid : scoreid,
                                   key     : dist,
                                   score   : score
                               }
                               jsonData.score.push(data);

                           }
                       });

                       if (total != totalScore) {
                           console.log(total, totalScore);
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

                    $.ajax({
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: eventurl,
                        data: {data:sendJson}
                    }).done(function( json ) {

                        if (json.success) {
                            $('.alert').addClass('alert-success').html('Scores Entered Succesfully').removeClass('hidden');
                            setTimeout(function (e) {
                                location.reload();
                            }, 1000);

                        }
                        else {
                            $('.alert').addClass('alert-danger').html(errormessage.join('')).removeClass('hidden');
                            setTimeout(function (e) {
                                location.reload();
                            }, 1000);

                        }
                    });
                });
            });

        </script>

@endsection