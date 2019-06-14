<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <v-layout justify-center align-center>
                <div v-show="loader" style="text-align: center; width: 100%;">
                    <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
                </div>
                <v-card v-show="!loader">
                    <v-card-title>
                        Roles
                        <v-btn @click="openAdd" flat color="primary">Add Roles</v-btn>
                        <!-- <v-spacer></v-spacer> -->
                        <v-tooltip right>
                            <v-btn icon slot="activator" class="mx-0" @click="getRoles">
                                <v-icon color="blue darken-2" small>refresh</v-icon>
                            </v-btn>
                            <span>Refresh</span>
                        </v-tooltip>
                        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="AllRoles" class="elevation-1" :search="search" :loading="loading">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.name }}</td>
                            <td class="text-xs-right">{{ props.item.description }}</td>
                            <td class="text-xs-right">{{ props.item.created_at }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom>
                                    <v-btn slot="activator" icon class="mx-0" @click="openEdit(props.item)">
                                        <v-icon small color="blue darken-2">edit</v-icon>
                                    </v-btn>
                                    <span>Edit</span>
                                </v-tooltip>
                                <v-tooltip bottom>
                                    <v-btn slot="activator" icon class="mx-0" @click="deleteItem(props.item)">
                                        <v-icon small color="red darken-2">delete</v-icon>
                                    </v-btn>
                                    <span>delete</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                </v-card>
                <v-snackbar :timeout="timeout" bottom="bottom" :color="color" left='left' v-model="snackbar">
                    {{ message }}
                    <v-icon dark right>check_circle</v-icon>
                </v-snackbar>
            </v-layout>
        </v-container>
    </v-content>
    <AddRole @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert"></AddRole>
    <!--  <ShowRole @closeRequest="close" :openShowRequest="dispShow"></ShowRole> -->
    <EditRole @closeRequest="close" :openEditRequest="dispEdit" @alertRequest="showAlert" :form="editedItem" :userPerm="userPerm"></EditRole>
</div>
</template>

<script>
import AddRole from './AddRole.vue'
// // import ShowRole from './ShowRole.vue'
import EditRole from './EditRole.vue'
export default {
    props: ['user', 'role'],
    components: {
        AddRole,
        // ShowRole,
        EditRole
    },
    data() {
        return {
            headers: [{
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Description",
                    value: "description"
                },
                {
                    text: "Created At",
                    value: "created_at"
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],
            search: '',
            loader: false,
            dispAdd: false,
            dispShow: false,
            dispEdit: false,
            snackbar: false,
            loading: false,
            timeout: 5000,
            color: 'black',
            message: 'Success',
            AllRoles: [],
            userPerm: [],
            temp: '',
            editedItem: {},
        }
    },
    methods: {
        openShow(key) {
            // this.$children[4].list = this.company[key]
            this.$children[2].list = this.AllRoles[key]
            this.dispShow = true
        },
        openAdd() {
            this.dispAdd = true
        },
        openEdit(item) {
            console.log(item)
            this.editedIndex = this.AllRoles.indexOf(item)
            this.editedItem = Object.assign({}, item)
            // axios.post('/getPerms')
            // .then((response) => {
            //     this.userPerm = response.data
            // })
            // .catch((error) => {
            //     this.errors = error.response.data.errors
            // })
            axios.post(`/getRolesPerm/${item.id}`, item)
                .then((response) => {
                    eventBus.$emit('RolepermEvent', response.data);
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
            this.dispEdit = true
        },
        showAlert() {
            this.message = 'Successifully Added';
            this.snackbar = true;
            this.color = 'black';
        },

        deleteItem(item) {
            if (confirm('Are you sure you want to delete this item?')) {
                this.message = 'Deleting...'
                this.color = 'indigo'
                this.snackbar = true
                // this.timeout = 20000
                this.color = 'indigo'
                axios.delete(`/roles/${item}`)
                    .then((response) => {
                        this.snackbar = false
                        this.AllRoles.splice(index, 1)
                        this.message = 'deleted successifully'
                        this.color = 'red'
                        this.snackbar = true
                    })
                    .catch((error) => {
                        this.snackbar = false
                        this.message = 'something went wrong'
                        this.color = 'red'
                        this.snackbar = true
                        this.errors = error.response.data.errors
                    })
            }
        },
        close() {
            this.dispAdd = this.dispShow = this.dispEdit = false
        },
        getRoles() {
            this.loading = true
            axios.get('/getRoles')
                .then((response) => {
                    this.loading = false
                    this.loader = false
                    this.AllRoles = response.data
                })
                .catch((error) => {
                    this.loading = false
                    this.loader = false
                    this.errors = error.response.data.errors
                })
        }
    },
    mounted() {
        this.loader = true
        this.getRoles()
    },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (vm.user.can["view roles"]) {
        next();
      } else {
        next("/unauthorized");
      }
    });
  }
}
</script>

<style>
.container {
    /* margin-top: -30px; */
}
</style>
