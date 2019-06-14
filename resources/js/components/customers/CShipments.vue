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
                                <v-select :items="AllBranches" v-model="select" :hint="`${select.branch_name}, ${select.id}`" label="Filter By Branch" single-line item-text="branch_name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs4 sm2 offset-sm1>
                                <v-select :items="AllStatus" v-model="selectItem" label="Filter By Status" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
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
                            <v-btn color="primary" flat @click="openShipment">Add Shipment</v-btn>
                            <v-btn color="primary" flat @click="ShipmentCsv">Upload Excel</v-btn>
                            <v-tooltip right>
                                <v-btn icon slot="activator" class="mx-0" @click="getShipments">
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
                            <td>
                                {{ props.item.bar_code }}
                            </td>
                            <td class="text-xs-right">
                                <barcode v-bind:value="props.item.bar_code" style="height: 10px;">
                                    No barcode
                                </barcode>
                            </td>
                            <td class="text-xs-right">{{ props.item.client_name }}</td>
                            <!-- <td class="text-xs-right">{{ props.item.sender_name }}</td> -->
                            <td class="text-xs-right">{{ props.item.client_phone }}</td>
                            <!-- <td class="text-xs-right">{{ props.item.client_email }}</td> -->
                            <td class="text-xs-right">{{ props.item.client_address }}</td>
                            <!-- <td class="text-xs-right">{{ props.item.client_city }}</td>
                            <td class="text-xs-right">{{ props.item.sender_name }}</td>
                            <td class="text-xs-right">{{ props.item.sender_city }}</td>
                            <td class="text-xs-right">{{ props.item.booking_date }}</td>
                            <td class="text-xs-right">{{ props.item.cod_amount }}</td>
                            <td class="text-xs-right">{{ props.item.amount_ordered }}</td> -->
                            <td class="text-xs-right">{{ props.item.status }}</td>
                            <td class="text-xs-right">{{ props.item.derivery_date }}</td>
                            <td class="text-xs-right">{{ props.item.charges }}</td>
                            <td class="text-xs-right">{{ props.item.created_at }}</td>
                            <!-- <td class="text-xs-right" v-if="props.item.printed === 1" style="background: Green;">
                                <p style="color: #fff;">Printed</p>
                            </td>
                            <td class="text-xs-right" v-else>
                                <p style="color: #000;">Not Printed</p>
                            </td> -->
                            <td class="text-xs-right" v-if="props.item.printReceipt === 1" style="background: green;">
                                <v-btn color="white" flat @click="notPrinted(props.item)" :loading="nloading" :disabled="nloading">Mark Not Printed</v-btn>
                            </td>
                            <td class="text-xs-right" v-else>
                                <v-btn color="info" flat @click="printed(props.item)" :loading="ploading" :disabled="ploading">Mark Printed </v-btn>
                            </td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="editItem(props.item)" slot="activator">
                                        <v-icon color="blue darken-2" small>edit</v-icon>
                                    </v-btn>
                                    <span>Edit</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="UpdateItems(props.item)" slot="activator">
                                        <v-icon color="blue darken-2" dark small>save</v-icon>
                                    </v-btn>
                                    <span>Update Status</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="deleteItem(props.item)" slot="activator">
                                        <v-icon color="pink darken-2" small>delete</v-icon>
                                    </v-btn>
                                    <span>Delete</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="showDetails(props.item)" slot="activator">
                                        <v-icon color="info darken-2" small>visibility</v-icon>
                                    </v-btn>
                                    <span>View</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="ShipmentTrack(props.item)" slot="activator">
                                        <v-icon color="teal darken-2" small>call_split</v-icon>
                                    </v-btn>
                                    <span>View Status</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="Shipcharges(props.item)" slot="activator">
                                        <v-icon color="indigo darken-2" small>attach_money</v-icon>
                                    </v-btn>
                                    <span>Charges</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                    <v-btn color="primary" raised style="float: right;" @click="UpdateShipmentStatus">Update Status</v-btn>
                    <v-btn color="info" raised style="float: right;" @click="assignDriver">Assign Driver</v-btn>
                    <v-btn color="success" raised style="float: right;" @click="assignBranch">Assign Branch</v-btn>
                    <!-- <v-btn color="success" raised style="float: right;" @click="assignPrint">Print</v-btn> -->
                </div>
            </div>
            <!-- Data table -->
        </v-container>
    </v-content>
    <AddShipment :addShipment="dialog" @closeRequest="close" @alertRequest="showalert" :Allcustomer="Allcustomers" :user="user" :role="role" :AllBranches="AllBranches" :AllDrivers="AllDrivers"></AddShipment>
    <EditShipment :EditShipment="dialog1" @closeRequest="close" @alertRequest="showalert" :Allcustomer="Allcustomers" :user="user" :role="role" :AllBranches="AllBranches" :AllDrivers="AllDrivers" :form="editedItem" ></EditShipment>
    <!-- <EditShipment :EditShipment="dialog1" @closeRequest="close" :customers="Allcustomers" :form="editedItem" @alertRequest="showalert" :role="role"></EditShipment> -->
    <ShowShipment :element="element" @closeRequest="close" :customers="Allcustomers" :showItems="showItem"></ShowShipment>
    <UpdateShipment :UpdateShipment="updateModal" @closeRequest="close" :markers="markers" :updateitedItem="updateitedItem" @alertRequest="showalert"></UpdateShipment>
    <UpdateShipmentStatus :UpdateShipmentStatus="UpdateShipmentModel" @alertRequest="showalert" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected"></UpdateShipmentStatus>
    <AssignDriver :AllDrivers="AllDrivers" :OpenAssignDriver="AssignDriverModel" @alertRequest="showalert" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected"></AssignDriver>
    <AssignBranch :AllBranches="AllBranches" :OpenAssignBranch="AssignBranchModel" @alertRequest="showalert" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected"></AssignBranch>
    <TrackShipment @refreshRequest="getShipments" :shipments="shipment" :OpenTrackBranch="trackModel" @alertRequest="showalert" @closeRequest="close" :updateitedItem="editedItem" :selectedItems="selected"></TrackShipment>
    <myCsvFile :OpenCsv="csvModel" @closeRequest="close"></myCsvFile>
    <mySCharges :mySCharges="chargeModal" @closeRequest="close" :updateCharges="shipment" @alertRequest="showalert"></mySCharges>
    <myRows :myRows="RowModal" @closeRequest="close" :updateCharges="shipment"></myRows>
    <!-- <myPrintPod :PrintRequest="printModal" @closeRequest="close" :selected="selected"></myPrintPod> -->
    <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import VueBarcode from "vue-barcode";
let AddShipment = require("./AddShipment");
let EditShipment = require("./EditShipment");
let ShowShipment = require("./PrintSPdf");
let UpdateShipment = require("./UpdateShipment");
let UpdateShipmentStatus = require("./UpdateShipmentStatus");
let AssignDriver = require("./AssignDriver");
let AssignBranch = require("./AssignBranch");
let TrackShipment = require("./TrackShipment");
let myCsvFile = require("../csv/CsvFile");
let mySCharges = require("./Charge");
// let myPrintPod = require('./PrintPod')
let myRows = require("./rows/Rows");
// let myPrintSPdf = require('./PrintSPdf.js');
export default {
  props: ["user", "role"],
  components: {
    myRows,
    AddShipment,
    ShowShipment,
    EditShipment,
    barcode: VueBarcode,
    UpdateShipmentStatus,
    UpdateShipment,
    AssignDriver,
    AssignBranch,
    TrackShipment,
    myCsvFile,
    mySCharges
    // myPrintSPdf,
    // myPrintPod
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
      AllBranches: [],
      AllCountries: [],
      AllDrivers: [],
      element: [],
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
      statuses: [
        {
          state: "Awaiting Confirmation"
        },
        {
          state: "Cancelled"
        },
        {
          state: "Call Back"
        },
        {
          state: "Dispatched"
        },
        {
          state: "Delivered"
        },
        {
          state: "Not Available"
        },
        {
          state: "Not Picking"
        },
        {
          state: "Offline"
        },
        {
          state: "Returned"
        },
        {
          state: "Scheduled"
        },
        {
          state: "Wrong Number"
        }
      ],
      items: [
        {
          state: "All",
          abbr: "all"
        },
        {
          state: "Admin",
          abbr: "Admin"
        },
        {
          state: "company Admin",
          abbr: "companyAdmin"
        },
        {
          state: "Customers",
          abbr: "Customer"
        },
        {
          state: "Drivers",
          abbr: "Driver"
        }
      ],
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
      headers: [
        {
          text: "Waybill Number",
          value: "airway_bill_no"
        },
        {
          text: "Barcode",
          value: "bar_code"
        },
        {
          text: "Client",
          value: "client_name"
        },
        // {
        //   text: "From",
        //   value: "sender_name"
        // },
        {
          text: "Client Phone",
          value: "client_phone"
        },
        // {
        //   text: "Client Email",
        //   value: "client_email"
        // },
        {
          text: "Client Address",
          value: "client_address"
        },
        // {
        //   text: "Client City",
        //   value: "client_city"
        // },
        // {
        //   text: "Sender Name",
        //   value: "sender_name"
        // },
        // {
        //   text: "Sender City",
        //   value: "sender_city"
        // },
        // {
        //   text: "Order Date",
        //   value: "booking_date"
        // },
        // {
        //   text: "Cod Amount",
        //   value: "cod_amount"
        // },
        // {
        //   text: "Quantity",
        //   value: "amount_ordered"
        // },
        {
          text: "Status",
          value: "status"
        },
        {
          text: "Derivery Date",
          value: "derivery_date"
        },
        {
          text: "Charges",
          value: "charges"
        },
        {
          text: "Created",
          value: "created_at"
        },
        // {
        //   text: "Waybill Printed",
        //   value: "created_at"
        // },
        {
          text: "Receipt Printed",
          value: "name"
        },
        {
          text: "Actions",
          value: "name",
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
      shipmentsCount: null
    };
  },
  methods: {
    UpdateStatus() {
      // alert(this.updateitedItem.id);
      axios
        .post(`/updateStatus/${this.updateitedItem.id}`, {
          formobg: this.$data.updateitedItem,
          address: this.$data.address
        })
        .then(response => {
          this.resetForm();
          // console.log(response);
          this.message = "Updated";
          this.color = "black";
          this.snackbar = true;
          // this.markers.push(response.data);
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
    addProduct() {
      // alert(this.updateitedItem.id);
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
    // editShipment(key) {
    //   // alert(key);
    //   this.$children[2].list = this.AllShipments[key];
    //   this.dialog1 = true;
    // },
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
      this.shipment = Object.assign({}, item);
      this.editedIndex = this.AllShipments.indexOf(item);
      this.trackModel = true;
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
            this.getShipments();
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
            this.getShipments();
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
    sort() {
      this.loading = true;
      axios
        .post("/filterShipment", {
          select: this.select,
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
    next() {
      this.loading = true;
      this.between.start = parseInt(this.between.start) + 500;
      this.between.end = parseInt(this.between.end) + 500;
      axios
        .post("/betweenShipments", this.$data.between)
        .then(response => {
          this.loading = false;
          this.AllShipments = response.data;
        })
        .catch(error => {
          this.loading = false;
          this.errors = error.response.data.errors;
        });
    },
    previous() {
      this.loading = true;
      if (this.between.start >= 500) {
        this.between.start = parseInt(this.between.start) - 500;
        this.between.end = parseInt(this.between.end) - 500;
        axios
          .post("/betweenShipments", this.$data.between)
          .then(response => {
            this.loading = false;
            this.AllShipments = response.data;
          })
          .catch(error => {
            this.loading = false;
            this.errors = error.response.data.errors;
          });
      } else {
        return;
        this.loading = false;
      }
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
      this.getShipments();
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
    getShipments() {
      this.loading = true;
      this.between.start = 1;
      this.between.end = 500;
      axios
        .get("/getShipments")
        .then(response => {
          this.loading = false;
          this.loader = false;
          this.AllShipments = response.data;
        })
        .catch(error => {
          this.loading = false;
          this.loader = false;
          this.errors = error.response.data.errors;
        });
    }
  },

  created() {
    eventBus.$on("selectClear", data => {
      this.selected = [];
    });
  },
  mounted() {
    this.loader = true;
    this.getBranch();
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
      })
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
</style>
