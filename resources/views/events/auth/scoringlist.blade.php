@extends('template.default')

@section ('title')Open Event Scoring @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title "><a href="javascript">Open Scoring Events</a></h4>
            </div>
        </div>
    </div>

    @include('template.alerts')

    <div class="row">
        <div class="col-12">

            <div class="card-columns">
                @foreach(array_slice($events, 0, 3) as $event)
                    <a href="/scoring/{{$event->eventurl}}">
                        <div class="card m-b-20">
                            <img class="card-img-top img-fluid" src="{{URL::asset('/images/archery.jpg')}}"
                                 alt="Card image cap">
                            <div class="card-body">
                                <h4 class="card-title font-18 mt-0">{{$event->label}}</h4>
                                @if(!empty($event->entryclose))
                                    <p class="card-text">Entries Close : {!! date('d F Y', strtotime($event->entryclose)) !!}</p>
                                @endif
                                <p class="card-text">Start : {!! date('d F Y', strtotime($event->start)) !!}</p>
                                <p class="card-text">Status : {{$event->eventstatus}}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

@endsection