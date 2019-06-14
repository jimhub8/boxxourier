<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
            <v-layout justify-center align-center v-show="!loader">
                <v-layout row wrap>
                    <v-flex sm12>
                        <v-btn @click="docsAdd" flat color="primary">Add Documents</v-btn>
                        <v-layout wrap>
                            <v-flex sm12>
                                <v-card style="background: rgba(5, 117, 230, 0.16);">
                                    <v-layout wrap>
                                        <v-flex xs4 sm2 offset-sm1>
                                            <v-text-field v-model="form.start_date" color="blue darken-2" type="date" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs4 sm2 offset-sm1>
                                            <v-text-field v-model="form.end_date" color="blue darken-2" type="date" required></v-text-field>
                                        </v-flex>
                                        <v-flex xs4 sm3 offset-sm1>
                                            <v-select :items="AllClients" v-model="select" :hint="`${select.name}`" label="Select" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                                        </v-flex>
                                        <!-- <v-spacer></v-spacer> -->
                                        <v-flex xs2 sm2>
                                            <v-btn raised color="info" @click="sort">Filter</v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-card>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                    <v-flex sm12>
                        <v-card>
                            <v-card-title> Uploads
                                <!-- <download-excel :data="invoices"> Export <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;"> </download-excel> -->
                                <v-btn @click="docsAdd" color="primary" flat>Upload</v-btn>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headers" :items="AllDocs" class="elevation-1" :search="search" :loading="loading">
                                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.name }}</td>
                                    <td class="text-xs-right">{{ props.item.type }}</td>
                                    <td class="text-xs-right">{{ props.item.size }}</td>
                                    <td class="text-xs-right">{{ props.item.created_at }}</td>
                                    <td class="justify-center layout px-0">
                                        <v-tooltip bottom>
                                            <v-btn slot="activator" icon class="mx-0" @click="docsEdit(props.item)">
                                                <v-icon small color="info darken-2">assignment_turned_in</v-icon>
                                            </v-btn>
                                            <span>assign client</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                            <v-btn slot="activator" icon class="mx-0" @click="download(props.item)">
                                                <a :href="props.item.path" target="_blank" style="text-decoration: none;">
                                                    <v-icon small color="blue darken-2">get_app</v-icon>
                                                </a>
                                            </v-btn>
                                            <span>Download</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                                <v-alert slot="no-results" :value="true" color="error" icon="warning"> Your search for "{{ search }}" found no results. </v-alert>
                            </v-data-table>
                        </v-card>
                    </v-flex>
                </v-layout>
            </v-layout>
        </v-container>
        <v-snackbar :timeout="timeout" bottom :color="color" left v-model="snackbar"> {{ message }}
            <v-icon dark right>check_circle</v-icon>
        </v-snackbar>
    </v-content>
    <upload-list @closeRequest="close" :openRequest="dispAdd" @alertRequest="showAlert" :buyers="AllClients"></upload-list>
    <Assign @closeRequest="close" :openRequest="dispAssign" @alertRequest="showAlert" :editdata="editdocs"></Assign>
</div>
</template>

<script>
let Assign = require('./Assign');
export default {
    components: {
        Assign,
    },
    data() {
        return {
            select: {
                name: 'All',
                id: 'all'
            },
            dispAdd: false,
            dispEdit: false,
            dispAssign: false,
            dispMail: false,
            loader: false,
            snackbar: false,
            timeout: 5000,
            color: '',
            message: '',
            AllDocs: [],
            AllCats: [],
            AllClients: [],
            editdocs: [],
            form: {
                start_date: '',
                end_date: ''
            },
            loading: false,
            search: '',
            headers: [{
                    text: 'Name',
                    align: 'left',
                    sortable: false,
                    value: 'name'
                },
                {
                    text: 'Type',
                    value: 'type'
                },
                {
                    text: 'Size',
                    value: 'size'
                },
                {
                    text: 'Uploaded On',
                    value: 'created_at'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
        }
    },
    methods: {
        docsAdd() {
            this.dispAdd = true
        },
        sort() {

            this.loading = true

            axios.post('/getDocsSort', {
                    form: this.form,
                    select: this.select
                })

                .then((response) => {

                    this.loading = false

                    this.AllDocs = response.data

                })

                .catch((error) => {

                    this.loading = false

                    this.errors = error.response.data.errors

                })

        },
        docsEdit(docs) {
            this.editedIndex = this.AllDocs.indexOf(docs)
            this.editdocs = Object.assign({}, docs)
            this.dispAssign = true
        },
        download(invoice) {
            this.editinvoice = Object.assign({}, invoice)
            this.editedIndex = this.AllDocs.indexOf(invoice)
            this.dispShow = true
        },
        showAlert() {
            this.message = 'Success';
            this.snackbar = true;
            this.color = 'indigo';
        },
        close() {
            this.dispAdd = this.dispAssign = false
        },
    },
    mounted() {
        axios.get('/getClientsDocs')
            .then((response) => {
                this.AllClients = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
        axios.get('/getDocs')
            .then((response) => {
                this.AllDocs = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
        axios.get('/categories')
            .then((response) => {
                this.AllCats = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    },
}
</script>
