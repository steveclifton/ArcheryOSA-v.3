@extends('template.default')

@section ('title') Exports @endsection

@section('content')

    @include('template.alerts')

    <div class="row">
        <div class="offset-2 col-8">
            <div class="card-box table-responsive">
                <h4 class="m-t-0 header-title">Event Exports</h4>
                <p class="text-muted font-14 m-b-30">
                    <br>
                </p>
                <br>

                <meta name="csrf-token" content="{{ csrf_token() }}">

                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th>Export</th>
                        <th>File</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td><strong>July - Now Summary</strong></td>
                        <td>
                            <a href="/reports/julysummary/csv"><i class="fa fa-file-excel-o fa-3x"></i></a>
                        </td>
                    </tr>


                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection