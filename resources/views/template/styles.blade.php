<!-- App css -->
<link href="{{URL::asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{URL::asset('/css/icons.css')}}" rel="stylesheet" type="text/css" />

<link href="{{URL::asset('/css/archeryosa.css')}}" rel="stylesheet" type="text/css" />


@if(Auth::check() && Auth::user()->dark)
   <link href="{{URL::asset('/css/styledark.css')}}" rel="stylesheet" type="text/css" />
@else
   <link href="{{URL::asset('/css/style.css')}}" rel="stylesheet" type="text/css" />
@endif

<script src="{{URL::asset('/js/modernizr.min.js')}}"></script>
