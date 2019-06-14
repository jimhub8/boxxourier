<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <!-- <v-layout justify-center align-center> -->
            <!-- Show Container details -->
            <v-layout row justify-center>
                <v-dialog v-model="showdialog1" fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-card id="Container_det">
                        <v-card-title>
                            <v-toolbar dark color="white">
                                <v-btn icon dark @click.native="showdialog1 = false">
                                    <v-icon color="black">close</v-icon>
                                </v-btn>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <span class="headline"></span>
                        </v-card-title>
                        <v-card-text>
                            <v-container grid-list-md text-xs-center>
                                <v-layout row wrap>
                                    <v-flex xs7 sm7>
                                        <v-card>
                                            <v-card-actions>

                                            </v-card-actions>
                                        </v-card>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
                </v-dialog>
                <!-- Show Container details -->

                <!-- Assign Driver -->
                <v-dialog v-model="assigndialog" persistent max-width="290">
                    <!-- <v-btn slot="activator" color="primary" dark>Open Dialog</v-btn> -->
                    <v-card>
                        <v-card-title class="headline">Assign Driver</v-card-title>
                        <v-card-text>

                            <v-flex xs12 sm12>
                                <v-select :items="AllDrivers" v-model="assignItem.driver" label="Select" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                            </v-flex>

                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="darken-1" flat @click.native="assigndialog = false">Close</v-btn>
                            <v-btn color="darken-1" flat @click.native="assignDriver">Assign</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <!-- Assign Driver -->

                <!-- Update Container -->
                <v-dialog v-model="updateModal" fullscreen hide-overlay transition="dialog-bottom-transition">
                    <v-card>
                        <v-toolbar dark color="primary">
                            <v-btn icon dark @click.native="updateModal = false">
                                <v-icon>close</v-icon>
                            </v-btn>
                            <v-toolbar-title>Edit Container</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-container grid-list-md>
                            <v-container fill-height>
                                <v-layout align-center>
                                    <v-flex>
                                        <v-card>
                                            <div v-show="loader" style="text-align: center">
                                                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                                            </div>
                                            <v-card-actions v-show="!loader">
                                                <v-layout row wrap>
                                                    <v-flex xs8 sm8>
                                                        <v-form ref="form" @submit.prevent="submit">
                                                            <h6 class="display-3">Add Shipments To The Container</h6><br>
                                                            <v-divider class="my-3"></v-divider>
                                                            <v-select :items="AllShipments" v-model="addToContainer.selected" label="Select" item-text="client_email" item-value="id" multiple chips max-height="auto" autocomplete>
                                                                <template slot="selection" slot-scope="data">
                                                                    <v-chip :selected="data.selected" :key="JSON.stringify(data.item)" close class="chip--select-multi" @input="data.parent.selectItem(data.item)">
                                                                        <v-avatar>
                                                                            <img :src="data.item.avatar">
                                  </v-avatar>
                                                                            {{ data.item.client_email }}
                                                                    </v-chip>
                                                                </template>
                                                                <template slot="item" slot-scope="data">
                                                                    <template v-if="typeof data.item !== 'object'">
                                                                        <v-list-tile-content v-text="data.item"></v-list-tile-content>
                                                                    </template>
                                                                    <template v-else>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title v-html="data.item.client_email"></v-list-tile-title>
                                                                        </v-list-tile-content>
                                                                    </template>
                                                                </template>
                                                            </v-select>

                                                            <v-card-actions>
                                                                <v-btn flat @click="resetForm">reset</v-btn>
                                                                <v-btn flat @click="close">Close</v-btn>
                                                                <v-spacer></v-spacer>
                                                                <v-btn flat color="primary" @click="addShipment">Add Container</v-btn>
                                                            </v-card-actions>
                                                        </v-form>
                                                    </v-flex>

                                                    <v-flex xs4>
                                                        <v-card>
                                                            <v-flex xs12 sm12>
                                                                <v-text-field v-model="addToContainer.city" :rules="rules.name" color="blue darken-2" label="Current City" required></v-text-field>
                                                            </v-flex>
                                                            <select class="custom-select custom-select-md col-md-12" v-model="addToContainer.status">
                              <!-- <option selected>Insurance Status</option> -->
                              <option value="waiting_pproval">Awaiting Approval</option>
                              <option value="approved">Approved</option>
                              <option value="Cancled">Cancled</option>
                              <option value="Dispatched">Dispatched</option>
                            </select>
                                                            <v-flex xs12 sm12>
                                                                <v-text-field v-model="addToContainer.remark" color="blue" multi-line>
                                                                    <div slot="label">
                                                                        Remark <small>(optional)</small>
                                                                    </div>
                                                                </v-text-field>
                                                                <!-- <v-checkbox 
                              :label="Apply" 
                              v-model="addToContainer.apply"
                            ></v-checkbox> -->
                                                            </v-flex>
                                                            <v-btn color="primary" flat @click="UpdateStatus">Update Status</v-btn>
                                                        </v-card>
                                                    </v-flex>

                                                </v-layout>
                                            </v-card-actions>
                                        </v-card>

                                        <v-layout>
                                            <v-flex xs12 sm12>
                                                <v-card>
                                                    <div v-show="loader" style="text-align: center">
                                                        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                                                    </div>
                                                    <div v-show="!loader">
                                                        <v-card-title>
                                                            Shipments
                                                            <v-spacer></v-spacer>
                                                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                                                        </v-card-title>
                                                        <v-data-table :headers="Shipheaders" :items="ShipmentArray" :search="search" counter class="elevation-1">
                                                            <template slot="items" slot-scope="props">
                                                                <td>
                                                                    {{ props.item.bar_code }}
                                                                </td>
                                                                <td class="text-xs-right">
                                                                    <barcode v-bind:value="props.item.bar_code" style="height: 30px;">
                                                                        No barcode
                                                                    </barcode>
                                                                </td>
                                                                <td class="text-xs-right">{{ props.item.client_name }}</td>
                                                                <td class="text-xs-right">{{ props.item.sender_city }}</td>
                                                                <td class="text-xs-right">{{ props.item.client_city }}</td>
                                                                <td class="text-xs-right">{{ props.item.booking_date }}</td>
                                                                <td class="text-xs-right">{{ props.item.status }}</td>

                                                            </template>
                                                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                                                Your search for "{{ search }}" found no results.
                                                            </v-alert>
                                                        </v-data-table>
                                                    </div>
                                                </v-card>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>

                            </v-container>
                        </v-container>
                        <v-divider></v-divider>
                    </v-card>
                </v-dialog>
                <!-- Update container Modal -->

                <!-- Data table -->
                <div style="width: 100%;">
                    <v-dialog v-model="dialog1" fullscreen hide-overlay transition="dialog-bottom-transition">
                        <v-card>
                            <v-toolbar dark color="primary">
                                <v-btn icon dark @click.native="dialog1 = false">
                                    <v-icon>close</v-icon>
                                </v-btn>
                                <v-toolbar-title>Edit Container</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-form ref="form" @submit.prevent="submit">
                                        <v-container grid-list-xl fluid>
                                            <v-layout wrap>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.address" :rules="rules.name" color="blue darken-2" label="Address" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.city" :rules="rules.name" color="blue darken-2" label="City" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.assign_staff" :rules="rules.name" color="blue darken-2" label="Assigned Staff" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.bar_code" :rules="rules.name" color="blue darken-2" label="Container Number" required></v-text-field>
                                                </v-flex>

                                                <v-spacer></v-spacer>
                                                <!-- date picker -->
                                                <v-flex xs6 sm3 md3>
                                                    <v-dialog ref="dialog2" v-model="dmodal2" :return-value.sync="editedItem.derivery_date" persistent lazy full-width width="290px">
                                                        <v-text-field slot="activator" v-model="editedItem.derivery_date" label="Derivery Date" prepend-icon="event" readonly></v-text-field>
                                                        <v-date-picker v-model="editedItem.derivery_date" scrollable>
                                                            <v-spacer></v-spacer>
                                                            <v-btn flat color="primary" @click="dmodal2 = false">Cancel</v-btn>
                                                            <v-btn flat color="primary" @click="$refs.dialog2.save(editedItem.derivery_date)">OK</v-btn>
                                                        </v-date-picker>
                                                    </v-dialog>
                                                </v-flex>
                                                <!-- date picker -->

                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.derivery_time" :rules="rules.name" :type="'time'" color="blue darken-2" label="Derivery Time" required></v-text-field>
                                                </v-flex>
                                                <barcode v-bind:value="editedItem.bar_code">
                                                    Show this if the rendering fails.
                                                </barcode>

                                            </v-layout>
                                        </v-container>
                                        <v-card-actions>
                                            <!-- <v-btn flat @click="resetForm">reset</v-btn> -->
                                            <v-btn flat @click="close">Close</v-btn>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="update">Edit Container</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-layout>
                            </v-container>
                            <v-divider></v-divider>
                        </v-card>
                    </v-dialog>
                    <!-- Container Edit -->
                    <div v-show="loader" style="text-align: center">
                        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                    </div>
                    <div v-show="!loader">
                        <v-card-title>
                            <v-btn color="primary" flat @click="openContainer">Add Container</v-btn>
                            Containers
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="AllContainers" :search="search" counter class="elevation-1">
                            <template slot="items" slot-scope="props">
                                <td>
                                    {{ props.item.bar_code }}
                                </td>
                                <td class="text-xs-right">
                                    <barcode v-bind:value="props.item.bar_code" style="height: 30px;">
                                        No barcode
                                    </barcode>
                                </td>
                                <td class="text-xs-right">{{ props.item.address }}</td>
                                <td class="text-xs-right">{{ props.item.assign_staff }}</td>
                                <td class="text-xs-right">{{ props.item.derivery_date }}</td>
                                <td class="text-xs-right">{{ props.item.derivery_time }}</td>
                                <td class="text-xs-right">{{ props.item.status }}</td>
                                <td class="justify-center layout px-0">
                                    <v-btn icon class="mx-0" @click="editItem(props.item)">
                                        <v-icon color="blue darken-2">edit</v-icon>
                                    </v-btn>
                                    <v-btn icon class="mx-0" @click="UpdateItem(props.item)">
                                        <v-icon color="blue darken-2" dark>save</v-icon>
                                    </v-btn>
                                    <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                                        <v-icon color="pink darken-2">delete</v-icon>
                                    </v-btn>
                                    <!-- <v-btn icon class="mx-0" @click="showDetails(props.item)">
   <v-icon color="blue darken-2">assign</v-icon>
 </v-btn> -->
                                    <v-btn flat color="primary" @click="openAssign(props.item)">Assign</v-btn>
                                    <v-btn icon class="mx-0" @click="showDetails(props.item)">
                                        <v-icon color="blue darken-2">visibility</v-icon>
                                    </v-btn>

                                </td>
                                </template>
                                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                        </v-data-table>
                    </div>
                </div>
                <!-- Data table -->
            </v-layout>
        </v-container>
    </v-content>
    <AddContainer :addContainer="dialog" @closeRequest="close"></AddContainer>
    <EditContainer :editContainer="dialog1" @closeRequest="close"></EditContainer>
    <!-- <UpdateContainer :UpdateContainer="dialog1" @closeRequest="close"></UpdateContainer> -->
    <v-snackbar :timeout="timeout" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import VueBarcode from 'vue-barcode';
let AddContainer = require('./AddContainer')
let EditContainer = require('./EditContainer')
// let UpdateContainer = require('./UpdateContainer')
export default {
    props: ['user', 'role'],
    components: {
        AddContainer,
        EditContainer,
        'barcode': VueBarcode
        // , UpdateContainer
    },
    data() {
        const defaultForm = Object.freeze({})
        return {
            // select: { state: 'All', abbr: 'all' },
            assigndialog: false,
            snackbar: false,
            timeout: 5000,
            message: 'Success',
            color: 'black',
            y: 'bottom',
            x: 'left',
            // select
            loading: false,
            items: [],
            searchSelected: null,
            select: [],
            addToContainer: {},
            // select
            loader: false,
            barcodeValue: 'test',
            notifications: false,
            dmodal1: false,
            dmodal2: false,
            tmodal: false,
            updateModal: false,
            showdialog1: false,
            sound: true,
            AllContainers: [],
            AllShipments: [],
            AllDrivers: [],
            drivers: {},
            ShipmentArray: [],

            Shipheaders: [{
                    text: 'Airwaybill',
                    value: 'airway_bill_no'
                },
                {
                    text: 'Barcode',
                    value: 'bar_code'
                },
                {
                    text: 'Client',
                    value: 'client_name'
                },
                {
                    text: 'From',
                    value: 'sender_name'
                },
                {
                    text: 'To',
                    value: 'client_city'
                },
                {
                    text: 'Booked on',
                    value: 'booking_date'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
            ],
            search: '',
            temp: '',
            dialog: false,
            dialog1: false,
            pdialog2: false,
            widgets: false,
            form: Object.assign({}, defaultForm),
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
            updateitedItem: {

            },
            AllProducts: {},
            newProducts: {},
            showItem: {},
            editedItem: {},
            assignItem: {},
            headers: [{
                    text: 'airway_bill_no',
                    align: 'left',
                    sortable: false,
                    value: 'name'
                },
                {
                    text: 'Barcode',
                    value: 'bar_code'
                },
                {
                    text: 'Address',
                    value: 'address'
                },
                {
                    text: 'Assigned Staff',
                    value: 'assign_staff'
                },
                {
                    text: 'Derivery Date',
                    value: 'derivery_date'
                },
                {
                    text: 'Derivery Time',
                    value: 'derivery_time'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
            emailRules: [
                v => {
                    return !!v || 'E-mail is required'
                },
                v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
            ],
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
        }
    },
    methods: {
        openContainer() {
            this.dialog = true
        },
        /*
          addShipment() {
            axios.post(`/container/${this.addToContainer.id}`, this.$data.form)
            .then((response) => {
             console.log(response.data); 
             this.AllContainers.push(response.data) 
             this.pdialog2 = false
           })
         },*/
        editContainer(key) {
            // alert(key);
            this.$children[2].list = this.AllContainers[key]
            this.dialog1 = true
        },
        openProduct(updateitedItem) {
            console.log(updateitedItem);
            this.pdialog2 = true
        },
        addShipment() {
            axios.post(`/AddShipments/${this.addToContainer.id}`, this.$data.addToContainer)
                .then((response) => {
                    console.log(response);
                    this.loader = false
                    this.snackbar = true
                    this.color = 'black'
                    this.message = 'Shipment Added'
                })
                .catch((error) => {
                    console.log(error);
                    this.errors = error.response.data.errors
                    this.loader = false
                    this.snackbar = false
                    this.color = 'red'
                })
        },

        UpdateItem(item) {
            this.updateModal = true
            this.addToContainer = Object.assign({}, item)
            this.updatedIndex = this.AllContainers.indexOf(item)
            // console.log(this.updateitedItem);

            this.loader = true
            axios.post(`/getShipmentArray/${item.id}`)
                .then((response) => {
                    this.ShipmentArray = response.data
                    this.loader = false
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loader = false
                    this.snackbar = false
                    this.color = 'red'
                    this.message = 'Something went wrong'
                })
        },
        editItem(item) {
            this.editedItem = Object.assign({}, item)
            this.editedIndex = this.AllContainers.indexOf(item)
            // console.log(this.editedItem);
            this.dialog1 = true
        },

        showDetails(item) {
            this.addToContainer = Object.assign({}, item)
            // alert(this.AllProducts);
            this.editedIndex = this.AllContainers.indexOf(item)
            this.showdialog1 = true
        },

        update() {
            // alert(this.editedItem.id);
            axios.post(`/container/${this.editedItem.id}`, this.$data.editedItem)
                .then((response) => {
                    // this.close()
                    console.log(response);
                    this.snackbar = true
                    this.color = 'black'
                    this.message = 'updated'
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.snackbar = false
                    this.color = 'red'
                    this.message = 'Something went wrong'
                })
        },

        UpdateStatus() {
            // alert(this.updateitedItem.id);      
            axios.post(`/conupdateStatus/${this.addToContainer.id}`, this.$data.addToContainer)
                .then((response) => {
                    console.log(response);
                    this.snackbar = true
                    this.color = 'black'
                    this.message = 'Status Updated'
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.snackbar = false
                    this.color = 'red'
                    this.message = 'Something went wrong'
                })
        },

        openAssign(item) {
            this.assignItem = Object.assign({}, item)
            this.editedIndex = this.AllDrivers.indexOf(item)
            console.log(this.assignItem);
            this.assigndialog = true

        },

        assignDriver() {
            axios.post(`/assigndialog/${this.assignItem.id}`, this.$data.assignItem)
                .then((response) => {
                    this.assigndialog = false
                    this.snackbar = true
                    this.color = 'black'
                    this.message = 'Updated'
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.snackbar = false
                    this.color = 'red'
                    this.message = 'Something went wrong'
                })
        },

        deleteItem(item) {
            const index = this.AllContainers.indexOf(item)
            if (confirm('Are you sure you want to delete this container?')) {
                axios.delete(`/container/${item.id}`)
                    .then((response) => {
                        // this.snackbar = true
                        this.text = 'deleted Success'
                        this.AllContainers.splice(index, 1)
                        console.log(response);
                    })
                    .catch((error) => this.errors = error.response.data.errors)
            }
            this.snackbar = false
            this.color = 'red'
            this.message = 'Something went wrong'
        },

        close() {
            this.dialog1 = this.dialog = this.pdialog2 = false
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
        querySelections(v) {
            this.loading = true
            // Simulated ajax query
        }
    },
    mounted() {
        this.loader = true
        axios.post('/getContainers')
            .then((response) => {
                this.AllContainers = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                this.snackbar = false
                this.color = 'red'
                this.message = 'Something went wrong'
            })

        axios.post('/getContainers')
            .then((response) => {
                this.AllProducts = response.data
            })
            .catch((error) => {
                console.log(error);
                this.errors = error.response.data.errors
                this.snackbar = false
                this.color = 'red'
                this.message = 'Something went wrong'
            })

        axios.post('/getDrivers')
            .then((response) => {
                this.AllDrivers = response.data
            })
            .catch((error) => {
                console.log(error);
                this.errors = error.response.data.errors
                this.snackbar = false
                this.color = 'red'
                this.message = 'Something went wrong'
            })

        axios.post('/getShipments')
            .then((response) => {
                this.AllShipments = response.data
                this.loader = false
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                this.loader = false
                this.snackbar = false
                this.color = 'red'
                this.message = 'Something went wrong'
            })

    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        }
    },
    watch: {
        dialog(val) {
            val || this.close()
        },
        searchSelected(val) {
            val && this.querySelections(val)
        }
    },
}
</script>

<style>
/* This is for documentation purposes and will not be needed in your application */
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
