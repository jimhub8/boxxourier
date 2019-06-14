<template>
<div>
    <v-content>
        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <div class="container">
                    <v-card style="background: rgba(5, 117, 230, 0.16);">
                        <v-layout wrap>
                            <v-flex xs4 sm3 offset-sm4>
                                <v-select :items="AllRoles" v-model="select" label="Select Role" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
                            </v-flex>
                            <!-- <v-spacer></v-spacer> -->
                            <v-flex xs4 sm3>
                                <v-btn raised color="info" @click="sort">Filter</v-btn>
                            </v-flex>
                        </v-layout>
                    </v-card>
                    <!-- users display -->
                    <v-card-title>
                        Users
                        <download-excel :data="Allusers" :fields="json_fields">
                            Export
                            <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                            <v-btn slot="activator" color="primary" dark flat @click="openAdd">Add User</v-btn>
                            <v-btn slot="activator" color="orange" dark flat @click="openDeleted">Deleted Users</v-btn>
                            <v-tooltip right>
                                <v-btn icon slot="activator" class="mx-0" @click="getUsers">
                                    <v-icon color="blue darken-2" small>refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line></v-text-field>
                    </v-card-title>
                    <v-data-table :headers="headers" :items="Allusers" class="elevation-1" :loading="loading" :search="search">
                        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                        <template slot="items" slot-scope="props">
                            <td>{{ props.item.name }}</td>
                            <td class="text-xs-right">{{ props.item.email }}</td>
                            <td class="text-xs-right">{{ props.item.address }}</td>
                            <td class="text-xs-right">{{ props.item.phone }}</td>
                            <td class="text-xs-right">{{ props.item.city }}</td>
                            <td class="text-xs-right">{{ props.item.branch }}</td>
                            <td class="text-xs-right">{{ props.item.country }}</td>
                            <td class="text-xs-right">{{ props.item.status }}</td>
                            <td class="justify-center layout px-0">
                                <v-tooltip bottom v-if="user.can['edit users']">
                                    <v-btn icon class="mx-0" @click="openEdit(props.item)" slot="activator">
                                        <v-icon small color="blue darken-2">edit</v-icon>
                                    </v-btn>
                                    <span>Edit</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['view users']">
                                    <v-btn icon class="mx-0" @click="openShow(props.item)" slot="activator">
                                        <v-icon small color="blue darken-2">visibility</v-icon>
                                    </v-btn>
                                    <span>View user</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['edit users']">
                                    <v-btn icon class="mx-0" @click="openPerm(props.item)" slot="activator">
                                        <v-icon small color="orange darken-2">dialpad</v-icon>
                                    </v-btn>
                                    <span>Edit Permissions</span>
                                </v-tooltip>
                                <v-tooltip bottom v-if="user.can['edit users']">
                                    <v-btn icon class="mx-0" @click="deleteItem(props.item)" slot="activator">
                                        <v-icon small color="pink darken-2">delete</v-icon>
                                    </v-btn>
                                    <span>Delete</span>
                                </v-tooltip>
                            </td>
                        </template>
                        <v-alert slot="no-results" :value="true" color="error" icon="warning">
                            Your search for "{{ search }}" found no results.
                        </v-alert>
                    </v-data-table>
                    <!-- users display -->
                </div>
            </v-layout>
        </v-container>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="green" style="margin: 1rem"></v-progress-circular>
        </div>
        <v-snackbar :timeout="timeout" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" v-model="snackbar">
            {{ message }}
            <v-icon dark right>check_circle</v-icon>
        </v-snackbar>
    </v-content>
    <AddUser @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert" :AllBranches="AllBranches" :AllRoles="AllRoles" :countryList="Allcountries"></AddUser>
    <EditUser @closeRequest="close" :openEditRequest="dispEdit" @alertRequest="showAlert" :form="editedItem" :AllBranches="AllBranches" :AllRoles="AllRoles" :countryList="Allcountries"></EditUser>
    <PermUser @closeRequest="close" :openPermRequest="permEdit" :form="editedItem"></PermUser>
    <UserProfile @closeRequest="close" :openShowRequest="dispShow" :user="editedItem" :AllShips="AllShips"></UserProfile>
    <DeletedUsers></DeletedUsers>
</div>
</template>

<script>
import AddUser from "./AddUser.vue"
import PermUser from './Permission.vue'
import DeletedUsers from './DeletedUsers.vue'
import EditUser from "./EditUser.vue"
import UserProfile from "./UserProfile.vue" 
export default {
    props: ["user", "role"],
    components: {
        AddUser,
        PermUser,
        EditUser,
        UserProfile,
        DeletedUsers
    },
    data() {
        return {
            AllShips: [],
            permEdit: false,
            Allcountries: [],
            select: {
                state: "All",
                abbr: "all"
            },
            headers: [{
                    text: "Name",
                    value: "name"
                },
                {
                    text: "Email",
                    value: "email"
                },
                {
                    text: "Address",
                    value: "address"
                },
                {
                    text: "Phone Number",
                    value: "phone"
                },
                {
                    text: "City",
                    value: "city"
                },
                {
                    text: "Branch",
                    value: "branch"
                },
                {
                    text: "Country",
                    value: "country"
                },
                {
                    text: "Status",
                    value: "status"
                },
                {
                    text: "Actions",
                    value: "name",
                    sortable: false
                }
            ],
            json_fields: {
                Name: "name",
                Email: "email",
                Phone: "phone",
                City: "city",
                Address: "address",
                Country: "country"
            },
            AllBranches: {},
            search: "",
            AllRoles: [],
            loader: false,
            a1: null,
            dispAdd: false,
            dispShow: false,
            dispEdit: false,
            snackbar: false,
            loading: false,
            timeout: 5000,
            color: "black",
            message: "Success",
            y: "bottom",
            x: "left",
            Allusers: [],
            temp: "",
            editedItem: {},
            select: {
                state: "All",
                abbr: "all"
            },
            items: [{
                    state: "All",
                    abbr: "all"
                },
                {
                    state: "Admin",
                    abbr: "1"
                },
                {
                    state: "Branch Admin",
                    abbr: "2"
                },
                {
                    state: "Customers",
                    abbr: "3"
                },
                {
                    state: "Drivers",
                    abbr: "4"
                }
            ]
        };
    },
    methods: {
        openDeleted() {
            eventBus.$emit('openDeletedEvent')
        },
        openAdd() {
            this.dispAdd = true;
            this.getCountry();
        },
        openEdit(item) {
            this.getCountry();
            this.editedIndex = this.Allusers.indexOf(item);
            this.editedItem = Object.assign({}, item);
            axios
                .post(`/getUserPerm/${item.id}`)
                .then(response => {
                    eventBus.$emit("permEvent", response.data);
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                });
            this.dispEdit = true;
        },
        openPerm(item) {
            this.editedIndex = this.Allusers.indexOf(item)
            this.editedItem = Object.assign({}, item)
            axios.post(`/getUserPerm/${item.id}`)
                .then((response) => {
                    eventBus.$emit('permEvent', response.data);
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
            this.permEdit = true
        },
        openShow(item) {
            this.editedIndex = this.Allusers.indexOf(item);
            this.editedItem = Object.assign({}, item);
            eventBus.$emit("getShipEvent", this.editedItem);

            this.dispShow = true;
        },
        showAlert() {
            this.message = "Successifully Added";
            this.snackbar = true;
            this.color = "black";
        },
        sort() {
            this.loading = true;
            axios
                .post("/getSorted", this.select)
                .then(response => {
                    this.loading = false;
                    this.Allusers = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        deleteItem(item) {
            if (confirm("Are you sure you want to delete this item?")) {
                this.loading = true;
                const index = this.Allusers.indexOf(item)
                axios
                    .delete(`/users/${item.id}`)
                    .then(response => {
                        this.Allusers.splice(index, 1)
                        this.loading = false;
                        this.message = "deleted successifully";
                        this.color = "red";
                        this.snackbar = true;
                        this.Allusers.splice(index, 1);
                    })
                    .catch(error => {
                        this.loading = false;
                        this.errors = error.response.data.errors;
                        this.message = "something went wrong";
                        this.color = "red";
                        this.snackbar = true;
                    });
            }
        },
        close() {
            this.dispAdd = this.dispShow = this.permEdit = this.dispEdit = this.dispShow = false;
        },
        getUsers() {
            this.loading = true;
            axios
                .get("/getUsers")
                .then(response => {
                    this.loading = false;
                    this.Allusers = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        getCountry() {
            this.loading = true;
            axios
                .get("/getCountry")
                .then(response => {
                    this.loading = false;
                    this.Allcountries = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        }
    },
    mounted() {
        this.loader = true;
        this.getUsers();
        axios
            .get("/getBranch")
            .then(response => {
                this.loader = false;
                this.AllBranches = response.data;
            })
            .catch(error => {
                this.loader = false;
                this.errors = error.response.data.errors;
            });

        axios
            .get("/getRoles")
            .then(response => {
                this.AllRoles = response.data;
            })
            .catch(error => {
                this.errors = error.response.data.errors;
            });
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can["view users"]) {
                next();
            } else {
                next("/unauthorized");
            }
        });
    },

    created() {
        eventBus.$on('userEvent', data => {
            this.getUsers()
        })
    },
};
</script>

<style scoped>
.content--wrap {
    margin-top: -100px;
}

#profile {
    width: 70px;
    height: 60px;
    border-radius: 50%;
    margin-left: 80px;
    margin-top: -30px;
}

i {
    padding: 7px;
    background: #f0f0f0;
    border-radius: 50%;
}

.list-group-item:hover,
.list-group-item:focus {
    z-index: 1;
    background: #f9f9f9;
    text-decoration: none;
}
</style>
