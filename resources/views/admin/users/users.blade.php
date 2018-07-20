@extends('template.default')

@section ('title')Users @endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">All Users</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Image</th>
                            <th>User ID</th>
                            <th>User Type</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Event Enteries</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row"><img src="assets/images/users/avatar-3.jpg" alt="image" class="img-fluid thumb-md rounded"></th>
                            <td>1</td>
                            <td>Admin</td>
                            <td>Steve</td>
                            <td>Clifton</td>
                            <td>Steve.clifton@outlook.com</td>
                            <td>3</td>
                        </tr>
                        <tr>
                            <th scope="row"><img src="assets/images/users/avatar-3.jpg" alt="image" class="img-fluid thumb-md rounded"></th>
                            <td>2</td>
                            <td>User</td>
                            <td>Milo</td>
                            <td>Clifton</td>
                            <td>Milo.clifton@outlook.com</td>
                            <td>0</td>

                        </tr>
                        <tr>
                            <th scope="row"><img src="assets/images/users/avatar-3.jpg" alt="image" class="img-fluid thumb-md rounded"></th>
                            <td>3</td>
                            <td>User</td>
                            <td>Milo</td>
                            <td>Clifton</td>
                            <td>Milo.clifton@outlook.com</td>
                            <td>0</td>

                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection