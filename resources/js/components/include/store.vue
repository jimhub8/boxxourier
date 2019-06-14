<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <v-layout justify-center align-center>
                <v-dialog v-model="pdialog2" max-width="500px">
                    <v-card>
                        <v-card-title>
                            Products
                        </v-card-title>
                        <v-card-text>
                            <v-form ref="form" @submit.prevent="submit">
                                <v-container grid-list-xl fluid>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="form.product_name" :rules="rules.name" color="blue darken-2" label="Product Name" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="form.weight" :rules="rules.name" color="blue darken-2" label="Weight" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="form.quantity" :rules="rules.name" color="blue darken-2" label="Quantity" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="form.each_cost" :rules="rules.name" color="blue darken-2" label="Each Cost" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="form.total_cost" :rules="rules.name" color="blue darken-2" label="Total Cost" required></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="primary" flat @click.stop="pdialog2=false">Close</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="primary" flat @click.stop="add">Add</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
                <!-- <v-card>
            <v-card-title>
            <v-btn slot="activator" color="primary" dark @click="openShipment">Add a Shipment</v-btn>
              Shipments
              <v-spacer></v-spacer>
              <v-text-field
              v-model="search"
              append-icon="search"
              label="Search"
              single-line
              hide-details
              ></v-text-field>
           </v-card-title>
           <v-data-table
           item-key="id"
           :headers="headers"
           :items="AllShipments"
           :search="search"
           >
           <template slot="items" slot-scope="props">
              <td>{{ props.item.name }}</td>
              <td class="text-xs-right">{{ props.item.bar_code }}</td>
              <td class="text-xs-right">{{ props.item.airway_bill_no }}</td>
              <td class="text-xs-right">{{ props.item.receiver_name }}</td>
              <td class="text-xs-right">{{ props.item.sender_name }}</td>
              <td class="text-xs-right">{{ props.item.receiver_city }}</td>
              <td class="text-xs-right">{{ props.item.booking_date }}</td>
              <td class="text-xs-right">{{ props.item.status }}</td>
              <td class="text-xs-right">
                 <v-speed-dial
                       v-model="fab"
                       :bottom="bottom"
                       :left="left"
                       :direction="direction"
                       :open-on-hover="hover"
                       :transition="transition"
                     >
                       <v-btn
                         slot="activator"
                         v-model="fab"
                         color="blue darken-2"
                         dark
                         fab
                       >
                         <v-icon>account_circle</v-icon>
                         <v-icon>close</v-icon>
                       </v-btn>
                       <v-btn
                         fab
                         dark
                         small
                         color="green"
                         @click="editShipment(id)"
                       >
                         <v-icon>edit</v-icon>
                       </v-btn>
                       <v-btn
                         fab
                         dark
                         small
                         color="indigo"
                         @click="openShipment"
                       >
                         <v-icon>add</v-icon>
                       </v-btn>
                       <v-btn
                         fab
                         dark
                         small
                         color="red"
                         @click="del(id)"
                       >
                         <v-icon>delete</v-icon>
                       </v-btn>
                     </v-speed-dial>
              </td>
           </template>
           <v-alert slot="no-results" :value="true" color="error" icon="warning">
              Your search for "{{ search }}" found no results.
           </v-alert>
        </v-data-table>
     </v-card> -->

                <!-- data tables -->
                <div>
                    <v-dialog v-model="dialog1" fullscreen hide-overlay transition="dialog-bottom-transition">
                        <v-card>
                            <v-toolbar dark color="primary">
                                <v-btn icon dark @click="close">
                                    <v-icon>close</v-icon>
                                </v-btn>
                                <v-toolbar-title>Edit Shipment</v-toolbar-title>
                                <v-spacer></v-spacer>
                            </v-toolbar>
                            <v-container grid-list-md>
                                <v-layout wrap>
                                    <v-form ref="form" @submit.prevent="submit">
                                        <v-container grid-list-xl fluid>
                                            <v-layout wrap>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.receiver_name" :rules="rules.name" color="blue darken-2" label="Receiver name" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.receiver_email" :rules="emailRules" color="blue darken-2" label="Receiver Email" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.receiver_address" :rules="rules.name" color="blue darken-2" label="Receiver Address" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.receiver_city" :rules="rules.name" color="blue darken-2" label="Receiver City" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.receiver_phone" :rules="rules.name" color="blue darken-2" label="Receiver Phone" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.assign_staff" :rules="rules.name" color="blue darken-2" label="Assigned Staff" required></v-text-field>
                                                </v-flex>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.airway_bill_no" :rules="rules.name" color="blue darken-2" label="Zip Code" required></v-text-field>
                                                </v-flex>
                                                <v-divider></v-divider>
                                                <v-flex xs4 sm3>
                                                    <v-text-field v-model="editedItem.shipment_type" :rules="rules.name" color="blue darken-2" label="Shipment Type" required></v-text-field>
                                                </v-flex>

                                                <v-spacer></v-spacer>
                                                <!-- date picker -->
                                                <v-flex xs6 sm3 md3>
                                                    <v-dialog ref="dialog1" v-model="dmodal1" :return-value.sync="editedItem.booking_date" persistent lazy full-width width="290px">
                                                        <v-text-field slot="activator" v-model="editedItem.booking_date" label="Booking Date" prepend-icon="event" readonly></v-text-field>
                                                        <v-date-picker v-model="editedItem.booking_date" scrollable>
                                                            <v-spacer></v-spacer>
                                                            <v-btn flat color="primary" @click="dmodal1 = false">Cancel</v-btn>
                                                            <v-btn flat color="primary" @click="$refs.dialog1.save(editedItem.booking_date)">OK</v-btn>
                                                        </v-date-picker>
                                                    </v-dialog>
                                                </v-flex>

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
                                                <select class="custom-select custom-select-md col-md-3" v-model="editedItem.insuarance_status">
                      <option selected>Insurance Status</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                    </select>
                                                <select class="custom-select custom-select-md col-md-3" v-model="editedItem.payment">
                      <option selected>Paid</option>
                      <option value="yes">Yes</option>
                      <option value="no">No</option>
                    </select>
                                                <v-divider></v-divider>
                                                <v-btn color="success" dark @click="pdialog2 = true">Add products</v-btn>
                                                <v-divider></v-divider>
                                            </v-layout>
                                        </v-container>
                                        <v-card-actions>
                                            <!-- <v-btn flat @click="resetForm">reset</v-btn> -->
                                            <v-btn flat @click="close">Close</v-btn>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click="update">Add Shipment</v-btn>
                                        </v-card-actions>
                                    </v-form>
                                </v-layout>
                            </v-container>
                            <v-divider></v-divider>
                        </v-card>
                    </v-dialog>
                    <v-data-table :headers="headers" :items="AllShipments" :search="search" class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.id }}</td>
                            <td class="text-xs-right">{{ props.item.bar_code }}</td>
                            <td class="text-xs-right">{{ props.item.airway_bill_no }}</td>
                            <td class="text-xs-right">{{ props.item.receiver_name }}</td>
                            <td class="text-xs-right">{{ props.item.sender_name }}</td>
                            <td class="text-xs-right">{{ props.item.receiver_city }}</td>
                            <td class="text-xs-right">{{ props.item.booking_date }}</td>
                            <td class="text-xs-right">{{ props.item.status }}</td>
                            <td class="justify-center layout px-0">
                                <v-btn icon class="mx-0" @click="editItem(props.item)">
                                    <v-icon color="teal">edit</v-icon>
                                </v-btn>
                                <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                                    <v-icon color="pink">delete</v-icon>
                                </v-btn>
                            </td>
                        </template>
                        <template slot="no-data">
                            <v-btn color="primary" @click="initialize">Reset</v-btn>
                        </template>
                    </v-data-table>
                </div>

                <!-- data tables -->

                <!-- <table class="table table-hover table-striped table-responsive">
        <thead>
           <th>Bar code</th>
           <th>Airway Bill Number</th>
           <th>Receiver Name</th>
           <th>Sender Name</th>
           <th>From</th>
           <th>To</th>
           <th>Booking Date</th>
           <th>Status</th>
           <th>#</th>
        </thead>
        <tbody>
           <tr v-for="shipment, key in AllShipments">
              <td>{{ key }}</td>
              <td>{{ shipment.airway_bill_no }}</td>
              <td>{{ shipment.receiver_name }}</td>
              <td>{{ shipment.sender_name }}</td>
              <td>{{ shipment.sender_city }}</td>
              <td>{{ shipment.receiver_city }}</td>
              <td>{{ shipment.booking_date }}</td>
              <td>{{ shipment.status }}</td>
              <td>
                  <v-btn color="success" dark @click="pdialog2 = true">Add products</v-btn>
                 <v-speed-dial
                 v-model="fab"
                 :bottom="bottom"
                 :left="left"
                 :direction="direction"
                 :open-on-hover="hover"
                 :transition="transition"
                 >

                 <v-btn
                 slot="activator"
                 v-model="fab"
                 color="blue darken-2"
                 dark
                 fab
                 >
                 <v-icon>account_circle</v-icon>
                 <v-icon>close</v-icon>
              </v-btn>
              <v-btn
              fab
              dark
              small
              color="green"
              @click="editShipment(key)"
              >
              <v-icon>edit</v-icon>
           </v-btn>
           <v-btn
           fab
           dark
           small
           color="indigo"
           @click="addProduct(key, shipment.id)"
           >
           <v-icon>add</v-icon>
        </v-btn> -->
                <!-- <v-btn
        fab
        dark
        small
        color="red"
        @click="del(key, id)"
        >
        <v-icon>delete</v-icon>
     </v-btn> -->
                </v-speed-dial>
                </td>
                </tr>
                </tbody>
                </table>
            </v-layout>
        </v-container>
    </v-content>
    <AddShipment :addShipment="dialog" @closeRequest="close"></AddShipment>
    <EditShipment :EditShipment="dialog1" @closeRequest="close"></EditShipment>
</div>
</template>

<script>
let AddShipment = require('./AddShipment')
let EditShipment = require('./EditShipment')
export default {
    components: {
        AddShipment,
        EditShipment
    },
    data() {
        const defaultForm = Object.freeze({
            total_weight: '',
            total_cost: '',
            quantity: '',
            weight: '',
            each_cost: '',
            product_name: '',
        })
        return {
            notifications: false,
            dmodal1: false,
            dmodal2: false,
            tmodal: false,
            sound: true,
            AllShipments: [],
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
            editedItem: {
                receiver_name: '',
                receiver_phone: '',
                receiver_email: '',
                receiver_address: '',
                receiver_city: '',
                assign_staff: '',
                airway_bill_no: '',
                shipment_type: '',
                payment: '',
                total_freight: '',
                insuarance_status: '',
                booking_date: null,
                derivery_date: null,
                derivery_time: null,
            },
            headers: [
                // {
                //    text: 'Dessert (100g serving)',
                //    align: 'left',
                //    sortable: false,
                //    value: 'name'
                // },
                {
                    text: 'Barcode',
                    value: 'bar_code'
                },
                {
                    text: 'Airwaybill',
                    value: 'airway_bill_no'
                },
                {
                    text: 'Client',
                    value: 'receiver_name'
                },
                {
                    text: 'From',
                    value: 'sender_name'
                },
                {
                    text: 'To',
                    value: 'receiver_city'
                },
                {
                    text: 'Booked on',
                    value: 'booking_date'
                },
                {
                    text: 'Status',
                    value: 'status'
                },
                // { text: 'Action', value: '#' },
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
            direction: 'left',
            fab: false,
            fling: false,
            hover: false,
            tabs: null,
            bottom: true,
            left: true,
            transition: 'slide-y-reverse-transition'
        }
    },
    methods: {
        openShipment() {
            this.dialog = true
        },
        addProduct(key, id) {
            pdialog2 = true;
        },
        editShipment(key) {
            // alert(key);
            this.$children[2].list = this.AllShipments[key]
            this.dialog1 = true
        },
        /*del(key, id) {

        },*/
        /*close() {
           this.dialog = this.dialog1 = false
        },*/
        initialize() {
            this.AllShipments
        },
        editItem(item) {
            this.editedItem = Object.assign({}, item)
            this.editedIndex = this.AllShipments.indexOf(item)
            console.log(this.editedItem);
            this.dialog1 = true
        },

        update() {
            alert(this.editedItem.id);
            axios.patch(`/shipment/${this.editedItem.id}`, this.$data.editedItem)
                .then((response) => {
                    // this.close()
                    console.log(response);
                    // this.$emit('alertRequest')
                    // this.$parent.manage.push(response.data)
                })
                .catch((error) => this.errors = error.response.data.errors)
        },

        deleteItem(item) {
            const index = this.AllShipments.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.AllShipments.splice(index, 1)
        },

        close() {
            this.dialog1 = false
        },

        /*save () {
          if (this.editedIndex > -1) {
            Object.assign(this.AllShipments[this.editedIndex], this.editedItem)
          } else {
            axios.patch('/getShipments/${this.list.id}')
               .then((response) => {
                 this.AllShipments.push(this.editedItem)               
              })
               .catch((error) => this.errors = error.response.data.errors)
          }
          this.close()
        }*/
    },
    mounted() {
        axios.post('/getShipments')
            .then((response) => this.AllShipments = this.temp = response.data)
            .catch((error) => this.errors = error.response.data.errors)
    },
    computed: {
        activeFab() {
            switch (this.tabs) {
                case 'one':
                    return {
                        'class': 'purple',
                        icon: 'account_circle'
                    }
                case 'two':
                    return {
                        'class': 'red',
                        icon: 'edit'
                    }
                case 'three':
                    return {
                        'class': 'green',
                        icon: 'keyboard_arrow_up'
                    }
                default:
                    return {}
            }
        },
        formTitle() {
            return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        }
    },
    created() {
        this.initialize()
    },
    watch: {
        dialog(val) {
            val || this.close()
        },

        top(val) {
            this.bottom = !val
        },
        right(val) {
            this.left = !val
        },
        bottom(val) {
            this.top = !val
        },
        left(val) {
            this.right = !val
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
