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
                        <v-btn @click="invoiceAdd" flat color="primary">Add Invoice</v-btn>
                        <v-tooltip bottom>
                            <v-btn slot="activator" icon class="mx-0" @click="getInvoices">
                                <v-icon small color="blue darken-2">refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
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
                            <v-card-title>
                                Invoices
                                <download-excel :data="invoices" :fields = "json_fields">
                                    Export
                                    <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                                </download-excel>
                                <v-spacer></v-spacer>
                                <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                            </v-card-title>
                            <v-data-table :headers="headers" :items="invoices" class="elevation-1" :search="search" :loading="loading">
                                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                                <template slot="items" slot-scope="props">
                                    <td>{{ props.item.invoice_no }}</td>
                                    <td class="text-xs-right">{{ props.item.client }}</td>
                                    <td class="text-xs-right">{{ props.item.invoice_date }}</td>
                                    <td class="text-xs-right">{{ props.item.due_date }}</td>
                                    <td class="text-xs-right">{{ props.item.grand_total }}</td>
                                    <td class="justify-center layout px-0">
                                        <v-tooltip bottom>
                                            <v-btn slot="activator" icon class="mx-0" @click="invoiceEdit(props.item)">
                                                <v-icon small color="blue darken-2">edit</v-icon>
                                            </v-btn>
                                            <span>Edit</span>
                                        </v-tooltip>
                                        <v-tooltip top>
                                            <v-btn slot="activator" icon class="mx-0" @click="invoiceShow(props.item)">
                                                <v-icon small color="blue darken-2">visibility</v-icon>
                                            </v-btn>
                                            <span>View</span>
                                        </v-tooltip>
                                        <v-tooltip right>
                                            <v-btn slot="activator" icon class="mx-0" @click="invoiceMail(props.item)">
                                                <v-icon small color="blue darken-2">mail</v-icon>
                                            </v-btn>
                                            <span>Send email</span>
                                        </v-tooltip>
                                    </td>
                                </template>
                                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                    Your search for "{{ search }}" found no results.
                                </v-alert>
                            </v-data-table>
                        </v-card>
                    </v-flex>
                    <!-- <v-flex sm12> -->
                </v-layout>
            </v-layout>
        </v-container>
        <v-snackbar :timeout="timeout" bottom :color="color" left v-model="snackbar">
            {{ message }}
            <!-- <v-icon dark right>check_circle</v-icon> -->
            <v-btn>close</v-btn>
        </v-snackbar>
    </v-content>
    <AddInvoice @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert" :buyers="AllClients"></AddInvoice>
    <EditInvoice @closeRequest="close" :openAddRequest="dispEdit" @alertRequest="showAlert" :buyers="AllClients" :invoiceData="editinvoice"></EditInvoice>
    <ShowInvoice @closeRequest="close" :openAddRequest="dispShow" @alertRequest="showAlert" :invoice="editinvoice"></ShowInvoice>
    <MailInvoice @closeRequest="close" :openMailRequest="dispMail" @alertRequest="showAlert" :invoice="editinvoice"></MailInvoice>
</div>
</template>

<script>
let AddInvoice = require('./AddInvoice');
let EditInvoice = require('./EditInvoice');
let ShowInvoice = require('./ShowInvoice');
let MailInvoice = require('./EMail');
export default {
    components: {
        AddInvoice,
        EditInvoice,
        ShowInvoice,
        MailInvoice
    },
    data() {
        return {
            select: {
                name: 'All',
                id: 'all'
            },
            dispAdd: false,
            dispEdit: false,
            dispShow: false,
            dispMail: false,
            loader: false,
            snackbar: false,
            timeout: 5000,
            color: '',
            message: '',
            Allusers: [],
            invoices: [],
            AllClients: [],
            editinvoice: {},
            form: {
                start_date: '',
                end_date: ''
            },
            loading: false,
            search: '',
            headers: [{
                    text: 'Invoice Number',
                    align: 'left',
                    sortable: false,
                    value: 'invoice_no'
                },
                {
                    text: 'Client Name',
                    value: 'calories'
                },
                {
                    text: 'Invoice Date',
                    value: 'fat'
                },
                {
                    text: '	Due Date',
                    value: 'carbs'
                },
                {
                    text: 'Grand Total',
                    value: 'protein'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
            json_fields: {
                 'Invoice Number': 'invoice_no',
                 'Client': 'client',
                 'Client Address': 'client_address',
                 'Invoice Date': 'invoice_date',
                 'Invoice Title': 'title',
                 'Due Date': 'due_date',
                 'Discount': 'discount',
                 'VAT': 'vat',
                 'Grand Total': 'grand_total',
            },
        }
    },
    methods: {
        sort() {
            this.loading = true
            axios.post('/getInvoiceSort', {
                    form: this.form,
                    select: this.select
                })
                .then((response) => {
                    this.loading = false
                    this.invoices = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        invoiceEdit(invoice) {
            // console.log(invoice);
            this.editinvoice = Object.assign({}, invoice)
            this.editedIndex = this.invoices.indexOf(invoice)
            // console.log(this.editedItem);
            this.dispEdit = true
        },
        invoiceAdd() {
            this.dispAdd = true
        },
        /*invoiceEdit(key){		      	this.$children[2].list = this.invoices[key]		this.dispEdit  = true		},*/
        invoiceShow(invoice) {
            this.editinvoice = Object.assign({}, invoice)
            this.editedIndex = this.invoices.indexOf(invoice)
            // console.log(this.editedItem);
            this.dispShow = true
            // this.$children[3].list = this.invoices[key]
        },
        invoiceMail(invoice) {
            this.editinvoice = Object.assign({}, invoice)
            this.editedIndex = this.invoices.indexOf(invoice)
            // console.log(this.editedItem);
            this.dispMail = true
            // this.$children[3].list = this.invoices[key]
        },
        editItem(item) {
            this.editedItem = Object.assign({}, item)
            this.editedIndex = this.Allusers.indexOf(item)
            // console.log(this.editedItem);
            this.pdialog2 = true
        },
        showAlert() {
            this.message = 'Successifully Added';
            this.snackbar = true;
            this.color = 'indigo';
        },
        invoicedel(key, id) {
            if (confirm('Are you sure you want to delete this item?')) {
                this.loader = true
                axios.delete(`/users/${id}`)
                    .then((response) => {
                        this.Allusers.splice(index, 1)
                        this.loader = false
                        this.message = 'deleted successifully'
                        this.color = 'red'
                        this.snackbar = true
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                        this.loader = false
                        this.message = 'something went wrong'
                        this.color = 'red'
                        this.snackbar = true
                    })
            }
        },
        close() {
            this.dispAdd = this.dispShow = this.dispEdit = this.dispMail = false
        },
        getInvoices() {
            axios.get('/getInvoice')
                .then((response) => {
                    this.invoices = response.data
                })
                .catch((error) => {
                    this.loader = false
                    this.errors = error.response.data.errors
                })
        }
    },
    computed: {
        Start_dates() {
            return this.form.start_date;
        },
        end_dates() {
            return this.form.end_date;
        }
    },
    mounted() {
        this.loader = true
        this.getInvoices()
        axios.get('/getUsers')
            .then((response) => {
                this.Allusers = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
        axios.get('/getCustomer')
            .then((response) => {
                this.loader = false
                this.AllClients = response.data
            })
            .catch((error) => {
                this.loader = false
                this.errors = error.response.data.errors
            })

    },
}
</script>
