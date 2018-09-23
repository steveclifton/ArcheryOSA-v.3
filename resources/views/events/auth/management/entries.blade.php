@extends('template.default')

@section ('title'){{ucwords($event->label)}} Entries @endsection

@section('content')
    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet">


    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage">Events</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            <i class="ion-arrow-right-c"></i>
            <a href="javascript:;">Event Entries</a>
        </h4>
    </div>

    @include('template.alerts')

    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Event Entries</h4>
                <p class="text-muted font-14 m-b-30">
                    Use the checkboxes to <br>
                    -  Approve an archer's entry <br>
                    -  Update an archer's payment status <br>
                    -  Send an archer the confirmation email <br>
                </p>

                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-form-label"></div>
                    <div class="col-3">
                        <a href="/events/manage/evententries/{{$event->eventurl}}/add" class="myButton btn btn-inverse btn-info waves-effect waves-light">Add Entry</a>
                    </div>

                </div>
                <br>


                <meta name="csrf-token" content="{{ csrf_token() }}">
                <meta name="eventurl" content="{{ $event->eventurl}}">

                <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Division</th>
                            <th>Status</th>
                            <th>Paid</th>
                            <th>Entry date</th>
                            <th>Approve Entry</th>
                            <th>Mark Paid</th>
                            <th>Confirmation Email</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($evententries as $entry)
                        <tr>
                            <td>{{$entry->name}}</td>
                            <td>{{$entry->division}}</td>
                            <td id="status">{{$entry->status}}</td>
                            <td id="paid" align="center">{!! $entry->paid ? '<i class="fa fa-check"></i>' : '' !!}</td>
                            <td>{{ date('d F Y', strtotime($entry->created)) }}</td>
                            <td align="center">
                                <input class="entrycheck" type="checkbox" data-entryid="{{$entry->entryid}}"
                                        {!! $entry->status == 'Entered' ? 'Checked' : '' !!}>
                            </td>
                            <td align="center">
                                <input class="paidcheck" type="checkbox" data-entryid="{{$entry->entryid}}"
                                        {!! $entry->paid ? 'Checked' : '' !!}>
                            </td>

                            <td align="center">
                                <input class="confirmemail" type="checkbox" data-entryid="{{$entry->entryid}}"
                                        {!! $entry->confirmationemail ? 'Checked' : '' !!} {!! $entry->confirmationemail ? 'disabled' : '' !!}>
                            </td>
                        </tr>
                        @endforeach
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
            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                lengthChange: false,
                pageLength:30,
                buttons: ['excel', 'pdf']
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection