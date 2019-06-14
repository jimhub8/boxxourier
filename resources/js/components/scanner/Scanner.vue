<template>
<v-content>
    <v-container fluid fill-height v-show="!loader">
        <v-layout justify-center align-center>
            <v-snackbar v-model="snackbar" absolute bottom left dark :color="color">
                <span>{{message}}</span>
                <v-icon dark>{{ icon }}</v-icon>
            </v-snackbar>
            <v-card>
                <!-- <small class="has-text-danger" v-if="errors.form.status_in">{{ errors.status_in[0] }}</small><br>
                <small class="has-text-danger" v-if="errors.form.branch_id">{{ errors.branch_id[0] }}</small><br>
                <small class="has-text-danger" v-if="errors.form.status_out">{{ errors.status_out[0] }}</small><br>
                <small class="has-text-danger" v-if="errors.form.rider_out">{{ errors.rider_out[0] }}</small> -->
                <v-layout row wrap>
                    <v-flex sm6>
                        <v-form ref="form" @submit.prevent style="width: 100%;">
                            <v-container grid-list-md text-xs-center>
                                <h2>Branch </h2>
                                <v-divider></v-divider>
                                <v-layout row wrap>
                                    <!-- <v-flex xs6 sm6>
                                        <v-text-field v-model="form_in.scan_date" :type="'date'" color="blue darken-2" label="Date" required></v-text-field>
                                    </v-flex> -->
                                    <div class="form-group col-md-4">
                                        <label for="">Status</label>
                                        <select v-model="form_in.status_in" class="custom-select custom-select-md col-md-12">
                                            <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">Branches</label>
                                        <select v-model="form_in.branch_id" class="custom-select custom-select-md col-md-12">
                                            <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{ branch.branch_name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputAddress2">Location</label>
                                        <input type="text" class="form-control" id="inputAddress2" placeholder="Location" v-model="form_in.location_in">
                                    </div>
                                        <div class="form-group col-md-12">
                                            <label for="inputCity">Remarks</label>
                                            <textarea class="form-control" v-model="form_in.remarks_in" placeholder="Remarks" rows="3"></textarea>
                                        </div>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex xs12 sm12>
                                        <div class="form-group col-md-12">
                                            <label for="inputAddress1">Inscan</label>
                                            <input type="text" class="form-control" id="inputAddress1" placeholder="Barcode" v-model="form_in.bar_code_in" @change="Inscan" ref="scanIn">
                                        </div><br>
                                            <v-flex xs12 sm12>
                                                <barcode :value="form_in.bar_code_in" style="height: 30px;"></barcode>
                                            </v-flex>
                                            <v-divider></v-divider>
                                            <v-btn color="primary" flat @click="Inscansub" :disabled="loading_in" :loading="loading_in">Outscan
                                            </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-flex>
                    <v-divider vertical></v-divider>
                    <v-flex sm5>
                        <v-form ref="form" @submit.prevent style="width: 100%;">
                            <v-container grid-list-md text-xs-center>
                                <h2>Out Scan</h2>
                                <v-divider></v-divider>
                                <v-layout row wrap>
                                    <div class="form-group col-md-4">
                                        <label for="">Status</label>
                                        <select v-model="form_out.status_out" class="custom-select custom-select-md col-md-12">
                                            <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="">Rider</label>
                                        <select v-model="form_out.rider_out" class="custom-select custom-select-md col-md-12">
                                            <option v-for="rider in AllRiders" :key="rider.id" :value="rider.id">{{ rider.name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="inputAddress2">Location</label>
                                        <input type="text" class="form-control" id="inputAddress2" placeholder="Location" v-model="form_out.location_out">
                                    </div>

                                        <v-flex xs6 sm6>
                                            <v-text-field v-model="form_out.scan_date_out" :type="'date'" color="blue darken-2" label="Date" required></v-text-field>
                                        </v-flex>
                                        <div class="form-group col-md-12">
                                            <label for="inputCity">Remarks</label>
                                            <textarea class="form-control" v-model="form_out.remarks_out" placeholder="Remarks" rows="3"></textarea>
                                        </div>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex xs12 sm12>
                                        <div class="form-group">
                                            <label for="inputAddress2">Outscan</label>
                                            <input type="text" class="form-control" id="inputAddress2" placeholder="Barcode" v-model="form_out.bar_code_out" @change="Outscan">
                                        </div><br>
                                            <v-flex xs12 sm12>
                                                <barcode :value="form_out.bar_code_out" style="height: 30px;"></barcode>
                                            </v-flex>
                                            <v-divider></v-divider>
                                            <v-btn color="primary" flat @click="OutscanSub" :disabled="loading" :loading="loading">Outscan
                                            </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-form>
                    </v-flex>
                </v-layout>
                <v-card v-if="AllScanned.length > 0">
                    <v-card-title>
                        Shipments
                        <!-- <v-spacer></v-spacer> -->
                        <v-tooltip bottom>
                            <v-btn slot="activator" icon class="mx-0" @click="resetForm">
                                <v-icon small color="blue darken-2">refresh</v-icon>
                            </v-btn>
                            <span>Reset</span>
                        </v-tooltip>
                        <!-- <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field> -->
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllScanned" class="elevation-1" :loading="loading">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.bar_code }}</td>
                            <td class="text-xs-right">{{ props.item.cod_amount }}</td>
                            <td class="text-xs-right">{{ props.item.client_name }}</td>
                            <td class="text-xs-right">{{ props.item.client_address }}</td>
                            <td class="text-xs-right">{{ props.item.client_phone }}</td>
                            <td class="text-xs-right">{{ props.item.derivery_date }}</td>
                            <td class="text-xs-right">{{ props.item.speciial_instruction }}</td>
                            <!-- <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <v-btn slot="activator" icon class="mx-0" @click="assignDriver(props.item)">
                                        <v-icon small color="blue darken-2">edit</v-icon>
                                    </v-btn>
                                    <span>Update</span>
                                </v-tooltip>
                            </td> -->
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                </v-card>
            </v-card>
        </v-layout>
    </v-container>
    <div v-show="loader" style="text-align: center; width: 100%;">
        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
    </div>
</v-content>
</template>

<script>
import VueBarcode from 'vue-barcode';
export default {
    props: ['user'],
    components: {
        'barcode': VueBarcode
        // , UpdateShipment
    },
    data() {
        const defaultForm = Object.freeze({
            bar_code: ''
        })
        return {
            errors: [],
            message: 'test',
            AllShipments: {},
            form: Object.assign({}, defaultForm),
            form_out: {
                rider_out: '',
                bar_code_out: '',
                status_out: '',
                scan_date_out: '',
                remarks_out: '',
                location_out: ''
            },
            form_in: {
                branch_id: '',
                bar_code_in: '',
                status_in: '',
                // scan_date: '',
                remarks_in: '',
                location_in: ''
            },
            branches: [],
            snackbar: false,
            errors: {},
            icon: 'check_circle',
            color: 'black',
            loader: false,
            loading_in: false,
            loading: false,
            search: '',
            statuses: [],
            AllRiders: [],
            AllScanned: [],
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
                    text: 'Delivery date',
                    value: 'derivery_date'
                },
                {
                    text: 'Special Instructions',
                    value: 'speciial_instruction'
                },
                // {
                //     text: 'Actions',
                //     sortable: false
                // }
            ],
        }
    },
    methods: {
        reset() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form_in.reset()
        },
        resetForm() {
            this.AllScanned = []
        },
        Outscan() {
            this.loading = true
            axios.post(`/barcodeUpdate/${this.form_out.bar_code_out}`, this.$data.form_out)
                .then((response) => {
                    this.loading = false
                    if (response.data.errors === 'errors') {
                        this.snackbar = true
                        this.message = 'shipment Not found'
                        this.icon = 'block'
                        this.color = 'red'
                    } else {
                        this.AllScanned.push(response.data);
                        this.form_out.bar_code_out = ''
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loading = false
                })
        },
        // },
        Inscan() {
            this.loading_in = true
            axios.post(`/barcodeIn/${this.form_in.bar_code_in}`, this.$data.form_in)
                .then((response) => {
                    this.loading_in = false
                    if (response.data.errors === 'errors') {
                        // if (response.errors) {
                        this.snackbar = true
                        this.message = 'shipment Not found'
                        this.icon = 'block'
                        this.color = 'red'
                    } else {
                        this.AllScanned.push(response.data);
                        this.form_in.bar_code_in = ''
                    }
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loading_in = false
                })
        },

        OutscanSub() {
            this.loading = true
            axios.post('/statusUpdate', {
                    scan: this.$data.AllScanned,
                    form: this.$data.form_out
                })
                .then((response) => {
                    this.loading = false
                    this.resetForm()
                    this.snackbar = true
                    this.message = 'successifully scanned'
                    this.icon = 'check_circle'
                    this.color = 'indigo'
                    this.form_out.rider_out = ''
                    this.form_out.bar_code_out = ''
                    this.form_out.status_out = ''
                    this.form_out.scan_date_out = ''
                    this.form_out.remarks_out = ''
                    this.form_out.location_out = ''
                })
                .catch((error) => {
                    this.loading = false
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.errors = error.response.data.errors
                })
        },
        Inscansub() {
            this.loading_in = true
            axios.post('/statusUpdateIn', {
                    scan: this.$data.AllScanned,
                    form: this.$data.form_in
                })
                .then((response) => {
                    this.loading_in = false
                    this.snackbar = true
                    this.message = 'successifully scanned'
                    this.icon = 'check_circle'
                    this.color = 'indigo'
                    this.resetForm()
                    this.form_in.branch_id = ''
                    this.form_in.bar_code_in = ''
                    this.form_in.status_in = ''
                    // this.form_in.scan_date_in = ''
                    this.form_in.remarks_in = ''
                    this.form_in.location_in = ''
                })
                .catch((error) => {
                    this.loading_in = false
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.errors = error.response.data.errors
                })
        },
    },
    mounted() {
        this.loader = true
        // axios.get('/getShipments')
        //     .then((response) => {
        //         this.AllShipments = response.data
        //         this.loader = false
        //     })
        //     .catch((error) => {
        //         this.errors = error.response.data.errors
        //         this.loader = false
        //     })
        axios.get('/getStatuses')
            .then((response) => {
                this.statuses = response.data
                this.loader = false
            })
            .catch((error) => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if (error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
            })

        axios.get('/getBranch')
            .then((response) => {
                this.branches = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                this.loader = false
            })

        axios.get('/getDrivers')
            .then((response) => {
                this.AllRiders = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    },
    computed: {
        formIsValid() {
            return (
                this.form.bar_code
            )
        },
        submit() {
            if (this.form.bar_code) {
                return this.Outscan
            }
        }
    },
}
</script>

<style scoped>

</style>
