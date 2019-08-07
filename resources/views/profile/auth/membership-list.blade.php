@extends('template.default')

@section ('title')Memberships @endsection

@section('content')


    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <a href="/profile">Profile</a> <i class="ion-arrow-right-c"></i>
                    <a href="javascript:;">Memberships</a>
                </h4>


            </div>
        </div>
    </div>

    <!-- Section-Title -->
    <div class="row">
        <div class="col-sm-12">


            <div class="container" style="margin-left: 0; padding-left: 0;">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group row" style="display:block; margin-left: 0; margin-right: 1em; text-align: left;">
                            <div class="col-md-12">
                                <a role="button" href="/profile/membership/create" class="btn btn-inverse waves-effect waves-light ">
                                    <span class="btn-label">
                                        <i class="fa fa-plus"></i>
                                    </span>Add
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card-box">
                @include('template.alerts')
                <p class="text-muted font-14 m-b-30">
                    Memberships for the various organisations<br>
                </p>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>User</th>
                            <th>Organisation</th>
                            <th>Membership Number</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($memberships as $membership)
                                <tr>
                                    <th scope="row">
                                        <a href="/profile/membership/update/{{$membership->membershipid}}">
                                            {{ ucwords($membership->username) }}
                                        </a>
                                    </th>
                                    <th>
                                        <a href="/profile/membership/update/{{$membership->membershipid}}">
                                            {{$membership->organisationname }}
                                        </a>
                                    </th>
                                    <th>{{$membership->membership}}</th>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        $(function() {
            $(document).on('click', '.md-delete', function () {
               var confirmed = confirm('Are you sure you want to delete this relationship?');
               var userid    = $(this).attr('data-userid');
                var _this = $(this);
               if (confirmed && userid != '') {

                   $.ajax({
                       method: "POST",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       url: '/profile/relationships/remove',
                       data: {
                           userid:userid
                       }
                   }).done(function( json ) {
                       if (json.success) {
                           $('.ajaxAlert').addClass('alert-success').html(json.message).removeClass('hidden');
                           setTimeout(function () {
                               $('.ajaxAlert').addClass('hidden');
                           }, 2000);
                       }
                       else {
                           $('.ajaxAlert').addClass('alert-danger').html(json.message).removeClass('hidden');
                       }

                       _this.parents('tr').remove();
                   });
               }
            });
        });

    </script>
@endsection