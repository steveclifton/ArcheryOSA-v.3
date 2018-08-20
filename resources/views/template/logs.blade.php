
@if(Auth::check() && Auth::id() == 1)
    <div class="container">
        <div class="row">
            {{Session::get('time')}}ms
        </div>
        <div class="row">
            @php debug(Session::get('queries')) @endphp
        </div>
    </div>
    @php Session::forget(['queries', 'time']) @endphp
@endif