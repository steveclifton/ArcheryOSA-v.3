@extends('template.default')

@section ('title')Create Division @endsection

@section('content')

  <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/admin/clubs;">Divisions</a> > <a href="javascript:;">Create</a></h4>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            <h4 class="m-t-0 m-b-30 text-center addFormHeader header-title">Create New Division</h4>

            <form class="form-horizontal myForms" role="form">
            	 <div class="form-group row">
                    <label for="inputOrgName3" class="col-sm-12 col-md-3 col-form-label">Division*</label>
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
	                    <span class="help-block"><small>Select an organisation the division belongs to</small></span>
	                </div>
	            </div>
	             <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Age Range</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control">
                    </div>
                </div>
                 <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Code</label>
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
                   {{--  <div class="col-3">
                        <button type="submit" class="myButton btn btn-danger btn-info waves-effect waves-light">Delete</button>
                    </div> --}}
                </div>
               
            </form>
        </div>
    </div>

</div>

@endsection