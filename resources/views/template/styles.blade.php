<!-- App css -->
<link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('/css/icons.css')}}" rel="stylesheet" type="text/css" />

<link href="{{URL::asset('/css/archeryosa.css')}}" rel="stylesheet" type="text/css" />


@if(Auth::check() && Auth::user()->dark)
   <link href="{{mix('/css/scssdark/style.css')}}" rel="stylesheet" type="text/css" />
@else
   <link href="{{mix('/css/scsslight/style.css')}}" rel="stylesheet" type="text/css" />
@endif

<script src="{{URL::asset('/js/modernizr.min.js')}}"></script>
