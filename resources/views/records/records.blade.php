@extends('template.default')

@section ('title') @endsection

@section('content')
    <div class="col-md-12 homePageBanner">

        {{-- <p class="text-muted m-b-30 font-13">A slideshow component for cycling through elements, like a carousel.</p> --}}

        <div class="panel panel-default text-center d-lg-none text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybannerdt2.jpg')}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="text-white font-600">Archery OSA<br>Records</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em></em></p>
                            {{--<button class="btn btn-inverse btn-sm m-t-40">Latest Results</button>--}}
                        </div><!-- /.item -->
                    </div><!-- /#tiles-slide-2 -->
                </div>
            </div> <!-- panel-body -->
        </div><!-- Panel -->

        <div class="panel panel-default text-center desktopOnlyImg d-none d-lg-block text-white slider-bg m-b-0"
             style="background: url({{asset('/images/archerybannerdt2.jpg')}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="archeryHeadText">Archery OSA<br>Records</a></h3>
                            {{-- <p class="small">02 April, 2015</p> --}}
                            <p class="m-t-30"><em></em></p>
                            {{--<button class="btn btn-inverse btn-sm m-t-40">Latest Results</button>--}}
                        </div><!-- /.item -->
                    </div><!-- /#tiles-slide-2 -->
                </div>
            </div> <!-- panel-body -->
        </div>
    </div> <!-- col-->



    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                {{-- <h4 class="page-title">Upcoming Events</h4> --}}
            </div>
        </div>
    </div>

    <h3>Coming Soon!</h3>
@endsection