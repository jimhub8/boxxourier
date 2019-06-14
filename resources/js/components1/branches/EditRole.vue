<template>
<v-layout row justify-center>
    <v-dialog v-model="openEditRequest" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Branch</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">                         <v-icon color="black">close</v-icon>                     </v-btn>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent="submit">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.name" :rules="rules.name" color="purple darken-2" label="Full name" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.email" :rules="emailRules" color="blue darken-2" label="Email" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field :append-icon="e1 ? 'visibility' : 'visibility_off'" :append-icon-cb="() => (e1 = !e1)" :type="e1 ? 'password' : 'text'" v-model="form.password" name="input-10-2" label="Enter your password" hint="At least 8 characters" min="8" value="" class="input-group--focused"></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.address" :rules="rules.name" color="blue darken-2" label="Address" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.city" :rules="rules.name" color="blue darken-2" label="City" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.country" :rules="rules.name" color="blue darken-2" label="Country" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.phone" :rules="rules.name" color="blue darken-2" label="Phone" required></v-text-field>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.zipcode" :rules="rules.name" color="blue darken-2" label="Zip Code" required></v-text-field>
                                    </v-flex>

                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.branch" :rules="rules.name" color="blue darken-2" label="Branch" required></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="resetForm">reset</v-btn>
                                <v-btn flat @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn :disabled="!formIsValid" flat color="primary" @click="update">Submit</v-btn>
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
    props: ['openEditRequest'],
    data() {
        const defaultForm = Object.freeze({
            name: '',
            password: '',
            email: '',
            phone: null,
            zipcode: null,
            branch: '',
            address: '',
            city: '',
            country: '',
        })
        return {
            defaultForm,
            e1: false,
            list: {},
            form: Object.assign({}, defaultForm),
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
        update() {
            axios.patch(`/users/${this.form.id}`, this.$data.form).
            then((response) => {
                    // console.log(response);
                    this.close;
                    this.resetForm();
                    this.$emit('closeRequest');
                    this.$emit('alertRequest');

                })
                .catch((error) => this.errors = error.response.data.errors)
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
        close() {
            this.$emit('closeRequest')
        },
    },
    computed: {
        formIsValid() {
            return (
                this.form.name &&
                this.form.email &&
                this.form.phone &&
                this.form.password &&
                this.form.zipcode &&
                this.form.branch &&
                this.form.address &&
                this.form.city &&
                this.form.country
            )
        },
    },
    mounted() {

    }
}
</script>
