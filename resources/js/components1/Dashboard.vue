<template>
<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-flex sm12>
                <div v-for="role in user.roles" :key="role.id" v-if="role.name === 'Admin'" style="width: 100%; margin-top: 70px;">
                    <v-select :items="AllCount" v-model="select" hint="COUNTRY" label="Filter By Country" single-line item-text="country_name" item-value="id" return-object persistent-hint @change="countryDash()"></v-select>
                </div>
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
                                            <h3 class="info-title"><span><b>{{ country_count }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Shipments</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
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
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-info">
                                                <v-icon color="indigo">map</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ countryC.length }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Countries</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-2">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-danger">
                                                <v-icon color="red">call_split</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ branchC.length }}</b></span></h3>
                                            <h6 class="stats-title"><strong> Branches</strong></h6>
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

                <div class="col-md-12" style="margin-top: 40px;">
                    <div class="card card-stats card-raised">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-danger">
                                                <v-icon color="danger">local_shipping</v-icon>
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
                                                <v-icon color="pink">check_circle</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ AllDelivered }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Delivered</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-3">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-success">
                                                <v-icon color="orange">cloud</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ AllPending }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Pending</strong></h6>
                                        </div>
                                    </div>
                                </div>
                                <v-divider vertical></v-divider>
                                <div class="col-md-2">
                                    <div class="statistics">
                                        <div class="">
                                            <div class="icon icon-info">
                                                <v-icon color="info">block</v-icon>
                                            </div>
                                            <h3 class="info-title"><span><b>{{ AllCanceled }}</b></span></h3>
                                            <h6 class="stats-title"><strong>Cancelled</strong></h6>
                                        </div>
                                    </div>
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
                                    <my-country :height="255"></my-country>
                                </div>
                                <!-- <div class="progress-Ship">
                                    <img src="storage/country/ke.png" alt="">
                                    Kenya: {{ parseInt(countryC.Kenya/Allshipments*100) }} %
                                    <v-progress-linear color="secondary" height="2" :value="countryC.Kenya / Allshipments * 100"></v-progress-linear>
                                    <img src="storage/country/tz.png" alt="">
                                    Tanzania: {{ parseInt(countryC.Tanzania/Allshipments*100) }} %
                                    <v-progress-linear color="success" height="2" :value="countryC.Tanzania / Allshipments * 100"></v-progress-linear>
                                    <img src="storage/country/ug.png" alt="">
                                    Uganda: {{ parseInt(countryC.Uganda/Allshipments*100) }} %
                                    <v-progress-linear color="info" height="2" :value="countryC.Uganda / Allshipments * 100"></v-progress-linear>
                                </div> -->
                                <div class="progress-Ship">
                                    <div v-for="country in countryC" :key="country.id">
                                        {{ country.name }}: {{ parseInt(country.count / Allshipments * 100) }} %
                                        <v-progress-linear color="red" height="2" :value="country.count / Allshipments * 100"></v-progress-linear>
                                    </div>
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
import Country from './charts/Country'

export default {
    name: 'VueChartJS',
    components: {
        'my-shipment': Shipment,
        'my-schedule': Scheduled,
        'my-delivered': Delivered,
        'my-branch': Branch,
        'my-cancled': Cancled,
        'my-country': Country,
    },
    props: ["user"],
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
            colorCache: {},
            AllCount: [],
            select: [],
            AllPending: null,
            AllDelivered: null,
            country_count: '',
            countryFilter: {
                id: null
            },
        }
    },
    methods: {
        countryDash() {
            this.countryFilter = this.select
            eventBus.$emit('DashchartEvent', this.countryFilter);

            // this.getCountCount()
            this.getBranchCount()
            this.loader = true
            this.getCountShip()
            this.getUsersCount()
            this.countDelivered()
            this.countPending()
            // this.getShipmentsCount()
            this.scheduledShipmentCount()
            //         // Dashboard
            this.delayedShipmentCount()
            this.getCanceledCount()
            this.deriveredShipmentCount()
            this.deriveredShipmentCount()
            this.getCountryhipments()
            this.getCountry()

        },

        getCountShip() {
            axios.post('/countCountShipments', this.countryFilter)
                .then((response) => {
                    this.country_count = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        getCountCount() {
            axios.post('/getCountryhipments', this.countryFilter)
                .then((response) => {
                    this.countryC = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        getBranchCount() {
            axios.post('/getBranchCount', this.countryFilter)
                .then((response) => {
                    this.branchC = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        ref() {
            axios.post('/getChartData', this.countryFilter)
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

        getUsersCount() {
            axios.post('/getUsersCount', this.countryFilter)
                .then((response) => {
                    this.Allusers = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
        countDelivered() {
            eventBus.$emit("progressEvent");
            axios.post('/countDelivered', this.countryFilter)
                .then((response) => {
                    eventBus.$emit("StoprogEvent");
                    this.AllDelivered = response.data
                })
                .catch((error) => {
                    eventBus.$emit("StoprogEvent");
                    this.errors = error.response.data.errors
                })
        },
        countPending() {
            axios.post('/countPending', this.countryFilter)
                .then((response) => {
                    this.AllPending = response.data
                }) 
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        getShipmentsCount() {
            axios.post('/getDashCount', this.countryFilter)
                .then((response) => {
                    this.Allshipments = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        scheduledShipmentCount() {
            axios.post('/scheduledShipmentCount', this.countryFilter)
                .then((response) => {
                    this.AllScheduled = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        // Dashboard
        delayedShipmentCount() {
            axios.post('/delayedShipmentCount', this.countryFilter)
                .then((response) => {
                    this.AlldelayedShipment = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        getCanceledCount() {
            axios.post('/getCanceledCount', this.countryFilter)
                .then((response) => {
                    this.AllCanceled = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        deriveredShipmentCount() {
            axios.post('/deriveredShipmentCount', this.countryFilter)
                .then((response) => {
                    this.AllderiveredShipment = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        deriveredShipmentCount() {
            axios.post('/deriveredShipmentCount', this.countryFilter)
                .then((response) => {
                    this.loader = false
                    this.AllderiveredShipment = response.data
                })
                .catch((error) => {
                    this.loader = false
                    this.errors = error.response.data.errors
                })
        },

        getCountryhipments() {
            axios.post('/getCountryhipments', this.countryFilter)
                .then((response) => {
                    this.countryC = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },

        getCountry() {
            axios.post('/getCountry', this.countryFilter)
                .then((response) => {
                    this.AllCount = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        },
    },
    mounted() {
        this.loader = true
        this.getCountShip()
        this.getCountCount()
        this.getBranchCount()
        this.getUsersCount()
        this.countDelivered()
        this.countPending()
        this.getShipmentsCount()
        this.scheduledShipmentCount()
        // Dashboard
        this.delayedShipmentCount()
        this.getCanceledCount()
        this.deriveredShipmentCount()
        this.deriveredShipmentCount()
        this.getCountryhipments()
        this.getCountry()
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can['view users']) {
                next();
            } else {
                next('/unauthorized');
            }
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

.info-title {
    margin-top: 20px;
}
</style>
