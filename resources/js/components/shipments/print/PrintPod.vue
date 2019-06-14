<template>
<v-layout row justify-center>
    <v-dialog v-model="PrintRequest" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card id="shipment_det">
            <v-card-title>
                <v-toolbar dark color="indigo">
                    <v-btn icon dark @click.native="close">
                        <v-icon color="black">close</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <span class="headline"></span>
            </v-card-title>
            <v-card-text id="printMe" v-for="showItems, index in selected" :key="index">
                <v-container grid-list-md text-xs-center  id="printMe">
                    <v-layout row wrap>
                        <!-- COURIER SCRIPTS PVT LTD. -->
                        <v-flex xs5 sm5>
                            <v-card style="border: 1px solid #000;">
                                <img src="storage/logo-white.png" alt="SpeedBall" sizes="100">
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">SpeedBall Courier Services.</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">map</v-icon> -->
                                                <b>Location</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.sender_city }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">phone</v-icon> -->
                                                Phone:
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>+25420760877</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">mail</v-icon> -->
                                                Email:
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>info@speedballcourier.com</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <!-- COURIER SCRIPTS PVT LTD. -->
                        <v-spacer></v-spacer>
                        <!-- TRACKING NO -->
                        <v-flex xs7 sm7>
                            <v-card style="border: 1px solid #000;">
                                <v-card-title primary-title style="width: 100%">
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title style="height: 130px;">
                                                <barcode v-bind:value="showItems.bar_code" style="height: 75px; width: 400px;">
                                                    {{ showItems.bar_code }}
                                                </barcode>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <!-- <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title style="text-align: center !important;">
                                                TRACKING NO: <br>
                                                {{ showItems.bar_code }}
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile> --> 
                                </v-card-title>
                                <v-card-actions>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Booking Date</v-list-tile-title>
                                            <v-list-tile-sub-title>{{ showItems.booking_date}}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <!-- <v-divider vertical></v-divider> -->
                                        <v-list-tile-content style="margin-left: 100px;">
                                            <v-list-tile-title>From</v-list-tile-title>
                                            <v-list-tile-sub-title>{{ showItems.sende_city }}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <!-- <v-divider vertical></v-divider> -->
                                        <v-list-tile-content style="margin-left: 100px;">
                                            <v-list-tile-title>To</v-list-tile-title>
                                            <v-list-tile-sub-title>{{ showItems.client_address }}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <!-- TRACKING NO -->
                    </v-layout>
                    <v-divider></v-divider>
                    <!-- Sender Receiver and Shipment Details  -->
                    <v-layout row wrap>
                        <!-- sender details -->
                        <v-flex xs4 sm4>
                            <v-card style="border: 1px solid #000;">
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">Sender Details :</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">account_circle</v-icon> -->
                                                <b>Name</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>SpeedBall</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">phone</v-icon> -->
                                                <b>Phone</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>+25420760877</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">mail</v-icon> -->
                                                <b>Email:</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.sender_email }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <!-- sender details -->
                        <!-- Receiver details -->
                        <v-flex xs4 sm4>
                            <v-card style="border: 1px solid #000;">
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">Client Details :</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">account_circle</v-icon> -->
                                                <b>Name</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.client_name }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">phone</v-icon> -->
                                                <b>Phone</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.client_phone }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">mail</v-icon> -->
                                                <b>Email</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.client_email }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <!-- Receiver details -->
                        <!-- Shipment details -->
                        <v-flex xs4 sm4>
                            <v-card style="border: 1px solid #000;">
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">Shipment Details :</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">account_circle</v-icon> -->
                                                <b>WayBill No:</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.airway_bill_no }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">event</v-icon> -->
                                                <b>Derivery Date:</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.derivery_date }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">Isured</v-icon> -->
                                                <b>Insured:</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.insuarance_status }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">Isured</v-icon> -->
                                                <b>Special Instructions:</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.speciial_instruction }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <!-- <v-icon color="indigo">Isured</v-icon> -->
                                                <b>Status:</b>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.status }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <!-- Receiver details -->
                    </v-layout>
                    <v-divider></v-divider>
                    <!-- Sender Receiver and Shipment Details  -->
                    <table class="table table-striped table-hover">
                        <thead>
                            <th>Product Description</th>
                            <th>Weight </th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </thead>
                        <tbody>
                            <tr v-for="pros in showItems.products" :key="pros.id" v-if="pros.shipments_id === showItems.id">
                                <td>{{ pros.product_name }}</td>
                                <td>{{ pros.weight }}</td>
                                <td>{{ pros.price }}</td>
                                <td>{{ pros.quantity }}</td>
                                <td>{{ pros.price * pros.quantity }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>Sub Total</th>
                            <th>{{ showItems.sub_total }}</th>
                        </tfoot>
                    </table>
                    <b style="float: left;">Authorized Signature____________________________</b>
                    <b style="float: right;">Customer Signature____________________________</b>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn v-print="'#printMe'" flat color="primary">Print</v-btn>
                <!-- <button  v-print>Print the entire page</button> -->
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
import VueBarcode from "vue-barcode";
export default {
    components: {
        barcode: VueBarcode,
    },
    props: ['selected', 'PrintRequest'],
    methods: {
        close() {
            this.$emit('closeRequest')
        }
    }
}
</script>

<style>
.vue-barcode-element {
    margin-left: 40% !important;
}
.v-card{
    border: 1px solid #000 !important;
}
</style>

