<template>
<v-content>
    <div v-show="loader" style="text-align: center">
        <v-progress-circular :width="3" indeterminate color="blue" style="margin: 1rem"></v-progress-circular>
    </div>
    <v-container fluid fill-height>
        <div v-show="!loader">
            <v-layout justify-center align-center>
                <v-layout row wrap>
                    <v-flex xs12 sm8>
                        <v-layout row wrap>
                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Shipments.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="shipmentExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Bookings.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="bookingExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Approved.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="approvedExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Pending.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="pendingExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Cancelled.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="cancledExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Customers.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="customersExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Deriverd.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="deriverdExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Users.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="userExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                            <v-flex xs4 sm3>
                                <v-card>
                                    <img id="doc" src="/storage/profile/doc.png" alt="Docs" class="img-responsive text-center" style="width: 100%; height:150px;">
                                    <v-card-title primary-title>
                                        <div>
                                            <p>Branches.xls</p>
                                            <small></small>
                                        </div>
                                    </v-card-title>
                                    <v-card-actions>
                                        <form action="branchesExpo" method="post">
                                            <v-btn flat type="submit" color="blue">Export</v-btn>
                                        </form>
                                    </v-card-actions>
                                </v-card>
                            </v-flex>

                        </v-layout>
                    </v-flex>
                    <v-flex xs12 sm3 offset-sm1>
                        <v-card>
                            <v-divider></v-divider>
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

                            <v-divider></v-divider>
                            <h1>Status Reports</h1>
                            <hr>
                            <form action="displayReport" method="post">
                                <select class="custom-select custom-select-md col-md-12" name="status">
                                <option value="Awaiting Approval">Awaiting Approval</option>
                                <option value="Approved">Approved</option>
                                <option value="Arrived">Arrived</option>
                                <option value="Awaiting Confirmation">Awaiting Confirmation</option>
                                <option value="Cancelled ">cancelled</option>
                                <option value="Cleared">Cleared</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Dispatched">Dispatched</option>
                                <!-- <option value="Cancled">Cancled</option> -->
                                <option value="Hold">Hold</option>
                                <option value="Not Picking">Not Picking</option>
                                <option value="Out For Destination">Out For Destination</option>
                                <option value="Out For Delivery">Out For Delivery</option>
                                <option value="Returned">Returned</option>
                                <option value="Ready For Depart">Ready For Depart</option>
                                <option value="Scheduled">Scheduled</option>
                                <option value="Shipment Collected">Shipment Collected</option>
                                <option value="Transit">Transit</option>
                                <option value="Waiting for Scan">Waiting for scan</option>
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
