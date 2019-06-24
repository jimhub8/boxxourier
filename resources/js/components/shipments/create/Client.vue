<template>
<v-card>
    <v-card-title>
        Add Shipment
        <v-spacer></v-spacer>
        <v-btn icon dark @click="close">
            <v-icon color="black">close</v-icon>
        </v-btn>
    </v-card-title>
    <v-container grid-list-md v-show="!loader">
        <v-card-text>
            <v-layout wrap>
                <v-form ref="form" @submit.prevent="submit">
                    <v-container grid-list-xl fluid>
                        <v-layout wrap>
                            <!-- <div v-for="client in Allcustomer" :key="client.id" v-if="client.id = selectC.id"> -->

                            <!--  -->
                            <v-flex sm6>
                                <h3><b>Pickup At:</b></h3>
                                <v-divider></v-divider>
                                <div v-if="role === 'Customer'">
                                    <v-layout wrap row>
                                        <v-flex xs6 sm6>
                                            <v-text-field v-model="role" :rules="rules.name" color="blue darken-2" label="Sender name" required></v-text-field>
                                        </v-flex>
                                        <!-- </div> -->
                                        <v-flex xs6 sm6>
                                            <v-text-field v-model="user.email" :rules="emailRules" color="blue darken-2" label="Sender Email" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 sm6>
                                            <v-text-field v-model="user.address" :rules="rules.name" color="blue darken-2" label="Sender Address" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 sm6>
                                            <v-text-field v-model="user.city" :rules="rules.name" color="blue darken-2" label="Sender City" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs6 sm6>
                                            <v-text-field v-model="user.phone" color="blue darken-2" label="Sender Phone" required></v-text-field>
                                        </v-flex>

                                        <v-flex xs6 sm6>
                                            <v-text-field v-model="user.country" :rules="rules.name" color="blue darken-2" label="Country" required></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </div>
                            </v-flex>
                            <v-flex sm6>
                                <h3><b>Deliver To:</b></h3>
                                <v-divider></v-divider>
                                <v-layout wrap>
                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.client_name" :rules="rules.name" color="blue darken-2" label="Client name" required></v-text-field>
                                    </v-flex>
                                    <!-- </div> -->
                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.client_email" :rules="emailRules" color="blue darken-2" label="Client Email" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.client_address" :rules="rules.name" color="blue darken-2" label="Client Address" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.client_city" :rules="rules.name" color="blue darken-2" label="Client City" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.client_phone" color="blue darken-2" label="Client Phone" required></v-text-field>
                                    </v-flex>

                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.cod_amount" :rules="rules.name" color="blue darken-2" label="COD Amount" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.from_city" :rules="rules.name" color="blue darken-2" label="From" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs6 sm6>
                                        <v-text-field v-model="form.to_city" :rules="rules.name" color="blue darken-2" label="To" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>

                            <!-- Product Details -->
                            <v-layout wrap>
                                <v-flex sm12>
                                    <h3><b>Parcel Details</b></h3>
                                    <v-divider></v-divider>
                                    <v-layout wrap>

                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th scope="col">#</th>
                                                    <th scope="col">Product Description</th>
                                                    <!-- <th scope="col">COD Amount</th> -->
                                                    <th scope="col">Price</th>
                                                    <th scope="col">Quantity</th>
                                                    <th scope="col">Total Weight</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="product, key in form.products">
                                                    <th scope="row">{{ key+1 }}</th>
                                                    <td><input type="text" class="form-control" placeholder="Product Description" v-model="product.product_name"></td>
                                                        <!-- <td><input type="text" class="form-control" placeholder="COD Amount" v-model="product.cod_amount"></td> -->
                                                    <td><input type="text" class="form-control" placeholder="Price" v-model="product.price"></td>
                                                    <td><input type="text" class="form-control" placeholder="Quantity" v-model="product.quantity"></td>
                                                    <td><input type="text" class="form-control" placeholder="Total Weight" v-model="product.weight"></td>
                                                    <td>
                                                        <v-btn @click="remove(product)" icon class="mx-0">
                                                            <v-icon color="pink darken-2" small>delete</v-icon>
                                                        </v-btn>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <v-divider></v-divider>
                                        <v-layout wrap>
                                            <v-flex xs12 sm6>
                                                <v-text-field v-model="subTotal" color="blue darken-2" label="Price" disabled></v-text-field>
                                            </v-flex>

                                            <v-flex xs12 sm6 class="form-group">
                                                <label for="insurance" class="col-md-4 col-form-label text-md-right">Payment Type</label>
                                                <select class="custom-select" v-model="form.payment">
                                                            <option value="cash">Cash</option>
                                                            <option value="invoice">Invoice</option>
                                                        </select>
                                            </v-flex>
                                            <v-btn color="primary" flat @click="add_product">Add product</v-btn>
                                        </v-layout>
                                    </v-layout>
                                    <v-divider></v-divider>
                                </v-flex>
                            </v-layout>
                            <!-- Product Details -->

                            <v-flex xs12 sm12>
                                <v-layout wrap>
                                    <v-divider></v-divider>

                                    <v-flex sm4>
                                        <h3><b>Service Type</b></h3>
                                        <v-divider></v-divider>
                                        <v-layout wrap>
                                            <select class="custom-select custom-select-md col-md-12" v-model="form.shipment_type">
                                                        <option value="Pick up">Pick up</option>
                                                        <option value="OVS">OVS</option>
                                                        <option value="Same day">Same day</option>
                                                    </select>

                                            <div class="form-group col-md-6">
                                                <label for="insurance" class="col-md-4 col-form-label text-md-right">Insuarance</label>
                                                <select class="custom-select" v-model="form.insuarance_status">
                                                            <option value="yes">Yes</option>
                                                            <option value="no">No</option>
                                                        </select>
                                            </div>

                                            <!-- </div>  -->
                                            <div class="form-group col-md-4">
                                                <label for="payment" class="col-md-4 col-form-label text-md-right">Paid</label>
                                                <select class="custom-select" v-model="form.payment">
                                                            <option value="yes">Yes</option>
                                                            <option value="no">No</option>
                                                        </select>
                                            </div>
                                        </v-layout>
                                    </v-flex>

                                    <v-flex sm4>
                                        <h3><b>Special Instructions</b></h3>
                                        <v-divider></v-divider>
                                        <v-layout wrap>
                                            <v-flex xs12 sm12>

                                                <v-textarea v-model="form.remark" color="blue">
                                                    <div slot="label">
                                                        Instructions
                                                    </div>
                                                </v-textarea>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>

                                    <v-flex xs12 sm4>
                                        <v-select :items="Allcustomer" v-model="selectC" :hint="`${selectC.name}`" label="Select Client" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm4>
                                        <v-select :items="AllDrivers" v-model="selectD" :hint="`${select.name}`" label="Select Driver" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                                    </v-flex>
                                    <v-flex xs12 sm4>
                                        <v-select :items="AllBranches" v-model="selectB" :hint="`${selectB.branch_name}`" label="Select Branch" single-line item-text="branch_name" item-value="id" return-object persistent-hint></v-select>
                                    </v-flex>

                                    <v-flex xs12 sm4>
                                        <v-text-field v-model="form.derivery_date" :type="'date'" color="blue darken-2" label="Delivery Date" required></v-text-field>
                                    </v-flex>

                                    <v-flex xs12 sm4>
                                        <v-text-field v-model="form.derivery_time" :type="'time'" color="blue darken-2" label="Ready Time" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-flex>
                            <!--  -->

                        </v-layout>
                        <div class="row">

                        </div>
                        <v-flex xs6 sm6>
                            <v-text-field v-model="form.bar_code" color="blue darken-2" label="Barcode" required></v-text-field>
                        </v-flex>
                        <barcode v-bind:value="form.bar_code"></barcode>
                    </v-container>
                </v-form>
            </v-layout>
        </v-card-text>
        <v-card-actions>
            <v-btn flat @click="resetForm">reset</v-btn>
            <v-btn flat @click="close">Close</v-btn>
            <v-spacer></v-spacer>
            <v-btn flat color="primary" @click="save" :loading="loading" :disabled="loading">Add Shipment</v-btn>
        </v-card-actions>
    </v-container>
    <div v-show="loader" style="text-align: center">
        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
    </div>
</v-card>
</template>

<script>
import VueBarcode from "vue-barcode";
export default {
    props: ["addShipment", "user", 'role', 'Allcustomer', 'AllDrivers', 'AllBranches'],
    components: {
        barcode: VueBarcode
    },
    data() {
        const defaultForm = Object.freeze({
            client_name: "",
            client_phone: "",
            client_email: "",
            client_address: "",
            client_city: "",
            airway_bill_no: "",
            shipment_type: "",
            payment: "",
            total_freight: "",
            insuarance_status: "",
            payment: "",
            booking_date: null,
            derivery_date: null,
            derivery_time: null,
            bar_code: "",
            getTotal: '',
            status: 'warehouse',
            cod_amount: '',
            from_city: '',
            to_city: '',
            products: [{
                product_name: '',
                weight: null,
                quantity: 1,
                price: 0,
            }],
            // sender_name: 'SpeedBall courier services',
            // sender_email: 'info@boxleocourier.com',
            // sender_phone: '+254728492446',
            // sender_address: '636400100',
            // sender_city: 'Embakasi, Nairobi',

            sender_name: '',
            sender_email: '',
            sender_phone: '',
            sender_address: '',
            sender_city: '',
        });
        return {
            selectCl: [],
            cust: {
                name: "",
                phone: "",
                email: "",
                address: "",
                city: "",
                country: "",
            },
            customerArr: [],
            loading: false,
            selectD: [],
            selectC: [],
            selectB: [],
            select: [],
            loader: false,
            isLoading: false,
            items: [],
            model: null,
            search: null,
            form: Object.assign({}, defaultForm),
            emailRules: [
                v => {
                    return !!v || "E-mail is required";
                },
                v =>
                /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                "E-mail must be valid"
            ],
            rules: {
                name: [val => (val || "").length > 0 || "This field is required"]
            },
            items: [{
                    state: "Yes",
                    abbr: "yes"
                },
                {
                    state: "No",
                    abbr: "no"
                }
            ]
        };
    },
    methods: {
        save() {
            this.loading = true;
            axios
                .post("/shipment", {
                    form: this.$data.form,
                    selectD: this.$data.selectD,
                    model: this.$data.model,
                    selectB: this.$data.selectB,
                    user: this.user,
                    selectCl: this.$data.selectC
                })
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    this.$emit('alertRequest');
                    // this.$emit('closeRequest');
                    this.$parent.AllShipments.push(response.data);
                    // this.$emit('closeRequest');
                    this.resetForm;
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.$emit("closeRequest");
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm);
            this.$refs.form.reset();
        },
        add_product() {
            this.form.products.push({
                product_name: '',
                weight: '',
                quantity: 1,
                price: 0,
            })
        },
        remove(product) {
            const index = this.form.products.indexOf(product)
            this.form.products.splice(index, 1)
        },
        // getUserDetails() {
        //     axios.get(`getUserDetails/${this.model}`)
        //   .then(res => {
        //     this.customerArr = res.data
        //     this.isLoading = false
        //   })
        //   .catch(err => {
        //     console.log(err)
        //   })
        //   .finally(() => (this.isLoading = false))
        // }
    },
    watch: {
        search(val) {
            // Items have already been loaded
            if (this.customerArr.length > 0) return

            this.isLoading = true

            // Lazily load input customerArr
            // axios.get('/getCustomer')
            //     .then(res => {
            this.customerArr = this.Allcustomer
            this.isLoading = false
            // })
            // .catch(err => {
            //     console.log(err)
            // })
            //   .finally(() => (this.isLoading = false))
        }
    },
    computed: {
        subTotal: function () {
            return this.form.products.reduce(function (carry, product) {
                return carry + parseFloat(product.price);
            }, 0);
        },
        // vat: function() {
        //     return this.grandTotal * parseFloat(0.16);
        //     // (this.subTotal - parseFloat(this.form.discount)) * parseFloat(0.16);
        // },
        // grandTotal: function() {
        //     return this.subTotal - parseFloat(this.form.discount);
        // },
        formIsValid() {
            return (
                this.form.client_name &&
                this.form.client_phone &&
                this.form.client_email &&
                this.form.client_address &&
                this.form.client_city &&
                this.form.airway_bill_no &&
                this.form.total_weight &&
                this.form.shipment_type &&
                this.form.payment &&
                this.form.total_freight &&
                this.form.insuarance_status &&
                this.form.booking_date &&
                this.form.derivery_date
            );
        }
    },
};
</script>
