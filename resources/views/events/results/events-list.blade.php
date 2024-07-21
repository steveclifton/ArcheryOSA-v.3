@extends('template.default')

@section ('title')Previous Events @endsection

@section('content')
    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet">

    @if(!empty($events))

        <div class="row" style="padding-top: 20px; padding-bottom: 10px">
            <div class="col-lg-12">
                <h3 style="text-align: center">
                    Completed Events
                </h3>

                <div class="tab-content" style="background: white; border: 2px solid lightgrey;">
                    @if (!empty($events))
                        <div class="table-responsive text-nowrap">
                            <table id="datatable-buttons" class="table table-hover">
                                <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Start</th>
                                    <th>Finish</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <th scope="row"><a href="/event/results/{{$event->eventurl}}">{{$event->label}}</a></th>
                                        <td>{{date('d M Y', strtotime($event->start))}}</td>
                                        <td>{{date('d M Y', strtotime($event->end))}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>

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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
        <script src="https://cdn.datatables.net/plug-ins/1.13.7/sorting/datetime-moment.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                //Buttons examples
                $.fn.dataTable.moment( 'DD MMM YYYY' );
                $('#datatable-buttons').DataTable({
                    order: [[2, 'desc']],
                    lengthChange: false,
                    pageLength: 10,
                    searching: false,
                    info: false,
                });
            });
        </script>
    @endif

@endsection