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
                    <span class="headline">Add A Country</span>
                </v-card-title>
                <v-card-text>
                    <v-container grid-list-md>
                        <v-layout wrap>
                            <v-form ref="form" @submit.prevent="submit">
                                <v-container grid-list-xl fluid>
                                    <v-layout wrap>
                                        <v-flex xs12 sm12>
                                            <v-text-field v-model="editedItem.country_name" :rules="rules.name" color="blue darken-2" label="Country name" required>
                                            </v-text-field>
                                            <small class="has-text-danger" v-if="errors.country_name">{{ errors.country_name[0] }}</small>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-card-actions>
                                    <v-btn flat @click="resetForm">reset</v-btn>
                                    <v-btn flat @click="close">Close</v-btn>
                                    <v-spacer></v-spacer>
                                    <v-btn :disabled="Editloader" :loading="Editloader" flat color="primary" @click="save">Submit</v-btn>
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
                <!-- <v-btn @click="openAdd" color="primary">Add A Country</v-btn> -->
                <div v-show="!loader" style="width: 100%;">
                    <v-card-title>
                        <v-btn color="primary" flat @click="openAdd">Add A Country</v-btn>
                        Countries
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllCountries" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.country_name }} </td>
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
    <AddCountry :openAddRequest="OpenAdd" @closeRequest="close" @alertRequest="alert"></AddCountry>
    <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import AddCountry from './AddCountry'
export default {
    props: ['user'],
    components: {
        AddCountry
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
                    text: 'Country Name',
                    align: 'left',
                    value: 'country_name'
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
            AllCountries: [],
            editedItem: {
                country_name: '',
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
            this.editedIndex = this.AllCountries.indexOf(item)
            this.editedItem = Object.assign({}, item)
        },
        save() {
            this.Editloader = true
            axios.patch(`/country/${this.editedItem.id}`, this.$data.editedItem)
                .then((response) => {
                    console.log(response);
                    // this.AllCountries.push(this.editedItem)
                    Object.assign(this.AllCountries[this.editedIndex], this.editedItem)
                    this.Editloader = false
                    this.close()
                    this.color = 'black'
                    this.message = 'Country Updated'
                    this.snackbar = true
                })
                .catch((error) => {
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    } else if (error.response.status === 401 || error.response.status === 409) {
                        eventBus.$emit('reloadRequest', error.response.statusText)
                    }
                    this.errors = error.response.data.errors
                })
        },
        openAdd() {
            this.OpenAdd = true
        },

        deleteItem(item) {
            const index = this.AllCountries.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
        },

        alert() {
            this.message = 'Country added'
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
        axios.get('/getCountry')
            .then((response) => {
                this.AllCountries = response.data
                this.loader = false
            })
            .catch((error) => {
                this.loader = false
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if (error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
            })
    },
    computed: {
        // test() {
        //     return 'country/1'
        // },
        // test: function () {
        //     var vm = this;
        //     return function (item) {
        //         return 'country/1';
        //     };
        // },
        formIsValid() {
            return (
                this.editedItem.country_name
            )
        },
    },
}
</script>
