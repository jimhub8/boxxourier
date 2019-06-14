<template>
    <v-card>
        <v-container grid-list-md>
            <v-card-text>
                    <v-flex xs12>
                            <select class="custom-select custom-select-md col-md-12" v-model="form.driver" style="font-size: 15px;">
                                <option v-for="driver in AllDrivers" :key="driver.id" :value="driver.id">{{ driver.name }}</option>
                          </select>
                            <v-flex xs12 sm12>
                                <v-textarea v-model="form.remark" color="blue">
                                    <div slot="label">
                                        Remark <small>(optional)</small>
                                    </div>
                                </v-textarea>
                            </v-flex>
                    </v-flex>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" flat @click="driverAssign" :loading="loading" :disabled="loading">Assign Driver</v-btn>
            </v-card-actions>
        </v-container>
    </v-card>
</template>

<script>
export default {
    // props: ['dialog', 'updateitedItem', 'selectedItems', 'AllDrivers'],
    data() {
        return {
            loading: false,
            snackbar: false,
            timeout: 5000,
            dialog: false,
            message: "",
            color: "",
            AllDrivers: [],
            selectedItems: [],
            updateitedItem: [],
            form: {
                'remark': '',
                'driver': '',
            },
        }
    },
    methods: {
        driverAssign() {
            // alert(this.updateitedItem.id);
            this.loading = true
            axios
                .post(`/assignDriver`, {
                    selected: this.selectedItems,
                    form: this.form
                })
                .then(response => {
                    this.loading = false
                    this.$emit("alertRequest");
                    this.$emit("alertRequest");
                    eventBus.$emit('selectClear');
                    this.close();
                    //   this.$emit("closeRequest");
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
            this.$emit("closeRequest");
        },

        getDrivers() {
            axios
                .get("/getDrivers")
                .then(response => {
                    this.AllDrivers = response.data;
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
        mounted() {
            this.getDrivers()
        },
    },
}
</script>
