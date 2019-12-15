@extends('template.default')

@section ('title')Event Registration @endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/event/details/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
                        /
                    <a href="javascript:;">Registration</a>
                </h4>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                @include('template.alerts')
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                            <tr>
                                <th width="50%">Name</th>
                                <th width="40%">Entry Status</th>
                                <th>Paid</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row" >
                                        <a href="/event/registration/{{$event->eventurl}}/{{Auth::user()->username}}">{{Auth::user()->getFullName()}}</a>
                                    </th>

                                    @php $evententry = Auth::user()->getEventEntry($event->eventid) @endphp
                                    <td class="text-{{getEntryStatusText($evententry->entrystatusid ?? null)}}">
                                        {{ Auth::user()->getEventEntryStatus($event->eventid) ?: 'Not Entered'}}
                                    </td>

                                    <td align="justify">@if(!empty($evententry->paid))<i class="fa fa-check-square-o"></i>@endif</td>
                                </tr>

                                @foreach($relations as $relation)
                                <tr>
                                    <th scope="row" >
                                        <a href="/event/registration/{{$event->eventurl}}/{{$relation->username}}">{{$relation->getFullName()}}</a>
                                    </th>
                                    @php $evententry = $relation->getEventEntry($event->eventid) @endphp
                                    <td class="text-{{getEntryStatusText($evententry->entrystatusid ?? null)}}">
                                        {{$relation->getEventEntryStatus($event->eventid) ?: 'Not Entered'}}
                                    </td>

                                    <td align="justify">@if(!empty($evententry->paid))<i class="fa fa-check-square-o"></i>@endif</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>



@endsection