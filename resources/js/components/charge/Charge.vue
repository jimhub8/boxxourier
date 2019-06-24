<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
            <v-layout justify-center align-center v-show="!loader">
                <v-layout row wrap>
                    <v-card style="width: 100%;">
                        <v-card-title>
                            Charges
                            <v-btn @click="openAdd" flat color="primary">Add Charges</v-btn>
                            <!-- <v-spacer></v-spacer> -->
                            <v-tooltip bottom>
                                <v-btn slot="activator" icon class="mx-0" @click="getCharges">
                                    <v-icon small color="blue darken-2">refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="AllCharges" class="elevation-1" :search="search" :loading="loading">
                            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.town }}</td>
                                <td class="text-xs-right">{{ props.item.charge }}</td>
                                <td class="text-xs-right">{{ props.item.vat }}</td>
                                <td class="text-xs-right">{{ props.item.total }}</td>
                                <td class="justify-center layout px-0">
                                    <v-tooltip bottom>
                                        <v-btn slot="activator" icon class="mx-0" @click="taskEdit(props.item)">
                                            <v-icon small color="blue darken-2">edit</v-icon>
                                        </v-btn>
                                        <span>Edit</span>
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
    <AddCharge @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert"></AddCharge>
    <EditCharge @closeRequest="close" :openAddRequest="dispEdit" @alertRequest="showAlert" :charges="editCharge"></EditCharge>
    <!-- <ShowTask @closeRequest="close" :openAddRequest="dispShow" @alertRequest="showAlert" :task="editCharge"></ShowTask> -->

</div>
</template>

<script>
import AddCharge from "./AddCharge";
import EditCharge from "./EditCharge";
// import ShowTask from './ShowTask';

export default {
    components: {
        AddCharge,
        EditCharge
        // ShowTask,
    },

    data() {
        return {
            search: "",
            loading: false,
            dispAdd: false,
            dispShow: false,
            dispEdit: false,
            AllCharges: [],
            editCharge: [],
            loader: false,
            snackbar: false,
            timeout: 5000,
            color: "",
            message: "",
            headers: [{
                    text: "Town",
                    align: "left",
                    value: "town"
                },
                {
                    text: "Charges",
                    value: "charge"
                },
                {
                    text: "16%VAT",
                    value: "vat"
                },
                {
                    text: "Total",
                    value: "total"
                },
                {
                    text: "Actions",
                    value: "name",
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
            this.dispAdd = this.dispShow = this.dispEdit = this.dispMail = false;
        },
        showAlert() {
            this.message = "Successifully Added";
            this.snackbar = true;
            this.color = "indigo";
        },

        taskEdit(task) {
            this.editCharge = Object.assign({}, task);
            this.editedIndex = this.AllCharges.indexOf(task);
            this.dispEdit = true;
        },
        getCharges() {
            this.loader = true;
            axios
                .get("/getCharges")
                .then(response => {
                    this.loader = false;
                    this.AllCharges = response.data;
                })
                .catch(error => {
                    this.loader = false;
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.errors = error.response.data.errors;
                });
        }
    },
    mounted() {
        this.loader = true;
        this.getCharges();
    }
};
</script>
