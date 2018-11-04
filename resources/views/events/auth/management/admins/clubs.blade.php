@extends('template.default')

@section ('title')Admin Clubs @endsection

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
                  action="/events/manage/eventadmins/clubs/add/{{$event->eventurl}}"
                  role="form">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                <input name="eventid" type="hidden" value="{{$event->eventid}}">
                <input name="eventadminid" type="hidden" value="{{$eventadmin->eventadminid}}">


                <div class="form-group row">
                    <label class="col-sm-12 col-md-3 col-form-label">Divisions*</label>
                    <div class="col-md-9">
                        <div class="">
                            <div class="card-box">
                                <h4 class="text-dark header-title m-t-0 m-b-30">Select the clubs this user can score for.</h4>
                                <div id="treeClubs">
                                    @foreach($clubs as $club)
                                        <ul>
                                            <li data-jstree='{"icon": "ti-angle-right",
                                            "selected":"{{ in_array($club->clubid, $clubids)}}"}'
                                                data-clubid="{{$club->clubid}}">{{ucwords($club->label)}} </li>
                                        </ul>
                                    @endforeach
                                </div>
                            </div>
                        </div><!-- end col -->
                    </div>
                </div>
                <input name="clubids" type="hidden" id="clubids" value="" />


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

            $('#treeClubs').jstree(jsTreeObj);

            $(document).on('submit', '.treeForm', function(e) {
                e.preventDefault();

                // Competitions and rounds
                var selectedElmsIds = $('#treeClubs').jstree("get_selected", true);
                console.log(selectedElmsIds);
                var checkedClubs = [];

                $.each(selectedElmsIds, function() {
                    if (this.data.clubid != '') {
                        checkedClubs.push(this.data.clubid);
                    }
                });

                $('#clubids').val(checkedClubs.join(","));

                this.submit();
            });

        });
    </script>


@endsection