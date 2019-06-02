
@if(Auth::check() && Auth::id() == 1)
    <div class="container">
        <div class="row">
            {{Session::get('time')}}ms
        </div>
        <div class="row">
            <div class="col-lg-12">
                @php debug(Session::get('queries')) @endphp
            </div>
        </div>
    </div>
@endif

@php Session::forget(['queries', 'time']) @endphp
