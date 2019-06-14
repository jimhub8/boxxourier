<template>
<div class="text-xs-center">
    <v-dialog v-model="OpenTrackBranch" hide-overlay persistent width="1500">
        <v-card v-if="OpenTrackBranch">
            <div style="text-align: center">
                <v-divider></v-divider>
            </div>
            <v-toolbar card blue-grey darken-1>
                <v-toolbar-title class="body-2">Shipments Details</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-card-text>
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
                            <li class="list-group-item"><label for=""><b>Delivery Status: </b></label>{{ shipments.derivery_status }}</li> 
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

                <!-- <v-toolbar card blue-grey darken-1>
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
                    </table> -->
                <!-- </v-layout> -->
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
                        <v-spacer></v-spacer>
                        <!-- <v-btn v-print="'#printMe'" flat color="primary">Print</v-btn> -->
            </v-card-actions>
            <v-divider></v-divider>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
import VueBarcode from "vue-barcode";
export default {
    components: {
        barcode: VueBarcode
    },
    data() {
        return {
            shipD: [],
            shipments: [],
            OpenTrackBranch: false
        };
    },
    methods: {
        refresh() {
            this.$emit("refreshRequest");
        },
        TrackEvent() {
            eventBus.$emit("TrackEvent", this.shipments);
        },
        close() {
            // this.$emit("closeRequest");
            this.OpenTrackBranch = false
        },
        showShip(data) {
            axios
                .post(`/getshipD/${data.id}`)
                .then(response => {
                    this.shipD = response.data;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
        },
        // shipmentGet(data) {
        //     axios
        //         .post(`/getShipSingle/${data.id}`)
        //         .then(response => {
        //             this.shipments = response.data;
        //         })
        //         .catch(error => {
        //             this.errors = error.response.data.errors;
        //         });
        // }
    },

    created() {
        eventBus.$on("ShowShipEvent", data => {
            // this.shipmentGet(data);
            this.showShip(data.shipment);
            this.shipments = data.shipment
            this.OpenTrackBranch = true;
        });
    },
    computed: {
        getUrl() {
            return "pod/" + this.shipments.id;
        }
    }
};
</script>

<style scoped>
.vue-barcode-element {
    margin-top: 5px !important;
}
</style>
