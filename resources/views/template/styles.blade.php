<!-- App css -->
<link href="{{URL::asset('/css/archeryosa.css')}}" rel="stylesheet" type="text/css" />

{{--<link href="{{URL::asset('/plugins/timepicker/bootstrap-timepicker.min.css')}}" rel="stylesheet">--}}
{{--<link href="{{URL::asset('/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet">--}}
<link href="{{URL::asset('/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
{{--<link href="{{URL::asset('/plugins/clockpicker/css/bootstrap-clockpicker.min.css')}}" rel="stylesheet">--}}
{{--<link href="{{URL::asset('/plugins/bootstrap-daterangepicker/daterangepicker.css')}}" rel="stylesheet">--}}

<link href="{{URL::asset('plugins/jstree/style.css')}}" rel="stylesheet" type="text/css" />


<link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{url('/css/icons.css')}}" rel="stylesheet" type="text/css" />

@if(Auth::check() && Auth::user()->dark)
   <link href="{{mix('/css/scssdark/style.css')}}" rel="stylesheet" type="text/css" />
@else
   <link href="{{mix('/css/scsslight/style.css')}}" rel="stylesheet" type="text/css" />
@endif

<script src="{{URL::asset('/js/modernizr.min.js')}}"></script>
<script src="{{URL::asset('/js/jquery.min.js')}}"></script>