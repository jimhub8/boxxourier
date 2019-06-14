<template>
<v-content>
    <!-- <div v-show="loader" style="text-align: center">
        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
    </div> -->
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-snackbar v-model="snackbar" absolute bottom left dark :color="color">
                <span>{{message}}</span>
                <v-icon dark>check_circle</v-icon>
            </v-snackbar>
            <div class="hide-overflow" style="width: 100% !important;">
                <v-toolbar absolute color="indigo" dark scroll-off-screen scroll-target="#scrolling-techniques">
                    <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->
                    <v-toolbar-title>Filter</v-toolbar-title>
                    <v-divider vertical></v-divider>

                    <v-flex xs4 sm2 offset-sm1>
                        <v-select :items="AllRiders" v-model="selectRider" label="Filter By Assigned" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
                    </v-flex>
                    <!-- <v-spacer></v-spacer> -->
                    <v-flex xs12 sm2 offset-sm1>
                        <v-text-field v-model="form.start_date" color="blue darken-2" type="date" label="Start Date" required></v-text-field>
                    </v-flex>
                    <v-flex xs12 sm2 offset-sm1>
                        <v-text-field v-model="form.end_date" color="blue darken-2" type="date" label="End Date" required></v-text-field>
                    </v-flex>
                    <v-btn flat @click="filterR" :disabled="loading" :loading="loading">Filter</v-btn>
                </v-toolbar>
                <div id="scrolling-techniques" class="scroll-y" style="max-height: 990px;">
                    <v-container>
                        <v-toolbar dark color="primary" v-if="shipFilter.length > 0">
                            <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->
                            <v-toolbar-title class="white--text">Orders</v-toolbar-title>
                            <v-spacer></v-spacer>
                            <v-tooltip bottom>
                                <v-btn slot="activator" color="info darken-2" class="mx-0">{{ shipFilter.length }}
                                    <v-icon color="white darken-2" small>check_circle</v-icon>
                                </v-btn>
                                <span>Orders</span>
                            </v-tooltip>
                            <v-divider vertical></v-divider>
                            <v-divider vertical></v-divider>
                            <v-divider vertical></v-divider>
                            <v-tooltip bottom>
                                <v-btn slot="activator" color="green darken-2" class="mx-0">{{ AllDelShip }}
                                    <v-icon color="white darken-2" small>check_circle</v-icon>
                                </v-btn>
                                <span>Delivered</span>
                            </v-tooltip>
                            <v-divider vertical></v-divider>
                            <v-divider vertical></v-divider>
                            <v-divider vertical></v-divider>
                            <v-tooltip bottom>
                                <v-btn slot="activator" color="brown darken-2" class="mx-0">{{ AllPe }}
                                    <v-icon color="white darken-2" small>block</v-icon>
                                </v-btn>
                                <span>Pending</span>
                            </v-tooltip>
                        </v-toolbar>
                        <v-card v-if="shipFilter.length > 0" style="margin-top: 60px;">
                            <v-card-title>
                                <download-excel :data="shipFilter" :fields="json_fields">
                                    Export
                                    <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                                </download-excel>
                                    <!-- <v-tooltip right>
                                        <v-btn icon slot="activator" class="mx-0" @click="getShipBranch">
                                            <v-icon color="blue darken-2" small>refresh</v-icon>
                                        </v-btn>
                                        <span>Refresh</span>
                                    </v-tooltip> -->
                                    <v-spacer></v-spacer>
                                    <!-- <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field> -->
                            </v-card-title>
                            <v-data-table :headers="headers" :items="shipFilter" select-all class="elevation-1" v-model="selected" :loading="loading">
                                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                                <template slot="items" slot-scope="props">
                                    <td>
                                        <v-checkbox v-model="props.selected" primary></v-checkbox>
                                    </td>
                                    <td>{{ props.item.bar_code }}</td>
                                    <td class="text-xs-right">{{ props.item.cod_amount }}</td>
                                    <td class="text-xs-right">{{ props.item.client_name }}</td>
                                    <td class="text-xs-right">{{ props.item.client_address }}</td>
                                    <td class="text-xs-right">{{ props.item.client_phone }}</td>
                                    <td class="text-xs-right" style="background: 'blue'" v-if="props.item.status === 'Returned'">{{ props.item.status }}</td>
                                    <td class="text-xs-right" v-else>{{ props.item.status }}</td>
                                    <td class="text-xs-right">{{ props.item.derivery_date }}</td>
                                    <td class="text-xs-right">{{ props.item.speciial_instruction }}</td>
                                </template>
                                <!-- <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert> -->
                            </v-data-table>
                            <v-btn raised color="primary" @click="UpdateShipmentStatus">Update Status</v-btn>
                        </v-card>
                    </v-container>
                </div>
            </div>
        </v-layout>
    </v-container>
    <UpdateShipmentStatus :UpdateShipmentStatus="UpdateShipmentModel" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected"></UpdateShipmentStatus>
</v-content>
</template>

<script>
import UpdateShipmentStatus from "../shipments/UpdateShipmentStatus";
export default {
    components: {
        UpdateShipmentStatus
    },
    data() {
        return {
            selected: [],
            AllRiders: [],
            errors: {},
            shipFilter: [],
            selectRider: {
                name: 'All',
            },
            form: {
                start_date: '',
                end_date: '',
            },
            editedItem: {},
            AllDelShip: null,
            AllPe: null,
            loading: false,
            loader: false,
            snackbar: false,
            UpdateShipmentModel: false,
            timeout: 5000,
            message: "Success",
            color: "black",
            search: '',
            headers: [{
                    text: 'Waybill Number',
                    align: 'left',
                    value: 'bar_code'
                },
                {
                    text: 'Cod Amount',
                    value: 'cod_amount'
                },
                {
                    text: 'Client Name',
                    value: 'client_name'
                },
                {
                    text: 'Client Address',
                    value: 'client_address'
                },
                {
                    text: 'Client Phone',
                    value: 'client_phone'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Delivery date',
                    value: 'derivery_date'
                },
                {
                    text: 'Special Instructions',
                    value: 'speciial_instruction'
                },
            ],

            json_fields: {
                'Order Id': 'order_id',
                'Sender Name': 'sender_name',
                'Sender Email': 'sender_email',
                'Sender Phone': 'sender_phone',
                'Sender City': 'sender_city',
                'Sender Address': 'sender_address',
                'Driver': 'driver',
                'Client Name': 'client_name',
                'Client Email': 'client_email',
                'Client Phone': 'client_phone',
                'Client City': 'client_city',
                'Client Address': 'client_address',
                'Derivery Status': 'status',
                'From': 'sender_address',
                'To': 'client_address',
                'Derivery Date': 'derivery_date',
                'Derivery Time': 'derivery_time',
                'Quantity': 'amount_ordered',
                'COD Amount': 'cod_amount',
                'Booking Date': 'booking_date',
                'Special Instructions': 'speciial_instruction'
            },
        }
    },
    methods: {
        filterR() {
            this.loading = true
            axios.post('/filterR', {
                    selectRider: this.$data.selectRider,
                    form: this.$data.form
                }).then((response) => {
                    this.loading = false
                    this.shipFilter = response.data
                    this.getDeriveredS()
                    this.getNotDeriveredS()
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },

        UpdateShipmentStatus(item) {
            if (this.selected.length < 1) {
                this.message = "at least one shipment is need";
                this.color = "red";
                this.snackbar = true;
            } else {
                this.UpdateShipmentModel = true;
            }
        },
        getDeriveredS() {
            axios
                .post("/getDelScan", {
                    selectRider: this.$data.selectRider,
                    form: this.$data.form
                })
                .then(response => {
                    this.AllDelShip = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        getNotDeriveredS() {
            axios
                .post("/getNotDelScan", {
                    selectRider: this.$data.selectRider,
                    form: this.$data.form
                })
                .then(response => {
                    this.AllPe = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        showAlert() {
            this.message = 'Success';
            this.snackbar = true;
            this.color = 'black';
        },
        close() {
            this.UpdateShipmentModel = false;
        },
    },

  created() {
    eventBus.$on("selectClear", data => {
        this.snackbar = true
        this.message = 'success'
        this.color = 'black'
        this.selected = [];
        this.filterR()
    });
  },
    mounted() {
        this.loader = true
        axios.get('/getDrivers')
            .then((response) => {
                this.loader = false
                this.AllRiders = response.data
            })
            .catch((error) => {
                this.loader = false
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if(error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
            })
    }
}
</script>
