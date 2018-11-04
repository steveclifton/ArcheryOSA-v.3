@extends('template.default')

@section ('title')Admin Schools @endsection

@section('content')

    <div class="page-title-box">
        <h4 class="page-title">
            <a href="/events/manage">Events</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/{{$event->eventurl}}">{{ucwords($event->label)}}</a>
            <i class="ion-arrow-right-c"></i>
            <a href="/events/manage/eventadmins/{{$event->eventurl}}">Event Admins</a>
        </h4>
    </div>


    <div class="col-md-8 offset-md-2">
        <div class="card-box">

            @include('template.alerts')

            <form class="form-horizontal myForms treeForm"
                  method="POST"
                  action="/events/manage/eventadmins/schools/add/{{$event->eventurl}}"
                  role="form">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input name="eventid" type="hidden" value="{{$event->eventid}}">
                <input name="eventadminid" type="hidden" value="{{$eventadmin->eventadminid}}">


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Schools</label>
                    <div class="col-md-9">
                        <div class="">
                            <div class="card-box">
                                <h4 class="text-dark header-title m-t-0 m-b-30">Select the schools this user can score for.</h4>
                                <div id="treeSchools">
                                    @foreach($schools as $school)
                                        <ul>
                                            <li data-jstree='{"icon": "ti-angle-right",
                                            "selected":"{{ in_array($school->schoolid, $schoolids)}}"}'
                                                data-schoolid="{{$school->schoolid}}">{{ucwords($school->label)}} </li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div>
                </div>
                <input name="schoolids" type="hidden" id="schoolids" value="" />


                <div class="form-group mb-0 justify-content-start row">
                    <div class="col-sm-12 col-md-3 col-form-label"></div>
                    <div class="col-3">
                        <button type="submit" class="myButton btn btn-inverse btn-info waves-effect waves-light">Save</button>
                    </div>

                </div>

            </form>
        </div>
    </div>



    <script>

        $(function () {

            var jsTreeObj = {
                'core': {
                    'check_callback': true,
                    'themes': {
                        'responsive': false
                    }
                },
                'types': {
                    'default': {
                        'icon': 'fa fa-folder'
                    },
                    'file': {
                        'icon': 'fa fa-file'
                    }
                },
                'plugins': ['types', 'checkbox']
            };

            $('#treeSchools').jstree(jsTreeObj);

            $(document).on('submit', '.treeForm', function(e) {
                e.preventDefault();

                // Competitions and rounds
                var selectedElmsIds = $('#treeSchools').jstree("get_selected", true);
                var checkedSchools = [];

                $.each(selectedElmsIds, function() {
                    if (this.data.schoolid != '') {
                        checkedSchools.push(this.data.schoolid);
                    }
                });

                $('#schoolids').val(checkedSchools.join(","));

                this.submit();
            });

        });
    </script>


@endsection