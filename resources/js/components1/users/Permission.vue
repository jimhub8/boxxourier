<template>
<v-layout row justify-center>
    <v-dialog v-model="openPermRequest" persistent max-width="700px">
        <v-card>
            <v-card-title fixed>
                <span class="headline">Permissions</span>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="close">
                    <v-icon color="black">close</v-icon>
                </v-btn>
            </v-card-title>
            <v-card-text>
                <v-layout wrap>
                    <v-flex xs6 sm3 v-for="perm in sortPerm" :key="perm.id">
                        <v-checkbox v-model="selected" :label="perm.name" :value="perm.name"></v-checkbox>
                    </v-flex>
                </v-layout>
            </v-card-text>

            <v-card-actions>
                <!-- <v-btn flat @click="resetForm">reset</v-btn> -->
                <v-btn flat @click="close">Close</v-btn>
                <v-spacer></v-spacer>
                <v-btn :disabled="loading" flat color="primary" @click="save" :loading="loading">Submit</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ['openPermRequest', 'form'],
    data() {
        return {
            e1: true,
            loader: false,
            loading: false,
            selected: [],
            permissions: [],
        }
    },
    methods: {
        save() {
            this.loading = true
            axios.post(`/permisions/${this.form.id}`, {
                selected: this.selected
            }).
            then((response) => {
                    // console.log(response);
                    this.loading = false
                    this.$emit('alertRequest');
                    this.close();
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
                this.form.company
            )
        },
        sortPerm() {
            return _.orderBy(this.permissions, 'name', 'asc')
        }
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
