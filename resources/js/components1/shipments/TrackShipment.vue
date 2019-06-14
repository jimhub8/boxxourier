<template>
<div class="text-xs-center">
    <v-dialog v-model="OpenTrackBranch" hide-overlay persistent width="1500">
        <v-card v-if="OpenTrackBranch">
            <!-- <v-card> -->
            <v-card-text id="printMe">
                <ul class="list-group">
                    <li class="list-group-item active text-center">
                        <barcode v-bind:value="shipments.bar_code" style="margin-top: 5px !important;"></barcode>
                    </li>
                    <span>
                        <v-btn icon dark @click="close">
                            <v-icon color="black">close</v-icon>
                        </v-btn>
                    </span>
                </ul>
                <table class="table table-hover table-striped">
                    <thead>
                        <th>Waybill</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Status</th>
                        <th>Delivery Date</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.bar_code }}</td>
                            <td>{{ shipments.sender_city }}</td>
                            <td>{{ shipments.client_city }}</td>
                            <td>{{ shipments.status }}</td>
                            <td>{{ shipments.derivery_date }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="card-header">
                    <ul class="list-group text-center">
                        <li class="list-group-item active">Product Details</li>
                    </ul>
                </div>

                <!-- <v-toolbar card style="background: #3490dc; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Product Details</v-toolbar-title>
                </v-toolbar> -->
                <table class="table table-hover table-striped">
                    <thead>
                        <th>Waybill Status</th>
                        <th>Receiver Name</th>
                        <th>Delivery Date</th>
                        <th>COD Amount</th>
                        <th>Quantity</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.status }}</td>
                            <td>{{ shipments.receiver_name }}</td>
                            <td>{{ shipments.derivery_date }}</td>
                            <td>{{ shipments.cod_amount }}</td>
                            <td>{{ shipments.amount_ordered }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="card-header">
                    <ul class="list-group text-center">
                        <li class="list-group-item active">Client Details</li>
                    </ul>
                </div>
                <!-- <v-toolbar card style="background: #3490dc; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Client Details</v-toolbar-title>
                </v-toolbar> -->
                <table class="table table-hover table-striped">
                    <thead>
                        <th>Client Name</th>
                        <th>Client Email</th>
                        <th>Client Phone</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.client_name }}</td>
                            <td>{{ shipments.client_email }}</td>
                            <td>{{ shipments.client_phone }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="card-header">
                    <ul class="list-group text-center">
                        <li class="list-group-item active">Branch&Rider</li>
                    </ul>
                </div>
                <!-- <v-toolbar card style="background: #3490dc; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Branch&Rider</v-toolbar-title>
                </v-toolbar> -->
                <table class="table table-hover table-striped">
                    <thead>
                        <th>Rider Name</th>
                        <th>Branch Name</th>
                    </thead>
                    <tbody>
                        <tr v-for="shipment in shipD" :key="shipment.id">
                            <td>{{ shipment.driver }}</td>
                            <td>{{ shipment.branch_id }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-header">
                    <ul class="list-group text-center">
                        <li class="list-group-item active">Sender Details</li>
                    </ul>
                </div>
                <!-- <v-toolbar card style="background: #3490dc; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Sender Details</v-toolbar-title>
                </v-toolbar> -->
                <table class="table table-hover table-striped">
                    <thead>
                        <th>Sender Name</th>
                        <th>Sender Email</th>
                        <th>Sender Phone</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ shipments.sender_name }}</td>
                            <td>{{ shipments.sender_email }}</td>
                            <td>{{ shipments.sender_phone }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="card-header">
                    <ul class="list-group text-center">
                        <li class="list-group-item active">Waybill Event Tracking</li>
                    </ul>
                </div>
                <!-- <v-toolbar card style="background: #3490dc; color: #fff;" darken-1>
                    <v-toolbar-title class="body-2">Waybill Event Tracking</v-toolbar-title>
                </v-toolbar> -->
                <v-layout wrap>
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Events</th>
                                <th scope="col">Event date and time</th>
                                <th scope="col">Location</th>
                                <th scope="col">Remark</th>
                                <th scope="col">Updated by</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(statuses, index) in status" :key="statuses.id">
                                <th scope="row">{{ index + 1 }}</th>
                                <td>{{ statuses.status }}</td>
                                <td>{{ statuses.created_at }}</td>
                                <td>{{ statuses.location }}</td>
                                <td>{{ statuses.remark }}</td>
                                <td>{{ statuses.user_id }}</td>
                            </tr>
                        </tbody>
                    </table>
                </v-layout>
            </v-card-text>

            <v-card-actions>
                <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
                <v-btn color="blue darken-1" flat @click="TrackEvent" v-if="user.can['update status']">Update status</v-btn>
                <v-spacer></v-spacer>
                <v-btn v-print="'#printMe'" flat color="primary">Print</v-btn>
            </v-card-actions>
            <v-divider></v-divider>
            <v-divider></v-divider>
        </v-card>
    </v-dialog>
</div>
</template>

<script>
import VueBarcode from "vue-barcode";
export default {
    props: ["shipments", "OpenTrackBranch", 'user'],
    components: {
        barcode: VueBarcode
    },
    data() {
        return {
            shipD: [],
            dialog: false,
            status: [],
        };
    },
    methods: {
        refresh() {
            this.$emit("refreshRequest");
        },
        TrackEvent() {
            eventBus.$emit('TrackEvent', this.shipments);
        },
        close() {
            this.$emit("closeRequest");
        },
        trackShip(data) {
            axios.post(`/getshipD/${data.id}`)
                .then((response) => {
                    this.shipD = response.data
                })
                .catch((error) => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if(error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                    this.errors = error.response.data.errors
                })
        },
        getShipStatus(data) {
            axios.get(`/getShipStatus/${data.id}`)
                .then((response) => {
                    this.status = response.data
                })
                .catch((error) => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if(error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                    this.errors = error.response.data.errors
                })
        },
    },

    created() {
        eventBus.$on("TrackShipEvent", data => {
            this.getShipStatus(data)
            this.trackShip(data)
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
