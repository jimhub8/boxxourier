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
                    <span class="headline">Add A Town</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-form ref="form" @submit.prevent="submit">
                                <v-container grid-list-xl fluid>
                                    <v-layout wrap>
                                        <v-flex xs12 sm12>
                                            <v-text-field v-model="editedItem.town_name" :rules="rules.name" color="blue darken-2" label="Town name" required>
                                            </v-text-field>
                                            <!-- <small class="has-text-danger" v-if="errors.town_name">{{ errors.town_name[0] }}</small> -->
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-card-actions>
                                    <v-btn flat @click="resetForm">reset</v-btn>
                                    <v-btn flat @click="close">Close</v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn :disabled="loading" :loading="loading" flat color="primary" @click="save">Submit</v-btn>
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
                <!-- <v-btn @click="openAdd" color="primary">Add A Town</v-btn> -->
                <div v-show="!loader" style="width: 100%;">
                    <v-card-title>
                        <download-excel :data="AllTowns" :fields = "json_fields">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                        <v-btn color="primary" flat @click="openAdd">Add A Town</v-btn>
                        towns
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllTowns" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.town_name }} </td>
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
    <AddTown :openAddRequest="OpenAdd" @closeRequest="close" @alertRequest="alert"></AddTown>
    <v-snackbar :timeout="timeout" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import AddTown from './AddTown'
export default {
    props: ['user', 'role'],
    components: {
        AddTown
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
            y: 'bottom',
            x: 'left',
            loading: false,
            dialog: false,
            headers: [{
                    text: 'Town Name',
                    align: 'left',
                    value: 'town_name'
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
            json_fields: {
                 'Town Name': 'town_name',
                 'Created At': 'created_at',
            },
            Allusers: [],
            editedIndex: -1,
            loader: false,
            editModal: false,
            AllTowns: [],
            editedItem: {
                town_name: '',
            },
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
        }
    },

    watch: {
        dialog(val) {
            val || this.close()
        }
    },

    methods: {
        editItem(item) {
            this.editModal = true
            this.editedIndex = this.AllTowns.indexOf(item)
            this.editedItem = Object.assign({}, item)
        },
        save() {
            this.loading = true
            axios.patch(`/townes/${this.editedItem.id}`, this.$data.editedItem)
                .then((response) => {
                    console.log(response);
                    // this.AllTowns.push(this.editedItem)
                    Object.assign(this.AllTowns[this.editedIndex], this.editedItem)
                    this.loading = false
                    this.close()
                    this.color = 'black'
                    this.message = 'Town Updated'
                    this.snackbar = true
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loading = false
                    this.close()
                    this.color = 'red'
                    this.message = 'Something went wrong'
                    this.snackbar = true
                })
        },
        openAdd() {
            this.OpenAdd = true
        },

        deleteItem(item) {
            const index = this.AllTowns.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
        },

        alert() {
            this.message = 'Town added'
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
        axios.get('/getTowns')
            .then((response) => {
                this.AllTowns = response.data
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
                this.editedItem.town_name
            )
        },
    },
}
</script>
