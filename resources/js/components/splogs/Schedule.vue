<template>
<div>
    <v-content>
        <div v-show="loader" style="text-align: center; width: 100%;">
            <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
        </div>

        <v-container fluid fill-height v-show="!loader">
            <v-layout justify-center align-center>
                <!-- <v-layout wrap>
                    <v-flex xs4 sm3 offset-sm4>
                        <v-select :items="AllUsers" v-model="select" label="Select User" single-line item-text="name" item-value="name" return-object persistent-hint></v-select>
                    </v-flex>
                    <v-flex xs4 sm3>
                        <v-btn raised color="info" @click="sort">Filter</v-btn>
                    </v-flex>
                </v-layout> -->
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <v-tooltip right>
                        <v-btn icon slot="activator" class="mx-0" @click="schedulepct">
                            <v-icon color="blue darken-2" small>refresh</v-icon>
                        </v-btn>
                        <span>Refresh</span>
                    </v-tooltip>
                    <div class="card card-chart">
                        <div class="card-body">
                            <div class="chart-area">
                                <my-branch :height="255"></my-branch>
                            </div>
                            <div class="progress-Ship">
                                <div v-for="user in AllSc" :key="user.id">
                                    {{ user.name }}: {{ user.count }} %
                                    <v-progress-linear color="indigo" height="2" :value="user.count"></v-progress-linear>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </v-layout>
        </v-container>
    </v-content>
    <show></show>
</div>
</template>

<script>
import show from "./Show";
export default {
    // props: ["user"],
    components: {
        show
    },
    data() {
        return {
            search: "",
            AllSc: [],
            loader: false,
            editedItem: {},
            select: [],
            Allusers: [],
            loading: false,
            headers: [{
                    text: "User Id",
                    align: "left",
                    value: "user_id"
                },
                {
                    text: "User Name",
                    align: "left",
                    value: "user_name"
                },
                {
                    text: "Event",
                    value: "event"
                },
                {
                    text: "Waybill Number",
                    value: "bar_code"
                },
                {
                    text: "Updated at",
                    value: "updated_at"
                },
                {
                    text: "Action",
                    sortable: false
                }
            ]
        };
    },
    methods: {
        schedulepct() {
            eventBus.$emit("progressEvent");
            // this.loader = true;
            axios
                .get("/schedulepct")
                .then(response => {
                    eventBus.$emit("StoprogEvent");
                    this.AllSc = response.data;
                    this.loader = false;
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    this.loader = false;
                    this.errors = error.response.data.errors;
                });
        },
        sort() {
            eventBus.$emit("progressEvent");
            // this.loader = true;
            axios
                .get("/customerS")
                .then(response => {
                    eventBus.$emit("StoprogEvent");
                    this.Allusers = response.data;
                    // this.loader = false;
                })
                .catch(error => {
                    eventBus.$emit("StoprogEvent");
                    // this.loader = false;
                    this.errors = error.response.data.errors;
                });
        }
    },
    mounted() {
        this.loader = true;
        this.schedulepct();
    },
    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can["view logs"]) {
                next();
            } else {
                next("/unauthorized");
            }
        });
    }
};
</script>
