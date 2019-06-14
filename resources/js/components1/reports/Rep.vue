<template>
<v-content>
    <div v-show="loader" style="text-align: center">
        <v-progress-circular :width="3" indeterminate color="blue" style="margin: 1rem"></v-progress-circular>
    </div>
    <v-container fluid fill-height>
        <div v-show="!loader">
            <v-layout justify-center align-center>
                <v-layout row wrap>
                    <v-flex xs12 sm12 offset-sm1>
                        <v-card>
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-md-8">
                                        <div class="card card-default">
                                            <div class="card-header">Reports</div>

                                            <div class="card-body">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <label class="input-group-text" for="inputGroupSelect01">Options</label>
                                                    </div>
                                                    <select class="custom-select" id="inputGroupSelect01" v-model="form.type">
                                                        <option selected>Choose...</option>
                                                        <option value="Shipments">Shipments</option>
                                                        <option value="Users">Users</option>
                                                        <option value="Clients">Clients</option>
                                                        <option value="Riders">Riders</option>
                                                        <option value="Status">Status</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <v-divider></v-divider>
                            <div v-if="form.type == Clients">
                                <h1>Clients Reports</h1>
                                <hr>
                                <form action="userDateExpo" method="post">
                                    <select class="custom-select custom-select-md col-md-12 col-md-12" name="name" style="font-size: 13px;">
                                        <option v-for="customer in Allcustomers" :key="customer.id">{{ customer.name }}</option>
                                    </select> Between
                                    <hr>
                                    <v-flex xs10 sm9 offset-sm1>
                                        <v-text-field name="start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs10 sm9 offset-sm1>
                                        <v-text-field name="end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                                    </v-flex>
                                    <v-btn flat type="submit" success color="black">Download</v-btn>

                                </form>
                            </div>

                            <v-divider></v-divider>
                            <h1>Status Reports</h1>
                            <hr>
                            <form action="displayReport" method="post">
                                <select class="custom-select custom-select-md col-md-12" name="status">
  
                                <option value="Awaiting Approval">Awaiting Approval</option>

                                <option value="Approved">Approved</option>

                                <option value="Cancelled">Cancelled</option>

                                <option value="Shipment Collected">Shipment Collected</option>

                                <option value="Waiting for Scan">Waiting for scan</option>

                                <option value="Ready For Depart">Ready For Depart</option>

                                <option value="Dispatched">Dispatched</option>

                                <option value="Arrived">Arrived</option>

                                <option value="Cleared">Cleared</option>

                                <option value="Transit">Transit</option>

                                <option value="Out For Destination">Out For Destination</option>

                                <option value="Out For Delivery">Out For Delivery</option>

                                <option value="Delivered">Delivered</option>

                                <option value="Returned">Returned</option>

                                <option value="Hold">Hold</option>

                            </select> Between
                                <hr>
                                <v-flex xs10 sm9 offset-sm1>
                                    <v-text-field name="start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                                </v-flex>
                                <v-flex xs10 sm9 offset-sm1>
                                    <v-text-field name="end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                                </v-flex>
                                <v-btn flat type="submit" success color="black">Download</v-btn>
                            </form>

                            <v-divider></v-divider>
                            <h1>Rider Reports</h1>
                            <hr>
                            <form action="DriverReport" method="post">
                                <select class="custom-select custom-select-md col-md-12" name="status">
                                <option v-for="driver in AllDrivers" :key="driver.id">{{ driver.name }}</option>
                            </select> Between
                                <hr>
                                <v-flex xs10 sm9 offset-sm1>
                                    <v-text-field name="start_date" label="start date" type="date" color="blue darken-2" required></v-text-field>
                                </v-flex>
                                <v-flex xs10 sm9 offset-sm1>
                                    <v-text-field name="end_date" label="End Date" type="date" color="blue darken-2" required></v-text-field>
                                </v-flex>
                                <v-btn flat type="submit" success color="black">Download</v-btn>
                            </form>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-layout>
        </div>
    </v-container>
</v-content>
</template>

<script>
export default {
    data() {
        return {
            Allcustomers: [],
            AllDrivers: [],
            loader: false,
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

    }
}
</script>
