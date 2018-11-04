@extends('template.default')

@section ('title'){{ucwords($event->label)}} Entries @endsection

@section('content')
    <link href="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.css')}}" rel="stylesheet">
    <link href="{{URL::asset('/plugins/datatables/select.bootstrap4.min.css')}}" rel="stylesheet">


    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage">Events</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            <i class="ion-arrow-right-c"></i>
            <a href="javascript:;">Event Admins</a>
        </h4>
    </div>

    @include('template.alerts')

    <div class="alert alert-success" style="display: none"></div>
    <div class="alert alert-warning" style="display: none"></div>

    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">

                    <div class="form-group row">
                        <div class="col-3">
                            <input type="email" id="example-email"
                                   name="adminemail" class="form-control"
                                   placeholder="Add Admin (Email)">
                        </div>
                        <a href="javascript:;" role="button" class="btn btn-inverse waves-effect waves-light addAdmin">Add</a>
                    </div>
                <p>
                    For events that require scoring on behalf of a school/club<br>
                    - Click the icon on the users row to select those they can score for
                </p>

                <meta name="csrf-token" content="{{ csrf_token() }}">


                <table id="datatable-buttons" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th style="text-align:center">Can Edit</th>
                            <th style="text-align:center">Can Score</th>
                            <th style="text-align:center">Status</th>
                            <th style="text-align:center">Clubs</th>
                            <th style="text-align:center">Schools</th>



                        </tr>
                    </thead>

                    <tbody>
                        @foreach($eventadmins as $admin)
                        <tr>
                            <td>
                                {{ucwords($admin->user->firstname . ' ' . $admin->user->lastname)}}
                            </td>
                            <td align="center">
                                <input class="canedit" type="checkbox" data-userid="{{$admin->userid}}"
                                        {!! $admin->canedit ? 'Checked' : '' !!}
                                        {!! Auth::id() == $admin->userid ? 'disabled' : '' !!}
                                        >
                            </td>
                            <td align="center">
                                <input class="canscore" type="checkbox" data-userid="{{$admin->userid}}"
                                        {!! $admin->canscore ? 'Checked' : '' !!}
                                        {!! Auth::id() == $admin->userid ? 'disabled' : '' !!}
                                        >
                            </td>
                            <td align="center">
                                @if(Auth::id() != $admin->userid)
                                    <a href="javascript:;" class="">
                                        <i class="md md-delete deleteadmin" data-userid="{{$admin->userid}}"></i>
                                    </a>
                                @endif
                            </td>

                            <td align="center">
                                <a href="/events/manage/eventadmins/clubs/{{$event->eventurl}}/{{$admin->eventadminid}}">
                                    <i class="md md-school"></i>
                                </a>
                            </td>
                            <td align="center">
                                <a href="/events/manage/eventadmins/schools/{{$event->eventurl}}/{{$admin->eventadminid}}">
                                    <i class="md md-home" data-userid="{{$admin->userid}}"></i>
                                </a>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).on('change', '.canscore, .canedit', function () {

            var type = $(this).attr("class");
            var userid = $(this).attr('data-userid');

            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/ajax/events/manage/{{$event->eventurl}}/updateadmin/",
                data: {
                    type: type,
                    userid: userid
                }
            }).done(function( json ) {
                if (json.success) {

                    $('.alert-success').html('Update successful').show();

                    setTimeout(function (e) {
                        $('.alert-success').html('').hide();
                    }, 1000);

                }

            });

        });

        $(document).on('click', '.deleteadmin', function () {

            var confirmed = confirm("Are you sure you want to remove this admin?");

            if (!confirmed) {
                return;
            }

            var userid = $(this).attr('data-userid');
            var self = $(this);

            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/ajax/events/manage/{{$event->eventurl}}/deleteadmin/",
                data: {
                    userid: userid
                }
            }).done(function( json ) {
                if (json.success) {
                    self.closest('tr').remove();
                    $('.alert-success').html('Admin Removed').show();

                    setTimeout(function (e) {
                        $('.alert-success').html('').hide();
                    }, 1500);
                }

            });

        });

        $(document).on('click', '.addAdmin', function(e) {
            $('.alert-success, .alert-danger').hide();


            var email = $('input[name="adminemail"]').val();

            if (email === '') {
                alert('Please enter an email address');
                return;
            }

            $.ajax({
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "/ajax/events/manage/{{$event->eventurl}}/addadmin/",
                data: {
                    email: email
                }
            }).done(function( json ) {
                if (json.success) {
                    $('.alert-success').html('User added, please wait..').show();

                    setTimeout(function (e) {
                        location.reload();
                    }, 1000);
                }
                else {
                    $('.alert-warning').html(json.data).show();

                    setTimeout(function (e) {
                        $('.alert-success').html('').hide();
                    }, 1000);
                }

            });

        });

        $(document).ready(function () {
            //Buttons examples
            var table = $('#datatable-buttons').DataTable({
                lengthChange: false,
                pageLength:30,
                bFilter:false
            });

            table.buttons().container()
                .appendTo('#datatable-buttons_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script src="{{URL::asset('/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.keyTable.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/dataTables.select.min.js')}}"></script>

    <script src="{{URL::asset('/plugins/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/jszip.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/pdfmake.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/vfs_fonts.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{URL::asset('/plugins/datatables/buttons.print.min.js')}}"></script>

@endsection