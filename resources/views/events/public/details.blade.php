@extends('template.default')

@php
    $text = !empty($event->info) ? $event->info : $event->label;
@endphp

@section ('title', ucwords($event->label) . ' ')
@section('description', substr($text, 0, 155))
@section('url', 'event/details/' . $event->eventurl)

@section('content')

    <link href="{{URL::asset("/plugins/custombox/css/custombox.css")}}" rel="stylesheet">

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="ion-arrow-left-c"></i>
                    <a href="/events">Events</a>

                </h4>
            </div>
        </div>
    </div>

    <div class="row">


        <div class="card-box ">
            @include('template.alerts')
            <div class="row ">
                <div class="col-lg-6">

                    <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">

                        <div class="carousel-inner" role="listbox">
                            <div class="carousel-item active">
                                <img class="d-block img-fluid" src="{{URL::asset('/images/events/' . $event->imagedt)}}"
                                     alt="{{$event->label}}"/>

                            </div>


                        </div>

                    </div>
                    <div class="d-flex justify-content-around row" id="myWidgetRow">
                        <div class="widget-bg-color-icon card-box col-lg-5 " id="myUserWidget">
                            <div class="widget-inline-box text-center">
                                <h3>
                                    <i class="text-inverse md md-account-child"></i><br>
                                    <b data-plugin="counterup">{!! $confirmed !!}</b>
                                </h3>
                                <h4 class="text-muted font-17">Confirmed Entries</h4>
                                @if(!empty($event->entrylimit) && $evententryopen)
                                    <p class="text-muted font-14">{!! $event->entrylimit - $entrycount !!} Spots
                                        Left</p>
                                @endif
                                <p class="text-muted font-14">{{ $entrycount }} Total Registrations</p>
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
                                        data-target="#myModal">See Entries
                                </button>
                            </div>

                            @if(!empty($targetallocations))
                            <br>
                            <div class="widget-inline-box text-center">
                                <button type="button" class="btn btn-danger"
                                        data-toggle="modal"
                                        data-target="#targetAllo">Target Allocations
                                </button>
                            </div>
                            @endif
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
                    <h1 style="font-size: 24px; text-align: center">{{$event->label}}</h1>
                @php
                    $entryclose = (!empty($event->entryclose) && $event->entryclose != '1970-01-01') ? date('d F Y', strtotime($event->entryclose)) : 'Not Specified';

                @endphp
                <!-- START Table-->
                    <div class="table-responsive">
                        <table class="table table-hover" style="table-layout:fixed;">
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
                                <th scope="row">Event Level</th>
                                <td>
                                    {!! ucwords($event->level) !!}
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
                                    {!! nl2br($event->location ?? '')!!}
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Region</th>
                                <td>
                                    {!! $event->region !!}
                                </td>
                            </tr>

                            @if(!empty($event->cost))
                                <tr>
                                    <th scope="row">Cost</th>
                                    <td>
                                        {{$event->cost ?? 'TBA'}}
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
                                <td style="word-wrap: break-word;">
                                    {!! nl2br($event->info ?? '') !!}
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Schedule</th>
                                <td>
                                    {!! nl2br($event->schedule ?? '') !!}
                                </td>
                            </tr>
                            @if(!empty($event->filename))
                                <tr>
                                    <th scope="row">Downloads</th>
                                    <td>
                                        <a href="/files/events/{{$event->filename}}">{{ $event->filename }}</a>
                                    </td>
                                </tr>
                            @endif
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
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                @if(!empty($targetallocations))
                <div id="targetAllo" class="modal fade"
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
                                <div class="col-lg-12">
                                    <ul class="nav nav-tabs tabs">
                                        @php $i = 1; @endphp

                                        @foreach(array_keys($targetallocations) as $key)
                                            <li class="nav-item tab">
                                                <a href="#{{ \Illuminate\Support\Str::slug($key) }}" data-toggle="tab" aria-expanded="false"
                                                   class="nav-link {{$i++ == 1 ? 'active  show' : ''}}">
                                                    {{$key}}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="tab-content">
                                        @php $i = 1; @endphp
                                        @foreach ($targetallocations as $key => $day)
                                            <div class="tab-pane {{$i++ == 1 ? 'active' : ''}}" id="{{\Illuminate\Support\Str::slug($key)}}">

                                                <div class="table-responsive">

                                                        <div class="col-sm-12">
                                                            <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Archer</th>
                                                                        <th>Target</th>
                                                                    </tr>
                                                                </thead>

                                                                <tbody>
                                                                @foreach($day as $archer)
                                                                    <tr>
                                                                        <th>{{ucwords($archer->fullname)}}</th>
                                                                        <td>{{$archer->target ?? 'TBD'}}</td>
                                                                    </tr>
                                                                @endforeach()
                                                                </tbody>
                                                            </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                @endif

            </div>
            {{-- </div> --}}
        </div>


@endsection