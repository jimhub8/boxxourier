<template>
<v-layout row justify-center>
    <v-dialog v-model="openRequest" persistent max-width="600px">
        <v-card>
            <v-card-title>
                <span class="headline">User Profile</span>
                <v-spacer></v-spacer>
                <router-link to="/Shipments" class="v-list__tile v-list__tile--link" v-if="user.can['view shipments']">
                    <div class="v-list__tile__content">
                        <div class="v-list__tile__title">
                            Go To Shipments
                        </div>
                    </div>
                </router-link>
                <v-btn flat color="primary" @click="updateShip">Update Status</v-btn>
                 <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <ul class="list-group">
                        <li class="list-group-item active"><b>Client Details</b></li>
                        <li class="list-group-item"><label for="">Client Name:</label><span style="float: right;">{{ shipment.client_name }}</span></li>
                        <li class="list-group-item"><label for="">Client Phone:</label><span style="float: right;">{{ shipment.client_phone }}</span></li>
                        <li class="list-group-item"><label for="">Client Email:</label><span style="float: right;">{{ shipment.client_email }}</span></li>
                        <li class="list-group-item"><label for="">Client City:</label><span style="float: right;">{{ shipment.client_city }}</span></li>
                        <li class="list-group-item active"><b>Sender Details</b></li>
                        <li class="list-group-item"><label for="">Sender Name:</label><span style="float: right;">{{ shipment.sender_name }}</span></li>
                        <li class="list-group-item"><label for="">Sender Phone:</label><span style="float: right;">{{ shipment.sender_phone }}</span></li>
                        <li class="list-group-item"><label for="">Sender Email:</label><span style="float: right;">{{ shipment.sender_email }}</span></li>
                        <li class="list-group-item active"><b>Shipment Details</b></li>
                        <li class="list-group-item"><label for="">WayBill Number:</label><span style="float: right;">{{ shipment.bar_code }}</span></li>
                        <li class="list-group-item"><label for="">Booking Date:</label><span style="float: right;">{{ shipment.booking_date }}</span></li>
                        <li class="list-group-item"><label for="">From:</label><span style="float: right;">{{ shipment.from_city }}</span></li>
                        <li class="list-group-item"><label for="">To:</label><span style="float: right;">{{ shipment.to_city }}</span></li>
                        <li class="list-group-item"><label for="">Status:</label><span style="float: right;">{{ shipment.status }}</span></li>
                    </ul>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="close">Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <Update></Update>
</v-layout>
</template>

<script>
import Update from './Update';
export default {
    components: {
        Update
    },
    props: ['openRequest', 'shipment', 'user'],
    data() {
        return {

        }
    },
    methods: {
        updateShip() {
            eventBus.$emit('updateEvent', this.shipment)
        },
        close() {
            this.$emit('closeRequest')
        }
    }
}
</script>
