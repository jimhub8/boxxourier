<template>
<v-layout row justify-center>

    <v-dialog v-model="openAddRequest" persistent max-width="1300px">

        <v-card>

            <v-card-title fixed>

                <span class="headline">Invoice</span>

            </v-card-title>

            <v-card-text>

                <v-container grid-list-md>

                    <v-layout wrap>

                        <div class="panel panel-default col-md-12" id="printMe">

                            <div class="panel-heading">

                                <div class="clearfix">

                                    <span class="panel-title"><strong>SPEED BALL COURIER SERVICES LTD</strong></span>

                                </div>

                            </div>

                            <div class="panel-body">

                                <div class="row">

                                    <div class="col-sm-4">

                                        <div class="form-group">

                                            <label><b>VAT NO:</b> </label>

                                            <span>P051681186K</span>

                                        </div>

                                        <div class="form-group">

                                            <label><b> P.O BOX:</b></label>

                                            <span>17254</span>

                                        </div>

                                    </div>

                                    <div class="col-sm-4">
										
                                        <label><b></b> Nyayo Embakasi.Court328.House No.5</label>

                                            <!-- <pre class="pre">{{invoice.client_address}}</pre> -->


                                    </div>

                                    <div class="col-sm-4">

                                        <div class="form-group">

                                            <label><b>Number</b> :</label>

                                            <span>{{invoice.invoice_no}}</span>

                                        </div>


                                            <div class="">

                                                <label><b>Date</b> </label>

                                                <span>{{ invoice.invoice_date }}</span>

                                            </div>

                                            <div class="">

                                                <label><b>page</b> </label>

                                                <span>1/3</span>

                                            </div>
                                            <div class="">

                                                <label><b>Reference:</b> :</label>

                                                <span></span>

                                            </div>
                                            <div class="">

                                                <label><b>Sales Rep:</b> </label>

                                                <span>1/3</span>

                                            </div>
                                            <div class="">

                                                <label><b>Due Date:</b> </label>

                                                <span>{{ invoice.due_date }}</span>

                                            </div>
                                            <div class="">

                                                <label><b>Overrall Discount%:</b> </label>

                                                <span>1000</span>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                                <hr>

                                <table class="table table-bordered table-striped">

                                    <thead>

                                        <tr>
                                            <th>Description</th>
                                            <th>Quantity</th>
                                            <th>Excl.Price</th>
                                            <th>Discount</th>
                                            <th>Vat</th>
                                            <th>Exclusive Total</th>
                                            <th>Inclusive Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="product in invoice.products" :key="product.id">
                                            <td class="table-name">{{product.name}}</td>
                                            <td class="table-qty">{{product.qty}}</td>
                                            <td class="table-price">Ksh{{product.price}}</td>
                                            <td class="table-price">Ksh{{ invoice.discount}}</td>
                                            <td class="table-price">Ksh{{ invoice.vat}}</td>
                                            <td class="table-total text-right">Ksh{{product.qty * product.price}}</td>
                                            <td class="table-total text-right">Ksh{{product.qty * product.price}}</td>
                                        </tr>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td class="table-empty" colspan="5"></td>
                                            <td class="table-label"><b>Total Discount</b></td>
                                            <td class="table-amount">Ksh{{invoice.discount}}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-empty" colspan="5"></td>
                                            <td class="table-label"><b>Total Exclusive</b></td>
                                            <td class="table-amount">Ksh{{ invoice.discount}}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-empty" colspan="5"></td>
                                            <td class="table-label"><b>Sub Total</b></td>
                                            <td class="table-amount">Ksh{{invoice.sub_total}}</td>
                                        </tr>
                                        <tr>
                                            <td class="table-empty" colspan="5"></td>
                                            <td class="table-label"><b>Total</b></td>
                                            <td class="table-amount">Ksh{{invoice.grand_total}}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                        </div>
                    </v-layout>

                </v-container>

            </v-card-text>

            <v-card-actions>
                <v-btn @click="close" flat color="primary">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn  v-print="'#printMe'" flat color="primary">Print</v-btn>
            </v-card-actions>

        </v-card>

    </v-dialog>

</v-layout>
</template>

<script>
export default {

    props: ['openAddRequest', 'invoice'],

    data() {

        return {

        }

    },

    methods: {

        close() {

            this.$emit('closeRequest')

        },

    },

    mounted() {},

}
</script>
