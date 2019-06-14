<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
            <v-layout justify-center align-center v-show="!loader">
                <v-card>
                    <div style="text-align: center">
                        <h2 class="text-center">Welcome {{ user.name }}</h2>
                        <v-divider></v-divider>
                    </div>
                    <v-toolbar card blue-grey darken-1>
                        <v-toolbar-title class="body-2">Shipments Details</v-toolbar-title>
                    </v-toolbar>
                    <v-text-field v-model="form.search" append-icon="search" label="Search" single-line hide-details @keyup.enter="searchRe"></v-text-field>
                    <v-spacer></v-spacer>
                    <v-tooltip right>
                        <v-btn icon slot="activator" class="mx-0" @click="refresh">
                            <v-icon color="blue darken-2" small>refresh</v-icon>
                        </v-btn>
                        <span>Refresh</span>
                    </v-tooltip>
                    <v-card-text v-for="shipments in AllShip.data" :key="shipments.id" v-show="!nextPage">
                        <!-- <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear> -->
                        <ul class="list-group">
                            <li class="list-group-item active text-center">
                                <h2>Shipment {{ shipments.airway_bill_no }}</h2>
                            </li>
                        </ul>
                        <v-layout wrap>
                            <!-- <div v-for="shipments in AllShip.data" :key="shipments.id"> -->
                            <v-flex xs12 sm6>
                                <ul class="list-group">
                                    <li class="list-group-item"><label for=""><b>Waybill Number: </b></label>{{ shipments.airway_bill_no }}</li>
                                    <li class="list-group-item"><label for=""><b>From: </b></label>{{ shipments.sender_city }}</li>
                                    <li class="list-group-item"><label for=""><b>Status: </b></label>{{ shipments.status }}</li>
                                </ul>
                            </v-flex>

                            <v-flex xs12 sm6>
                                <ul class="list-group">
                                    <li class="list-group-item"><label for=""><b>Derivery Date: </b></label> {{ shipments.derivery_date }}</li>
                                    <li class="list-group-item"><label for=""><b>To: </b></label> {{ shipments.client_city }}</li>
                                </ul>
                            </v-flex>
                            <!-- </div> -->
                        </v-layout>

                        <v-toolbar card blue-grey darken-1>
                            <v-toolbar-title class="body-2">Product Details</v-toolbar-title>
                        </v-toolbar>
                        <v-layout wrap>
                            <v-flex xs12 sm6>
                                <ul class="list-group">
                                    <li class="list-group-item"><label for=""><b>Waybill Status:</b></label>{{ shipments.status }}</li>
                                    <!-- <li class="list-group-item"><label for="">Receiver Name: </label>{{ shipments.client_name }}</li> -->
                                    <li class="list-group-item"><label for=""><b>Delivery Date: </b></label>{{ shipments.derivery_date }}</li>
                                </ul>
                            </v-flex>

                            <v-flex xs12 sm6>
                                <ul class="list-group">
                                    <li class="list-group-item"><label for=""><b>COD amount: </b></label> {{ shipments.cod_amount }}</li>
                                    <li class="list-group-item"><label for=""><b>product Description: </b></label> {{ shipments.bar_code }}</li>
                                    <li class="list-group-item"><label for=""><b>Quantity: </b></label> {{ shipments.amount_ordered }}</li>
                                </ul>
                            </v-flex>
                        </v-layout>

                        <v-toolbar card blue-grey darken-1>
                            <v-toolbar-title class="body-2">
                                <v-layout wrap>
                                    <v-flex sm6>Client Details</v-flex>
                                    <v-flex sm6 style="margin-left: 750px;margin-top: -20px;"><b>Sender Details</b></v-flex>
                                </v-layout>
                            </v-toolbar-title>
                        </v-toolbar>
                        <v-layout wrap>
                            <v-flex xs12 sm6>
                                <ul class="list-group">
                                    <li class="list-group-item"><label for=""><b>Client Name:</b> </label>{{ shipments.client_name }}</li>
                                    <li class="list-group-item"><label for=""><b>Client Email:</b> </label>{{ shipments.client_email }}</li>
                                    <li class="list-group-item"><label for=""><b>Client Phone:</b> </label>{{ shipments.client_phone }}</li>
                                </ul>
                            </v-flex>
                            <v-flex xs12 sm6>
                                <ul class="list-group">
                                    <li class="list-group-item"><label for=""><b>Sender Name:</b> </label>Boxleo Services</li>
                                    <li class="list-group-item"><label for=""><b>Sender Email:</b> </label>info@boxleo.co.ke</li>
                                    <li class="list-group-item"><label for=""><b>Sender Phone:</b> </label>+254743332743</li>
                                </ul>
                            </v-flex>
                        </v-layout>
                        <v-toolbar card blue-grey darken-1>
                            <v-toolbar-title class="body-2">Waybill Event Tracking</v-toolbar-title>
                        </v-toolbar>
                        <v-layout wrap>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Events</th>
                                        <th scope="col">Event date and time</th>
                                        <th scope="col">Location</th>
                                        <th scope="col">Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="statuses in shipments.statuses" :key="statuses.id">
                                        <th scope="row">1</th>
                                        <td>{{ statuses.status }}</td>
                                        <td>{{ statuses.created_at }}</td>
                                        <td>{{ statuses.location }}</td>
                                        <td>{{ statuses.remark }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </v-layout>
                    </v-card-text>
                    <v-card-actions>
                    </v-card-actions>
                    <div v-show="nextPage" style="text-align: center; width: 100%;">
                        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                    </div>
                    <div class="text-xs-center">
                        <v-pagination v-model="AllShip.current_page" :length="AllShip.last_page" total-visible="7" @input="next" circle></v-pagination>
                    </div>
                    <v-divider></v-divider>
                    <v-divider></v-divider>
                </v-card>
            </v-layout>
        </v-container>
    </v-content>
</div>
</template>

<script>
export default {
    props: ['user'],
    components: {},
    data() {
        return {
            loader: false,
            Customerdialog: false,
            dispAdd: false,
            dispShow: false,
            dispEdit: false,
            snackbar: false,
            timeout: 5000,
            color: 'black',
            message: 'Success',
            Allcustomers: [],
            form: {
                search: ''
            },
            AllShip: [],
            nextPage: false,
        }
    },
    methods: {
        next(page) {
            this.nextPage = true
            axios.get(`/DriverShip?page=` + this.AllShip.current_page)
                .then((response) => {
                    this.nextPage = false
                    this.AllShip = response.data
                })
        },
        searchRe() {
            this.nextPage = true
            // alert('success')
            axios.post('/getsearchRe', this.form)
                .then((response) => {
                    this.nextPage = false
                    this.AllShip = response.data
                })
        },
        refresh() {
            this.loader = true
            axios.get('/DriverShip')
                .then((response) => {
                    this.AllShip = response.data
                    this.loader = false
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loader = false
                })
        },
        close() {
            this.dispAdd = this.dispShow = this.dispEdit = false
        },
        showAlert() {
            this.snackbar = true
            this.color = 'indigo'
            this.message = 'success'
        }
    },
    mounted() {
        this.loader = true
        this.refresh()

        axios.get('/getCustomer')
            .then((response) => {
                this.Allcustomers = response.data
                this.loader = false
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                this.loader = false
            })
    },
    // beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //         if (vm.role === 'Admin' || vm.role === 'companyAdmin') {
    //             next();
    //         } else {
    //             next('/unauthorized');
    //         }
    //     })
    // }
}
</script>

<style scoped>
.content--wrap {
    margin-top: -100px
}

#profile {
    width: 70px;
    height: 60px;
    border-radius: 50%;
    margin-left: 80px;
    margin-top: -30px;
}

i {
    padding: 7px;
    background: #f0f0f0;
    border-radius: 50%;
}

.list-group-item:hover,
.list-group-item:focus {
    z-index: 1;
    background: #f9f9f9;
    text-decoration: none;
}

.v-pagination .v-pagination__item--active {
    background: #1867c0 !important;
}

.active,
.list-group-item:focus {
    z-index: 1;
    background: #3490dc !important;
    text-decoration: none;
}
</style>
