<template>
<v-layout row justify-center>
    <v-dialog v-model="UpdateShipmentStatus" persistent width="600px">
        <v-card v-if="UpdateShipmentStatus">
            <v-card-title>
                Update Shipment Status
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-container grid-list-md>
                <v-layout row wrap>
                    <v-flex xs12>
                        <v-card>
                            <div class="form-group col-md-12">
                                <label for="">Status</label>
                                <select v-model="form.status" class="custom-select custom-select-md col-md-12">
                                    <option v-for="status in statuses" :key="status.id" :value="status.name">{{ status.name }}</option>
                                </select>
                            </div>
                            <!-- <v-layout wrap> -->    
                            <div v-if="form.status === 'Delivered'">
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="form.receiver_name" color="blue darken-2" label="Received By"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="form.receiver_id" color="blue darken-2" label="Receiver id/phone number"></v-text-field>
                                </v-flex>
                            </div>
                            <div v-if="form.status === 'Scheduled' || form.status === 'Delivered'">
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="form.delivery_date" color="blue darken-2" label="Delivery Date" type="date"></v-text-field>
                                </v-flex>
                                <v-flex xs12 sm12>
                                    <v-text-field v-model="form.derivery_time" color="blue darken-2" label="Delivery Time" type="time"></v-text-field>
                                </v-flex>
                                <v-flex xs4 sm12>
                                    <v-text-field v-model="form.location" color="blue darken-2" label="Location" required></v-text-field>
                                </v-flex>
                            </div>
                            <v-flex xs12 sm12>
                                <v-textarea v-model="form.remark" color="blue">
                                    <div slot="label">
                                        Special Instructions <small>(optional)</small>
                                    </div>
                                </v-textarea>
                            </v-flex>
                            <!-- </v-layout> -->
                        </v-card>
                    </v-flex>
                </v-layout>
                <v-divider></v-divider>
                <v-card-actions>
                    <v-btn flat @click="close">Close</v-btn>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" flat @click="UpdateShipment" :loading="loading" :disabled="loading">Update Status</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ["UpdateShipmentStatus", "updateitedItem", "selectedItems"],
    data() {
        return {
            loading: false,
            snackbar: false,
            timeout: 5000,
            message: "",
            color: "",
            statuses: [],
            form: {
                delivery_date: "",
                derivery_time: "",
                location: "",
                remark: "",
                receiver_name: '',
                receiver_id: '',
            }
        };
    },
    methods: {
        UpdateShipment() {
            // alert(this.updateitedItem.id);
            this.loading = true;
            axios
                .patch(`/UpdateFollowUp`, {
                    selected: this.selectedItems,
                    form: this.form
                })
                .then(response => {
                    this.loading = false;
                    this.$emit("alertRequest");
                    this.close();
                    this.form.delivery_date = ''
                    this.form.derivery_time = ''
                    this.form.location = ''
                    this.form.remark = ''
                    this.receiver_name = ''
                    this.receiver_id = ''
                    eventBus.$emit('selectClear');
                    eventBus.$emit("refreshShipEvent");
                    console.log(response.data)
                    // Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.$parent.editedItem)
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.$emit("closeRequest");
        }
    },
    mounted() {
        axios
            .get("/delStatus")
            .then(response => {
                this.statuses = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
    }
};
</script>
