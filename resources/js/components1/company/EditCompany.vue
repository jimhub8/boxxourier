<template>
<v-layout row justify-center>
    <v-dialog v-model="openEditRequest" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Company</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent="submit">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="company.company_name" :rules="rules.name" color="blue darken-2" label="Company name" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.company_name">{{ errors.company_name[0] }}</small>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="company.address" :rules="rules.name" color="blue darken-2" label="Company Address" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="company.phone" :rules="rules.name" color="blue darken-2" label="Telephone Number" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="company.email" :rules="rules.name" color="blue darken-2" label="Company Email" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="company.website" :rules="rules.name" color="blue darken-2" label="Company Website" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.website">{{ errors.website[0] }}</small>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn flat color="primary" @click="save" :loading="loading" :disabled="loading">Submit</v-btn>
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
    props: ['openEditRequest', 'company'],
    components: {},
    data() {
        return {
            errors: {},
            loading: false,
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
        }
    },
    methods: {
        save() {
            this.loading = true
            axios.patch(`/companies/${this.company.id}`, this.company)
                .then((response) => {
                    this.loading = false
                    this.alert()
                    this.close()
                    Object.assign(this.$parent.AllCompanies[this.$parent.editedIndex], this.company)
                })
                .catch((error) => {
                    this.loading = false
                    this.Editloader = false
                    this.close()
                    this.color = 'red'
                    this.message = 'Something went wrong'
                    this.snackbar = true
                    this.errors = error.response.data.errors
                })
        },
        alert() {
            this.$emit('alertRequest')
        },
        close() {
            this.$emit('closeRequest')
        },

    },
}
</script>
