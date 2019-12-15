@extends('template.default')

@section ('title') NZ Outdoor Records @endsection

@section('content')
    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet">


    <div class="page-title-box">
        <h4 class="page-title">
            <a href="javascript:;">Records</a>
            /
            <a href="javascript:;">ArcheryNZ Outdoor Records</a>
        </h4>
    </div>

    @include('template.alerts')

    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Records</h4>

                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-form-label"></div>
                </div>
                <br>


                <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Round</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Bowtype</th>
                        <th>Division</th>
                        <th>Score</th>
                        <th>X-Count</th>
                        <th>Date</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>{{$record->round}}</td>
                            <td>{{$record->firstname}}</td>
                            <td>{{$record->lastname}}</td>
                            <td>{{$record->bowtype}}</td>
                            <td>{{$record->division}}</td>
                            <td>{{$record->score}}</td>
                            <td>{{$record->xcount}}</td>
                            <td>{{date('d F Y', strtotime($record->date))}}</td>

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
                pageLength:200,

            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
