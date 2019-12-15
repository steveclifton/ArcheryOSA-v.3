@extends('template.default')

@section ('title'){{ucwords($event->label)}} Target Allocations @endsection

@section('content')
    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet">


    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            /
            <a href="javascript:;">Target Allocations</a>
        </h4>
    </div>

    @include('template.alerts')

    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Target Allocations</h4>

                <meta name="csrf-token" content="{{ csrf_token() }}">
                <meta name="eventurl" content="{{ $event->eventurl}}">

                <div class="col-md-8 offset-md-2">
                    <div class="card-box">
                        <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Event Competition</h4>
                        <div class="form-group row">
                            <div class="col-md-4 offset-md-4">
                                <select id="eventcompetition" class="form-control">
                                    @foreach($eventcompetitions as $comp)
                                        <option value="{{$comp->eventcompetitionid}}">
                                            {{$comp->label . ' - ' . date('d F', strtotime($comp->date))}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>


                <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Target</th>
                            <th>Archer</th>
                            <th>Division</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody id="targettable">
                        @include('events.auth.management.includes.targettable')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- end row -->
    <script src="{{URL::asset('/js/admin/evententries.js')}}"></script>

    <script src="{{URL::asset('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.keyTable.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.select.min.js')}}"></script>

    <script src="{{URL::asset('/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.print.min.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var eventurl = $('meta[name="eventurl"]').attr('content');

            $(document).on('blur', '.targetAss, .targetNote', function(e) {

                var self = $(this);

                var entrycompid = $(this).closest('tr').attr('data-compid');
                var target = $(this).closest('tr').find('input[name="target"]').val();
                var note = $(this).closest('tr').find('input[name="note"]').val();


                // update
                $.ajax({
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/ajax/events/manage/targetallocation/update/" + eventurl,
                    data: {
                        entrycompid:entrycompid,
                        target:target,
                        note:note
                    }
                }).done(function( json ) {

                    if (json.success) {
                        $(self).closest('td').find('span').show();
                        setTimeout(function() {
                            $(self).closest('td').find('span').hide();

                        },1000);
                    }

                });

            });

            $('#eventcompetition').on('change', function(e) {
                var eventcompetition = $('#eventcompetition').find(":selected").val();
                $.ajax({
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "/ajax/events/manage/targetallocation/getcomp/" + eventurl,
                    data: {
                        eventcompetitionid:eventcompetition
                    }
                }).done(function( json ) {
                    if (json.success) {
                        $('#targettable').empty();
                        $('#targettable').append(json.html);

                    }
                });
            });

            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                lengthChange: false,
                pageLength:30,
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection