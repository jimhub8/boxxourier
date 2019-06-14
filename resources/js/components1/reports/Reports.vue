<template>
<v-content>
    <div v-show="loader" style="text-align: center">
        <v-progress-circular :width="3" indeterminate color="blue" style="margin: 1rem"></v-progress-circular>
    </div>
    <v-container fluid fill-height v-show="!loader">
        <!-- <div> -->
        <v-layout justify-center align-center>
            <!-- <v-layout row>
                <v-flex xs6 sm6>
                </v-flex>
            </v-layout> -->
            <v-layout wrap>
                <v-flex xs4 sm3 style="margin-top: 40px;margin-left: 10vw;">
                    <v-card style="padding: 5px;border-radius: 10px;">
                        <h1>Clients Reports</h1>
                        <hr>
                        <!-- <form action="userDateExpo" method="post"> -->
                        <label for="">Client</label>
                        <select class="custom-select custom-select-md col-md-12 col-md-12" v-model="Client.client_id" style="font-size: 
                        13px;">
                                    <option value=""></option>
                                    <option v-for="customer in Allcustomers" :value="customer.id" :key="customer.id">{{ customer.name }}</option>
                            </select> Between
                        <hr>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Client.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Client.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-card-actions>
                            <v-btn flat @click="ClientReport" :loading="Cload" :disabled="Cload" success color="black">Get Data</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn flat color="orange">{{ AllClientR.length }} </v-btn>
                        </v-card-actions>
                        <v-divider></v-divider>
                        <download-excel :data="AllClientR" :fields="json_fields" v-show="Cdown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>

                            <!-- </form> -->
                    </v-card>
                </v-flex>

                <!-- <v-divider vertical></v-divider> -->
                <v-flex xs4 sm3 style="margin-top: 40px;margin-left: 10px;">
                    <v-card style="padding: 5px;     border-radius: 10px;">
                        <h1>Status Reports</h1>
                        <hr>
                        <form action="displayReport" method="post">
                            <div>
                                <label for="">Status</label>
                                <select v-model="statusR.status" class="custom-select custom-select-md col-md-12">
                                    <option value=""></option>
                                    <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                                </select>
                            </div>
                            <hr>
                            <v-flex xs12 sm12>
                                <v-text-field v-model="statusR.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12>
                                <v-text-field v-model="statusR.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                            <v-card-actions>
                                <v-btn flat @click="AllStatusR" :loading="Sload" :disabled="Sload" success color="black">Get Data</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn flat color="orange">{{ AllStatus.length }} </v-btn>
                                <v-divider></v-divider>
                            </v-card-actions>
                            <download-excel :data="AllStatus" :fields="json_fields" v-show="Sdown">
                                Export
                                <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                        </form>
                    </v-card>
                </v-flex>

                <v-flex xs4 sm3 style="margin-top: 40px;margin-left: 10px;">
                    <v-card style="padding: 5px;     border-radius: 10px;">
                        <h1>Branch Reports</h1>
                        <hr>
                        <!-- <form action="DriverReport" method="post"> -->
                        <div>
                            <label for="">Status</label>
                            <select v-model="branchStatus.status" class="custom-select custom-select-md col-md-12">
                                    <option value=""></option>
                                    <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                                </select>
                        </div>
                        <label for="">Branch</label>
                        <select class="custom-select custom-select-md col-md-12" v-model="branchR.branch_id">
                            <option value=""></option>
                            <option v-for="branch in AllBranches" :key="branch.id" :value="branch.id">{{ branch.branch_name }}</option>
                        </select> Between
                        <hr>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="branchR.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="branchR.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-card-actions>
                            <v-btn flat @click="AllbranchR" :loading="Bload" :disabled="Bload" success color="black">Get Data</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn flat color="orange">{{ AllBranchD.length }} </v-btn>
                        </v-card-actions>
                        <v-divider></v-divider>
                        <download-excel :data="AllBranchD" :fields="json_fields" v-show="Bdown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <!-- </form> -->
                    </v-card>
                </v-flex>
                <!-- <v-divider vertical></v-divider> -->
                <v-flex xs4 sm3 style="margin-top: 40px;margin-left: 10vw;">
                    <v-card style="padding: 5px;     border-radius: 10px;">
                        <h1>Rider Reports</h1>
                        <hr>
                        <!-- <form action="DriverReport" method="post"> -->
                        <label for="">Rider</label>
                        <select class="custom-select custom-select-md col-md-12" v-model="Rinder.rinder_id">
                                    <option value=""></option>
                                    <option v-for="driver in AllDrivers" :key="driver.id" :value="driver.id">{{ driver.name }}</option>
                                </select> Between
                        <hr>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Rinder.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="Rinder.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-card-actions>
                            <v-btn flat @click="AllRinderR" :loading="Rload" :disabled="Rload" success color="black">Get Data</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn flat color="orange">{{ AllRinder.length }} </v-btn>
                        </v-card-actions>
                        <v-divider></v-divider>
                        <download-excel :data="AllRinder" :fields="json_fields" v-show="Rdown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <!-- </form> -->
                    </v-card>
                </v-flex>
                <v-flex xs4 sm3 style="margin-top: 40px;margin-left: 10px;">
                    <v-card style="padding: 5px;     border-radius: 10px;">
                        <h1>Delivery Reports</h1>
                        <hr>
                        <!-- <form action="DriverReport" method="post"> -->
                        <label for="">Status</label>
                        <select v-model="DeliveryR.status" class="custom-select custom-select-md col-md-12">
                            <option value=""></option>
                            <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                        </select>

                        <label for="">Branch</label>
                        <select class="custom-select custom-select-md col-md-12" v-model="DeliveryR.branch_id">
                            <option value=""></option>
                            <option v-for="branch in AllBranches" :key="branch.id" :value="branch.id">{{ branch.branch_name }}</option>
                        </select> Between
                        <hr>
                        <h2>Delivery Date Between:</h2>

                        <hr>
                        <v-layout wrap>
                            <v-flex xs12 sm5>
                                <v-text-field v-model="DeliveryR.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6 offset-sm-1>
                                <v-text-field v-model="DeliveryR.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <h2>Upload Date Between:</h2>
                        <hr>
                        <v-layout wrap>
                            <v-flex xs12 sm6>
                                <v-text-field v-model="DeliveryR.Upstart_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <v-text-field v-model="DeliveryR.Upend_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                            </v-flex>
                        </v-layout>
                        <v-card-actions>
                            <v-btn flat @click="AllDeliveryRR" :loading="Dload" :disabled="Dload" success color="black">Get Data</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn flat color="orange">{{ AllDeliveryR.length }} </v-btn>
                        </v-card-actions>
                        <v-divider></v-divider>
                        <download-excel :data="AllDeliveryR" :fields="json_fields" v-show="Ddown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <!-- </form> -->
                            <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
                                {{ message }}
                                <v-icon dark right>check_circle</v-icon>
                            </v-snackbar>
                    </v-card>
                </v-flex>

                <v-flex xs4 sm3 style="margin-top: 40px;margin-left: 10px;">
                    <v-card style="padding: 5px;     border-radius: 10px;">
                        <h1>Product Reports</h1>
                        <hr>
                        <!-- <form action="DriverReport" method="post"> -->
                        <label for="">Status</label>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="ProdR.email" color="blue darken-2" label="Product email" required></v-text-field>
                        </v-flex>
                        <select v-model="ProdR.status" class="custom-select custom-select-md col-md-12">
                            <option value=""></option>
                            <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                        </select>
                        Delivery Date Between:
                        <hr>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="ProdR.start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="ProdR.end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                        </v-flex>
                        <v-card-actions>
                            <v-btn flat @click="AllProdR" :loading="Pload" :disabled="Pload" success color="black">Get Data</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn flat color="orange">{{ AllProd.length }} </v-btn>
                        </v-card-actions>
                        <v-divider></v-divider>
                        <download-excel :data="AllProd" :fields="json_fields" v-show="Pdown">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <!-- </form> -->
                            <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
                                {{ message }}
                                <v-icon dark right>check_circle</v-icon>
                            </v-snackbar>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-layout>

        <!-- </div> -->
    </v-container>
</v-content>
</template>

<script>
export default {
    data() {
        return {
            AllProd: [],
            ProdR: {},
            Allcustomers: [],
            AllDrivers: [],
            statusR: {},
            statusD: {},
            Client: {},
            DeliveryR: {
                branch_id: ''
            },
            Rinder: {},
            branchStatus: {},
            AllBranches: [],
            AllStatus: [],
            AllClientR: [],
            AllRinder: [],
            branchRD: {},
            branchR: {},
            AllBranchD: [],
            AllDeliveryR: [],
            statuses: [],
            loader: false,
            Sload: false,
            Cload: false,
            Bload: false,
            Dload: false,
            Rload: false,
            Cdown: false,
            Bdown: false,
            Sdown: false,
            Pdown: false,
            Ddown: false,
            Pload: false,
            Rdown: false,
            snackbar: false,
            timeout: 5000,
            message: 'Success',
            color: 'black',
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
        ClientReport() {
            eventBus.$emit("progressEvent");
            this.Cload = true
            this.AllClientR = []
            axios.post("/userDateExpo", this.$data.Client)
                .then(response => {
                    this.Cload = false
                    this.AllClientR = response.data
                    if (this.AllClientR.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Cdown = false
                    } else {
                        this.Cdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                    eventBus.$emit("StoprogEvent");
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.Cload = false
                    this.errors = error.response.data.errors;
                });
        },

        AllStatusR() {
            eventBus.$emit("progressEvent");
            this.Sload = true
            this.AllStatus = []
            axios.post("/displayReport", this.$data.statusR)
                .then(response => {
                    this.Sload = false
                    this.AllStatus = response.data
                    // console.log(response.data)
                    if (this.AllStatus.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Sdown = false
                    } else {
                        this.Sdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                    eventBus.$emit("StoprogEvent");
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.Sload = false
                    this.errors = error.response.data.errors;
                });
        },

        AllRinderR() {
            eventBus.$emit("progressEvent");
            this.Rload = true
            this.AllRinder = []
            axios.post("/DriverReport", this.$data.Rinder)
                .then(response => {
                    this.Rload = false
                    this.AllRinder = response.data
                    if (this.AllRinder.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Rdown = false
                    } else {
                        this.Rdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                    eventBus.$emit("StoprogEvent");
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.Rload = false
                    this.errors = error.response.data.errors;
                });
        },

        AllProdR() {
            eventBus.$emit("progressEvent");
            this.Pload = true
            // this.AllProd = []
            axios.post("/ProdReport", this.$data.ProdR)
                .then(response => {
                    this.Pload = false
                    this.AllProd = response.data
                    if (this.AllProd.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Pdown = false
                    } else {
                        this.Pdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                    eventBus.$emit("StoprogEvent");
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.Rload = false
                    this.errors = error.response.data.errors;
                });
        },
        AllDeliveryRR() {
            eventBus.$emit("progressEvent");
            this.Dload = true
            this.AllDeliveryR = []
            axios.post("/DelivReport", this.$data.DeliveryR)
                .then(response => {
                    this.Dload = false
                    this.AllDeliveryR = response.data
                    if (this.AllDeliveryR.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Ddown = false
                    } else {
                        this.Ddown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                    }
                    eventBus.$emit("StoprogEvent");
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.Rload = false
                    this.errors = error.response.data.errors;
                });
        },
        AllbranchR() {
            eventBus.$emit("progressEvent");
            this.Bload = true
            this.AllBranchD = []
            axios.post("/branchesExpo", {
                    branch: this.$data.branchR,
                    branch_status: this.$data.branchStatus,
                })
                .then(response => {
                    this.Bload = false
                    this.AllBranchD = response.data;
                    if (this.AllBranchD.length < 1) {
                        this.message = 'no data'
                        this.color = 'red'
                        this.snackbar = true
                        this.Bdown = false
                    } else {
                        this.Bdown = true
                        this.message = 'success'
                        this.color = 'black'
                        this.snackbar = true
                        this.AllBranchD = response.data
                    }
                    eventBus.$emit("StoprogEvent");
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.Bload = false
                    this.errors = error.response.data.errors;
                });
        }
    },
    mounted() {
        this.loader = true;

        axios.get("/getCustomer")
            .then(response => {
                this.Allcustomers = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });

        axios.get("/getDrivers")
            .then(response => {
                this.AllDrivers = response.data;
                this.loader = false;
            })

            .catch(error => {
                this.loader = false;
                this.errors = error.response.data.errors;
            });

        axios.get("/getBranch")
            .then(response => {
                this.AllBranches = response.data;
                this.loader = false;
            })

            .catch(error => {
                this.loader = false;
                this.errors = error.response.data.errors;
            })

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
.theme--light.v-card {
    background-color: rgba(158, 158, 158, 0.08);
    height: 550px;
}
</style>
