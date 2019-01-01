@extends('template.default')

@section ('title'){{ucwords($event->label)}} @endsection

@section('content')

    <link href="{{URL::asset("/plugins/custombox/css/custombox.css")}}" rel="stylesheet">

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title" ">
                <a href="/events">Events</a>
                <i class="ion-arrow-right-c"></i>
                <a href="javascript:;">{{ucwords($event->label)}}</a>
                </h4>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="card-box ">
            <div class="row ">
                <div class="col-lg-6">

                    {{--<p class="text-muted m-b-30 font-16">Sponsors and prizes </p>--}}

                    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                        {{--<ol class="carousel-indicators">--}}
                            {{--<li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>--}}
                        {{--</ol>--}}
                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img class="d-block img-fluid" src="{{URL::asset('/images/events/' . $event->imagedt)}}"
                                     alt="First slide"/>
                                {{--<div class="carousel-caption d-none d-md-block">--}}
                                    {{-- <h3 class="text-white">First slide label</h3> --}}
                                    {{-- <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p> --}}
                                {{--</div>--}}
                            </div>


                        </div>
                        {{--<a class="carousel-control-prev" href="#carouselExampleCaptions" role="button"--}}
                           {{--data-slide="prev">--}}
                            {{--<span class="carousel-control-prev-icon" aria-hidden="true"></span>--}}
                            {{--<span class="sr-only">Previous</span>--}}
                        {{--</a>--}}
                        {{--<a class="carousel-control-next" href="#carouselExampleCaptions" role="button"--}}
                           {{--data-slide="next">--}}
                            {{--<span class="carousel-control-next-icon" aria-hidden="true"></span>--}}
                            {{--<span class="sr-only">Next</span>--}}
                        {{--</a>--}}
                    </div>
                    <div class="d-flex justify-content-around row" id="myWidgetRow">
                        <div class="widget-bg-color-icon card-box col-lg-5 " id="myUserWidget">
                            <div class="widget-inline-box text-center">
                                <h3><i class="text-inverse md md-account-child"></i> <b data-plugin="counterup">{!! $entrycount !!}</b>
                                </h3>
                                <h4 class="text-muted font-17">Total Entries</h4>
                                @if(!empty($event->entrylimit) && $evententryopen)
                                    <p class="text-muted font-14">{!! $event->entrylimit - $entrycount !!} Spots Left</p>
                                @endif
                                @if($evententryopen)
                                    <a href="/event/register/{{$event->eventurl}}"
                                   class="btn btn-inverse waves-effect waves-light">Enter Now</a>
                                @else
                                    <a href="javascript:;"
                                       class="btn btn-inverse waves-effect waves-light">Closed</a>
                                @endif


                            </div>
                            <br>
                            <div class="widget-inline-box text-center">
                                <button type="button" class="btn btn-primary waves-effect waves-light"
                                        data-toggle="modal"
                                        data-target="#myModal">See Entries</button>
                            </div>

                        </div>
                        <div class="widget-bg-color-icon card-box col-lg-5 " id="myUserWidget">
                            <div class="widget-inline-box text-center">
                                <h3><i class="text-inverse md icon-trophy"></i></h3>
                                <h4 class="text-muted font-17">Results</h4>
                                @if(!empty($scorecount))
                                    <p class="text-muted font-14">Results are in</p>
                                    <a href="/event/results/{{$event->eventurl}}"
                                       class="btn btn-inverse waves-effect waves-light">See Results</a>
                                @else
                                    <p class="text-muted font-14">No Results Yet</p>
                                    <a href="/event/results/{{$event->eventurl}}"
                                       class="btn btn-inverse waves-effect waves-light">Results</a>
                                @endif

                            </div>

                        </div>
                    </div>
                </div>

                <div class="clearfix visible-xs"></div>
                <div class="clearfix visible-sm"></div>

                <div class="col-lg-6 m-t-sm-40 ">
                    <p class="text-muted m-b-30 font-16">Event Details</p>

                @php
                    $entryclose = (!empty($event->entryclose) && $event->entryclose != '1970-01-01') ? date('d F Y', strtotime($event->entryclose)) : 'Not Specified';
                @endphp
                    <!-- START Table-->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th class="w-25">Start Date</th>
                                    <td>{!! date('d F Y', strtotime($event->start)) !!}</td>
                                </tr>
                                <tr>
                                    <th scope="row">End Date</th>
                                    <td>{!! date('d F Y', strtotime($event->end)) !!}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Entries Close</th>
                                    <td>{!! $entryclose !!}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Rounds</th>
                                    <td>{!! is_array($roundlabels) ? implode('<br>', $roundlabels) : $roundlabels !!}</br>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Event Type</th>
                                    <td>
                                        {!! ucwords($competitiontype) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td>
                                        {{$event->email}}
                                    </td>
                                </tr>
                                @if(!empty($clublabel))
                                    <tr>
                                        <th scope="row">Host Club</th>
                                        <td>
                                            {{ucwords($clublabel)}}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th scope="row">Location</th>
                                    <td>
                                        {!! nl2br($event->location)!!}
                                    </td>
                                </tr>
                                @if(!empty($event->cost))
                                    <tr>
                                        <th scope="row">Cost</th>
                                        <td>
                                            {!! (strpos($event->cost, '$') === 0) ? $event->cost : '$' . $event->cost !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Bank Details</th>
                                        <td>
                                            {{$event->bankaccount}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Bank Reference</th>
                                        <td>
                                            {{$event->bankreference}}
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <th scope="row">Event Info</th>
                                    <td>
                                        {!! nl2br($event->info) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">Schedule</th>
                                    <td>
                                        {!! nl2br($event->schedule) !!}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- END-->
                </div>
                <div class="clearfix visible-xs"></div>
                <div class="clearfix visible-sm"></div>
                <div class="col-md-6 col-lg-6 ">

                </div>


                <div class="button-list">

                </div>


                <div id="myModal" class="modal fade"
                     tabindex="-1" role="dialog"
                     aria-labelledby="full-width-modalLabel"
                     aria-hidden="true" style="display: none;">

                    <div class="modal-dialog ">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="full-width-modalLabel">{{ucwords($event->label)}}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <h4>Entries</h4>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <tbody>
                                            @foreach($entries as $entry)
                                                <tr>
                                                    <th class="">{{ucwords($entry->firstname ?? '') . ' ' . ucwords($entry->lastname ?? '')}}</th>
                                                    <td>{{$entry->divisionname}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->


            </div>
            {{-- </div> --}}
        </div>


@endsection