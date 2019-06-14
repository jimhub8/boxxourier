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
                                <v-select :items="AllBranches" v-model="select" label="Filter By Branch" single-line item-text="branch_name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs4 sm2 offset-sm1>
                                <v-select :items="AllStatus" v-model="selectItem" label="Filter By Status" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs4 sm2 offset-sm1 v-for="role in user.roles" v-if="role.name === 'Admin'" :key="role.id">
                                <v-select :items="AllCountries" v-model="selectCountry" :hint="`${selectCountry.country_name}, ${selectCountry.id}`" label="Filter By country" single-line item-text="country_name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex>
                            <v-flex xs12 sm2 offset-sm1>
                                <v-text-field label="Start Date" v-model="form.start_date" color="blue darken-2" type="date" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm2>
                                <v-text-field label="End Date" v-model="form.end_date" color="blue darken-2" type="date" required></v-text-field>
                            </v-flex>
                            <v-flex xs4 sm2>
                                <v-select :items="Allpayment" v-model="selectPay" label="Filter By Payment" single-line item-text="status" item-value="status" return-object persistent-hint></v-select>
                            </v-flex>
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
                                <v-btn icon slot="activator" class="mx-0" @click="getShipments">
                                    <v-icon color="blue darken-2" small>refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllShipments" :search="search" counter class="elevation-1" :loading="loading">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>
                                {{ props.item.bar_code }}
                            </td>
                            <td class="text-xs-right">{{ props.item.client_name }}</td>
                            <td class="text-xs-right">{{ props.item.client_phone }}</td>
                            <td class="text-xs-right">{{ props.item.client_address }}</td>
                            <td class="text-xs-right">{{ props.item.cod_amount }}</td>
                            <td class="text-xs-right">{{ props.item.amount_ordered }}</td>
                            <td class="text-xs-right">{{ props.item.status }}</td>
                            <!-- <td class="text-xs-right">{{ props.item.derivery_date }}</td> -->
                            <td class="text-xs-right">{{ props.item.charges }}</td>
                            <td class="text-xs-right">{{ props.item.created_at }}</td>
                            <td class="justify-center layout px-0" v-if="user.can['update charges']">
                                <v-tooltip bottom v-if="props.item.paid">
                                    <v-btn icon class="mx-0" @click="paidOrNot(props.item)" slot="activator">
                                        <v-icon color="blue darken-2" small>check_circle</v-icon>
                                    </v-btn>
                                    <span>Mark as paid</span>
                                </v-tooltip>
                                <v-tooltip bottom v-else>
                                    <v-btn icon class="mx-0" @click="paidOrNot(props.item)" slot="activator">
                                        <v-icon color="blue darken-2" dark small>block</v-icon>
                                    </v-btn>
                                    <span>Mark not paid</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn icon class="mx-0" @click="Shipcharges(props.item)" slot="activator">
                                        <v-icon color="indigo darken-2" small>attach_money</v-icon>
                                    </v-btn>
                                    <span>Charges</span>
                                </v-tooltip>
                                    <form :action="'/invoice/'+props.item.id" method="get" target="_blank">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="type" value="stream">
                                        <v-tooltip bottom>
                                            <v-btn flat slot="activator" color="info" icon class="mx-0" type="submit">
                                                <v-icon>book</v-icon>
                                            </v-btn>
                                            <span>Stream invoice</span>
                                        </v-tooltip>
                                    </form>
                                    <form :action="'/invoice/'+props.item.id" method="get">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="type" value="download">
                                        <v-tooltip bottom>
                                            <v-btn flat slot="activator" color="primary" icon class="mx-0" type="submit">
                                                <v-icon>cloud_upload</v-icon>
                                            </v-btn>
                                            <span>Download invoice</span>
                                        </v-tooltip>
                                    </form>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                </div>
            </div>
            <!-- Data table -->
        </v-container>
    </v-content>
    <mySCharges :mySCharges="chargeModal" @closeRequest="close" :updateCharges="shipment" @alertRequest="showalert"></mySCharges>
    <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import VueBarcode from "vue-barcode";
import mySCharges from"../shipments/Charge";
export default {
  props: ["user"],
  components: {
    barcode: VueBarcode,
    mySCharges
  },
  data() {
    return {
      csrf: document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content"),
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
      selectPay: {
        status: "All",
        paid: "All"
      },
      AllCountries: [],
      AllDrivers: [],
      element: [],
      Allpayment: [
        {
          status: "All",
          paid: "All"
        },
        {
          status: "Paid",
          paid: 1
        },
        {
          status: "Not Paid",
          paid: 0
        }
      ],
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
      UpdateShipmentModel: false,
      AllShipments: [],
      search: "",
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
          text: "Client",
          value: "client_name"
        },
        {
          text: "Client Phone",
          value: "client_phone"
        },
        {
          text: "Client Address",
          value: "client_address"
        },
        {
          text: "Cod Amount",
          value: "cod_amount"
        },
        {
          text: "Quantity",
          value: "amount_ordered"
        },
        {
          text: "Status",
          value: "status"
        },
        // {
        //   text: "Derivery Date",
        //   value: "derivery_date"
        // },
        {
          text: "Charges",
          value: "charges"
        },
        {
          text: "Created on",
          value: "created_at"
        },
        {
          text: "Actions",
          sortable: false
        }
      ],
      selected: [],
      selectStatus: [],
      Allcustomers: [],
      shipment: {},
      AllStatus: [],
      shipmentsCount: null
    };
  },
  methods: {
    paidOrNot(item) {
      if (item.paid == 0) {
        item.paid = 1;
      } else {
        item.paid = 0;
      }
      axios
        .post(`/paid/${item.id}`, item)
        .then(response => {})
        .catch(error => {
          this.errors = error.response.data.errors;
        });
    },
    resetForm() {
      this.form = Object.assign({}, this.defaultForm);
      this.$refs.form.reset();
    },
    Shipcharges(item) {
      this.shipment = Object.assign({}, item);
      this.editedIndex = this.AllShipments.indexOf(item);
      this.chargeModal = true;
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
        .post("/filterFin", {
          select: this.select,
          selectStatus: this.selectItem,
          form: this.form,
          selectCountry: this.selectCountry,
          selectPay: this.selectPay
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
      this.UpdateShipmentModel = this.chargeModal = false;
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
    },
    cancelAutoUpdate() {
      clearInterval(this.timer);
    }
  },
  computed: {
    getShipmentsComp() {
      // this.loading = true;
      // axios
      //     .get("/getShipments")
      //     .then(response => {
      //         this.loading = false;
      //         this.loader = false;
      //         this.AllShipments = response.data;
      //     })
      //     .catch(error => {
      //         this.loading = false;
      //         this.loader = false;
      //         this.errors = error.response.data.errors;
      //     });
      // this.getShipments()
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

    this.getShipments();
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (vm.user.can["view finance"]) {
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
  padding: 0 10px;
}
</style>
