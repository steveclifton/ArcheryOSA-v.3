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


                // on score submit
                $(document).on('click', '.myButton', function () {

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