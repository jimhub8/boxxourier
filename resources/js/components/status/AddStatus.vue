<template>
<v-layout row justify-center>
    <v-dialog v-model="openAddRequest" persistent max-width="400px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Status</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent>
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm12>
                                        <v-text-field v-model="form.name" :rules="rules.name" color="blue darken-2" label="Title" required>
                                        </v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.branch_name">{{ errors.branch_name[0] }}</small> -->
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="resetForm">reset</v-btn>
                                <v-btn flat @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn :disabled="loading" :loading="loading" flat color="primary" @click="save">Submit</v-btn>
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
    props: ['openAddRequest'],
    data() {
        const defaultForm = Object.freeze({
            name: '',
        })
        return {
            errors: {},
            defaultForm,
            loading: false,
            form: Object.assign({}, defaultForm),
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
        }
    },
    methods: {
        save() {
            this.loading = true
            axios.post('/status', this.$data.form).
            then((response) => {
                    this.loading = false
                    console.log(response);
                    this.$parent.AllStatus.push(response.data)
                    // this.close();
                    this.resetForm();
                    // this.$emit('closeRequest');
                    this.$emit('alertRequest');

                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                    })
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm)
            this.$refs.form.reset()
        },
        alert() {
            this.$emit('alertRequest')
        },
        close() {
            this.$emit('closeRequest')
        },
    },
    computed: {
        formIsValid() {
            return (
                this.form.branch_name &&
                this.form.email &&
                this.form.phone &&
                this.form.address
            )
        },
    },
    mounted() {

    }
}
</script>
