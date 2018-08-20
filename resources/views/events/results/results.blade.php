@extends('template.default')

@section ('title')Event Results @endsection

@section('content')
	<div class="row">
		<div class="col-sm-12">
	    	<div class="page-title-box">
	        	<h4 class="page-title">Events</h4>
	    	</div>
		</div>
		<div class="row col-sm-12 sponsorImgContainer">
			<img class="sponsorImg img-fluid" src="https://weather-tekwindows.com/wp-content/uploads/narrow-header-placeholder.jpg" alt="">
			<div class="textSponsorImg d-none d-md-block">The Event Was Sponsored by Miley Popo and friends!</div>
		</div>
        <div class="col-sm-3 weekSelector">
            <select class="form-control">
                <option>Week 12</option>
                <option>Week 13</option>
                <option>Week 14</option>
                <option>Week 15</option>
                <option>Week 16</option>
            </select>
        </div>        
	</div>
	<div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs tabs">
                <li class="nav-item tab">
                    <a href="#compound" data-toggle="tab" aria-expanded="false" class="nav-link active show">
                        Compound
                    </a>
                </li>
                <li class="nav-item tab">
                    <a href="#recurve" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Recurve
                    </a>
                </li>
                <li class="nav-item tab">
                    <a href="#barebow" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Barebow
                    </a>
                </li>
            </ul>
            <ul class="nav nav-tabs tabs">
                <li class="nav-item tab">
                    <a href="#compound" data-toggle="tab" aria-expanded="false" class="nav-link active show">
                        Female
                    </a>
                </li>
                <li class="nav-item tab">
                    <a href="#recurve" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Male
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="compound">
                	<h5 class="tableTitle">Events</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Archer</th>
                                    <th>18m</th>
                                    <th>Total</th>
                                    <th>10+X</th>
                                    <th>X</th>
                                    <th>Average</th>
                                    <th>Handicap</th>
                                    <th>Points</th>
                                    <th>Total Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Adam Niziol</th>
                                    <td>299</td>
                                    <td>299</td>
                                    <td>29</td>
                                    <td>18</td>
                                    <td>297.36</td>
                                    <td>301.64</td>
                                    <td>0</td>
                                    <td>39</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="recurve">
                	<h5 class="tableTitle">Events</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Archer</th>
                                    <th>18m</th>
                                    <th>Total</th>
                                    <th>10+X</th>
                                    <th>X</th>
                                    <th>Average</th>
                                    <th>Handicap</th>
                                    <th>Points</th>
                                    <th>Total Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Adam Niziol</th>
                                    <td>299</td>
                                    <td>299</td>
                                    <td>29</td>
                                    <td>18</td>
                                    <td>297.36</td>
                                    <td>301.64</td>
                                    <td>0</td>
                                    <td>39</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane" id="barebow">
                	<h5 class="tableTitle">Events</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Archer</th>
                                    <th>18m</th>
                                    <th>Total</th>
                                    <th>10+X</th>
                                    <th>X</th>
                                    <th>Average</th>
                                    <th>Handicap</th>
                                    <th>Points</th>
                                    <th>Total Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row">Adam Niziol</th>
                                    <td>299</td>
                                    <td>299</td>
                                    <td>29</td>
                                    <td>18</td>
                                    <td>297.36</td>
                                    <td>301.64</td>
                                    <td>0</td>
                                    <td>39</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

         
    
@endsection