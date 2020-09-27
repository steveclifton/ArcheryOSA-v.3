<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Dashboard</h4>
                        <p class="sub-header">

                        </p>
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                graphs
                            </div>

                            <div class="col-lg-6">
                                charts
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Home",
        data: () => {
            return {
                showspinner: true
            }
        },
        created() {
            console.log(this.showspinner);
        }
    }
</script>

<style scoped>

</style>