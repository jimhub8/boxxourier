<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>


        <!-- Edit dialog -->

        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <!-- <v-btn @click="openAdd" color="primary">Add A Branch</v-btn> -->
                <div v-show="!loader">
                    <v-card-title>
                        Branchs
                        <v-spacer></v-spacer>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllBranches" :search="search" counter class="elevation-1">
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.branch_name }}</td>
                            <td class="text-xs-right">{{ props.item.phone }}</td>
                            <td class="text-xs-right">{{ props.item.email }}</td>
                            <td class="text-xs-right">{{ props.item.address }}</td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                </div>
            </v-layout>
        </v-container>
    </v-content>
</div>
</template>

<script>
import AddBranch from './AddBranch'
export default {
    props: ['user', 'role'],
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
            ],
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

    methods: {
    },
    created() {
        // this.loader = true
        axios.post(`/getBranchShip/${this.$route.params.id}`)
            .then((response) => {
                this.AllBranches = response.data
                // this.loader = false
            })
            .catch((error) => {
                if (error.response.status === 500) {
                    eventBus.$emit('errorEvent', error.response.statusText)
                    return
                } else if(error.response.status === 401 || error.response.status === 409) {
                    eventBus.$emit('reloadRequest', error.response.statusText)
                }
                this.errors = error.response.data.errors
                // this.loader = false
            })
    },
}
</script>
