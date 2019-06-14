<template>
<v-layout row justify-center>
    <v-dialog v-model="openEditRequest" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add User</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap v-show="!loader">
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

                                    <div class="form-group col-md-6">
                                        <label class="col-md-6 col-form-label text-md-right" for="">Role</label>
                                        <select class="custom-select custom-select-md col-md-12" v-for="role in form.roles" :key="role.id" v-model="role.name">
                                            <option v-for="roles in AllRoles" :key="roles.id" :value="roles.name">{{ roles.name }}</option>
                                    </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-md-6 col-form-label text-md-right" for="">Branch</label>
                                        <select class="custom-select custom-select-md col-md-12" v-model="form.branch_id">
                                            <option v-for="branches in AllBranches" :key="branches.id" :value="branches.id">{{ branches.branch_name }}</option>
                                    </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-md-6 col-form-label text-md-right" for="">Country</label>
                                        <select class="custom-select custom-select-md col-md-12" v-model="form.country_id">
                                            <option v-for="country in countryList" :key="country.id" :value="country.id">{{ country.country_name }}</option>
                                    </select>
                                        <!-- <small class="has-text-danger" v-if="errors.branch_id">{{ errors.branch_id[0] }}</small> -->
                                    </div>
                                    <v-flex xs12>
                                        <v-expansion-panel inset>
                                            <v-expansion-panel-content>
                                                <div slot="header">Permissions</div>
                                                <v-card>
                                                    <v-card-text>
                                                        <v-layout wrap>
                                                            <div v-for="perm in permissions" :key="perm.id">
                                                                <v-flex xs12 sm12>
                                                                    <v-checkbox v-model="selected" :label="perm.name" :value="perm.name"></v-checkbox>
                                                                </v-flex>
                                                            </div>
                                                        </v-layout>
                                                    </v-card-text>
                                                </v-card>
                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-flex>

                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="resetForm">reset</v-btn>
                                <v-btn flat @click="close">Close</v-btn>
                                <v-spacer></v-spacer>
                                <v-btn :disabled="loading" flat color="primary" @click="update" :loading="loading">Submit</v-btn>
                            </v-card-actions>
                        </v-form>
                    </v-layout>
                    <div v-show="loader" style="text-align: center">
                        <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                    </div>
                </v-container>
            </v-card-text>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openEditRequest', 'form', 'AllBranches', 'AllRoles', 'countryList'],
    data() {
        return {
            e1: true,
            loader: false,
            loading: false,
            list: {},
            selected: [],
            permissions: [],
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
            this.loading = true
            axios.patch(`/users/${this.form.id}`, {
                form: this.form,
                selected: this.selected
            }).
            then((response) => {
                    // console.log(response);
                    this.loading = false
                    this.$emit('alertRequest');
                    this.close();
                    Object.assign(this.$parent.Allusers[this.$parent.editedIndex], this.$parent.editedItem)
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                    this.loading = false
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
    created() {
        eventBus.$on('permEvent', data => {
            this.selected = data;
        });
    },
    computed: {
        formIsValid() {
            return (
                this.form.name &&
                this.form.email &&
                this.form.phone &&
                this.form.password &&
                this.form.branch &&
                this.form.address &&
                this.form.city &&
                this.form.country
            )
        },
    },
    mounted() {

        axios.get('/getPermissions')
            .then((response) => {
                this.permissions = response.data
            })
            .catch((errors) => {
                this.errors = error.response.data.errors
            })
    }
}
</script>
