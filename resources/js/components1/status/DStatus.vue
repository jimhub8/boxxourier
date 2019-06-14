<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <!-- Edit dialog -->
        <v-dialog v-model="editModal" persistent max-width="400px">
            <v-card>
                <v-card-title fixed>
                    <span class="headline">Add A Branch</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <div v-show="Editloader" style="text-align: center; width: 100%;">
                            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                        </div>
                        <v-layout wrap v-show="!Editloader">
                            <v-form ref="form" @submit.prevent="submit">
                                <v-container grid-list-xl fluid>
                                    <v-layout wrap>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="editedItem.name" :rules="rules.name" color="blue darken-2" label="Branch name" required>
                                            </v-text-field>
                                            <!-- <small class="has-text-danger" v-if="errors.branch_name">{{ errors.branch_name[0] }}</small> -->
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-card-actions>
                                    <v-btn flat @click="resetForm">reset</v-btn>
                                    <v-btn flat @click="close">Close</v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn :disabled="!formIsValid" flat color="primary" @click="save">Submit</v-btn>
                                </v-card-actions>
                            </v-form>
                        </v-layout>
                    </v-container>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!-- Edit dialog -->
<!-- <router-link :to="test">test</router-link> -->
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <!-- <v-btn @click="openAdd" color="primary">Add A Branch</v-btn> -->
                <div v-show="!loader">
                    <v-card-title>

                        <v-btn color="primary" flat @click="openAdd">Add A Status</v-btn>
                        Delivery Status
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllStatus" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.name }} </td>
                            <td class="text-xs-right">{{ props.item.created_at }}</td>
                            <td class="justify-center layout px-0">
                                <v-btn icon class="mx-0" @click="editItem(props.item)">
                                    <v-icon color="blue darken-2" small>edit</v-icon>
                                </v-btn>
                                <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                                    <v-icon color="pink darken-2" small>delete</v-icon>
                                </v-btn>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                </div>
            </v-layout>
        </v-container>
    </v-content>
    <AddDStatus :openAddRequest="OpenAdd" @closeRequest="close" @alertRequest="alert"></AddDStatus>
    <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import AddDStatus from './AddDStatus'
export default {
    props: ['user'],
    components: {
        AddDStatus
    },
    data() {
        return {
            errors: {},
            OpenAdd: false,
            search: '',
            snackbar: false,
            timeout: 5000,
            message: 'Success',
            color: 'black',
            headers: [{
                    text: 'Name',
                    align: 'left',
                    value: 'name'
                },
                {
                    text: 'Created At',
                    value: 'created_at'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
            loader: false,
            Editloader: false,
            editModal: false,
            AllStatus: [],
            editedItem: {
                name: '',
            },
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
        }
    },
    methods: {
        editItem(item) {
            this.editModal = true
            this.editedIndex = this.AllStatus.indexOf(item)
            this.editedItem = Object.assign({}, item)
        },
        save() {
            this.Editloader = true
            axios.patch(`/delStatus/${this.editedItem.id}`, this.$data.editedItem)
                .then((response) => {
                    console.log(response);
                    // this.AllStatus.push(this.editedItem)
                    Object.assign(this.AllStatus[this.editedIndex], this.editedItem)
                    this.Editloader = false
                    // this.close()
                    this.color = 'black'
                    this.message = 'Branch Updated'
                    this.snackbar = true
                })
                .catch((error) => {
                    this.Editloader = false
                    this.close()
                    this.color = 'red'
                    this.message = 'Something went wrong'
                    this.snackbar = true
                    this.errors = error.response.data.errors
                })
        },
        openAdd() {
            this.OpenAdd = true
        },

        deleteItem(item) {
            const index = this.AllStatus.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
        },

        alert() {
            this.message = 'Status added'
            this.color = 'black'
            this.snackbar = true
        },
        close() {
            this.OpenAdd = this.editModal = false
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
    },
    mounted() {
        this.loader = true
        axios.get('/delStatus')
            .then((response) => {
                this.AllStatus = response.data
                this.loader = false
            })
            .catch((error) => {
                this.errors = error.response.data.errors
                this.loader = false
            })
    },
    computed: {
        formIsValid() {
            return (
                this.editedItem.name
            )
        },
    },
    // beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //         if (vm.user.can['view branches']) {
    //             next();
    //         } else {
    //             next('/unauthorized');
    //         }
    //     })
    // }
}
</script>
