<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <!-- Data table -->
            <div style="width: 100%;">
                <div v-show="loader" style="text-align: center">
                    <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                </div>
                <div v-show="!loader">
                    <v-toolbar dark color="primary">
                        <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->
                        <v-toolbar-title class="white--text">Orders</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-tooltip bottom>
                            <v-btn slot="activator" color="info darken-2" class="mx-0">{{ AllShip }}
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
                        <v-tooltip bottom>
                            <v-btn slot="activator" color="red darken-2" class="mx-0">{{ returned }}
                                <v-icon color="white darken-2" small>arrow_back</v-icon>
                            </v-btn>
                            <span>Returned</span>
                        </v-tooltip>
                    </v-toolbar>
                    <!-- <v-btn color="primary" flat @click="openRow">Filter Rows</v-btn> -->
                    <!-- <v-spacer></v-spacer> -->
                    <!-- <v-flex sm6>
                        <v-tooltip bottom>
                            <v-btn icon class="mx-0" @click="next" slot="activator" style="background: hsla(122, 23%, 60%, 0.31);">
                                <v-icon color="blue darken-2">chevron_right</v-icon>
                            </v-btn>
                            <span>Next results</span>
                        </v-tooltip>
                        From {{ between.start }} to {{ between.end }}
                    </v-flex> -->
                    <v-card style="background: rgba(5, 117, 230, 0.16);">
                        <v-layout wrap>
                            <v-flex xs4 sm2>
                                <v-select :items="AllBranches" v-model="select" :hint="`${select.branch_name}, ${select.id}`" label="Filter By Branch" single-line item-text="branch_name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs4 sm2 offset-sm1>
                                <v-select :items="statuses" v-model="selectItem" hint="Select Status" label="Filter By Status" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs4 sm2 offset-sm1>
                                <v-select :items="items" v-model="selectAss" label="Filter By Assigned" single-line item-text="Assigned" item-value="Assigned" return-object persistent-hint></v-select>
                            </v-flex>
                            <!-- <v-flex xs4 sm2 offset-sm1>
                                <v-select :items="clients" v-model="selectCl" label="Filter By Client" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex> -->
                            <!-- <v-spacer></v-spacer> -->
                            <v-flex xs12 sm2 offset-sm1>
                                <v-text-field v-model="form.start_date" color="blue darken-2" type="date" label="Start Date" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2 offset-sm1>
                                <v-text-field v-model="form.end_date" color="blue darken-2" type="date" label="End Date" required></v-text-field>
                            </v-flex>
                            <!-- <v-spacer></v-spacer> -->
                            <v-flex xs4 sm1>
                                <v-btn raised color="info" @click="sort">Filter</v-btn>
                            </v-flex>

                            <v-flex xs4 sm1>
                                <v-btn raised color="info" @click="filReset">Reset</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <v-card-title>
                        <download-excel :data="AllShipments" :fields="json_fields">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <v-tooltip right>
                                <v-btn icon slot="activator" class="mx-0" @click="getShipBranch">
                                    <v-icon color="blue darken-2" small>refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllShipments" :search="search" counter select-all class="elevation-1" v-model="selected" :loading="loading">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>
                                <v-checkbox v-model="props.selected" primary></v-checkbox>
                            </td>
                            <td class="text-xs-right">{{ props.item.created_at }}</td>
                            <td>
                                {{ props.item.bar_code }}
                            </td>
                            <td class="text-xs-right">{{ props.item.client_name }}</td>
                            <td class="text-xs-right">{{ props.item.client_phone }}</td>
                            <td class="text-xs-right">{{ props.item.client_email }}</td>
                            <td class="text-xs-right">{{ props.item.client_address }}</td>
                            <td class="text-xs-right">{{ props.item.client_city }}</td>
                            <td class="text-xs-right">{{ props.item.amount_ordered }} | {{ props.item.cod_amount }}</td>
                            <td class="text-xs-right">{{ props.item.status }}</td>
                            <td class="text-xs-right">{{ props.item.derivery_date }}</td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                    <v-btn color="primary" raised style="float: right;" @click="UpdateShipmentStatus" v-if="user.can['assign']">Update Status</v-btn>
                </div>
            </div>
            <!-- Data table -->
        </v-container>
    </v-content>
    <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
    <UpdateShipmentStatus :UpdateShipmentStatus="UpdateShipmentModel" @alertRequest="showalert" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected"></UpdateShipmentStatus>
</div>
</template>

<script>
let UpdateShipmentStatus = require("../UpdateShipmentStatus");
import VueBarcode from "vue-barcode";
export default {
    props: ["user", "role"],
    components: {
        barcode: VueBarcode, UpdateShipmentStatus
    },
    data() {
        return {
            AllBranches: [],
            UpdateShipmentModel: false,
            // clients: [],
            selectAss: {
                Assigned: 'All',
            },
            // selectCl: [],
            select: {
                branch_name: 'All',
                id: 'all'
            },
            selectItem: {
                name: "All"
            },
            statuses: [],
            items: [{
                    Assigned: 'All',
                },
                {
                    Assigned: 'Assigned',
                },
                {
                    Assigned: 'Not Assigned',
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
            snackbar: false,
            timeout: 5000,
            message: "Success",
            color: "black",
            loader: false,
            loading: false,
            AllShipments: [],
            search: "",
            updateitedItem: {},
            AllProducts: {},
            newProducts: {},
            coordinatesArr: [],
            showItem: {},
            editedItem: {},
            AllDel: [],
            between: {
                start: 1,
                end: 500,
            },
            form: {
                start_date: '',
                end_date: '',
            },
            
            headers: [{
                    text: "Waybill Date",
                    value: "created_at"
                },
                {
                    text: "Waybill Number",
                    value: "bar_code"
                },
                {
                    text: "Client",
                    value: "client_name"
                },
                {
                    text: "Client Phone",
                    value: "client_phone"
                },
                {
                    text: "Client Email",
                    value: "client_email"
                },
                {
                    text: "Client Address",
                    value: "client_address"
                },
                {
                    text: "Client City",
                    value: "client_city"
                },
                {
                    text: "Qty&COD",
                    value: "amount_ordered"
                },
                {
                    text: "Status",
                    value: "status"
                },
                {
                    text: "Delivery Date",
                    value: "derivery_date"
                },
            ],
            between: {
                start: 1,
                end: 500
            },
            selected: [],
            AllRows: [],
            selectStatus: [],
            markers: [],
            AllDelShip: [],
            AllShip: [],
            AllPe: [],
            returned: '',
        };
    },
    methods: {
        showalert() {
            this.message = "success";
            this.color = "indigo";
            this.snackbar = true;
        },
        sort() {
            this.loading = true
            axios.post('/getShipBranch', {
                    select: this.select,
                    // selectCl: this.selectCl,
                    selectStatus: this.selectItem,
                    form: this.form,
                    selectAss: this.selectAss
                })
                .then((response) => {
                    this.loading = false
                    this.AllShipments = response.data
                    this.getDeriveredS()
                    this.getOrdersS()
                    this.getPendingS()
                    this.getreturned()
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        next() {
            this.loading = true
            this.between.start = parseInt(this.between.start) + 500;
            this.between.end = parseInt(this.between.end) + 500;
            axios.post('/betweenShipments', this.$data.between)
                .then((response) => {
                    this.loading = false
                    this.AllShipments = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        getShipBranch() {
            this.loading = true
            axios
                .get("/getShipments")
                .then(response => {
                    this.loading = false
                    this.AllShipments = response.data;
                })
                .catch(error => {
                    this.loading = false
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        getDeriveredS() {
            axios
                .post("/getDeriveredS", {
                    select: this.select,
                    selectStatus: this.selectItem,
                    form: this.form,
                    selectAss: this.selectAss
                })
                .then(response => {
                    this.AllDelShip = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        getOrdersS() {
            axios
                .post("/getOrdersS", {
                    select: this.select,
                    selectStatus: this.selectItem,
                    form: this.form,
                    selectAss: this.selectAss
                })
                .then(response => {
                    this.AllShip = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        getPendingS() {
            axios
                .post("/getPendingS", {
                    select: this.select,
                    selectStatus: this.selectItem,
                    form: this.form,
                    selectAss: this.selectAss
                })
                .then(response => {
                    this.AllPe = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        getreturned() {
            axios
                .post("/getreturned", {
                    select: this.select,
                    selectStatus: this.selectItem,
                    form: this.form,
                    selectAss: this.selectAss
                })
                .then(response => {
                    this.returned = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.UpdateShipmentModel = false;
        },
        
        showalert() {
            this.message = "success";
            this.color = "indigo";
            this.snackbar = true;
        },
        filReset() {
            this.selectAss = {
                Assigned: 'All',
            }
            this.select = {
                branch_name: 'All',
                id: 'all'
            }
            this.selectItem = {
                state: 'All',
            }
            // this.selectCl = []
            this.form.start_date = this.form.end_date = ''
        },
        countOrders() {
            axios
                .get("/getShipmentsCount")
                .then(response => {
                    this.AllShip = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        countDelivered() {
            axios
                .get("/countDelivered")
                .then(response => {
                    this.AllDelShip = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        countPending() {
            axios
                .get("/countPending")
                .then(response => {
                    this.AllPe = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        
        sortItem() {
            this.loading = true;
            axios
                .post("/filterShipment", {
                    select: this.select,
                    no_btw: this.between,
                    selectStatus: this.selectItem,
                    form: this.form,
                    selectCountry: this.selectCountry
                })
                .then(response => {
                    this.loading = false;
                    this.AllShipments = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        
        UpdateShipmentStatus(item) {
            if (this.selected.length < 1) {
                this.message = "please select a shipment";
                this.color = "red";
                this.snackbar = true;
            } else {
                this.UpdateShipmentModel = true;
            }
        },
    },
    
    created() {
        eventBus.$on("selectClear", data => {
            this.selected = [];
        });

        eventBus.$on("refreshShipEvent", data => {
            // this.getShipBranch()
            this.sortItem()
            // if (this.between.start >= 500 && this.form.start_date != "" && this.end_date != "") {
            //     this.currentSTPage()
            // } else if (this.between.start >= 500) {
            //     this.currentPage()
            // } else if (this.form.start_date != "" && this.end_date != "") {
            //     this.sortItem()
            // } else {
            //     this.sortItem()
            // }

        });
    },
    mounted() {
        this.countPending()
        this.countDelivered()
        this.countOrders()
        this.getreturned()
        this.loader = true;
        axios
            .get("/getDeriveredA")
            .then(response => {
                this.AllDel = response.data;
            })
            .catch(error => {
                console.log(error);
                this.errors = error.response.data.errors;
            });
        this.getShipBranch()
        axios
            .get("/getBranch")
            .then(response => {
                this.loader = false;
                this.AllBranches = response.data;
            })
            .catch(error => {
                this.loader = false;
                console.log(error);
                this.errors = error.response.data.errors;
            })

            
        // axios.get('/getCustomer')
        //     .then((response) => {
        //         this.clients = response.data
        //     })
        //     .catch((error) => {
        //         this.errors = error.response.data.errors
        //         this.loader = false
        //     })

            
        axios.get('/getStatuses')
            .then((response) => {
                this.statuses = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
            
    }
}
</script>

<style scoped>

table.v-table tbody td:first-child,
table.v-table tbody td:not(:first-child),
table.v-table tbody th:first-child,
table.v-table tbody th:not(:first-child),
table.v-table thead td:first-child,
table.v-table thead td:not(:first-child),
table.v-table thead th:first-child,
table.v-table thead th:not(:first-child) {
    padding: 0 6px;
    font-size: 14px !important;
}
</style>
