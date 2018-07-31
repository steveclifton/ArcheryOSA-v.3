@extends('template.default')

@section ('title')Event Details @endsection

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="page-title-box">
            <h4 class="page-title">North Island Youth Championships 2018</h4>
        </div>
    </div>
 </div>
 

<div class="row">

	<div class="card-box ">
    	<div class="row ">
        	<div class="col-lg-6" >

				<p class="text-muted m-b-30 font-16">Sponsors and prizes </p>

           		<div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                        <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
                    </ol>
                	<div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img class="d-block img-fluid" src="{{URL::asset('/images/archery.jpg')}}" alt="First slide" />
                            <div class="carousel-caption d-none d-md-block">
                                {{-- <h3 class="text-white">First slide label</h3> --}}
                                {{-- <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p> --}}
                            </div>
                        </div>
                    <div class="carousel-item">
                        <img class="d-block img-fluid" src="{{URL::asset('/images/archery.jpg')}}" alt="Second slide" />
                        <div class="carousel-caption d-none d-md-block">
                            {{-- <h3 class="text-white">Second slide label</h3> --}}
                            {{-- <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p> --}}
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img class="d-block img-fluid" src="{{URL::asset('/images/archery.jpg')}}" alt="Third slide" />
                        <div class="carousel-caption d-none d-md-block">
                            {{-- <h3 class="text-white">Third slide label</h3> --}}
                            {{-- <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p> --}}
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
			<div class="d-flex justify-content-around row" id="myWidgetRow">
            <div class="widget-bg-color-icon card-box col-lg-5 " id="myUserWidget">
            	<div class="widget-inline-box text-center">
	                <h3><i class="text-inverse md md-account-child"></i> <b data-plugin="counterup">39</b></h3>
	                <h4 class="text-muted font-17">Total Entries</h4>
	                <p class="text-muted font-14">34 Enteries Left</p>
	                 <button type="button" class="btn btn-inverse waves-effect waves-light">Enter Now</button>  
	            </div>    
	           
            </div>
			<div class="widget-bg-color-icon card-box col-lg-5 " id="myUserWidget">
            <div class="widget-inline-box text-center">
                <h3><i class="text-inverse md icon-trophy"></i></h3>
                <h4 class="text-muted font-17">Results</h4>
                <p class="text-muted font-14">Results are in</p>
                 <button type="button" class="btn btn-inverse waves-effect waves-light">I want to see</button>
            </div>
	        
		</div>
        </div>
    </div>

	<div class="clearfix visible-xs"></div>
    	<div class="clearfix visible-sm"></div>

			<div class="col-lg-6 m-t-sm-40 ">
				<p class="text-muted m-b-30 font-16">Event Details </p>

                <!-- START Table-->
    			<div class="table-responsive">
        			<table class="table table-hover">
          			  {{-- <thead class="thead-light"> --}}
             			 <tbody>
				            <tr>
				                <th class="w-25">Start Date</th>
				                <td>1 July 2018</td>
				                {{-- <td>Rounds</td>
				                <td>Visible</td> --}}
				            </tr>
				            {{-- </thead> --}}
				            <tr>
				                
				                <th scope="row">End Date</th>
				                <td>10 July 2018</td>
				                {{-- <td><i class="fa fa-check"></i></td> --}}

				            </tr>
				            <tr>
				                <th scope="row">Rounds</th>
				                <td>2x WA720 70m</br>2x WA720 70m</br>
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Distances</th>
				                <td>
				                	70m, 60m, 50m, 45m, 35m, 20m
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Event Type</th>
				                <td>
				                	Competition
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Email</th>
				                <td>
				                	MGACTournaments@gmail.com
				                	
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Host Club</th>
				                <td>
				                	Mountain Green
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Location</th>
				                <td>
				                	Mountain Green Archery Club - Outdoor Range, Mount Albert - Owairaka Domain, Auckland
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Cost</th>
				                <td>
				                	$60
				                </td>
				            </tr>
				                <tr>
				                <th scope="row">Bank Details</th>
				                <td>
				                	02-0110-0091898-00
				                </td>
				            </tr>
				                <tr>
				                <th scope="row">Bank Reference</th>
				                <td>
				                	NI Youth
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Event Info</th>
				                <td>
				                	Saturday: Double 720 (two x WA 72 arrow rounds)
									Sunday: Mixed Teams and Individual Matchplay Elimination
				                </td>
				            </tr>
				            <tr>
				                <th scope="row">Schedule</th>
				                <td>
				                	Saturday: Bow inspection at 8:30 am followed by first sighting end at 9:00 am
									Sunday: First sighting end at 9:00 am
				                </td>
				            </tr>
				        </tbody>
        			</table>
				</div>

                <!-- END-->
			</div>
			<div class="clearfix visible-xs"></div>
    <div class="clearfix visible-sm"></div>
			<div class="col-md-6 col-lg-6 ">

                    </div>


		</div>	
 	{{-- </div> --}}
</div>


@endsection