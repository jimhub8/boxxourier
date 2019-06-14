<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <!-- Assign Driver -->

        <!-- Assign Driver -->

        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <!-- <v-btn @click="openAdd" color="primary">Add A Branch</v-btn> -->
                <div v-show="!loader">
                    <v-card-title>
                        Companies
                        <v-btn color="primary" raised @click="openAdd">Add Company</v-btn>

                        <v-btn icon class="mx-0" @click="getCompany()">
                            <v-icon color="blue darken-2">refresh</v-icon>
                        </v-btn>
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllCompanies" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.company_name }}</td>
                            <td class="text-xs-right">{{ props.item.phone }}</td>
                            <td class="text-xs-right">{{ props.item.email }}</td>
                            <td class="text-xs-right">{{ props.item.address }}</td>
                            <td class="text-xs-right">{{ props.item.website }}</td>
                            <td class="justify-center layout px-0">
                                <v-btn icon class="mx-0" @click="editItem(props.item)">
                                    <v-icon color="blue darken-2">edit</v-icon>
                                </v-btn>
                                <v-btn icon class="mx-0" @click="imageUpload(props.item)">
                                    <v-icon color="blue darken-2">image</v-icon>
                                </v-btn>
                                <v-btn icon class="mx-0" @click="deleteItem(props.item)">
                                    <v-icon color="pink darken-2">delete</v-icon>
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
    <AddCompany :openAddRequest="OpenAdd" :compAdmin='Allusers' @closeRequest="close" @alertRequest="alert"></AddCompany>
    <EditCompany :openEditRequest="editModal" :company='editedItem' @closeRequest="close" @alertRequest="alert"></EditCompany>
    <Logo :openLogoRequest="LogoModal" :company='imageItem' @closeRequest="close" @alertRequest="alert"></Logo>
    <v-snackbar :timeout="timeout" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</div>
</template>

<script>
import AddCompany from './AddCompany';
import EditCompany from './EditCompany';
import Logo from './Logo';
export default {
    props: ['user', 'role'],
    components: {
        AddCompany,
        EditCompany,
        Logo
    },
    data() {
        return {
            errors: {},
            select: {},
            avatar: '',
            OpenAdd: false,
            LogoModal: false,
            search: '',
            snackbar: false,
            timeout: 5000,
            message: 'Success',
            color: 'black',
            y: 'bottom',
            x: 'left',
            dialog: false,
            loading: false,
            loading: false,
            headers: [{
                    text: 'Company Name',
                    align: 'left',
                    value: 'company_name'
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
                    text: 'Website',
                    value: 'website'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
            Allusers: [],
            companyLogo: {},
            imageModal: false,
            imagePlaced: false,
            editedIndex: -1,
            loader: false,
            Editloader: false,
            editModal: false,
            AllCompanies: [],
            logo: '',
            imageItem: {},
            address: '',
            editedItem: {},
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

    methods: {
        editItem(item) {
            this.editModal = true
            this.editedIndex = this.AllCompanies.indexOf(item)
            this.editedItem = Object.assign({}, item)
        },
        imageUpload(item) {
            this.editedIndex = this.AllCompanies.indexOf(item)
            this.imageItem = Object.assign({}, item)
            this.LogoModal = true
        },
        openAdd() {
            this.OpenAdd = true
        },

        deleteItem(item) {
            const index = this.AllCompanies.indexOf(item)
            confirm('Are you sure you want to delete this item?') && this.AllCompanies.splice(index, 1)
        },

        alert() {
            this.message = 'Success'
            this.color = 'black'
            this.snackbar = true
        },
        close() {
            this.OpenAdd = this.editModal = this.LogoModal = false
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },

        getCompany() {

            axios.post('/getCompanies')
                .then((response) => {
                    this.AllCompanies = response.data
                    this.loader = false
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loader = false
                })

        }

    },
    mounted() {
        this.loader = true
        this.getCompany()
        axios.post('/getCompanyAdmin')
            .then((response) => {
                this.Allusers = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
        axios.post('/getLogo')
            .then((response) => {
                this.companyLogo = response.data
                /*if (this.companyLogo.logo.length > 0) {
                  this.avatar = this.companyLogo.logo
                  this.imagePlaced = true
                }*/
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

        axios.post('/getLogoOnly')
            .then((response) => {
                this.loader = false
                if (response.data.length > 0) {
                    this.imagePlaced = true
                    this.avatar = response.data
                } else {
                    this.avatar = ''
                }
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
        formIsValid() {
            return (
                this.editedItem.company_name &&
                this.editedItem.email &&
                this.editedItem.phone &&
                this.editedItem.address
            )
        },
    },

    // beforeRouteEnter(to, from, next) {
    //     next(vm => {
    //         if (vm.role === 'Admin') {
    //             next();
    //         } else {
    //             next('/unauthorized');
    //         }
    //     })
    // }
}
</script>
