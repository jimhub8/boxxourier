<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div v-show="!loader">
                    <v-layout wrap>
                        <v-flex sm6>
                            <v-tooltip bottom v-if="between.start >= 500">
                                <v-btn icon class="mx-0" @click="previous" slot="activator" style="background: hsla(122, 23%, 60%, 0.31);">
                                    <v-icon color="blue darken-2">chevron_left</v-icon>
                                </v-btn>
                                <span>Previous results</span>
                            </v-tooltip>
                            <v-tooltip bottom v-if="callCount > between.end">
                                <v-btn icon class="mx-0" @click="next" slot="activator" style="background: hsla(122, 23%, 60%, 0.31);">
                                    <v-icon color="blue darken-2">chevron_right</v-icon>
                                </v-btn>
                                <span>Next results</span>
                            </v-tooltip>
                            From {{ between.start }} to {{ between.end }}
                        </v-flex>
                    </v-layout> 
                    <v-card-title>
                        Logs
                        <v-tooltip right>
                            <v-btn icon slot="activator" class="mx-0" @click="getCalls">
                                <v-icon color="blue darken-2" small>refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
                        <!-- <v-btn color="primary" raised @click="getCalls">Calls</v-btn> -->
                        <v-flex xs4 sm3>
                            <v-select :items="Allusers" v-model="select" label="Select User" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
                        </v-flex>
                        <!-- <v-spacer></v-spacer> -->
                        <v-flex xs12 sm2 offset-sm1>
                            <v-text-field label="Start Date" v-model="form.start_date" color="blue darken-2" type="date" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm2>
                            <v-text-field label="End Date" v-model="form.end_date" color="blue darken-2" type="date" required></v-text-field>
                        </v-flex>
                        <v-flex xs4 sm1>
                            <v-btn flat color="info" @click="filReset">Reset</v-btn>
                        </v-flex>
                        <v-btn color="orange" flat @click="filter">Filter</v-btn>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllCalls" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.user_id }}</td>
                            <td class="text-xs-right">{{ props.item.user_name }}</td>
                            <td class="text-xs-right">{{ props.item.event }}</td>
                            <td class="text-xs-right">{{ props.item.shipment.airway_bill_no }}</td>
                            <td class="text-xs-right">{{ props.item.shipment.updated_at }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="details(props.item)" slot="activator">
                                        <v-icon color="info darken-2" small>visibility</v-icon>
                                    </v-btn>
                                    <span>Details</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>

                    <!-- <v-layout wrap>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <v-tooltip right>
                                <v-btn icon slot="activator" class="mx-0" @click="schedulepct">
                                    <v-icon color="blue darken-2" small>refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <div class="card card-chart">
                                <div class="card-body">
                                    <div class="chart-area">
                                    </div>
                                    <div class="progress-Ship">
                                        <div v-for="userC in AllSc" :key="userC.id">
                                            {{ userC.name }}: {{ userC.count }} %
                                            <v-progress-linear color="indigo" height="2" :value="userC.count"></v-progress-linear>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-layout> -->
                </div>
            </v-layout>

            <!-- </v-layou/t> -->
        </v-container>
    </v-content>
    <show></show>
</div>
</template>

<script>
import show from './Show'
export default {
    props: ['user'],
    components: {
        show
    },
    data() {
        return {
            search: '',
            callCount: null,
            form: {},
            AllSc: [],
            AllCalls: [],
            loader: false,
            editedItem: {},
            loading: false,
            select: [],
            Allusers: [],
            between: {
                start: 1,
                end: 500
            },
            headers: [{
                    text: 'User Id',
                    align: 'left',
                    value: 'user_id'
                },
                {
                    text: 'User Name',
                    align: 'left',
                    value: 'user_name'
                },
                {
                    text: 'Event',
                    value: 'event'
                },
                {
                    text: 'Waybill Number',
                    value: 'shipment.airway_bill_no'
                },
                {
                    text: 'Updated at',
                    value: 'updated_at'
                },
                {
                    text: 'Action',
                    sortable: false
                },
            ],
        }
    },
    methods: {
        schedulepct() {
            eventBus.$emit("progressEvent");
            // this.loader = true;
            axios
                .get("/allLogs")
                .then(response => {
                    eventBus.$emit("StoprogEvent");
                    this.AllSc = response.data;
                    this.loader = false;
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.loader = false;
                    this.errors = error.response.data.errors;
                });
        },
        getUsers() {
            this.loading = true
            axios.get('/getUsers')
                .then((response) => {
                    this.loading = false
                    this.Allusers = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        details(item) {
            eventBus.$emit('ShowShipEvent', item);
        },
        getCalls() {
            eventBus.$emit("progressEvent");
            axios.get('/calls')
                .then((response) => {
                    eventBus.$emit("StoprogEvent");
                    this.loader = false;
                    this.AllCalls = response.data
                })
                .catch((error) => {
                    eventBus.$emit("StoprogEvent");
                    this.loader = false;
                    this.errors = error.response.data.errors
                })
        },
        filter() {
            eventBus.$emit("progressEvent");
            axios
                .post("/Filterlogs", {
                    select: this.select,
                    no_btw: this.between,
                    form: this.form,
                })
                .then(response => {
                    eventBus.$emit("StoprogEvent");
                    this.AllCalls = response.data;
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.errors = error.response.data.errors;
                });
        },
        filReset() {
            this.between.start = 1
            this.between.end = 500
            this.select = []
            this.form = []
            this.getCalls()
        },

        next() {
            this.loading = true;
            this.between.start = parseInt(this.between.start) + 500;
            this.between.end = parseInt(this.between.end) + 500;
            this.filter()
        },
        previous() {
            this.loading = true;
            if (this.between.start >= 500) {
                this.between.start = parseInt(this.between.start) - 500;
                this.between.end = parseInt(this.between.end) - 500;
                this.filter()

            } else {
                return;
                this.loading = false;
            }
        },
    },
    mounted() {
        this.loader = true
        this.getUsers()
        this.schedulepct()
        this.getCalls()
        axios
            .get("/callcount")
            .then(response => {
                this.callCount = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can['view logs']) {
                next();
            } else {
                next('/unauthorized');
            }
        })
    }
}
</script>
