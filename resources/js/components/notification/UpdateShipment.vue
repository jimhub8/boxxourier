<template>
<v-layout row justify-center>
    <v-container grid-list-md>
        <v-card>
            <v-card-title>
                Update Shipment
            </v-card-title>
            <v-card-text>
            <v-layout row wrap>
                <v-flex sm12 style="border-left: 1px solid #c1c1c1;">
                    <div class="form-group col-md-12">
                        <label for="">Status</label>
                        <select class="custom-select custom-select-md col-md-12" v-model="updateitedItem.status">
                                    <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                                </select>
                    </div>
                    <div v-if="updateitedItem.status === 'Delivered'">
                        <v-flex xs12 sm12>
                            <v-text-field v-model="updateitedItem.receiver_name" color="blue darken-2" label="Received By"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="updateitedItem.receiver_id" color="blue darken-2" label="Receiver id/phone number"></v-text-field>
                        </v-flex>
                    </div>
                    <div v-if="updateitedItem.status === 'Scheduled' || updateitedItem.status === 'Delivered'">
                        <v-flex xs12 sm12>
                            <v-text-field v-model="updateitedItem.derivery_date" color="blue darken-2" label="Delivery Date" type="date"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm12>
                            <v-text-field v-model="updateitedItem.derivery_time" color="blue darken-2" label="Delivery Time" type="time"></v-text-field>
                        </v-flex>
                        <v-flex xs4 sm12>
                            <v-text-field v-model="updateitedItem.location" color="blue darken-2" label="Location" required></v-text-field>
                        </v-flex>
                    </div>
                    <div v-if="updateitedItem.status === 'Not Picking'">
                        <v-flex xs12 sm12>
                            <v-text-field v-model="updateitedItem.derivery_date" color="blue darken-2" label="Date" type="date"></v-text-field>
                        </v-flex>
                        <v-flex xs4 sm12>
                            <v-text-field v-model="updateitedItem.location" color="blue darken-2" label="Location" required></v-text-field>
                        </v-flex>
                    </div>
                    <v-flex xs12 sm12>
                        <v-textarea v-model="updateitedItem.speciial_instruction" color="blue">
                            <div slot="label">
                                Special Instructions <small>(optional)</small>
                            </div>
                        </v-textarea>
                    </v-flex>
                </v-flex>
            </v-layout>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" flat @click="UpdateStatus" :loading="loading" :disabled="loading">Update Status</v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</v-layout>
</template>

<script>
export default {
    data() {
        return {
            loading: false,
            loading_loc: false,
            dialog: false,
            statuses: [],
            updateitedItem: [],
            AllProducts: [],
        };
    },
    description: "Maps",

    methods: {
        UpdateStatus() {
            this.loading = true;
            axios
                .post(`/updateStatus/${this.updateitedItem.id}`, {
                    formobg: this.updateitedItem,
                    address: this.address
                })
                .then(response => {
                    this.loading = false;
                    this.alert();
                    this.close();
                    eventBus.$emit("refreshShipEvent")
                    this.updateitedItem.derivery_date = "";
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
            this.dialog = false
        },
        alert() {
            this.$emit("alertRequest");
        },
    },

    mounted() {
        axios
            .get("/getStatuses")
            .then(response => {
                this.statuses = response.data;
            })
            .catch(error => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if (error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors;
            });
    },
    created() {
        eventBus.$on('UpdateShipmentEvent', data => {
            this.updateitedItem = data
        })
    },
};
</script>
