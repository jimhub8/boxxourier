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

                    <v-flex sm6 id="input-cont">
                        <v-text-field v-model="glsearch.search" append-icon="search" label="Global Search" single-line hide-details @keyup.enter="itemSearch"></v-text-field>
                    </v-flex>
                    <v-toolbar dark color="primary">
                        <!-- <v-toolbar-side-icon></v-toolbar-side-icon> -->
                        <v-toolbar-title class="white--text">Orders</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-tooltip bottom>
                            <v-btn slot="activator" color="info darken-2" class="mx-0">{{ AllShip }}
                                <v-icon color="white darken-2" small>alarm_add</v-icon>
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
                            <v-btn slot="activator" color="brown darken-2" class="mx-0">{{ parseInt(AllPe) - parseInt(returned) }}
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
                    <v-spacer></v-spacer>
                    <v-flex sm6>
                        <v-tooltip bottom v-if="between.start >= 500">
                            <v-btn icon class="mx-0" @click="previous" slot="activator" style="background: hsla(122, 23%, 60%, 0.31);">
                                <v-icon color="blue darken-2">chevron_left</v-icon>
                            </v-btn>
                            <span>Previous results</span>
                        </v-tooltip>
                        <v-tooltip bottom v-if="shipmentsCount > between.end">
                            <v-btn icon class="mx-0" @click="next" slot="activator" style="background: hsla(122, 23%, 60%, 0.31);">
                                <v-icon color="blue darken-2">chevron_right</v-icon>
                            </v-btn>
                            <span>Next results</span>
                        </v-tooltip>
                        From {{ between.start }} to {{ between.end }}
                    </v-flex>
                    <v-card style="background: rgba(5, 117, 230, 0.16);">
                        <v-layout wrap>
                            <v-flex xs4 sm2>
                                <v-select :items="AllBranches" v-model="select" hint="BRANCHES" label="Filter By Branch" single-line item-text="branch_name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs4 sm2 offset-sm1>
                                <v-select :items="AllStatus" v-model="selectItem" hint="STATUS" label="Filter By Status" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs4 sm2 offset-sm1 v-for="role in user.roles" v-if="role.name === 'Admin'" :key="role.id">
                                <v-select :items="AllCountries" v-model="selectCountry" hint="COUNTRY" label="Filter By country" single-line item-text="country_name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex>
                            <!-- <v-spacer></v-spacer> -->
                            <v-flex xs12 sm2 offset-sm1>
                                <v-text-field label="Start Date" v-model="form.start_date" color="blue darken-2" type="date" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2>
                                <v-text-field label="End Date" v-model="form.end_date" color="blue darken-2" type="date" required></v-text-field>
                            </v-flex>
                            <!-- <v-spacer></v-spacer> -->
                            <v-flex xs4 sm1>
                                <v-btn raised color="info" @click="sortItem">Filter</v-btn>
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
                                <v-btn icon slot="activator" class="mx-0" @click="sortItem">
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
                            <!-- <td class="text-xs-right">{{ props.item.sender_name }}</td> -->
                            <td class="text-xs-right">{{ props.item.client_phone }}</td>
                            <td class="text-xs-right">{{ props.item.client_email }}</td>
                            <td class="text-xs-right">{{ props.item.client_address }}</td>
                            <td class="text-xs-right">{{ props.item.client_city }}</td>
                            <td class="text-xs-right">{{ props.item.amount_ordered }} | {{ props.item.cod_amount }}</td>
                            <td class="text-xs-right">{{ props.item.status }}</td>
                            <td class="text-xs-right">{{ props.item.derivery_status }}</td>
                            <td class="text-xs-right">{{ props.item.derivery_date }}</td>
                            <td class="text-xs-right">{{ props.item.speciial_instruction }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom v-if="user.can['update status']">
                                    <v-btn icon class="mx-0" @click="UpdateItems(props.item)" slot="activator">
                                        <v-icon color="blue darken-2" dark small>save</v-icon>
                                    </v-btn>
                                    <span>Update Status</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['shipment status']">
                                    <v-btn icon class="mx-0" @click="ShipmentTrack(props.item)" slot="activator">
                                        <v-icon color="teal darken-2" small>call_split</v-icon>
                                    </v-btn>
                                    <span>View Status</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                    <v-btn color="primary" raised style="float: right;" @click="UpdateShipmentStatus" v-if="user.can['assign']">Update Status</v-btn>
                    <!-- <v-btn color="success" raised style="float: right;" @click="assignPrint">Print</v-btn> -->
                </div>
            </div>
            <!-- Data table -->
        </v-container>
    </v-content>
    <UpdateShipment :UpdateShipment="updateModal" @closeRequest="close" :markers="markers" :updateitedItem="updateitedItem" @alertRequest="showalert"></UpdateShipment>
    <UpdateShipmentStatus :UpdateShipmentStatus="UpdateShipmentModel" @alertRequest="showalert" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected"></UpdateShipmentStatus>
    <TrackShipment @refreshRequest="sortItem" :shipments="updateitedItem" :OpenTrackBranch="trackModel" @alertRequest="showalert" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected" :user="user"></TrackShipment>

    <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import VueBarcode from "vue-barcode";
import UpdateShipment from "./UpdateShipment";
import UpdateShipmentStatus from "./UpdateShipmentStatus";
import TrackShipment from "../TrackShipment";
export default {
    props: ["user"],
    components: {
        UpdateShipmentStatus,
        UpdateShipment,
        TrackShipment
    },
    data() {
        return {
            RowModal: false,
            csvModel: false,
            trackModel: false,
            chargeModal: false,
            printColor: "",
            printModal: false,
            ploading: false,
            nloading: false,
            mloading: false,
            timer: "",
            AllBranches: [],
            AllCountries: [],
            AllDrivers: [],
            element: [],
            glsearch: {
                search: ''
            },
            selectCountry: {
                country_id: "All",
                id: "all"
            },
            select: {
                branch_name: "All",
                id: "all"
            },
            selectItem: {
                name: "All"
            },
            json_fields: {
                "Order Id": "order_id",
                "Sender Name": "sender_name",
                "Sender Email": "sender_email",
                "Sender Phone": "sender_phone",
                "Sender City": "sender_city",
                "Sender Address": "sender_address",
                Driver: "driver",
                "Client Name": "client_name",
                "Client Email": "client_email",
                "Client Phone": "client_phone",
                "Client City": "client_city",
                "Client Address": "client_address",
                "Derivery Status": "status",
                From: "sender_address",
                To: "client_address",
                "Derivery Date": "derivery_date",
                "Derivery Time": "derivery_time",
                Quantity: "amount_ordered",
                "COD Amount": "cod_amount",
                "Booking Date": "booking_date",
                "Special Instructions": "speciial_instruction"
            },
            snackbar: false,
            timeout: 5000,
            message: "Success",
            color: "black",
            loader: false,
            updateModal: false,
            AssignBranchModel: false,
            UpdateShipmentModel: false,
            showdialog1: false,
            AllShipments: [],
            search: "",
            temp: "",
            dialog: false,
            loading: false,
            dialog1: false,
            pdialog2: false,
            AssignDriverModel: false,
            updateitedItem: {},
            AllProducts: {},
            newProducts: {},
            coordinatesArr: [],
            showItem: {},
            editedItem: {},
            between: {
                start: 1,
                end: 500
            },
            form: {
                start_date: "",
                end_date: ""
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
                    text: "Delivery Status",
                    value: "derivery_status"
                },
                {
                    text: "Delivery Date",
                    value: "derivery_date"
                },
                {
                    text: "Special Instructions",
                    value: "speciial_instruction"
                },

                {
                    text: "Actions",
                    sortable: false
                }
            ],
            selected: [],
            AllRows: [],
            selectStatus: [],
            direction: "left",
            Allcustomers: [],
            shipment: {},
            markers: [],
            AllStatus: [],
            shipmentsCount: null,
            AllDelShip: [],
            AllShip: [],
            AllPe: [],
            returned: '',
        };
    },
    methods: {
        UpdateStatus() {
            axios
                .post(`/UpdateFollowUp/${this.updateitedItem.id}`, {
                    formobg: this.$data.updateitedItem,
                    address: this.$data.address
                })
                .then(response => {
                    this.resetForm();
                    // console.log(response);
                    this.message = "Updated";
                    this.color = "black";
                    this.snackbar = true;
                });
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm);
            this.$refs.form.reset();
        },
        openShipment() {
            this.dialog = true;
            this.getBranch();
            this.getCustomer();
            this.getDrivers();
        },

        itemSearch() {
            this.loading = true;
            axios.post("/glSearch", this.glsearch)
                .then(response => {
                    this.loading = false;
                    this.AllShipments = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        addProduct() {
            axios
                .post(`/productAdd/${this.updateitedItem.id}`, this.$data.form)
                .then(response => {
                    // console.log(response.data);
                    this.message = "Product Added";
                    this.color = "black";
                    this.snackbar = true;
                    this.resetForm();
                    this.AllProducts.push(response.data);
                    this.pdialog2 = false;
                });
        },
        openProduct(updateitedItem) {
            this.pdialog2 = true;
        },
        UpdateItems(item) {
            axios
                .post(`/getcoordinatesArray/${item.id}`)
                .then(response => (this.markers = response.data))
                .catch(error => (this.errors = error.response.data.errors));
            console.log(this.coordinatesArr);
            this.updateitedItem = Object.assign({}, item);
            this.updatedIndex = this.AllShipments.indexOf(item);
            this.updateModal = true;
        },
        editItem(item) {
            this.editedItem = Object.assign({}, item);
            this.editedIndex = this.AllShipments.indexOf(item);
            // console.log(this.editedItem);
            this.dialog1 = true;
            this.getBranch();
            this.getCustomer();
            this.getDrivers();
        },
        showDetails(item) {
            eventBus.$emit("printEvent", item);
        },
        ShipmentTrack(item) {
            this.updateitedItem = Object.assign({}, item);
            this.editedIndex = this.AllShipments.indexOf(item);
            this.trackModel = true;
            eventBus.$emit('TrackShipEvent', item);
        },
        Shipcharges(item) {
            this.shipment = Object.assign({}, item);
            this.editedIndex = this.AllShipments.indexOf(item);
            this.chargeModal = true;
        },
        openRow() {
            this.RowModal = true;
        },
        ShipmentCsv() {
            this.csvModel = true;
            this.getCustomer();
        },
        deleteItem(item) {
            const index = this.AllShipments.indexOf(item);
            if (confirm("Are you sure you want to delete this item?")) {
                axios
                    .delete(`/shipment/${item.id}`)
                    .then(response => {
                        this.message = "Deleted";
                        this.color = "black";
                        this.snackbar = true;
                        this.AllShipments.splice(index, 1);
                        // console.log(response);
                    })
                    .catch(error => (this.errors = error.response.data.errors));
            }
        },
        notPrinted(item) {
            this.editedItem = Object.assign({}, item);
            this.editedIndex = this.AllShipments.indexOf(item);
            (this.loading = true),
            axios
                .post(`/notprinted/${item.id}`)
                .then(response => {
                    // this.printColor = 'red'
                    // this.loading = false,
                    this.sortItem();
                    this.message = "Not Printed";
                    this.color = "black";
                    this.snackbar = true;
                    // Object.assign(this.AllShipments[this.editedIndex], this.editedItem)
                    // console.log(response);
                })
                .catch(error => (this.errors = error.response.data.errors));
        },
        printed(item) {
            this.editedItem = Object.assign({}, item);
            this.editedIndex = this.AllShipments.indexOf(item);
            (this.loading = true),
            axios
                .post(`/printed/${item.id}`)
                .then(response => {
                    // this.printColor = 'green'
                    this.sortItem();
                    // this.loading = false,
                    this.message = "Printed";
                    this.color = "black";
                    this.snackbar = true;
                    // Object.assign(this.AllShipments[this.editedIndex], this.editedItem)
                    // console.log(response);
                })
                .catch(error => (this.errors = error.response.data.errors));
        },
        pending(item) {
            this.editedItem = Object.assign({}, item);
            this.editedIndex = this.AllShipments.indexOf(item);
            (this.mloading = true),
            axios
                .post(`/pending/${item.id}`)
                .then(response => {
                    // this.printColor = 'blue'
                    (this.mloading = false), (this.message = "Pending");
                    this.color = "black";
                    this.snackbar = true;
                    Object.assign(this.AllShipments[this.editedIndex], this.editedItem);
                    // console.log(response);
                })
                .catch(error => (this.errors = error.response.data.errors));
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
        assignDriver() {
            if (this.selected.length < 1) {
                this.message = "please select a shipment";
                this.color = "red";
                this.snackbar = true;
            } else {
                this.AssignDriverModel = true;
                this.getDrivers();
            }
        },
        assignBranch() {
            if (this.selected.length < 1) {
                this.message = "please select a shipment";
                this.color = "red";
                this.snackbar = true;
            } else {
                this.AssignBranchModel = true;
                this.getBranch();
            }
        },
        assignPrint() {
            if (this.selected.length < 1) {
                this.message = "please select a shipment";
                this.color = "red";
                this.snackbar = true;
            } else {
                this.printModal = true;
            }
        },
        getTotal() {
            this.gettotlaAmount = true;
            if (this.form.quantity && this.form.price) {
                this.form.total = this.form.quantity * this.form.price;
            } else {
                this.form.total = 0;
            }
        },
        showalert() {
            this.message = "success";
            this.color = "indigo";
            this.snackbar = true;
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
                    this.getDeriveredS()
                    this.getOrdersS()
                    this.getPendingS()
                    this.getreturned()
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        next() {
            this.loading = true;
            this.between.start = parseInt(this.between.start) + 500;
            this.between.end = parseInt(this.between.end) + 500;
            this.sortItem()
        },
        previous() {
            this.loading = true;
            if (this.between.start >= 500) {
                this.between.start = parseInt(this.between.start) - 500;
                this.between.end = parseInt(this.between.end) - 500;
                this.sortItem()
            } else {
                return;
                this.loading = false;
            }
        },
        currentPage() {
            this.loading = true;
            axios.post("/btwRefShipments", this.$data.between)
                .then(response => {
                    this.loading = false;
                    this.AllShipments = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },

        close() {
            this.dialog1 = this.dialog = this.pdialog2 = this.updateModal = this.showdialog1 = this.UpdateShipmentModel = this.AssignDriverModel = this.AssignBranchModel = this.trackModel = this.csvModel = this.chargeModal = this.RowModal = this.printModal = false;
        },
        filReset() {
            this.selectAss = {
                Assigned: "All"
            };
            this.selectItem = {
                name: "All"
            };
            this.selectCountry = {
                country_id: "All",
                id: "all"
            };
            this.select = {
                branch_name: "All",
                id: "all"
            };
            this.form.start_date = this.form.end_date = "";
            this.sortItem();
        },

        getCustomer() {
            axios
                .get("/getCustomer")
                .then(response => {
                    this.Allcustomers = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },
        getDrivers() {
            axios
                .get("/getDrivers")
                .then(response => {
                    this.AllDrivers = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        getBranch() {
            axios
                .get("/getBranchEger")
                .then(response => {
                    this.AllBranches = response.data;
                })
                .catch(error => {
                    console.log(error);
                    this.errors = error.response.data.errors;
                });
        },
        currentSTPage() {
            this.loading = true;
            axios.post("/btwSTdate", {
                    date_btw: this.$data.between,
                    filter_btw: this.form
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
        cancelAutoUpdate() {
            clearInterval(this.timer);
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
                    // console.log(error);
                    this.errors = error.response.data.errors;
                });
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
    },
    created() {
        eventBus.$on("selectClear", data => {
            this.selected = [];
        });

        eventBus.$on("refreshShipEvent", data => {
            this.sortItem()
        });
        eventBus.$on("TrackEvent", data => {
            this.updateModal = true;
        });
    },
    beforeDestroy() {
        clearInterval(this.timer);
    },
    mounted() {
        this.loader = true;
        this.getBranch();
        this.countPending()
        this.countDelivered()
        this.countOrders()
        this.getreturned()
        axios
            .get("/getCountry")
            .then(response => {
                this.AllCountries = response.data;
                this.loader = false;
            })
            .catch(error => {
                console.log(error);
                this.errors = error.response.data.errors;
                this.loader = false;
            });

        axios
            .get("/getShipmentsCount")
            .then(response => {
                this.shipmentsCount = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
        axios
            .get("/getStatuses")
            .then(response => {
                this.AllStatus = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });

        axios
            .get("/updateCancelled")
            .then(response => {
                // this.AllStatus = response.data;
                console.log(response.data);
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });

        this.sortItem();
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can["view shipments"]) {
                next();
            } else {
                next("/unauthorized");
            }
        });
    }
};
</script>

<style>
#create .speed-dial {
    position: absolute;
}

#create .btn--floating {
    position: relative;
}

.btn__content i {
    color: #fff !important;
    width: 50px;
}

.speed-dial__list i {
    color: #fff !important;
}

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

#input-cont .v-input__control {
    margin: 20px 0 !important;
}
</style>
