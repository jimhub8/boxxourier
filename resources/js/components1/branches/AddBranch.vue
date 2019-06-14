<template>
<v-layout row justify-center>
    <v-dialog v-model="openAddRequest" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Branch</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent="submit">
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.branch_name" :rules="rules.name" color="blue darken-2" label="Branch name" required></v-text-field>
                                        <small class="has-text-danger" v-if="errors.branch_name" >{{ errors.branch_name[0] }}</small>
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.address" :rules="rules.name" color="blue darken-2" label="Branch Address" required></v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.address">{{ errors.address[0] }}</small> -->
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.phone" :rules="rules.name" color="blue darken-2" label="Telephone Number" required></v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.phone">{{ errors.phone[0] }}</small> -->
                                    </v-flex>
                                    <v-flex xs12 sm6>
                                        <v-text-field v-model="form.email" :rules="rules.name" color="blue darken-2" label="Branch Email" required></v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.email">{{ errors.email[0] }}</small> -->
                                    </v-flex>
                                    <div class="form-group row">
                                        <label for="password" class="col-md-6 col-form-label text-md-right">Country</label>
                                        <select class="custom-select custom-select-md col-md-12" v-model="form.country_id">
                                                <option v-for="country in AllCountries" :key="country.id" :value="country.id">{{ country.country_name }}</option>
                                        </select>
                                        <small class="has-text-danger" v-if="errors.country_id" >{{ errors.country_id[0] }}</small>
                                    </div>
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
    props: ["openAddRequest"],
    data() {
        const defaultForm = Object.freeze({
            branch_name: "",
            email: "",
            phone: "",
            address: "",
            country_id: ''
        });
        return {
            errors: {},
            AllCountries: [],
            defaultForm,
            loading: false,
            form: Object.assign({}, defaultForm),
            rules: {
                name: [val => (val || "").length > 0 || "This field is required"]
            }
        };
    },
    methods: {
        save() {
            this.loading = true;
            axios
                .post("/branches", this.$data.form)
                .then(response => {
                    this.loading = false;
                    console.log(response);
                    this.$parent.AllBranches.push(response.data);
                    this.close;
                    this.resetForm();
                    this.$emit("closeRequest");
                    this.$emit("alertRequest");
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        resetForm() {
            this.form = Object.assign({}, this.defaultForm);
            this.$refs.form.reset();
        },
        alert() {
            this.$emit("alertRequest");
        },
        close() {
            this.$emit("closeRequest");
        }
    },
    computed: {
        formIsValid() {
            return (
                this.form.branch_name &&
                this.form.email &&
                this.form.phone &&
                this.form.address
            );
        }
    },
    mounted() {
        axios.get("/getCountry")
            .then(response => {
                this.AllCountries = response.data;
                this.loader = false;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
                this.loader = false;
            });
    }
};
</script>
