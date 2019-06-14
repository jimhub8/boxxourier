<template>
<v-layout row justify-center>

    <v-dialog v-model="openMailRequest" persistent max-width="900px">

        <v-card>

            <v-card-title fixed>

                <span class="headline">Send Email Invoice</span>

            </v-card-title>

            <v-card-text>

                <v-container grid-list-md>

                    <v-layout wrap>

                        <v-form ref="form" @submit.prevent>

                            <v-container grid-list-xl fluid>

                                <v-layout wrap>

                                    <v-flex xs12 sm6>

                                        <v-text-field v-model="invoice.subject" color="blue darken-2" label="Subject" required></v-text-field>

                                        <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->

                                    </v-flex>

                                    <v-flex xs12 sm3 offset-sm2>

                                        <v-btn @click="save" flat color="primary" :loading="loading" :disabled="loading">Send</v-btn>

                                        <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->

                                    </v-flex>

                                </v-layout>

                            </v-container>

                        </v-form>

                        <!-- <v-flex  xs12 sm12> -->

                        <div class="panel panel-default col-md-12">

                            <div class="panel-heading">

                                <div class="clearfix">

                                    <span class="panel-title">Invoice</span>

                                </div>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-sm-4">

                                        <div class="form-group">

                                            <label>Invoice No.</label>

                                            <p>{{invoice.invoice_no}}</p>

                                        </div>

                                        <div class="form-group">

                                            <label>Grand Total</label>

                                            <p>Ksh{{invoice.grand_total}}</p>

                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        <div class="form-group">

                                            <label>Client</label>

                                            <p>{{invoice.client}}</p>

                                        </div>

                                        <div class="form-group">

                                            <label>Client Address</label>

                                            <pre class="pre">{{invoice.client_address}}</pre>

                                        </div>

                                    </div>

                                    <div class="col-sm-4">

                                        <div class="form-group">

                                            <label>Title</label>

                                            <p>{{invoice.title}}</p>

                                        </div>

                                        <div class="row">

                                            <div class="col-sm-6">

                                                <label>Invoice Date</label>

                                                <p>{{invoice.invoice_date}}</p>

                                            </div>

                                            <div class="col-sm-6">

                                                <label>Due Date</label>

                                                <p>{{invoice.due_date}}</p>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <hr>

                                <table class="table table-bordered table-striped">

                                    <thead>

                                        <tr>

                                            <th>Product Name</th>

                                            <th>Price</th>

                                            <th>Qty</th>

                                            <th>Total</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr v-for="product in invoice.products">

                                            <td class="table-name">{{product.name}}</td>

                                            <td class="table-price">Ksh{{product.price}}</td>

                                            <td class="table-qty">{{product.qty}}</td>

                                            <td class="table-total text-right">Ksh{{product.qty * product.price}}</td>

                                        </tr>

                                    </tbody>

                                    <tfoot>

                                        <tr>

                                            <td class="table-empty" colspan="2"></td>

                                            <td class="table-label">Sub Total</td>

                                            <td class="table-amount">Ksh{{invoice.sub_total}}</td>

                                        </tr>

                                        <tr>

                                            <td class="table-empty" colspan="2"></td>

                                            <td class="table-label">Discount</td>

                                            <td class="table-amount">Ksh{{invoice.discount}}</td>

                                        </tr>

                                        <tr>

                                            <td class="table-empty" colspan="2"></td>

                                            <td class="table-label">Grand Total</td>

                                            <td class="table-amount">Ksh{{invoice.grand_total}}</td>

                                        </tr>

                                    </tfoot>

                                </table>

                            </div>

                        </div>

                        <!-- </v-flex> -->

                    </v-layout>

                </v-container>

            </v-card-text>

            <v-card-actions>

                <v-btn @click="close" flat color="primary">Close</v-btn>

            </v-card-actions>

        </v-card>

    </v-dialog>

</v-layout>
</template>

<script>
export default {

    props: ['openMailRequest', 'invoice'],

    data() {

        return {

            loading: false

        }

    },

    methods: {

        save() {

            this.loading = true

            // this.invoice.email

            axios.post('/sendMail', this.invoice).

            then((response) => {

                    this.loading = false

                    console.log(response.data);

                    // this.close;

                    // this.$emit('closeRequest');

                    this.$emit('alertRequest');

                    // Object.assign(this.$parent.invoices[this.$parent.editedIndex], this.$parent.editedItem)

                })

                .catch((error) => {

                    this.loading = false

                    this.errors = error.response.data.errors

                })

        },

        close() {

            this.$emit('closeRequest')

        },

    },

    mounted() {},

}
</script>
