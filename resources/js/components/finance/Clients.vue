<template>
<div>
    <v-content>
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div class="container">
                    <v-card style="background: rgba(5, 117, 230, 0.16);">
                        <v-layout wrap>
                            <v-flex xs4 sm3 offset-sm4>
                                <v-select :items="items" v-model="select" label="Payment Method" single-line item-text="state" item-value="abbr" return-object persistent-hint></v-select>
                            </v-flex>
                            <!-- <v-spacer></v-spacer> -->
                            <v-flex xs4 sm3>
                                <v-btn raised color="info" @click="sort">Filter</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <!-- users display -->
                    <v-card-title>
                        Users
                            <v-tooltip right>
                                <v-btn icon slot="activator" class="mx-0" @click="getCustomer">
                                    <v-icon color="blue darken-2" small>refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllClients" class="elevation-1" :loading="loading" :search="search">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.name }}</td>
                            <td class="text-xs-right">{{ props.item.email }}</td>
                            <td class="text-xs-right">{{ props.item.address }}</td>
                            <td class="text-xs-right">{{ props.item.phone }}</td>
                            <td class="text-xs-right">{{ props.item.city }}</td>
                            <td class="text-xs-right">{{ props.item.branch }}</td>
                            <td class="text-xs-right">{{ props.item.payment_status }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom v-if="props.item.payment_status === 'Cash'">
                                    <v-btn icon class="mx-0" @click="cashOrInvoice(props.item)" slot="activator">
                                        <v-icon color="blue darken-2" small>book</v-icon>
                                    </v-btn>
                                    <span>Mark as paid</span>
                                </v-tooltip>
                                <v-tooltip bottom v-else>
                                    <v-btn icon class="mx-0" @click="cashOrInvoice(props.item)" slot="activator">
                                        <v-icon color="blue darken-2" dark small>attach_money</v-icon>
                                    </v-btn>
                                    <span>Mark not paid</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                    <!-- users display -->
                </div>
            </v-layout>
        </v-container>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="green" style="margin: 1rem"></v-progress-circular>
        </div>
        <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
            {{ message }}
            <v-icon dark right>check_circle</v-icon>
        </v-snackbar>
    </v-content>
</div>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            Allcountries: [],
            select: {
                state: 'All',
                abbr: 'all'
            },
            items: [{
                    state: 'All',
                    abbr: 'all'
                },
                {
                    state: 'Admin',
                    abbr: 'Admin'
                },
                {
                    state: 'company Admin',
                    abbr: 'companyAdmin'
                },
                {
                    state: 'Customers',
                    abbr: 'Customer'
                },
                {
                    state: 'Drivers',
                    abbr: 'Driver'
                },
            ],
            headers: [{
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Email",
                    value: "email"
                },
                {
                    text: "Address",
                    value: "address"
                },
                {
                    text: "Phone Number",
                    value: "phone"
                },
                {
                    text: "City",
                    value: "city"
                },
                {
                    text: "Branch",
                    value: "branch"
                },
                {
                    text: "Payment status",
                    value: "payment_status"
                },
                {
                    text: 'Actions',
                    sortable: false
                }
            ],
            search: '',
            loader: false,
            dispShow: false,
            snackbar: false,
            loading: false,
            timeout: 5000,
            color: 'black',
            message: 'Success',
            AllClients: [],
            editedItem: {},
            select: {
                state: 'All',
                abbr: 'all'
            },
            items: [{
                    state: 'All',
                    abbr: 'All'
                },
                {
                    state: 'Cash',
                    abbr: 'Cash'
                },
                {
                    state: 'Invoice',
                    abbr: 'Invoice'
                },
            ],
        }
    },
    methods: {
        openShow(item) {
            this.editedIndex = this.AllClients.indexOf(item)
            this.editedItem = Object.assign({}, item)
            eventBus.$emit('getShipEvent', this.editedItem)
            this.dispShow = true
        },
        cashOrInvoice(item) {
            if (item.payment_status == 'Cash') {
                item.payment_status = 'Invoice'
            } else {
                item.payment_status = 'Cash'
            }
            axios
                .post(`/payment/${item.id}`, item)
                .then(response => {
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },
        showAlert() {
            this.message = 'Successifully Added';
            this.snackbar = true;
            this.color = 'black';
        },
        sort() {
            this.loading = true
            axios.post('/filterPayment', this.select)
                .then((response) => {
                    this.loading = false
                    this.AllClients = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        close() {
            this.dispShow = false
        },
        getCustomer() {
            this.loading = true
            axios.get('/getCustomer')
                .then((response) => {
                    this.loader = false
                    this.loading = false
                    this.AllClients = response.data
                })
                .catch((error) => {
                    this.loader = false
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        getCountry() {
            this.loading = true
            axios.get('/getCountry')
                .then((response) => {
                    this.loading = false
                    this.Allcountries = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
    },
    mounted() {
        this.loader = true
        this.getCustomer()
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can['view users']) {
                next();
            } else {
                next('/unauthorized');
            }
        })
    }
}
</script>
