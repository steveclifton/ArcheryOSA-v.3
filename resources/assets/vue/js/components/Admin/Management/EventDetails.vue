<template>
    <!-- Start Content-->
    <div class="container-fluid" v-if="event">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a @click="$router.go(-1)" href="javascript:;">Event List</a></li>
                            <li class="breadcrumb-item active">{{ event.name }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ event.name }}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->


        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <h4 class="mt-0 font-16">Entries Total</h4>
                    <h2 class="text-primary my-3 text-center">
                        <span data-plugin="counterup">{{ getEntryCount }}</span>
                    </h2>
                    <p class="text-muted mb-0"></p>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <h4 class="mt-0 font-16">Entries Confirmed</h4>
                    <h2 class="text-primary my-3 text-center">
                        <span data-plugin="counterup">{{ getConfirmedEntryCount }}</span>
                    </h2>
                </div>
            </div>

            <div class="col-md-6 col-xl-3">
                <div class="card-box">
                    <h4 class="mt-0 font-16">Entries Pending</h4>
                    <h2 class="text-primary my-3 text-center">
                        <span data-plugin="counterup">{{ getPendingEntryCount }}</span>
                    </h2>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-3">Entries</h4>

                        <div class="table-responsive">
                                <table id="basic-datatable" class="table table-hover">

                                    <thead>
                                        <tr role="row">
                                            <th >Bib</th>
                                            <th >Name</th>
                                            <th >Status</th>
                                            <th >Date</th>
                                            <th >Note</th>
                                            <th >Send Mail</th>
                                            <th >Approve</th>
                                            <th >Paid</th>
                                            <th >Confirmation </th>
                                            <th >Remove</th>
                                        </tr>
                                    </thead>

                                    <event-entry-detail-list :entries="entries"></event-entry-detail-list>

                                </table>
                            </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right d-none d-md-inline-block">
                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-xs btn-light">Today</button>
                                <button type="button" class="btn btn-xs btn-secondary">Weekly</button>
                                <button type="button" class="btn btn-xs btn-light">Monthly</button>
                            </div>
                        </div>
                        <h4 class="header-title">Revenue</h4>
                        <div class="row mt-4 text-center">
                            <div class="col-4">
                                <p class="text-muted font-15 mb-1 text-truncate">Target</p>
                                <h4><i class="fe-arrow-down text-danger mr-1"></i>$7.8k</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted font-15 mb-1 text-truncate">Last week</p>
                                <h4><i class="fe-arrow-up text-success mr-1"></i>$1.4k</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted font-15 mb-1 text-truncate">Last Month</p>
                                <h4><i class="fe-arrow-down text-danger mr-1"></i>$15k</h4>
                            </div>
                        </div>
                        <div class="mt-3 chartjs-chart">
                            <canvas id="revenue-chart" data-colors="#5671f0,#f1556c" height="300"></canvas>
                        </div>
                    </div> <!-- end card-body-->
                </div> <!-- end card-->
            </div> <!-- end col -->


            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="float-right d-none d-md-inline-block">
                            <div class="btn-group mb-2">
                                <button type="button" class="btn btn-xs btn-secondary">Today</button>
                                <button type="button" class="btn btn-xs btn-light">Weekly</button>
                                <button type="button" class="btn btn-xs btn-light">Monthly</button>
                            </div>
                        </div>
                        <h4 class="header-title">Projections Vs Actuals</h4>
                        <div class="row mt-4 text-center">
                            <div class="col-4">
                                <p class="text-muted font-15 mb-1 text-truncate">Target</p>
                                <h4><i class="fe-arrow-down text-danger mr-1"></i>$3.8k</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted font-15 mb-1 text-truncate">Last week</p>
                                <h4><i class="fe-arrow-up text-success mr-1"></i>$1.1k</h4>
                            </div>
                            <div class="col-4">
                                <p class="text-muted font-15 mb-1 text-truncate">Last Month</p>
                                <h4><i class="fe-arrow-down text-danger mr-1"></i>$25k</h4>
                            </div>
                        </div>
                        <div class="mt-3 chartjs-chart">
                            <canvas id="projections-actuals-chart" data-colors="#11ca6d,#e3eaef" height="300"></canvas>
                        </div>
                    </div>
                </div> <!-- end card-->
            </div> <!-- end col -->
        </div>

    </div>
</template>

<script>
    import 'datatables.net-bs4';
    import EventEntryDetailList from "./EventEntryDetailList";
    require('simplebar');

    export default {
        name: "EventDetails",
        components: {
            EventEntryDetailList
        },
        data() {
            return {
                event: null,
                entries: []
            }
        },
        computed: {
            confirmedEntries: function() {
                return this.event.entries.filter(entry => entry.entrystatusid == 2)
            },
            pendingEntries: function() {
                return this.event.entries.filter(entry => entry.entrystatusid == 1)
            },
            getEntryCount: function() {
                return this.event.entries.length;
            },
            getConfirmedEntryCount: function() {
                return this.confirmedEntries.length;
            },
            getPendingEntryCount: function() {
                return this.pendingEntries.length;
            }
        },
        created() {

            axios.post('/new-admin/event/details', {
                eventUrl : this.$route.params.eventUrl
            })
            .then((response) => {
                this.event = response.data.event;
                this.entries = this.event.entries;

                setTimeout(() => {

                    $("#basic-datatable").DataTable({
                        'language': {
                            paginate: {
                                previous: "<i class='mdi mdi-chevron-left'>",
                                next: "<i class='mdi mdi-chevron-right'>"
                            }
                        },
                        'columnDefs': [
                            {
                                'type': 'date',
                                'targets': [3]
                            }
                        ],
                        "order": [
                            [2, 'DESC'],
                            [3, 'ASC']
                        ],
                        "pageLength": 10,
                        drawCallback: function() {
                            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
                        }
                    });

                }, 200);
            })
            .catch((response) => {

            });
        }
    }
</script>
