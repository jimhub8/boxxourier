<template>
<v-layout row justify-center v-if="dialog">
    <v-dialog v-model="dialog" persistent max-width="1200px">
        <v-card>
            <v-card-title>
                Users
                <download-excel :data="AllDelusers" :fields="json_fields">
                    Export
                    <img src="/storage/csv.png" style="width: 30px; height: 30px; cursor: pointer;">
                        </download-excel>
                    <v-spacer></v-spacer>
                    <v-text-field v-model="search" append-icon="search" label="Search" single-line></v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="AllDelusers" class="elevation-1" :loading="loading" :search="search">
                <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.name }}</td>
                    <td class="text-xs-right">{{ props.item.email }}</td>
                    <td class="text-xs-right">{{ props.item.address }}</td>
                    <td class="text-xs-right">{{ props.item.phone }}</td>
                    <td class="text-xs-right">{{ props.item.city }}</td>
                    <td class="text-xs-right">{{ props.item.branch }}</td>
                    <td class="text-xs-right">{{ props.item.deleted_at }}</td>
                    <td class="justify-center layout px-0">
                        <v-tooltip bottom>
                            <v-btn icon class="mx-0" @click="refresh(props.item)" slot="activator">
                                <v-icon small color="blue darken-2">edit</v-icon>
                            </v-btn>
                            <span>Edit</span>
                        </v-tooltip>
                    </td>
                </template>
                <v-alert slot="no-results" :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </v-data-table>
            <v-btn flat @click="close">Close</v-btn>
        </v-card>
    </v-dialog>
</v-layout>
</template>

<script>
export default {
    props: ["openShowRequest"],
    data() {
        return {
            dialog: false,
            AllDelusers: [],
            loading: false,
            search: '',
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
                    text: "Deleted on",
                    value: "deleted_at"
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
        };
    },
    methods: {
        refresh(item) {
            this.loading = true;
            axios.patch(`/undeletedUser/${item.id}`)
                .then(response => {
                    this.loading = false;
                    this.deletedUsers()
                    eventBus.$emit("alertRequest", "Successifully reactivated");
                    eventBus.$emit("userEvent", "Successifully reactivated");
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        deletedUsers() {
            this.loading = true;
            axios
                .get("/deletedUsers")
                .then(response => {
                    this.loading = false;
                    this.AllDelusers = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    this.errors = error.response.data.errors;
                });
        },
        close() {
            this.dialog = false;
        }
    },
    created() {
        eventBus.$on('openDeletedEvent', data => {
            this.dialog = true
            this.deletedUsers()
        })
    },
};
</script>
