<template>
<v-layout row justify-center>
    <v-dialog v-model="ShowShipment" fullscreen hide-overlay transition="dialog-bottom-transition">
        <v-card id="shipment_det">
            <v-card-title>
                <v-toolbar dark color="white">
                    <v-btn icon dark @click.native="close">
                        <v-icon color="black">close</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <span class="headline"></span>
            </v-card-title>
            <v-card-text id="printMe">
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <!-- COURIER SCRIPTS PVT LTD. -->
                        <v-flex xs7 sm7>
                            <v-card>
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">Boxleo Services.</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">map</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.sender_city }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">phone</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.sender_phone }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">mail</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.sender_email }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                    </v-list>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <!-- COURIER SCRIPTS PVT LTD. -->
                        <v-spacer></v-spacer>
                        <!-- TRACKING NO -->
                        <v-flex xs4 sm4>
                            <v-card>
                                <v-card-title primary-title>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title style="height: 130px;">
                                                <barcode v-bind:value="showItems.bar_code">
                                                    Show this if the rendering fails.
                                                </barcode>
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>
                                                TRACKING NO:{{ showItems.bar_code }}
                                            </v-list-tile-title>
                                        </v-list-tile-content>
                                    </v-list-tile>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list-tile>
                                        <v-list-tile-content>
                                            <v-list-tile-title>Date</v-list-tile-title>
                                            <v-list-tile-sub-title>{{ showItems.booking_date}}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list-tile-content>
                                            <v-list-tile-title>From</v-list-tile-title>
                                            <v-list-tile-sub-title>{{ showItems.sender_name}}</v-list-tile-sub-title>
                                        </v-list-tile-content>
                                        <v-list-tile-content>
                                            <v-list-tile-title>To</v-list-tile-title>
                                            <v-list-tile-sub-title>{{ showItems.client_name}}</v-list-tile-sub-title>
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
                            <v-card>
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">Sender Details :</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">account_circle</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.sender_name }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">phone</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.sender_phone }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">mail</v-icon>
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
                            <v-card>
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">Client Details :</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">account_circle</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.client_name }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">phone</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.client_phone }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">mail</v-icon>
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
                            <v-card>
                                <v-card-title primary-title>
                                    <h3 class="headline mb-0">Shipment Details :</h3>
                                </v-card-title>
                                <v-card-actions>
                                    <v-list two-line>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">account_circle</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.airway_bill_no }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">event</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.derivery_date }}</v-list-tile-title>
                                            </v-list-tile-content>
                                        </v-list-tile>
                                        <v-list-tile>
                                            <v-list-tile-action>
                                                <v-icon color="indigo">Isured</v-icon>
                                            </v-list-tile-action>
                                            <v-list-tile-content>
                                                <v-list-tile-title>{{ showItems.insuarance_status }}</v-list-tile-title>
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
                            <th>Product Name</th>
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


<!-- <v-tabs
    dark
    color="cyan"
    show-arrows
  >
    <v-tabs-slider color="yellow"></v-tabs-slider>

    <v-tab
      v-for="i in 15"
      :href="'#tab-' + i"
      :key="i"
    >
      Item {{ i }}
    </v-tab>

    <v-tabs-items>
      <v-tab-item
        v-for="i in 15"
        :id="'tab-' + i"
        :key="i"
      >
        <v-card flat>
          <v-card-text>{{ text }}</v-card-text>
        </v-card>
      </v-tab-item>
    </v-tabs-items>
  </v-tabs> -->
</template>

<script>
import VueBarcode from "vue-barcode";
export default {
    components: {
        barcode: VueBarcode,
    },
    props: ['showItems', 'ShowShipment'],
    methods: {
        close() {
            this.$emit('closeRequest')
        }
    }
}
</script>


