@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@elseif($errors->any() || session('failure'))

    @if (is_array(session('failure')))
        @foreach(session('failure') as $failure)
            <div class="alert alert-danger">
                {{$failure}}
            </div>
        @endforeach
    @else
        <div class="alert alert-danger">
            {{session('failure') ?? 'Please check the details and try again'}}
        </div>
    @endif

@endif
