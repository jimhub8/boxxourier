<template>
<v-layout row justify-center>
    <v-dialog v-model="openAddRequest" persistent max-width="500px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Task</span>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent>
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs4 sm3 offset-sm4>
                                        <v-select :items="items" v-model="schedule" :hint="`${schedule.state}`" label="Select" single-line item-text="state" item-value="abbr" return-object persistent-hint></v-select>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-textarea v-model="form.description" color="blue">
                                            <div slot="label">
                                                Description <small>(optional)</small>
                                            </div>
                                        </v-textarea>
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
    props: ['openAddRequest', 'buyers'],
    components: {},
    data() {
        const defaultForm = Object.freeze({

        })
        return {
            errors: {},
            schedule: [],
            defaultForm,
            randomNumber: '',
            loading: false,
            form: Object.assign({}, defaultForm),
            rules: {
                name: [val => (val || '').length > 0 || 'This field is required']
            },
            items: [{
                    state: 'Dairy At 8',
                    abbr: 'dailyAt("08:00")',
                },
                {
                    state: 'Weekly',
                    abbr: 'weekly()',
                },
                {
                    state: 'Monthly',
                    abbr: 'monthly()',
                },
                {
                    state: 'Quarterly',
                    abbr: 'quarterly()',
                },
                {
                    state: 'Yearly',
                    abbr: 'yearly()',
                },
            ],
        }
    },
    methods: {
        save() {
            this.loading = true
            axios.post('/tasks', {
                form: this.$data.form,
                schedule: this.$data.schedule
                }).
            then((response) => {
                    this.loading = false
                    console.log(response);
                    // this.close;
                    // this.resetForm();
                    this.$emit('alertRequest');
                    this.$parent.AllTasks.push(response.data)
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
        close() {
            this.$emit('closeRequest')
        },
    },
}
</script>
