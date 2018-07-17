@extends('template.default')

@section ('title')Profile @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title"><a href="/profile">Profile</a> > <a href="javascript:;">My Details</a></h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputFirstName" class="col-form-label">First Name</label>
                            <input type="text" class="form-control" id="inputFirstName" placeholder="First Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputLastName" class="col-form-label">Last Name</label>
                            <input type="text" class="form-control" id="inputLastName" placeholder="Last Name">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail4" class="col-form-label">Email</label>
                            <input type="email" class="form-control" id="inputEmail4" placeholder="Email">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputPhone" class="col-form-label">Phone</label>
                            <input type="phone" class="form-control" id="inputPhone" placeholder="Phone">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputAddress" class="col-form-label">Address</label>
                        <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                    </div>
                    <div class="form-group">
                        <label for="inputAddress2" class="col-form-label">Address 2</label>
                        <input type="text" class="form-control" id="inputAddress2" placeholder="Apartment, studio, or floor">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputCity" class="col-form-label">City</label>
                            <input type="text" class="form-control" id="inputCity">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputState" class="col-form-label">State</label>
                            <select id="inputState" class="form-control">Choose</select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="inputZip" class="col-form-label">Zip</label>
                            <input type="text" class="form-control" id="inputZip">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Profile Image</label>
                        <div class="col-4">
                            <input type="file" class="form-control">
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-inverse">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection