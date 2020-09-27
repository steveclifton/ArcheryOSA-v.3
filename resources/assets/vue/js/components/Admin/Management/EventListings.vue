<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Events</h4>
                        <p class="text-muted font-13 mb-4">
                            <code></code>
                        </p>

                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Start</th>
                                <th>Finish</th>
                                <th>Status</th>
                                <th>Entries</th>
                                <th>Visible</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="event in events">
                                <td>
                                    <router-link :to="{name: 'Admin-EventDetails', params: {eventid: event.eventurl}}" style="color: inherit">
                                        {{event.label}}
                                    </router-link>
                                </td>
                                <td>{{event.start}}</td>
                                <td>{{event.end}}</td>
                                <td>{{event.status}}</td>
                                <td>{{event.entries}}</td>
                                <td>{{event.visible ? "yes" : "no"}}</td>
                            </tr>
                            </tbody>
                        </table>

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
    </div>
</template>

<script>
    import 'datatables.net-bs4';

    export default {
        name: "EventListings",
        data() {
            return {
                events: []
            }
        },
        created() {

            axios.post('/admin/events/list')
            .then((response) => {

                this.events = response.data;

                setTimeout(() => {

                    $("#basic-datatable").DataTable({
                        language: {
                            paginate: {
                                previous: "<i class='mdi mdi-chevron-left'>",
                                next: "<i class='mdi mdi-chevron-right'>"
                            }
                        },
                        "order": [
                            [1, "desc" ]
                        ],
                        "pageLength": 10,
                            drawCallback: function() {
                                $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                            }
                    });

                }, 100);

            })
            .catch((response) => {
            // error
            });

        }
    }
</script>
