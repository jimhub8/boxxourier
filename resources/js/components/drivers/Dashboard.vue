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
                
                <div class="col-md-12" style="margin-top: 40px;">
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
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-danger">
                                                <v-icon color="danger">map</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ AllScheduled }}</b></span></h3>
                                            <h6 class="stats-title"><strong> Scheduled</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-primary">
                                                <v-icon color="indigo">check_circle</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ AllDelivered }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Delivered</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-2">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-success">
                                                <v-icon color="info">block</v-icon>
                                                <!-- <v-icon color="orange">cloud</v-icon> -->
                                            </div>
                                            <h3 class="info-title"><span><b>{{ getPedding }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Pending</strong></h6>
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

export default {
    name: 'VueChartJS',
    components: {
        'my-shipment': Shipment,
        'my-schedule': Scheduled,
        'my-delivered': Delivered,
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
            countryC: {},
            branchC: {},
            AllPending: null,
            AllDelivered: null
        }
    },
    methods: {
        getCountCount() {
            axios.get('/getCountryhipments')
                .then((response) => {
                    this.countryC = response.data
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
    computed: {
        getPedding() {
            return (parseInt(this.Allshipments) - parseInt(this.AllScheduled) - parseInt(this.AllDelivered))
        }
    },
    mounted() {
        this.loader = true
        axios.get('/driverDelivered')
            .then((response) => {
                this.AllDelivered = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
        // axios.get('/countPending')
        //     .then((response) => {
        //         this.AllPending = response.data
        //     })
        //     .catch((error) => {
        //         this.errors = error.response.data.errors
        //     })

        axios.get('/driverCount')
            .then((response) => {
                this.Allshipments = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/driverScheduled')
            .then((response) => {
                this.AllScheduled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        // Dashboard
        // axios.get('/delayedCount')
        //     .then((response) => {
        //         this.AlldelayedShipment = response.data
        //     })
        //     .catch((error) => {
        //         this.errors = error.response.data.errors
        //     })

        axios.get('/driverCanceled')
            .then((response) => {
                this.AllCanceled = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })

        axios.get('/driverDelivered')
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

/* .statistics {
    background: #f0f0f0 !important;
} */

.progress-Ship {
    margin-top: 100px !important;
}

.v-icon {
    font-size: 64px !important;
}

.v-icon {
    box-shadow: 0 9px 30px -6px rgba(255, 54, 54, .5);
    padding: 5px;
    border-radius: 50%;
}
.info-title{
    margin-top: 20px;
}
</style>
