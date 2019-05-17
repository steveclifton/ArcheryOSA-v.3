@extends('template.default')

@section ('title')Events @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">Manage Existing Events</h4>
            </div>
            <div class="container" style="margin-left: 0; padding-left: 0;">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group row" style="display:block; margin-left: 0; margin-right: 1em; text-align: left;">
                            <div class="col-md-12">
                                <input class="form-control" placeholder="Search" type="search" id="searchinput">
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <a role="button" href="/events/create" class="btn btn-inverse waves-effect waves-light ">
                            <span class="btn-label">
                                <i class="fa fa-plus"></i>
                            </span>Add
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                <div class=" myTable table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Name</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Status</th>
                                <th>Visible</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr class="eventrow" data-search="{{$event->label}}">
                                        <th scope="row">
                                            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                                        </th>
                                        <td>{{date('d F Y', strtotime($event->start))}}</td>
                                        <td>{{date('d F Y', strtotime($event->end))}}</td>
                                        <td>{{$event->status}}</td>
                                        <td>@if($event->visible)<i class="fa fa-check"></i>@endif</td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function(){
            $(document).on('keyup', '#searchinput', function (e) {
                var search = $(this).val();


                $.each($('.eventrow'), function(key, value) {
                    $(value).show();

                    var val = $(value).attr('data-search');
                    if (!val.toLowerCase().includes(search.toLowerCase())) {
                        $(value).hide();
                    }

                })
            });
        });
    </script>

@endsection