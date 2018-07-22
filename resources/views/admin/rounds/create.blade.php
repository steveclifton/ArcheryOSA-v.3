@extends('template.default')

@section ('title')Create Round @endsection

@section('content')

<div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/admin/clubs">Rounds</a> > <a href="javascript:;">Create</a></h4>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create New Round</h4>

            <form class="form-horizontal myForms" role="form">
            	 <div class="form-group row">
                    <label for="inputOrgName3" class="col-sm-12 col-md-3 col-form-label">Name*</label>
                    <div class="col-md-9">
                        <input type="OrgName" class="form-control" id="inputOrgName3">
                    </div>
                </div>
               <div class="form-group row">
	                <label class="col-sm-12 col-md-3 col-form-label">Parent Organisation</label>
	                <div class="col-md-9">
	                    <select class="form-control">
	                        <option>Archery NZ</option>
	                        <option>Auckland District Archery Association</option>
	                        <option>ECBOPAA</option>
	                        <option>None</option>
	                        <option>NZFAA</option>
	                        <option>World Archery</option>
	                    </select>
	                </div>
	            </div>
	            <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Round Code*</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Description*</label>
                    <div class="col-md-9">
                        <textarea class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-group row">
	                <label class="col-sm-12 col-md-3 col-form-label">Round Units</label>
	                <div class="col-md-9">
	                    <select class="form-control">
	                        <option>Meters</option>
	                        <option>Yards</option>
	                    </select>
	                </div>
	            </div>
	            <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 1*</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
	            <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 1 Max*</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 2</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 2 Max</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 3</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 3 Max</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 4</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Distance 4 Max</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Max Total*</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Max Hits*</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Max Total X - Count*</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="form-group row justify-content-end">
                    <div class=" col-md-9">
                        <div class="checkbox checkbox-primary">
                            <input id="checkbox2" type="checkbox">
                            <label for="checkbox2">
                                Visible
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-0 justify-content-start row">
                	<div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Create</button>
                    </div>
                    {{-- <div class="col-3">
                        <button type="submit" class="myButton btn btn-danger btn-info waves-effect waves-light">Delete</button>
                    </div> --}}
                </div>
               
            </form>
        </div>
    </div>

</div>

@endsection