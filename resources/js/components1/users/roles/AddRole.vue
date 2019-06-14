<template>
<v-layout row justify-center>
    <v-dialog v-model="openAddRequest" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Add Role</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>

            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-form ref="form" @submit.prevent>
                            <v-container grid-list-xl fluid>
                                <v-layout wrap>
                                    <v-flex xs12 sm12>
                                        <v-text-field v-model="form.name" :rules="rules.name" color="blue darken-2" label="Role" required></v-text-field>
                                        <!-- <small class="has-text-danger" v-if="errors.name">{{ errors.name[0] }}</small> -->
                                    </v-flex>
                                    <v-checkbox v-model="selectAll" label="Select All" value="all" @change="selectRoles"></v-checkbox>
                                    <!-- <v-checkbox v-model="selectAll" label="Unselect All" value="all" @change="unselectRoles" v-else></v-checkbox> -->
                                </v-layout>
                                <v-layout wrap>
                                    <v-flex v-for="perm in sortPerm" :key="perm.id" xs6 sm3>
                                        <v-checkbox v-model="selected" :label="perm.name" :value="perm.name"></v-checkbox>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                            <v-card-actions>
                                <v-btn flat @click="resetForm">reset</v-btn>
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
    props: ["openAddRequest", "AllBranches"],
    data() {
        const defaultForm = Object.freeze({
            name: "",
            description: ""
        });
        return {
            errors: [],
            selectAll: [],
            loading: false,
            selected: [],
            defaultForm,
            loader: false,
            permissions: [],
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
                .post("/roles", {
                    form: this.$data.form,
                    selected: this.$data.selected
                })
                .then(response => {
                    this.loading = false;
                    console.log(response);
                    this.$emit("alertRequest");
                    this.$parent.AllRoles.push(response.data);
                    this.resetForm();
                    this.$emit("closeRequest");
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
        close() {
            this.$emit("closeRequest");
        },
        selectRoles() {
            this.selected = [];
            // console.log(sel)
            this.selectAll.forEach(sel => {
                this.permissions.forEach(perm => {
                    this.selected.push(perm.name);
                });
            });
            // console.log(this.selectAll)
        }
        // unselectRoles() {
        //     this.selected = []
        // }
    },
    computed: {
        formIsValid() {
            return this.form.name;
        },

        sortPerm() {
            return _.orderBy(this.permissions, 'name', 'asc')
        }
    },
    mounted() {
        axios
            .get("/getPermissions")
            .then(response => {
                this.permissions = response.data;
            })
            .catch(errors => {
                this.errors = error.response.data.errors;
            });
    }
};
</script>
