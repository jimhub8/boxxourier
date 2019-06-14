<template>
<v-layout row justify-center>
    <v-dialog v-model="openPermRequest" persistent max-width="500px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Role</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent>
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm6>
                                        <v-select :items="AllPermissions" v-model="selected" label="Select Permissions" multiple chips hint="Give Permissions" persistent-hint></v-select>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn :disabled="loading" flat color="primary" @click="save" :loading="loading">Submit</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-layout>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openPermRequest', 'AllShips'],
    data() {
        return {
            selected: [

            ],
            loading: false,
            AllPermissions: [],
        }
    },
    methods: {
        save() {
            this.loading = true
            axios.post('/permissions', this.$data.form).
            then((response) => {
                    this.loading = false
                    console.log(response);
                    this.$emit('alertRequest');
                    this.$parent.AllRoles.push(response.data)
                    // this.$emit('closeRequest');

                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        close() {
            this.$emit('closeRequest')
        },
    },
    mounted() {
        axios.get('/getPermissions')
            .then((response) => {
                this.AllPermissions = response.data
            })
            .catch((error) => {
                this.errors = error.response.data.errors
            })
    }
}
</script>
