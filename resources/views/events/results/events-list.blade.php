@extends('template.default')

@section ('title')Previous Events @endsection

@section('content')
    @if(!empty($events))

        <div class="row" style="padding-top: 20px">
            <div class="col-lg-12">
                <ul class="nav nav-tabs tabs">
                    <li class="nav-item tab">
                        <a href="javascript:;" data-toggle="tab" aria-expanded="true" class="nav-link show">
                            Completed Events
                        </a>
                    </li>
                </ul>

                <div class="tab-content">

                    @if (!empty($events))
                        <div class="table-responsive text-nowrap">
                            <table class="table table-hover">
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
    @endif
@endsection