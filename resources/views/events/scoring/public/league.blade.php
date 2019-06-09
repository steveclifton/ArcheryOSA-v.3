@extends('template.default')

@section ('title')Scoring @endsection

@section('content')

    <div class="row">
        <div class="offset-md-2 col-md-8 col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                <a href="/event/details/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="offset-md-2 col-md-8 col-sm-12">

            @foreach($entrys as $entry)

            <div class="card-box">
                <h4 class="m-t-0 m-b-30 header-title">{{$entry->firstname . ' ' . $entry->lastname . ' - ' . $entry->divisionname}}</h4>
                <h5>{{$entry->roundname ?? ''}}</h5>

                <form role="form" action="javascript:;">
                    <input type="hidden" id="userid" value="{{$entry->userid}}">
                    <input type="hidden" id="divisionid" value="{{$entry->divisionid}}">
                    <div class="alert alert-danger" style="display: none"></div>
                    <div class="alert alert-success" style="display: none"></div>

                    @foreach(range(1,4) as $i)
                        @php $dist = 'dist' . $i; $max = $dist . 'max';  @endphp
                        @if(isset($entry->{$dist}))
                            <div class="form-group col-md-6">
                                <label class="" for="{{$dist}}">Distance: {{ $entry->{$dist} . $entry->unit }}</label>
                                <input type="text" class="form-control"
                                       id="{{$dist}}"
                                       data-max="{{ $entry->{$max} }}"
                                       placeholder="Score"
                                       value="{{isset($entry->score->{$dist}) ? intval($entry->score->{$dist.'score'}): ''}}">
                                <small id="emailHelp" class="form-text text-muted">Score</small>
                            </div>
                        @endif
                    @endforeach

                    <div class="form-group col-md-6">
                        <input type="text" class="form-control"
                               id="totalhit" placeholder=""
                               value="{{isset($entry->score->totalhits) ? intval($entry->score->totalhits) : ''}}">
                        <small id="emailHelp" class="form-text text-muted">Total Hits</small>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control"
                               id="total10" placeholder=""
                               value="{{isset($entry->score->inners) ? intval($entry->score->inners) : ''}}">
                        <small id="emailHelp" class="form-text text-muted">Total 10s & Xs</small>
                    </div>
                    <div class="form-group col-md-6">
                        <input type="text" class="form-control"
                               id="totalx" placeholder=""
                               value="{{isset($entry->score->max) ? intval($entry->score->max) : ''}}">
                        <small id="emailHelp" class="form-text text-muted">Total Xs</small>
                    </div>

                    <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Submit</button>
                </form>
            </div>
            @endforeach
        </div>

        <input type="hidden" id="event" value="/events/scoring/{{$event->eventurl}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>

            $(function() {
                $(document).on('submit', 'form', function (e) {
                    e.preventDefault();

                    var errorDiv = $(this).find('.alert-danger');
                    errorDiv.html('').hide();
                    $(this).find('.alert-success').html('').hide();

                    var self = $(this);

                    var userid = $(this).find('#userid').val();
                    var divisionid = $(this).find('#divisionid').val();
                    var score1 = $(this).find('#dist1').val() || 0;
                    var score2 = $(this).find('#dist2').val() || 0;
                    var score3 = $(this).find('#dist3').val() || 0;
                    var score4 = $(this).find('#dist4').val() || 0;
                    var total10 = $(this).find('#total10').val() || 0;
                    var totalx  = $(this).find('#totalx').val() || 0;
                    var totalhit = $(this).find('#totalhit').val() || 0;
                    var distmax1 = $(this).find('#dist1').attr('data-max') || 0;
                    var distmax2 = $(this).find('#dist2').attr('data-max') || 0;
                    var distmax3 = $(this).find('#dist3').attr('data-max') || 0;
                    var distmax4 = $(this).find('#dist4').attr('data-max') || 0;

                    var scores = {
                        'dist1': parseInt(score1),
                        'dist2': parseInt(score2),
                        'dist3': parseInt(score3),
                        'dist4': parseInt(score4),
                        'totalhit': parseInt(totalhit),
                        'total10': parseInt(total10),
                        'totalx': parseInt(totalx),
                        'userid': userid,
                        'divisionid':divisionid
                    };

                    var errors = [];
                    var i = 1;
                    for (var s in scores) {
                        var max = $(this).find('#dist' + i++).attr('data-max');
                        max = parseInt(max);

                        if (!isNaN(max)
                            && scores[s] >= 0
                            && scores[s] > max
                        ) {
                            errors.push('Score ' + (i - 1) + ' above round max of ' + max);
                        }
                    }


                    if (errors.length > 0) {
                        errorDiv.html(errors.join('<br>')).show();
                        return false;
                    }
                    else {
                        var url = '/events/scoring/league/' + '{{$event->eventurl}}';
                        $.ajax({
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: url,
                            data: scores
                        }).done(function( json ) {
                            if (json.success) {
                                self.find('.alert-success').html(json.data).show();
                            }

                        });
                    }



                })
            });


        </script>
    </div>

@endsection