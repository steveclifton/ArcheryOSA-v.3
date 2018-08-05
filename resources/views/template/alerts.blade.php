@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@elseif($errors->any() || session('failure'))
    <div class="alert alert-danger">
        {{session('failure') ?? 'Please check the details and try again'}}
    </div>
@endif