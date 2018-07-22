@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@elseif($errors->any())
    <div class="alert alert-danger">
        Please check the details and try again
    </div>
@endif