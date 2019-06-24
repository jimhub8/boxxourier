<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <!-- Edit dialog -->
        <v-dialog v-model="editModal" persistent max-width="700px">
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
                                            <v-text-field v-model="editedItem.branch_name" :rules="rules.name" color="blue darken-2" label="Branch name" required>
                                            </v-text-field>
                                            <small class="has-text-danger" v-if="errors.branch_name">{{ errors.branch_name[0] }}</small>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="editedItem.address" :rules="rules.name" color="blue darken-2" label="Branch Address" required>
                                            </v-text-field>
                                            <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="editedItem.phone" :rules="rules.name" color="blue darken-2" label="Telephone Number" required>
                                            </v-text-field>
                                            <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                                        </v-flex>
                                        <v-flex xs12 sm6>
                                            <v-text-field v-model="editedItem.email" :rules="emailRules" color="blue darken-2" label="Branch Email" required>
                                            </v-text-field>
                                            <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                        </v-flex>
                                        <div class="form-group row">
                                            <label for="password" class="col-md-6 col-form-label text-md-right">Country</label>
                                            <select class="custom-select custom-select-md col-md-12" v-model="editedItem.country_id">
                                                <option v-for="country in AllCountries" :key="country.id" :value="country.id">{{ country.country_name }}</option>
                                        </select>
                                            <small class="has-text-danger" v-if="errors.country_id" >{{ errors.country_id[0] }}</small>
                                        </div>
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
                <div v-show="!loader" style="width: 100%;">
                    <v-card-title>
                        <download-excel :data="AllBranches" :fields="json_fields">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <v-btn color="primary" flat @click="openAdd">Add A Branch</v-btn>
                            Branchs
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllBranches" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.branch_name }} </td>
                            <td class="text-xs-right">{{ props.item.phone }}</td>
                            <td class="text-xs-right">{{ props.item.email }}</td>
                            <td class="text-xs-right">{{ props.item.address }}</td>
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
    <AddBranch :openAddRequest="OpenAdd" @closeRequest="close" @alertRequest="alert"></AddBranch>
    <v-snackbar :timeout="timeout" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import AddBranch from './AddBranch'
export default {
    props: ['user'],
    components: {
        AddBranch
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
            AllCountries: [],
            dialog: false,
            headers: [{
                    text: 'Branch Name',
                    align: 'left',
                    value: 'branch_name'
                },
                {
                    text: 'Telephone Number',
                    value: 'phone'
                },
                {
                    text: 'Email',
                    value: 'email'
                },
                {
                    text: 'Address',
                    value: 'address'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
            json_fields: {
                'Branch Name': 'branch_name',
                'Email': 'email',
                'Phone': 'phone',
                'Address': 'address',
            },
            Allusers: [],
            editedIndex: -1,
            loader: false,
            Editloader: false,
            editModal: false,
            AllBranches: [],
            editedItem: {
                branch_name: '',
                email: '',
                phone: '',
                address: '',
                country_id: '',
            },
            emailRules: [
                v => {
                    return !!v || 'E-mail is required'
                },
                v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
            ],
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
            this.editedIndex = this.AllBranches.indexOf(item)
            this.editedItem = Object.assign({}, item)
        },
        save() {
            this.Editloader = true
            axios.patch(`/branches/${this.editedItem.id}`, this.$data.editedItem)
                .then((response) => {
                    console.log(response);
                    // this.AllBranches.push(this.editedItem)
                    Object.assign(this.AllBranches[this.editedIndex], this.editedItem)
                    this.Editloader = false
                    this.close()
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
            const index = this.AllBranches.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
        },

        alert() {
            this.message = 'Branch added'
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
        axios.get('/getBranch')
            .then((response) => {
                this.AllBranches = response.data
                this.loader = false
            })
            .catch((error) => {
                this.loader = false
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if(error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
            })

        axios.get("/getCountry")
            .then(response => {
                this.AllCountries = response.data;
                this.loader = false;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
                this.loader = false;
            });
    },
    computed: {
        // test() {
        //     return 'branch/1'
        // },
        // test: function () {
        //     var vm = this;
        //     return function (item) {
        //         return 'branch/1';
        //     };
        // },
        formIsValid() {
            return (
                this.editedItem.branch_name &&
                this.editedItem.country_id
            )
        },
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can['view branches']) {
                next();
            } else {
                next('/unauthorized');
            }
        })
    }
}
</script>
