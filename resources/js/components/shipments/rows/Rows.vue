<template>
<v-layout row justify-center>
    <v-dialog v-model="myRows" persistent width="500px">
        <v-card v-if="myRows">
            <v-card-title>
                Shipment Rows
            </v-card-title>
            <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-container fill-height>
                            <v-layout align-center>
                                <v-select :items="items" v-model="AllRows" label="Select Item" multiple>
                                    <template slot="selection" slot-scope="{ item, index }">
                                        <v-chip v-if="index === 0">
                                            <span>{{ item.text }}</span>
                                        </v-chip>
                                        <span v-if="index === 1" class="grey--text caption">(+{{ AllRows.length - 1 }} others)</span>
                                    </template>
                                </v-select>
                            </v-layout>
                        </v-container>
                    </v-flex>
                </v-layout>
                <v-card-actions>
                    <v-btn flat @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn flat @click="update" :loading="loading" :disabled="loading" color="primary">Update</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['myRows'],
    data() {
        return {
            items: [
                {
                value: 'sender_name',
                text: 'Sender Name'
                },
                {
                value: 'sender_email',
                text: 'Sender Email'
                },
                {
                value: 'sender_address',
                text: 'Sender: Address'
                },
                {
                value: 'sender_city',
                text: 'Sender City'
                },
                {
                value: 'sender_phone',
                text: 'Sender Phone'
                },
                {
                value: 'client_name',
                text: 'Client Name'
                },
                {
                value: 'client_email',
                text: 'Client Email'
                },
                {
                value: 'client_address',
                text: 'Client Address'
                },
                {
                value: 'client_city',
                text: 'Client City'
                },
                {
                value: 'client_phone',
                text: 'Client Phone'
                },
                {
                value: 'client_id',
                text: 'Client Id'
                },
                {
                value: 'airway_bill_no',
                text: 'WayBill Number'
                },
                {
                value: 'amount_ordered',
                text: 'Quantity'
                },
                {
                value: 'paid',
                text: 'Paid'
                },
                {
                value: 'status',
                text: 'Status'
                },
                {
                value: 'driver',
                text: 'Driver'
                },
                {
                value: 'from',
                text: 'From'
                },
                {
                value: 'to',
                text: 'To'
                },
                {
                value: 'charges',
                text: 'Charges'
                },
                {
                value: 'booking_date',
                text: 'Booking Date'
                },
                {
                value: 'derivery_date',
                text: 'Derivery Date'
                },
                {
                value: 'derivery_time',
                text: 'Derivery Time'
                },
                {
                value: 'remark',
                text: 'Remarks'
                },
                {
                value: 'weight',
                text: 'Total Weight'
                },
                {
                value: 'receiver_name',
                text: 'Received By'
                },
                {
                value: 'cod_amount',
                text: 'COD Amount'
                },
                {
                value: 'speciial_instruction',
                text: 'Special Instructions '
                },
                {
                value: 'description',
                text: 'Description'
                }
            ],
            AllRows: [],
            select: [],
            loading: false,
        }
    },
    methods: {
        update() {
            this.loading = true;
            axios.post('/rows', this.$data.AllRows)
                .then(response => {
                    this.loading = false;
                    // console.log(response);
                    this.$emit('alertRequest');
                    // this.$emit('closeRequest');
                    // this.close()
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.$emit("closeRequest");
        },
    },
    mounted() {
        // axios
        //     .get("/getRows")
        //     .then(response => {
        //         this.AllRows = response.data;
        //         this.loader = false;
        //     })
        //     .catch(error => {
        //         console.log(error);
        //         this.errors = error.response.data.errors;
        //         this.loader = false;
        //     });
    },
}
</script>
