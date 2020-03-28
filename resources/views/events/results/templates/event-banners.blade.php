<div class="row">
    <div class="col-md-12 homePageBanner">
        <div class="panel panel-default text-center d-lg-none text-white slider-bg m-b-0"
             style="background-position:center !important;
                     background-size:contain !important;
                     background-size: cover !important;
                     background-repeat: no-repeat;
                     width: 100%;
                     background: url({{asset('images/events/' . $event->imagedt)}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class="text-white font-600 archeryHeadText">{{ucwords($event->label)}}</a></h3>

                            <p class="m-t-30"><em></em></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default text-center desktopOnlyImg d-none d-lg-block text-black slider-bg m-b-0"
             style="background-position:center !important;
                     background-size:contain !important;
                     background-size: cover !important;
                     background-repeat: no-repeat;
                     width: 100%; background: url({{asset('images/events/' . $event->imagedt)}});">
            <div class="slider-overlay br-radius"></div>
            <div class="panel-body p-0">
                <div class="">
                    <div id="owl-slider-2" class="owl-carousel">
                        <div class="item">
                            <h3><a href="#" class=" archeryHeadText">{{ucwords($event->label)}}</a></h3>
                            <p class="m-t-30"><em></em></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- col-->
</div>
