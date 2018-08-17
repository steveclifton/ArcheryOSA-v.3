@extends('template.default')

@section ('title')Relation Confirmed! @endsection

@section('content')
    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @if($status)
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @else
                <div class="alert alert-danger">
                    {{ $message }}
                </div>
            @endif
        </div>
    </div>

@endsection