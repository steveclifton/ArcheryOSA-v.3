@extends('template.default')

@section ('title'){{ucwords($event->label)}} Entries @endsection

@section('content')
    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet">


    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            /
            <a href="javascript:;">Event Costs</a>
        </h4>
    </div>

    @include('template.alerts')

    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Event Costs</h4>

                <meta name="csrf-token" content="{{ csrf_token() }}">
                <meta name="eventurl" content="{{ $event->eventurl }}">

                <p class="text-muted font-14 m-b-30">
                    Enter the costs for each competition and the total event cost <br>
                </p>

                <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date</th>
                            <th class="text-left">Cost</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($eventcompetitions as $eventcompetition)
                        <tr>
                            <td>{{$eventcompetition->label}}</td>
                            <td>{{$eventcompetition->date}}</td>
                            <td align="left">
                                $<input data-id="{{$eventcompetition->eventcompetitionid}}"
                                        value="{{ number_format($eventcompetition->cost, 2) }}"
                                        class="cost" type="text">
                                <span></span>

                            </td>
                        </tr>
                        @endforeach
                        <tr>
                            <td>Event Total</td>
                            <td></td>
                            <td align="left">
                                $<input data-id="total"
                                        value="{{ number_format($event->totalcost, 2) }}"
                                        class="cost" type="text">
                                <span></span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function() {

            $(document).on('change', '.cost', function () {

                var id  = $(this).attr('data-id');
                var cost  = $(this).val();
                var eventurl = $('meta[name="eventurl"]').attr('content');

                var _this    = $(this);

                $.ajax({
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/ajax/events/manage/eventcost/"+eventurl,
                    data: {
                        id: id,
                        cost: cost
                    }
                }).done(function( json ) {

                    if (json.success) {
                        $(_this).next('span').html('Saved!');
                        setTimeout(function() {
                            $(_this).next('span').html('');
                        }, 2000);
                    }

                });

            });

        });
    </script>

@endsection
