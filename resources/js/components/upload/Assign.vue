<template>
<v-layout row justify-center>
    <v-dialog v-model="openRequest" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <span class="headline">User Profile</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm12>
                            <v-select :items="AllClients" v-model="select" label="Select Client" single-line item-text="name" item-value="id" return-object persistent-hint></v-select>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-btn color="blue darken-1" flat @click="close" :loading="loading" :disabled="loading">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="assign">Assign</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openRequest', 'editdata'],
    data() {
        return {
            select: [],
            AllClients: [],
            errors: [],
            loading: false,
        }
    },
    methods: {
        assign() {
            axios.post(`/assign/${this.editdata.id}`, this.editdata)
                .then((response) => {
                    this.loading = false
                    this.$emit('alertRequest')
                })
                .catch(function (error) {
                    this.loading = false
                    console.log(error);
                    this.errors = error.response.data.errors
                });
        },
        close() {
            this.$emit('closeRequest')
        }
    },
    mounted() {
        axios.get('/getCustomer')
            .then((response) => {
                this.AllClients = response.data
            })
            .catch(function (error) {
                console.log(error);
                this.errors = error.response.data.errors
            });
    },
}
</script>
