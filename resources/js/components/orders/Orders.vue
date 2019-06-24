<template>
<div>
    <v-content>
        <v-container fluid fill-height>
            <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">
                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
            </div>
            <v-layout justify-center align-center v-show="!loader">
                <v-layout row wrap>
                    <v-card>
                        <v-card-title>
                            Order
                            <v-btn @click="openAdd" flat color="primary">Add Order</v-btn>

                            <!-- <v-spacer></v-spacer> -->
                            <v-tooltip bottom>
                                <v-btn slot="activator" icon class="mx-0" @click="getOrders">
                                    <v-icon small color="blue darken-2">refresh</v-icon>
                                </v-btn>
                                <span>Refresh</span>
                            </v-tooltip>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="orders" class="elevation-1" :search="search" :loading="loading">
                            <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.created_at }}</td>
                                <td class="text-xs-right">{{ props.item.buyer_id }}</td>
                                <td class="text-xs-right">{{ props.item.amount }}</td>
                                <td class="text-xs-right">{{ props.item.payment_id }}</td>
                                <!-- <td class="text-xs-right">{{ props.item.name }}</td> -->
                                <td class="text-xs-right">{{ props.item.address }}</td>
                                <td class="text-xs-right">{{ props.item.status }}</td>
                                <td class="justify-center layout px-0">
                                    <v-tooltip bottom v-if="user.can['edit orders']">
                                        <v-btn slot="activator" icon class="mx-0" @click="editProduct(props.item)">
                                            <v-icon small color="blue darken-2">edit</v-icon>
                                        </v-btn>
                                        <span>Edit</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['view orders']">
                                        <v-btn slot="activator" icon class="mx-0" @click="view(props.item)">
                                            <v-icon small color="indigo darken-2">visibility</v-icon>
                                        </v-btn>
                                        <span>View Order</span>
                                    </v-tooltip>
                                    <v-tooltip bottom v-if="user.can['delete orders']">
                                        <v-btn slot="activator" icon class="mx-0" @click="deleteItem(props.item)">
                                            <v-icon small color="pink darken-2">delete</v-icon>
                                        </v-btn>
                                        <span>delete Order</span>
                                    </v-tooltip>
                                    <form :action="'/invoice/'+props.item.id" method="get" target="_blank" v-if="user.can['download invoice']">
                                        <input type="hidden" name="_token" :value="csrf">
                                        <input type="hidden" name="type" value="stream">
                                        <v-tooltip bottom>
                                            <v-btn flat slot="activator" color="info" icon class="mx-0" type="submit">
                                                <v-icon>book</v-icon>
                                            </v-btn>
                                            <span>Stream invoice</span>
                                        </v-tooltip>
                                    </form>
                                    <form :action="'/invoice/'+props.item.id" method="get" v-if="user.can['download invoice']">
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
                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                    </v-card>
                </v-layout>
            </v-layout>
        </v-container>
        <v-snackbar :timeout="timeout" bottom :color="color" left v-model="snackbar">
            {{ message }}
            <v-btn>close</v-btn>
        </v-snackbar>
    </v-content>
    <Create @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert"></Create>
    <!-- <Edit @closeRequest="close" :openAddRequest="dispEdit" @alertRequest="showAlert" :order="catEdit"></Edit> -->
    <myView></myView>

</div>
</template>

<script>
import Create from "./Create";
// import Edit from "./Edit";
import myView from "./View";

export default {
    props: ['user'],
    components: {
        Create,
        myView
    },

    data() {
        return {
            csrf: document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            search: "",
            loading: false,
            dispAdd: false,
            dispEdit: false,
            dispShow: false,
            orders: [],
            catEdit: [],
            loader: false,
            snackbar: false,
            timeout: 5000,
            color: "",
            message: "",
            discount: 0,
            totalPrice: null,
            carts: [],
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
            ]
        };
    },

    methods: {
        openAdd() {
            this.dispAdd = true;
        },
        close() {
            this.dispAdd = this.dispShow = this.dispEdit = false;
        },
        showAlert() {
            this.message = "Successifully Added";
            this.snackbar = true;
            this.color = "indigo";
        },

        editProduct(task) {
            this.catEdit = Object.assign({}, task);
            this.editedIndex = this.orders.indexOf(task);
            this.dispEdit = true;
        },
        getOrders() {
            this.loading = true;
            axios
                .get("/orders")
                .then(response => {
                    this.loader = false;
                    this.loading = false;
                    this.orders = response.data;
                    // this.carts = response.data.carts;
                })
                .catch(error => {
                    this.loader = false;
                    this.loading = false;
                    if (error.response.status === 500) {
                        eventBus.$emit('errorEvent', error.response.statusText)
                        return
                    }
                    this.errors = error.response.data.errors;
                });
        },
        view(order) {
            eventBus.$emit("viewOrdEvent", order);
        },
        deleteItem(item) {
            if (confirm('Are you sure you want to delete this item?')) {
                this.loading = true
                axios.delete(`/orders/${item.id}`)
                    .then((response) => {
                        this.loading = false
                        eventBus.$emit("alertRequest", 'Successifully deleted');
                        this.orders.splice(index, 1)
                    })
                    .catch((error) => {
                        this.loading = false
                        if (error.response.status === 500) {
                            eventBus.$emit('errorEvent', error.response.statusText)
                            return
                        }
                        this.errors = error.response.data.errors
                    })
            }
        },

    },
    mounted() {
        this.loader = true;
        this.getOrders();
    },

    computed: {
        getSubTotal() {
            this.orders.forEach(element => {
                this.carts = element.cart;
            });
            // if (this.orders.carts.length > 0) {
            //     this.totalPrice = 0;
            //     for (let index = 0; index < this.orders.carts.length; index++) {
            //         const element = this.orders.carts[index];
            //         this.totalPrice = parseInt(element.price) + this.totalPrice;
            //     }
            // }
            // return this.carts;
        }
        // getTotal() {
        //     if (this.carts.length > 0) {
        //         return parseInt(this.getSubTotal) - this.discount;
        //     }
        // }
    },

    beforeRouteEnter(to, from, next) {
        next(vm => {
            if (vm.user.can['view orders']) {
                next();
            } else {
                next('/unauthorized');
            }
        })
    }
};
</script>
