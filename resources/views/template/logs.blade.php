
@if(Auth::check() && Auth::id() == 1 && false)
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
    @php Session::forget(['queries', 'time']) @endphp
@endif