<template>
<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <v-layout wrap>
                    <div class="panel-header panel-header-lg row" style="width: 120% !important">
                        <div class="column">
                            <h3 class="text-center" style="color: #fff;">Shipments Chart</h3>
                            <my-shipment :height="255" :width="1200"></my-shipment>
                        </div>
                        <!-- <v-btn @click="ref">Refresh</v-btn> -->
                    </div>
                </v-layout>
                <div class="col-md-12">
                    <div class="card card-stats card-raised">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-primary">
                                                <v-icon color="green">local_shipping</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ Allshipments }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Shipments</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-success">
                                                <v-icon color="purple">account_circle</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ Allusers }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Users</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-info">
                                                <v-icon color="indigo">block</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ AllCanceled }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Cancelled</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-danger">
                                                <v-icon color="red">check_circle</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ AllScheduled }}</b></span></h3>
                                            <h6 class="stats-title"><strong> Scheduled</strong></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- </div> -->

                <v-divider></v-divider>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <my-schedule :height="255"></my-schedule>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <my-delivered :height="255"></my-delivered>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <my-cancled :height="255"></my-cancled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <v-divider></v-divider>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <my-schedule :height="255"></my-schedule>
                                </div>
                                <div class="progress-Ship">
                                    <img src="storage/country/ke.png" alt="">
                                    Kenya: {{ parseInt(countryC.Kenya/Allshipments*100) }} %
                                    <v-progress-linear color="secondary" height="2" :value="countryC.Kenya / Allshipments * 100"></v-progress-linear>
                                    <img src="storage/country/tz.png" alt="">
                                    Tanzania: {{ parseInt(countryC.Tanzania/Allshipments*100) }} %
                                    <v-progress-linear color="success" height="2" :value="countryC.Tanzania / Allshipments * 100"></v-progress-linear>
                                    <img src="storage/country/ug.png" alt="">
                                    Uganda: {{ parseInt(countryC.Uganda/Allshipments*100) }} %
                                    <v-progress-linear color="info" height="2" :value="countryC.Uganda / Allshipments * 100"></v-progress-linear>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="card card-chart">
                            <div class="card-body">
                                <div class="chart-area">
                                    <my-branch :height="255"></my-branch>
                                </div>
                                <div class="progress-Ship">
                                    <div v-for="branch in branchC" :key="branch.id">
                                        {{ branch.name }}: {{ parseInt(branch.count / Allshipments * 100) }} %
                                        <v-progress-linear color="indigo" height="2" :value="branch.count / Allshipments * 100"></v-progress-linear>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <v-btn @click="getBranchCount" flat color="primary">Count</v-btn> -->
            </v-flex>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
// import LineChart from './csv/LineChart'
import Scheduled from './charts/Scheduled'
import Shipment from './charts/Shipment'
import Delivered from './charts/Delivered'
import Cancled from './charts/Cancled'
import Branch from './charts/Branch'

export default {
    name: 'VueChartJS',
    components: {
        'my-shipment': Shipment,
        'my-schedule': Scheduled,
        'my-delivered': Delivered,
        'my-branch': Branch,
        'my-cancled': Cancled,
    },
    data() {
        return {
            label: [],
            data: [],
            Allusers: {},
            loader: false,
            Allshipments: {},
            AllScheduled: {},
            AlldelayedShipment: {},
            notCompleted: {},
            AllCanceled: {},
            AllderiveredShipment: {},
            AllapprovedShipment: {},
            notifications: [],
            countryC: [],
            branchC: [],
            colorCache: {}
        }
    },
    methods: {
        getCountCount() {
            axios.get('/getCountCount')
                .then((response) => {
                    this.countryC = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        getBranchCount() {
            axios.get('/getBranchCount')
                .then((response) => {
                    this.branchC = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        ref() {
            axios.get('/getChartData')
                .then((response) => {
                    // console.log(response);
                    eventBus.$emit('chartEvent', response.data);
                    this.label = response.data.lables
                    this.data = response.data.rows
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
    },
    mounted() {
        this.getCountCount()
        this.getBranchCount()
        this.loader = true
        axios.get('/getUsersCount')
            .then((response) => {
                this.Allusers = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/getShipmentsCount')
            .then((response) => {
                this.Allshipments = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/scheduledShipmentCount')
            .then((response) => {
                this.AllScheduled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        // Dashboard
        axios.get('/delayedShipmentCount')
            .then((response) => {
                this.AlldelayedShipment = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/getCanceledCount')
            .then((response) => {
                this.AllCanceled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/deriveredShipmentCount')
            .then((response) => {
                this.AllderiveredShipment = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/deriveredShipmentCount')
            .then((response) => {
                this.loader = false
                this.AllderiveredShipment = response.data
            })
            .catch((error) => {
                this.loader = false
                this.errors = error.response.data.errors
            })

    }
}
</script>

<style scoped>
.justify-center {
    margin-top: -100px !important;
}

.card-stats {
    margin-top: -10px;
    padding: 20px 0;
}

.statistics {
    background: #f0f0f0 !important;
}

.progress-Ship {
    margin-top: 100px !important;
}
.v-icon{
    font-size: 64px !important;
}
</style>
