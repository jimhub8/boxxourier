<template>
<v-content>
    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <div id="home" class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card card-body">
                            <div class="form-group">
                                <v-layout row wrap>
                                    <v-flex sm10 offset-sm1>
                                        <div class="form-group col-md-12">
                                            <option value=""></option>
                                            <label class="col-form-label text-md-right" for="">Clients</label>
                                            <select class="custom-select custom-select-md col-md-12" v-model="form.client">
                                                <option value=""></option>
                                                <option v-for="client in AllClients" :key="client.id" :value="client.id">{{ client.name }}</option>
                                            </select>
                                        </div>
                                        <small class="has-text-danger" v-if="errors.client">{{ errors.client[0] }}</small>
                                    </v-flex>
                                    <v-flex xs12 sm5 offset-sm1>
                                        <v-text-field v-model="form.start_date" :type="'date'" color="blue darken-2" label="Start Date" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.start_date">{{ errors.start_date[0] }}</small>
                                    </v-flex>
                                    <v-flex xs12 sm5 offset-sm1>
                                        <v-text-field v-model="form.end_date" :type="'date'" color="blue darken-2" label="End Date" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.end_date">{{ errors.end_date[0] }}</small>
                                    </v-flex>
                                </v-layout>
                            </div>
                            <v-btn raised color="primary" @click="getInvoice" :disabled="loading" :loading="loading">View Invoce</v-btn>
                        </div>
                    </div>
                    <myInvoice></myInvoice>
                </div>
            </div>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
import myInvoice from './Invoice';
export default {
    components: {
        myInvoice,
    },
    data() {
        return {
            invoice_data: {},
            errors: {},
            form: {},
            loading: false,
            AllClients: [],
        }
    },
    methods: {
        getInvoice() {
            this.errors = {}
            axios.post('/filterInvoice', this.form).
            then((response) => {
                    this.loading = false
                    console.log(response);
                    eventBus.$emit('invoiceEvent', response.data)
                    // this.close();
                    // this.resetForm();
                    // eventBus.$emit("alertRequest", 'Successifully Created');
                    this.invoice_data = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },

        getCustomer() {
            this.loading = true
            axios.get('/getCustomer')
                .then((response) => {
                    this.loading = false
                    this.AllClients = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
    },
    mounted() {
        this.getCustomer()
    },
}
</script>
