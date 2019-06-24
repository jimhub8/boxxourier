<template>
<div>
    <!-- <v-layout justify-center align-center v-show="!loader"> -->
    <v-card>
        <v-card-title>
            Orders
            <v-spacer></v-spacer>
            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
        </v-card-title>
        <v-data-table :headers="headers" :items="allOrder" :loading="loading" class="elevation-1" :search="search">
            <v-progress-linear v-slot:progress color="blue" indeterminate></v-progress-linear>
            <template slot="items" slot-scope="props">
                <td>{{ props.item.created_at }}</td>
                <td class="text-xs-right">{{ props.item.buyer_id }}</td>
                <td class="text-xs-right">{{ props.item.amount }}</td>
                <td class="text-xs-right">{{ props.item.payment_id }}</td>
                <!-- <td class="text-xs-right">{{ props.item.name }}</td> -->
                <td class="text-xs-right">{{ props.item.address }}</td>
                <td class="text-xs-right">{{ props.item.status }}</td>
                <td class="justify-center layout px-0">

                    <v-tooltip bottom>
                        <v-btn slot="activator" icon class="mx-0" @click="view(props.item)">
                            <v-icon small color="indigo darken-2">visibility</v-icon>
                        </v-btn>
                        <span>View Order</span>
                    </v-tooltip>
                    <form :action="'/invoice/'+props.item.id" method="get" target="_blank">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" name="type" value="stream">
                        <v-tooltip bottom>
                            <v-btn flat slot="activator" color="info" icon class="mx-0" type="submit">
                                <v-icon>book</v-icon>
                            </v-btn>
                            <span>Stream invoice</span>
                        </v-tooltip>
                    </form>
                    <form :action="'/invoice/'+props.item.id" method="get">
                        <input type="hidden" name="_token" :value="csrf">
                        <input type="hidden" name="type" value="download">
                        <v-tooltip bottom>
                            <v-btn flat slot="activator" color="primary" icon class="mx-0" type="submit">
                                <v-icon>cloud_upload</v-icon>
                            </v-btn>
                            <span>Download invoice</span>
                        </v-tooltip>
                    </form>
                </td>
            </template>
            <template v-slot:no-results>
                <v-alert :value="true" color="error" icon="warning">
                    Your search for "{{ search }}" found no results.
                </v-alert>
            </template>
        </v-data-table>
    </v-card>
    <!-- </v-layout> -->
    <myView></myView>

</div>
</template>

<script>
import myView from "../orders/View";
export default {
    components: {
        myView
    },
    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            allOrder: [],
            loader: false,
            search: '',
            loading: false,

            headers: [{
                    text: "Created On",
                    value: "created_at"
                },
                {
                    text: "Client Name",
                    align: "left",
                    value: "name"
                },
                {
                    text: "Amount",
                    value: "amount"
                },
                {
                    text: "Payment Id",
                    value: "payment_id"
                },
                // {
                //     text: "Name",
                //     value: "name"
                // },
                {
                    text: "Address",
                    value: "address"
                },
                {
                    text: "Status",
                    value: "status"
                },
                {
                    text: "Actions",
                    sortable: false
                }
            ],

        }
    },
    methods: {
        view(order) {
            eventBus.$emit("viewOrdEvent", order);
        },
        getOrders() {
            axios.get("/orders")
                .then(response => {
                    this.loading = false;
                    this.allOrder = response.data;
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    }
                    this.errors = error.response.data.errors;
                });
        }
    },
    mounted() {
        this.loading = true;
        this.getOrders()
    },
}
</script>
