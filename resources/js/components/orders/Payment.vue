<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
            <v-layout justify-center align-center v-show="!loader">
                <v-layout row wrap>
                    <v-card>
                        <v-card-title>
                            Payment
                            <v-btn @click="openAdd" flat color="primary">Add Payment</v-btn>
                            <!-- <v-spacer></v-spacer> -->
                            <v-tooltip bottom>
                                <v-btn slot="activator" icon class="mx-0" @click="pay">
                                    <v-icon small color="blue darken-2">refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="payments" class="elevation-1" :search="search" :loading="loading">
                            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.create_time }}</td>
                                <td class="text-xs-right">{{ props.item.cart }}</td>
                                <td class="text-xs-right">{{ props.item.payment_id }}</td>
                                <!-- <td class="text-xs-right">{{ props.item.name }}</td> -->
                                <td class="text-xs-right">{{ props.item.address }}</td>
                                <td class="justify-center layout px-0">
                                    <v-tooltip bottom>
                                        <v-btn slot="activator" icon class="mx-0" @click="editProduct(props.item)">
                                            <v-icon small color="blue darken-2">edit</v-icon>
                                        </v-btn>
                                        <span>Edit</span>
                                    </v-tooltip>
                                    <v-tooltip bottom>
                                        <v-btn slot="activator" icon class="mx-0" @click="view(props.item)">
                                            <v-icon small color="indigo darken-2">visibility</v-icon>
                                        </v-btn>
                                        <span>View Payment</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                    </v-card>
                </v-layout>
            </v-layout>
        </v-container>
        <v-snackbar :timeout="timeout" bottom :color="color" left v-model="snackbar">
            {{ message }}
            <v-btn>close</v-btn>
        </v-snackbar>
    </v-content>
    <Create @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert"></Create>
    <!-- <Edit @closeRequest="close" :openAddRequest="dispEdit" @alertRequest="showAlert" :payment="catEdit"></Edit> -->
    <myView></myView>

</div>
</template>

<script>
import Create from "./Create";
// import Edit from "./Edit";
import myView from './View';

export default {
    components: {
        Create, myView
    },

    data() {
        return {
            search: "",
            loading: false,
            dispAdd: false,
            dispEdit: false,
            dispShow: false,
            payments: [],
            catEdit: [],
            loader: false,
            snackbar: false,
            timeout: 5000,
            color: "",
            message: "",
            headers: [
                {
                    text: "Created On",
                    value: "created_at"
                },
                {
                    text: "Client Name",
                    align: "left",
                    value: "name"
                },
                {
                    text: "Product Id",
                    value: "payment_id"
                },
                // {
                //     text: "Name",
                //     value: "name"
                // },
                {
                    text: "Address",
                    value: "address"
                },
                {
                    text: "Actions",
                    sortable: false
                }
            ]
        }
    },

    methods: {
        openAdd() {
            this.dispAdd = true;
        },
        close() {
            this.dispAdd = this.dispShow = this.dispEdit = false;
        },
        showAlert() {
            this.message = "Successifully Added";
            this.snackbar = true;
            this.color = "indigo";
        },

        editProduct(task) {
            this.catEdit = Object.assign({}, task);
            this.editedIndex = this.payments.indexOf(task);
            this.dispEdit = true;
        },
        pay() {
            this.loading = true;
            axios.get("/pay")
                .then(response => {
                    this.loader = false;
                    this.loading = false;
                    this.payments = response.data;
                })
                .catch(error => {
                    this.loader = false;
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        view(payment) {
            eventBus.$emit("viewOrdEvent", payment);
        },
    },
    mounted() {
        this.loader = true;
        this.pay();
    }
};
</script>
