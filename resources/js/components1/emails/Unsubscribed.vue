<template>
<v-layout row justify-center>
    <v-dialog v-model="openUnRequest" persistent max-width="900px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Unsubscribed</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap justify-center>
                        <v-card>
                            <v-flex sm12>
                                <v-card-title>
                                    UnSubscribed
                                    <v-spacer></v-spacer>
                                    <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                                </v-card-title>
                                <v-data-table :headers="headers" :items="Unsubscribed" :search="search" counter class="elevation-1">
                                    <template slot="items" slot-scope="props">
                                        <td>{{ props.item.name }}</td>
                                        <td class="text-xs-right">{{ props.item.email }}</td>
                                        <td class="text-xs-right">{{ props.item.deleted_at }}</td>
                                        <td class="justify-center layout px-0">
                                            <v-tooltip bottom>
                                                <v-btn icon class="mx-0" @click="refresh(props.item)" slot="activator">
                                                    <v-icon color="info darken-2" small>refresh</v-icon>
                                                </v-btn>
                                                <span>Subscribe</span>
                                            </v-tooltip>
                                        </td>
                                    </template>
                                    <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                        Your search for "{{ search }}" found no results.
                                    </v-alert>
                                </v-data-table>
                            </v-flex>
                        </v-card>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn flat @click="close">Close</v-btn>
                <v-spacer></v-spacer>
            </v-card-actions>
        </v-card>
        
    <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left="left" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openUnRequest', 'Unsubscribed'],
    data() {
        return {
            search: '',
            snackbar: false,
            timeout: 5000,
            message: 'Success',
            color: 'black',
            headers: [{
                    text: 'User Name',
                    align: 'left',
                    value: 'name'
                },
                {
                    text: 'Email',
                    value: 'email'
                },
                {
                    text: 'UnSubscribed On',
                    value: 'deleted_at'
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
        refresh(item) {
            this.message = 'Loading...'
            this.color = 'black'
            this.snackbar = true
            const index = this.Unsubscribed.indexOf(item)
            if (confirm('Are you sure you want to subscribe this user?')) {
                axios.post(`/refresh/${item.id}`)
                    .then((response) => {
                        this.snackbar = false
                        this.message = 'Subscribe success'
                        this.color = 'black'
                        this.snackbar = true
                        // this.AllSubscribers.splice(index, 1)
                        // console.log(response);
                    })
                    .catch((error) => {
                        this.errors = error.response.data.errors
                        this.message = 'something went wrong'
                        this.color = 'red'
                        this.snackbar = true
                    })
            }
            // confirm('Are you sure you want to delete this item?') && this.AllSubscribers.splice(index, 1)
        },
        close() {
            this.$emit('closeRequest');
        },
    }
}
</script>
